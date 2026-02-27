"use client";

import { useEffect, useMemo, useState } from "react";
import { Loader2, Download, Trash2, UploadCloud, RefreshCw } from "lucide-react";
import { Button } from "@/components/ui/button";

type BackupType = "json" | "sql" | "media" | "full";
type Frequency = "daily" | "weekly" | "monthly";

type CloudFile = {
  key: string;
  size: number;
  lastModified: string | null;
};

type Schedule = {
  enabled: boolean;
  frequency: Frequency;
  time: string;
  keepDaily: number;
  keepWeekly: number;
  keepMonthly: number;
};

const defaultSchedule: Schedule = {
  enabled: false,
  frequency: "weekly",
  time: "01:00",
  keepDaily: 7,
  keepWeekly: 4,
  keepMonthly: 3,
};

function bytesToHuman(bytes: number) {
  if (bytes < 1024) return `${bytes} B`;
  if (bytes < 1024 * 1024) return `${(bytes / 1024).toFixed(1)} KB`;
  if (bytes < 1024 * 1024 * 1024) return `${(bytes / (1024 * 1024)).toFixed(1)} MB`;
  return `${(bytes / (1024 * 1024 * 1024)).toFixed(2)} GB`;
}

export function BackupsSettingsPanel() {
  const [cloudFiles, setCloudFiles] = useState<CloudFile[]>([]);
  const [loadingCloudFiles, setLoadingCloudFiles] = useState(false);
  const [schedule, setSchedule] = useState<Schedule>(defaultSchedule);
  const [loadingSchedule, setLoadingSchedule] = useState(false);
  const [workingType, setWorkingType] = useState<string | null>(null);
  const [message, setMessage] = useState<string | null>(null);
  const [error, setError] = useState<string | null>(null);

  const sortedFiles = useMemo(
    () => [...cloudFiles].sort((a, b) => (b.lastModified || "").localeCompare(a.lastModified || "")),
    [cloudFiles],
  );

  const loadCloudFiles = async () => {
    setLoadingCloudFiles(true);
    setError(null);

    try {
      const response = await fetch("/api/backups/cloud");
      const data = (await response.json()) as { files?: CloudFile[]; error?: string };
      if (!response.ok) throw new Error(data.error || "Failed to load cloud backups.");
      setCloudFiles(data.files || []);
    } catch (e: any) {
      setError(e?.message || "Failed to load cloud backups.");
    } finally {
      setLoadingCloudFiles(false);
    }
  };

  const loadSchedule = async () => {
    setLoadingSchedule(true);
    try {
      const response = await fetch("/api/backups/schedule");
      const data = (await response.json()) as { schedule?: Schedule; error?: string };
      if (!response.ok) throw new Error(data.error || "Failed to load schedule.");
      if (data.schedule) setSchedule(data.schedule);
    } catch (e: any) {
      setError(e?.message || "Failed to load schedule.");
    } finally {
      setLoadingSchedule(false);
    }
  };

  useEffect(() => {
    void loadCloudFiles();
    void loadSchedule();
  }, []);

  const downloadExport = (type: BackupType) => {
    setMessage(null);
    setError(null);
    window.location.href = `/api/backups/export?type=${type}`;
  };

  const uploadToCloud = async (type: BackupType) => {
    setWorkingType(`upload-${type}`);
    setMessage(null);
    setError(null);
    try {
      const response = await fetch("/api/backups/cloud", {
        method: "POST",
        headers: { "content-type": "application/json" },
        body: JSON.stringify({ type }),
      });
      const data = (await response.json()) as { success?: boolean; error?: string };
      if (!response.ok) throw new Error(data.error || "Upload failed.");
      setMessage("Backup uploaded to cloud.");
      await loadCloudFiles();
    } catch (e: any) {
      setError(e?.message || "Upload failed.");
    } finally {
      setWorkingType(null);
    }
  };

  const deleteCloudFile = async (key: string) => {
    if (!confirm("Delete this backup from cloud storage?")) return;
    setWorkingType(`delete-${key}`);
    setMessage(null);
    setError(null);
    try {
      const response = await fetch(`/api/backups/cloud?key=${encodeURIComponent(key)}`, {
        method: "DELETE",
      });
      const data = (await response.json()) as { success?: boolean; error?: string };
      if (!response.ok) throw new Error(data.error || "Delete failed.");
      setMessage("Backup deleted from cloud.");
      await loadCloudFiles();
    } catch (e: any) {
      setError(e?.message || "Delete failed.");
    } finally {
      setWorkingType(null);
    }
  };

  const saveSchedule = async () => {
    setWorkingType("save-schedule");
    setMessage(null);
    setError(null);
    try {
      const response = await fetch("/api/backups/schedule", {
        method: "POST",
        headers: { "content-type": "application/json" },
        body: JSON.stringify(schedule),
      });
      const data = (await response.json()) as { success?: boolean; error?: string };
      if (!response.ok) throw new Error(data.error || "Failed to save schedule.");
      setMessage("Backup schedule saved.");
    } catch (e: any) {
      setError(e?.message || "Failed to save schedule.");
    } finally {
      setWorkingType(null);
    }
  };

  return (
    <div className="space-y-8">
      <div className="grid gap-4 md:grid-cols-2">
        <div className="rounded-2xl border border-border bg-card/70 p-5">
          <h3 className="text-sm font-bold uppercase tracking-wider text-foreground">Local exports</h3>
          <p className="mt-2 text-sm text-muted-foreground">Generate and download backup files from the current database and media store.</p>
          <div className="mt-4 flex flex-wrap gap-2">
            {(["json", "sql", "media", "full"] as BackupType[]).map((type) => (
              <Button key={type} type="button" variant="outline" onClick={() => downloadExport(type)}>
                <Download className="mr-2 h-4 w-4" />
                {type.toUpperCase()}
              </Button>
            ))}
          </div>
        </div>

        <div className="rounded-2xl border border-border bg-card/70 p-5">
          <h3 className="text-sm font-bold uppercase tracking-wider text-foreground">Cloud uploads</h3>
          <p className="mt-2 text-sm text-muted-foreground">Build a backup artifact and upload it to Cloudflare R2.</p>
          <div className="mt-4 flex flex-wrap gap-2">
            {(["json", "sql", "media", "full"] as BackupType[]).map((type) => (
              <Button
                key={`upload-${type}`}
                type="button"
                onClick={() => uploadToCloud(type)}
                disabled={workingType === `upload-${type}`}
              >
                {workingType === `upload-${type}` ? <Loader2 className="mr-2 h-4 w-4 animate-spin" /> : <UploadCloud className="mr-2 h-4 w-4" />}
                Upload {type.toUpperCase()}
              </Button>
            ))}
          </div>
        </div>
      </div>

      <div className="rounded-2xl border border-border bg-card/70 p-5">
        <div className="mb-4 flex items-center justify-between gap-2">
          <h3 className="text-sm font-bold uppercase tracking-wider text-foreground">Cloud backup files</h3>
          <Button type="button" variant="outline" onClick={() => void loadCloudFiles()} disabled={loadingCloudFiles}>
            {loadingCloudFiles ? <Loader2 className="mr-2 h-4 w-4 animate-spin" /> : <RefreshCw className="mr-2 h-4 w-4" />}
            Refresh
          </Button>
        </div>

        <div className="space-y-2">
          {sortedFiles.length === 0 ? (
            <p className="text-sm text-muted-foreground">No cloud backups found.</p>
          ) : (
            sortedFiles.map((file) => (
              <div key={file.key} className="flex flex-col gap-3 rounded-xl border border-border/60 p-3 md:flex-row md:items-center md:justify-between">
                <div>
                  <p className="text-sm font-medium text-foreground">{file.key}</p>
                  <p className="text-xs text-muted-foreground">
                    {bytesToHuman(file.size)} {file.lastModified ? `• ${new Date(file.lastModified).toLocaleString()}` : ""}
                  </p>
                </div>
                <div className="flex gap-2">
                  <Button
                    type="button"
                    variant="outline"
                    onClick={() => {
                      window.location.href = `/api/backups/cloud/download?key=${encodeURIComponent(file.key)}`;
                    }}
                  >
                    <Download className="mr-2 h-4 w-4" />
                    Download
                  </Button>
                  <Button
                    type="button"
                    variant="destructive"
                    onClick={() => void deleteCloudFile(file.key)}
                    disabled={workingType === `delete-${file.key}`}
                  >
                    {workingType === `delete-${file.key}` ? <Loader2 className="mr-2 h-4 w-4 animate-spin" /> : <Trash2 className="mr-2 h-4 w-4" />}
                    Delete
                  </Button>
                </div>
              </div>
            ))
          )}
        </div>
      </div>

      <div className="rounded-2xl border border-border bg-card/70 p-5">
        <h3 className="text-sm font-bold uppercase tracking-wider text-foreground">Backup schedule</h3>
        <p className="mt-2 text-sm text-muted-foreground">
          Store scheduling preferences for your cron worker. The app records settings; execution should run via your server scheduler.
        </p>
        <div className="mt-4 grid gap-4 md:grid-cols-3">
          <label className="flex items-center gap-2 text-sm text-foreground">
            <input
              type="checkbox"
              checked={schedule.enabled}
              onChange={(e) => setSchedule((prev) => ({ ...prev, enabled: e.target.checked }))}
            />
            Enable schedule
          </label>
          <label className="flex flex-col gap-1 text-sm text-foreground">
            Frequency
            <select
              value={schedule.frequency}
              onChange={(e) => setSchedule((prev) => ({ ...prev, frequency: e.target.value as Frequency }))}
              className="rounded-md border border-border bg-background px-3 py-2"
            >
              <option value="daily">Daily</option>
              <option value="weekly">Weekly</option>
              <option value="monthly">Monthly</option>
            </select>
          </label>
          <label className="flex flex-col gap-1 text-sm text-foreground">
            Time (HH:MM)
            <input
              type="time"
              value={schedule.time}
              onChange={(e) => setSchedule((prev) => ({ ...prev, time: e.target.value }))}
              className="rounded-md border border-border bg-background px-3 py-2"
            />
          </label>
        </div>
        <div className="mt-4 grid gap-4 md:grid-cols-3">
          <label className="flex flex-col gap-1 text-sm text-foreground">
            Keep daily
            <input
              type="number"
              min={1}
              max={365}
              value={schedule.keepDaily}
              onChange={(e) => setSchedule((prev) => ({ ...prev, keepDaily: Number(e.target.value) || 1 }))}
              className="rounded-md border border-border bg-background px-3 py-2"
            />
          </label>
          <label className="flex flex-col gap-1 text-sm text-foreground">
            Keep weekly
            <input
              type="number"
              min={1}
              max={52}
              value={schedule.keepWeekly}
              onChange={(e) => setSchedule((prev) => ({ ...prev, keepWeekly: Number(e.target.value) || 1 }))}
              className="rounded-md border border-border bg-background px-3 py-2"
            />
          </label>
          <label className="flex flex-col gap-1 text-sm text-foreground">
            Keep monthly
            <input
              type="number"
              min={1}
              max={24}
              value={schedule.keepMonthly}
              onChange={(e) => setSchedule((prev) => ({ ...prev, keepMonthly: Number(e.target.value) || 1 }))}
              className="rounded-md border border-border bg-background px-3 py-2"
            />
          </label>
        </div>
        <div className="mt-4 flex items-center gap-2">
          <Button type="button" onClick={() => void saveSchedule()} disabled={workingType === "save-schedule" || loadingSchedule}>
            {workingType === "save-schedule" ? <Loader2 className="mr-2 h-4 w-4 animate-spin" /> : null}
            Save schedule
          </Button>
        </div>
      </div>

      {message ? <p className="text-sm text-emerald-500">{message}</p> : null}
      {error ? <p className="text-sm text-red-500">{error}</p> : null}
    </div>
  );
}
