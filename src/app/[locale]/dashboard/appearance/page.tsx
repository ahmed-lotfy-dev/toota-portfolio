import { AppearanceSettingsForm } from "@/features/settings/components/AppearanceSettingsForm";
import { getTranslations } from "next-intl/server";

export default async function DashboardAppearancePage() {
  const t = await getTranslations("DashboardAppearance");

  return (
    <section className="space-y-6">
      <div className="space-y-1">
        <h2 className="text-2xl font-black tracking-tight text-foreground">{t("title")}</h2>
        <p className="text-sm text-muted-foreground">{t("description")}</p>
      </div>

      <div className="rounded-3xl border border-border bg-card/60 p-6">
        <AppearanceSettingsForm />
      </div>
    </section>
  );
}
