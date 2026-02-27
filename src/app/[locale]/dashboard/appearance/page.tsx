import { AppearanceSettingsForm } from "@/features/settings/components/AppearanceSettingsForm";

export default function DashboardAppearancePage() {
  return (
    <section className="space-y-6">
      <div className="space-y-1">
        <h2 className="text-2xl font-black tracking-tight text-foreground">Appearance</h2>
        <p className="text-sm text-muted-foreground">Control visual theme preferences.</p>
      </div>

      <div className="rounded-3xl border border-border bg-card/60 p-6">
        <AppearanceSettingsForm />
      </div>
    </section>
  );
}
