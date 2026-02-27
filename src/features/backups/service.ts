import { tmpdir } from "node:os";
import { dirname, join } from "node:path";
import { randomUUID } from "node:crypto";
import { Readable } from "node:stream";
import { createWriteStream } from "node:fs";
import { promises as fs } from "node:fs";
import { pipeline } from "node:stream/promises";
import { execFile } from "node:child_process";
import { promisify } from "node:util";
import {
  DeleteObjectCommand,
  GetObjectCommand,
  ListObjectsV2Command,
  PutObjectCommand,
} from "@aws-sdk/client-s3";
import { db } from "@/db";
import { categories, heroImages, projectImages, projects, subscribers, testimonials, backupSettings } from "@/db/schema";
import { asc, desc, eq } from "drizzle-orm";
import { s3Client, R2_BUCKET } from "@/lib/r2";

const execFileAsync = promisify(execFile);

export type BackupType = "json" | "sql" | "media" | "full";

export function toR2Key(pathOrUrl: string) {
  if (!pathOrUrl) return "";
  if (pathOrUrl.startsWith("http://") || pathOrUrl.startsWith("https://")) {
    try {
      const url = new URL(pathOrUrl);
      return url.pathname.replace(/^\/+/, "");
    } catch {
      return pathOrUrl.replace(/^\/+/, "");
    }
  }
  return pathOrUrl.replace(/^\/+/, "");
}

async function streamToFile(stream: Readable | ReadableStream<Uint8Array>, destinationPath: string) {
  const nodeStream = stream instanceof Readable ? stream : Readable.fromWeb(stream as any);
  await pipeline(nodeStream, createWriteStream(destinationPath));
}

async function createTempDir() {
  const dir = join(tmpdir(), `toota-backup-${randomUUID()}`);
  await fs.mkdir(dir, { recursive: true });
  return dir;
}

export async function getExportData() {
  const [categoryList, projectList, testimonialList, heroList, subscriberList] = await Promise.all([
    db.query.categories.findMany({ orderBy: [asc(categories.order), desc(categories.createdAt)] }),
    db.query.projects.findMany({
      orderBy: [asc(projects.order), desc(projects.createdAt)],
      with: {
        category: true,
        images: {
          orderBy: [asc(projectImages.order)],
        },
      },
    }),
    db.query.testimonials.findMany({ orderBy: [desc(testimonials.createdAt)] }),
    db.query.heroImages.findMany({ orderBy: [asc(heroImages.position)] }),
    db.query.subscribers.findMany({ orderBy: [desc(subscribers.createdAt)] }),
  ]);

  return {
    meta: {
      exportedAt: new Date().toISOString(),
      version: "2.0-nextjs",
    },
    categories: categoryList,
    projects: projectList,
    testimonials: testimonialList,
    heroImages: heroList,
    subscribers: subscriberList,
  };
}

export async function buildJsonExportFile() {
  const dir = await createTempDir();
  const path = join(dir, `toota-art-data-${Date.now()}.json`);
  const data = await getExportData();
  await fs.writeFile(path, JSON.stringify(data, null, 2), "utf8");
  return path;
}

export async function buildSqlDumpFile() {
  const databaseUrl = process.env.DATABASE_URL;
  if (!databaseUrl) {
    throw new Error("DATABASE_URL is not configured.");
  }

  const dir = await createTempDir();
  const path = join(dir, `toota-art-${Date.now()}.sql`);

  await execFileAsync("pg_dump", [
    "--no-owner",
    "--no-privileges",
    `--dbname=${databaseUrl}`,
    "-f",
    path,
  ]);

  return path;
}

async function getMediaKeys() {
  const [projectImageRows, heroImageRows] = await Promise.all([
    db.select({ imagePath: projectImages.imagePath }).from(projectImages),
    db.select({ imagePath: heroImages.imagePath }).from(heroImages),
  ]);

  const all = [...projectImageRows, ...heroImageRows]
    .map((row) => toR2Key(row.imagePath ?? ""))
    .filter(Boolean);

  return [...new Set(all)];
}

