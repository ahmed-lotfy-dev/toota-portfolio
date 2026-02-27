import { NextResponse } from "next/server";
import { getAdminSession } from "@/features/auth/guard";

export async function requireAdminApi() {
  const session = await getAdminSession();
  if (!session) {
    return {
      error: NextResponse.json({ error: "Unauthorized" }, { status: 401 }),
      session: null,
    };
  }

  return { error: null, session };
}
