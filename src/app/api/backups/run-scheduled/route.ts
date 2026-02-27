import { NextResponse } from "next/server";
import { cleanupCloudBackups, getBackupSchedule, uploadBackupToCloud } from "@/features/backups/service";

export const runtime = "nodejs";
export const dynamic = "force-dynamic";

function isSameLocalTime(now: Date, hhmm: string) {
  const [h, m] = hhmm.split(":").map(Number);
  return now.getHours() === h && now.getMinutes() === m;
}

function shouldRunForDate(now: Date, frequency: "daily" | "weekly" | "monthly") {
  if (frequency === "daily") return true;
  if (frequency === "weekly") return now.getDay() === 1;
  return now.getDate() === 1;
}

export async function POST(request: Request) {
  const secret = request.headers.get("x-cron-secret");
  if (!process.env.BACKUP_CRON_SECRET || secret !== process.env.BACKUP_CRON_SECRET) {
    return NextResponse.json({ error: "Unauthorized" }, { status: 401 });
  }

  const now = new Date();
  const schedule = await getBackupSchedule();

  if (!schedule.enabled) {
    return NextResponse.json({ skipped: true, reason: "Schedule disabled." });
  }

  if (!shouldRunForDate(now, schedule.frequency)) {
    return NextResponse.json({ skipped: true, reason: "Not scheduled for today." });
  }

  if (!isSameLocalTime(now, schedule.time)) {
    return NextResponse.json({ skipped: true, reason: "Not scheduled time minute." });
  }

  const uploaded = await uploadBackupToCloud("full");
  const cleanup = await cleanupCloudBackups({
    keepDaily: schedule.keepDaily,
    keepWeekly: schedule.keepWeekly,
    keepMonthly: schedule.keepMonthly,
  });

  return NextResponse.json({
    success: true,
    uploaded,
    cleanup,
  });
}