export async function buildMediaArchiveFile() {
  const keys = await getMediaKeys();
  const root = await createTempDir();
  const mediaDir = join(root, "media");
  await fs.mkdir(mediaDir, { recursive: true });

  for (const key of keys) {
    const command = new GetObjectCommand({ Bucket: R2_BUCKET, Key: key });
    try {
      const result = await s3Client.send(command);
      if (!result.Body) continue;

      const targetPath = join(mediaDir, key);
      await fs.mkdir(join(targetPath, ".."), { recursive: true });
      await streamToFile(result.Body as Readable | ReadableStream<Uint8Array>, targetPath);
    } catch (error) {
      console.warn(`Skipping missing media object: ${key}`, error);
    }
  }

  const archivePath = join(root, `toota-art-media-${Date.now()}.tar.gz`);
  await execFileAsync("tar", ["-czf", archivePath, "-C", root, "media"]);
  return archivePath;
}

export async function buildFullArchiveFile() {
  const root = await createTempDir();
  const bundleDir = join(root, "full-backup");
  await fs.mkdir(bundleDir, { recursive: true });

  const [jsonPath, sqlPath] = await Promise.all([
    buildJsonExportFile(),
    buildSqlDumpFile(),
  ]);

  await fs.copyFile(jsonPath, join(bundleDir, "portfolio-data.json"));
  await fs.copyFile(sqlPath, join(bundleDir, "database.sql"));

  const mediaKeys = await getMediaKeys();
  const mediaDir = join(bundleDir, "media");
  await fs.mkdir(mediaDir, { recursive: true });

  for (const key of mediaKeys) {
    try {
      const result = await s3Client.send(new GetObjectCommand({ Bucket: R2_BUCKET, Key: key }));
      if (!result.Body) continue;
      const targetPath = join(mediaDir, key);
      await fs.mkdir(join(targetPath, ".."), { recursive: true });
      await streamToFile(result.Body as Readable | ReadableStream<Uint8Array>, targetPath);
    } catch (error) {
      console.warn(`Skipping missing media object in full backup: ${key}`, error);
    }
  }

  const archivePath = join(root, `toota-art-full-${Date.now()}.tar.gz`);
  await execFileAsync("tar", ["-czf", archivePath, "-C", root, "full-backup"]);
  return archivePath;
}

export async function buildBackupArtifact(type: BackupType) {
  if (type === "json") return buildJsonExportFile();
  if (type === "sql") return buildSqlDumpFile();
  if (type === "media") return buildMediaArchiveFile();
  return buildFullArchiveFile();
}

export async function uploadBackupToCloud(type: BackupType) {
  const artifactPath = await buildBackupArtifact(type);
  const extension = artifactPath.endsWith(".json")
    ? "json"
    : artifactPath.endsWith(".sql")
      ? "sql"
      : "tar.gz";
  const fileName = `backups/${type}/${Date.now()}-${randomUUID()}.${extension}`;

  const body = await fs.readFile(artifactPath);
  await s3Client.send(
    new PutObjectCommand({
      Bucket: R2_BUCKET,
      Key: fileName,
      Body: body,
      ContentType:
        extension === "json"
          ? "application/json"
          : extension === "sql"
            ? "application/sql"
            : "application/gzip",
    }),
  );

  await fs.rm(dirname(artifactPath), { recursive: true, force: true }).catch(() => {});

  return { key: fileName };
}

export async function listCloudBackups() {
  const result = await s3Client.send(
    new ListObjectsV2Command({
      Bucket: R2_BUCKET,
      Prefix: "backups/",
      MaxKeys: 200,
    }),
  );

  const items = result.Contents ?? [];
  return items
    .filter((item) => item.Key)
    .map((item) => ({
      key: item.Key as string,
      size: item.Size ?? 0,
      lastModified: item.LastModified?.toISOString() ?? null,
    }))
    .sort((a, b) => (a.lastModified && b.lastModified ? b.lastModified.localeCompare(a.lastModified) : 0));
}

