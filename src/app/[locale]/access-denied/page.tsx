import { Link } from "@/i18n/navigation";
import { SignOutAction } from "@/features/auth/SignOutAction";
import { getTranslations } from "next-intl/server";

export default async function AccessDeniedPage() {
  const t = await getTranslations("Pages.accessDenied");

  return (
    <main className="mx-auto flex min-h-[70vh] w-full max-w-3xl flex-col items-center justify-center px-6 text-center">
      <p className="text-xs font-bold uppercase tracking-[0.2em] text-muted-foreground">403</p>
      <h1 className="mt-3 text-4xl font-black tracking-tight text-foreground">{t("title")}</h1>
      <p className="mt-4 max-w-xl text-sm text-muted-foreground">
        {t("description")}
      </p>
      <div className="mt-8 flex flex-wrap items-center justify-center gap-3">
        <Link
          href="/"
          className="rounded-xl border border-border px-4 py-2 text-sm font-semibold text-foreground hover:bg-accent"
        >
          {t("go_home")}
        </Link>
        <Link
          href="/auth/login"
          className="rounded-xl bg-primary px-4 py-2 text-sm font-semibold text-primary-foreground hover:opacity-90"
        >
          {t("sign_in")}
        </Link>
        <SignOutAction className="inline-flex items-center gap-2 rounded-xl border border-border px-4 py-2 text-sm font-semibold text-foreground hover:bg-accent disabled:opacity-60" />
      </div>
    </main>
  );
}
