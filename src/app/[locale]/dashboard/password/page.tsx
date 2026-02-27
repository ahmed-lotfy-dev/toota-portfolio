import { PasswordSettingsForm } from "@/features/settings/components/PasswordSettingsForm";

export default function DashboardPasswordPage() {
  return (
    <section className="space-y-6">
      <div className="space-y-1">
        <h2 className="text-2xl font-black tracking-tight text-foreground">Password</h2>
        <p className="text-sm text-muted-foreground">Set or update your account password.</p>
      </div>

      <div className="rounded-3xl border border-border bg-card/60 p-6">
        <PasswordSettingsForm />
      </div>
    </section>
  );
}
