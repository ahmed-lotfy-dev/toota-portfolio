import { Link } from "@/i18n/navigation";
import { getTranslations } from "next-intl/server";

export default async function NotFound() {
  const t = await getTranslations("Pages.notFound");

  return (
    <main className="mx-auto flex min-h-[70vh] w-full max-w-3xl flex-col items-center justify-center px-6 text-center">
      <p className="text-xs font-bold uppercase tracking-[0.2em] text-muted-foreground">404</p>
      <h1 className="mt-3 text-4xl font-black tracking-tight text-foreground">{t("title")}</h1>
      <p className="mt-4 max-w-xl text-sm text-muted-foreground">
        {t("description")}
      </p>
      <div className="mt-8">
        <Link
          href="/"
          className="rounded-xl bg-primary px-4 py-2 text-sm font-semibold text-primary-foreground hover:opacity-90"
        >
          {t("back_home")}
        </Link>
      </div>
    </main>
  );
}
