import { auth } from "@/lib/auth";
import { isAdminEmail } from "@/lib/admin-emails";
import { headers } from "next/headers";
import { redirect } from "next/navigation";

export async function getSession() {
  return auth.api.getSession({
    headers: await headers(),
  });
}

export async function getAdminSession() {
  const session = await getSession();

  if (!session || !isAdminEmail(session.user.email)) {
    return null;
  }

  return session;
}

export async function requireAdmin(locale?: string, callbackURL?: string) {
  const session = await getSession();

  if (!session) {
    if (locale) {
      const nextPath = callbackURL ?? `/${locale}/dashboard`;
      redirect(`/${locale}/auth/login?callbackURL=${encodeURIComponent(nextPath)}`);
    }

    redirect("/auth/login");
  }

  if (!isAdminEmail(session.user.email)) {
    if (locale) {
      redirect(`/${locale}/access-denied`);
    }
    redirect("/access-denied");
  }

  return session;
}
