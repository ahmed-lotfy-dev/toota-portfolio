import { NextResponse } from "next/server";
import { Readable } from "node:stream";
import { requireAdminApi } from "@/lib/server-admin";
import { downloadCloudBackup } from "@/features/backups/service";

export const runtime = "nodejs";
export const dynamic = "force-dynamic";

export async function GET(request: Request) {
  const { error } = await requireAdminApi();
  if (error) return error;

  const { searchParams } = new URL(request.url);
  const key = searchParams.get("key");

  if (!key || !key.startsWith("backups/")) {
    return NextResponse.json({ error: "Invalid backup key." }, { status: 400 });
  }

  try {
    const file = await downloadCloudBackup(key);
    const body = file.body instanceof Readable
      ? file.body
      : Readable.fromWeb(file.body as any);

    return new NextResponse(body as any, {
      status: 200,
      headers: {
        "content-type": file.contentType,
        ...(file.contentLength ? { "content-length": String(file.contentLength) } : {}),
        "content-disposition": `attachment; filename="${file.fileName}"`,
      },
    });
  } catch (error: any) {
    console.error("Cloud backup download failed:", error);
    return NextResponse.json({ error: "Download failed." }, { status: 500 });
  }
}
