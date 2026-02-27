import { TwoFactorSettingsPanel } from "@/features/settings/components/TwoFactorSettingsPanel";

export default function DashboardTwoFactorPage() {
  return (
    <section className="space-y-6">
      <div className="space-y-1">
        <h2 className="text-2xl font-black tracking-tight text-foreground">Two-Factor Authentication</h2>
        <p className="text-sm text-muted-foreground">Secure your account with a second verification step.</p>
      </div>

      <div className="rounded-3xl border border-border bg-card/60 p-6">
        <TwoFactorSettingsPanel />
      </div>
    </section>
  );
}
