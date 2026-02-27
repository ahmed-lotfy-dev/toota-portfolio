import { ProfileSettingsForm } from "@/features/settings/components/ProfileSettingsForm";

export default function DashboardProfilePage() {
  return (
    <section className="space-y-6">
      <div className="space-y-1">
        <h2 className="text-2xl font-black tracking-tight text-foreground">Profile</h2>
        <p className="text-sm text-muted-foreground">Manage your basic account information.</p>
      </div>

      <div className="rounded-3xl border border-border bg-card/60 p-6">
        <ProfileSettingsForm />
      </div>
    </section>
  );
}
