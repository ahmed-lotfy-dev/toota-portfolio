import { TwoFactorSettingsPanel } from "@/features/settings/components/TwoFactorSettingsPanel";
import { getTranslations } from "next-intl/server";

export default async function DashboardTwoFactorPage() {
  const t = await getTranslations("DashboardTwoFactor");

  return (
    <section className="space-y-6">
      <div className="space-y-1">
        <h2 className="text-2xl font-black tracking-tight text-foreground">{t("title")}</h2>
        <p className="text-sm text-muted-foreground">{t("description")}</p>
      </div>

      <div className="rounded-3xl border border-border bg-card/60 p-6">
        <TwoFactorSettingsPanel />
      </div>
    </section>
  );
}
