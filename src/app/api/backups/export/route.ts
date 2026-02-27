import { NextResponse } from "next/server";
import { requireAdminApi } from "@/lib/server-admin";
import { buildBackupArtifact, BackupType } from "@/features/backups/service";
import { promises as fs } from "node:fs";

export const runtime = "nodejs";
export const dynamic = "force-dynamic";

const validTypes: BackupType[] = ["json", "sql", "media", "full"];

function getContentType(type: BackupType) {
  if (type === "json") return "application/json";
  if (type === "sql") return "application/sql";
  return "application/gzip";
}

function getFileName(type: BackupType) {
  if (type === "json") return `toota-art-data-${Date.now()}.json`;
  if (type === "sql") return `toota-art-${Date.now()}.sql`;
  if (type === "media") return `toota-art-media-${Date.now()}.tar.gz`;
  return `toota-art-full-${Date.now()}.tar.gz`;
}

export async function GET(request: Request) {
  const { error } = await requireAdminApi();
  if (error) return error;

  const { searchParams } = new URL(request.url);
  const type = searchParams.get("type") as BackupType | null;

  if (!type || !validTypes.includes(type)) {
    return NextResponse.json({ error: "Invalid backup type." }, { status: 400 });
  }

  try {
    const filePath = await buildBackupArtifact(type);
    const stat = await fs.stat(filePath);
    const file = await fs.readFile(filePath);

    return new NextResponse(file, {
      status: 200,
      headers: {
        "content-type": getContentType(type),
        "content-length": String(stat.size),
        "content-disposition": `attachment; filename="${getFileName(type)}"`,
      },
    });
  } catch (error: any) {
    console.error("Backup export failed:", error);
    return NextResponse.json({ error: error?.message || "Failed to export backup." }, { status: 500 });
  }
}