export async function cleanupCloudBackups(retention: Pick<BackupSchedule, "keepDaily" | "keepWeekly" | "keepMonthly">) {
  const files = await listCloudBackups();
  const byType = new Map<string, CloudBackupFile[]>();

  for (const file of files) {
    const parts = file.key.split("/");
    const type = parts[1] || "other";
    const bucket = byType.get(type) ?? [];
    bucket.push(file);
    byType.set(type, bucket);
  }

  const keepCount = Math.max(retention.keepDaily, retention.keepWeekly, retention.keepMonthly, 1);
  const keysToDelete: string[] = [];
  for (const entries of byType.values()) {
    const sorted = [...entries].sort((a, b) => (b.lastModified || "").localeCompare(a.lastModified || ""));
    keysToDelete.push(...sorted.slice(keepCount).map((item) => item.key));
  }

  for (const key of keysToDelete) {
    await deleteCloudBackup(key);
  }

  return { deleted: keysToDelete.length };
}

export async function deleteCloudBackup(key: string) {
  await s3Client.send(
    new DeleteObjectCommand({
      Bucket: R2_BUCKET,
      Key: key,
    }),
  );
}

export async function downloadCloudBackup(key: string) {
  const result = await s3Client.send(
    new GetObjectCommand({
      Bucket: R2_BUCKET,
      Key: key,
    }),
  );

  if (!result.Body) {
    throw new Error("Backup file body is empty.");
  }

  return {
    body: result.Body as Readable | ReadableStream<Uint8Array>,
    contentType: result.ContentType ?? "application/octet-stream",
    contentLength: result.ContentLength ?? undefined,
    fileName: key.split("/").pop() || "backup-file",
  };
}

type CloudBackupFile = {
  key: string;
  size: number;
  lastModified: string | null;
};

export type BackupSchedule = {
  enabled: boolean;
  frequency: "daily" | "weekly" | "monthly";
  time: string;
  keepDaily: number;
  keepWeekly: number;
  keepMonthly: number;
};

export async function getBackupSchedule(): Promise<BackupSchedule> {
  const row = await db.query.backupSettings.findFirst({
    orderBy: [desc(backupSettings.updatedAt), desc(backupSettings.id)],
  });

  if (!row) {
    return {
      enabled: false,
      frequency: "weekly",
      time: "01:00",
      keepDaily: 7,
      keepWeekly: 4,
      keepMonthly: 3,
    };
  }

  return {
    enabled: row.enabled,
    frequency: (row.frequency as BackupSchedule["frequency"]) || "weekly",
    time: row.time || "01:00",
    keepDaily: row.keepDaily ?? 7,
    keepWeekly: row.keepWeekly ?? 4,
    keepMonthly: row.keepMonthly ?? 3,
  };
}

export async function saveBackupSchedule(input: BackupSchedule, userId?: string) {
  const existing = await db.query.backupSettings.findFirst({
    orderBy: [desc(backupSettings.updatedAt), desc(backupSettings.id)],
  });

  if (!existing) {
    await db.insert(backupSettings).values({
      enabled: input.enabled,
      frequency: input.frequency,
      time: input.time,
      keepDaily: input.keepDaily,
      keepWeekly: input.keepWeekly,
      keepMonthly: input.keepMonthly,
      updatedByUserId: userId,
      createdAt: new Date(),
      updatedAt: new Date(),
    });
    return;
  }

  await db
    .update(backupSettings)
    .set({
      enabled: input.enabled,
      frequency: input.frequency,
      time: input.time,
      keepDaily: input.keepDaily,
      keepWeekly: input.keepWeekly,
      keepMonthly: input.keepMonthly,
      updatedByUserId: userId,
      updatedAt: new Date(),
    })
    .where(eq(backupSettings.id, existing.id));
}
