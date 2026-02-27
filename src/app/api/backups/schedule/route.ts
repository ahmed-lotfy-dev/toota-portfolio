import { NextResponse } from "next/server";
import { z } from "zod";
import { requireAdminApi } from "@/lib/server-admin";
import { getBackupSchedule, saveBackupSchedule } from "@/features/backups/service";

export const runtime = "nodejs";
export const dynamic = "force-dynamic";

const scheduleSchema = z.object({
  enabled: z.boolean(),
  frequency: z.enum(["daily", "weekly", "monthly"]),
  time: z.string().regex(/^\d{2}:\d{2}$/),
  keepDaily: z.number().int().min(1).max(365),
  keepWeekly: z.number().int().min(1).max(52),
  keepMonthly: z.number().int().min(1).max(24),
});

export async function GET() {
  const { error } = await requireAdminApi();
  if (error) return error;

  try {
    const schedule = await getBackupSchedule();
    return NextResponse.json({ schedule });
  } catch (error) {
    console.error("Failed to load backup schedule:", error);
    return NextResponse.json({ error: "Failed to load schedule." }, { status: 500 });
  }
}

export async function POST(request: Request) {
  const { error, session } = await requireAdminApi();
  if (error) return error;

  let payload: unknown;
  try {
    payload = await request.json();
  } catch {
    return NextResponse.json({ error: "Invalid JSON payload." }, { status: 400 });
  }

  const parsed = scheduleSchema.safeParse(payload);
  if (!parsed.success) {
    return NextResponse.json({ error: "Invalid schedule payload." }, { status: 422 });
  }

  try {
    await saveBackupSchedule(parsed.data, session?.user?.id);
    return NextResponse.json({ success: true });
  } catch (e) {
    console.error("Failed to save backup schedule:", e);
    return NextResponse.json({ error: "Failed to save schedule." }, { status: 500 });
  }
}
