import { BackupsSettingsPanel } from "@/features/settings/components/BackupsSettingsPanel";

export default function DashboardBackupsPage() {
  return (
    <section className="space-y-6">
      <div className="space-y-1">
        <h2 className="text-2xl font-black tracking-tight text-foreground">Backups</h2>
        <p className="text-sm text-muted-foreground">Backup and export operations from the legacy dashboard.</p>
      </div>

      <div className="rounded-3xl border border-border bg-card/60 p-6">
        <BackupsSettingsPanel />
      </div>
    </section>
  );
}
