import { NextResponse } from "next/server";
import { z } from "zod";
import { db } from "@/db";
import { subscribers } from "@/db/schema";
import { checkRateLimit, getClientIp } from "@/lib/rate-limit";

export const runtime = "nodejs";
export const dynamic = "force-dynamic";

const bodySchema = z.object({
  email: z.string().trim().toLowerCase().email().max(255),
});

const RATE_LIMIT_WINDOW_MS = 60_000;
const RATE_LIMIT_MAX_REQUESTS = 10;

export async function POST(request: Request) {
  const ip = getClientIp(request);
  const rate = checkRateLimit(`newsletter:${ip}`, RATE_LIMIT_MAX_REQUESTS, RATE_LIMIT_WINDOW_MS);

  if (!rate.allowed) {
    return NextResponse.json(
      { error: "Too many requests. Please try again shortly." },
      { status: 429 },
    );
  }

  let payload: unknown;
  try {
    payload = await request.json();
  } catch {
    return NextResponse.json({ error: "Invalid JSON payload." }, { status: 400 });
  }

  const parsed = bodySchema.safeParse(payload);
  if (!parsed.success) {
    return NextResponse.json({ error: "Please enter a valid email." }, { status: 422 });
  }

  const email = parsed.data.email;

  try {
    await db.insert(subscribers).values({
      email,
      createdAt: new Date(),
      updatedAt: new Date(),
    });

    return NextResponse.json({ success: true, status: "subscribed" });
  } catch (error: any) {
    const message = String(error?.message ?? "");
    const isDuplicate = message.includes("duplicate key") || message.includes("unique");
    if (isDuplicate) {
      return NextResponse.json({ success: true, status: "already_subscribed" });
    }

    console.error("Newsletter subscribe failed:", error);
    return NextResponse.json({ error: "Subscription failed." }, { status: 500 });
  }
}
