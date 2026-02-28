import { ProfileSettingsForm } from "@/features/settings/components/ProfileSettingsForm";
import { getTranslations } from "next-intl/server";

export default async function DashboardProfilePage() {
  const t = await getTranslations("DashboardProfile");

  return (
    <section className="space-y-6">
      <div className="space-y-1">
        <h2 className="text-2xl font-black tracking-tight text-foreground">{t("title")}</h2>
        <p className="text-sm text-muted-foreground">{t("description")}</p>
      </div>

      <div className="rounded-3xl border border-border bg-card/60 p-6">
        <ProfileSettingsForm />
      </div>
    </section>
  );
}
