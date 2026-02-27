import { NextResponse } from "next/server";
import { z } from "zod";
import { requireAdminApi } from "@/lib/server-admin";
import {
  BackupType,
  deleteCloudBackup,
  listCloudBackups,
  uploadBackupToCloud,
} from "@/features/backups/service";

export const runtime = "nodejs";
export const dynamic = "force-dynamic";

const uploadSchema = z.object({
  type: z.enum(["json", "sql", "media", "full"]),
});

export async function GET() {
  const { error } = await requireAdminApi();
  if (error) return error;

  try {
    const files = await listCloudBackups();
    return NextResponse.json({ files });
  } catch (error: any) {
    console.error("List cloud backups failed:", error);
    return NextResponse.json({ error: "Failed to list backups." }, { status: 500 });
  }
}

export async function POST(request: Request) {
  const { error } = await requireAdminApi();
  if (error) return error;

  let payload: unknown;
  try {
    payload = await request.json();
  } catch {
    return NextResponse.json({ error: "Invalid JSON payload." }, { status: 400 });
  }

  const parsed = uploadSchema.safeParse(payload);
  if (!parsed.success) {
    return NextResponse.json({ error: "Invalid backup type." }, { status: 422 });
  }

  try {
    const result = await uploadBackupToCloud(parsed.data.type as BackupType);
    return NextResponse.json({ success: true, ...result });
  } catch (error: any) {
    console.error("Upload cloud backup failed:", error);
    return NextResponse.json({ error: error?.message || "Upload failed." }, { status: 500 });
  }
}

export async function DELETE(request: Request) {
  const { error } = await requireAdminApi();
  if (error) return error;

  const { searchParams } = new URL(request.url);
  const key = searchParams.get("key");
  if (!key || !key.startsWith("backups/")) {
    return NextResponse.json({ error: "Invalid backup key." }, { status: 400 });
  }

  try {
    await deleteCloudBackup(key);
    return NextResponse.json({ success: true });
  } catch (error: any) {
    console.error("Delete cloud backup failed:", error);
    return NextResponse.json({ error: "Delete failed." }, { status: 500 });
  }
}
