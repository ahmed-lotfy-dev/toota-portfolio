"use client";

import { useState } from "react";
import Image from "next/image";
import { Loader2 } from "lucide-react";
import { useSession } from "@/lib/auth-client";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";

type EnableResponse = {
  totpURI?: string;
  backupCodes?: string[];
  error?: { message?: string };
};

export function TwoFactorSettingsPanel() {
  const { data: session, refetch } = useSession();
  const [password, setPassword] = useState("");
  const [otpCode, setOtpCode] = useState("");
  const [totpUri, setTotpUri] = useState<string | null>(null);
  const [backupCodes, setBackupCodes] = useState<string[]>([]);
  const [status, setStatus] = useState<string | null>(null);
  const [error, setError] = useState<string | null>(null);
  const [loadingAction, setLoadingAction] = useState<string | null>(null);

  const isEnabled = Boolean((session?.user as any)?.twoFactorEnabled);
  const qrCodeUrl = totpUri
    ? `https://api.qrserver.com/v1/create-qr-code/?size=220x220&data=${encodeURIComponent(totpUri)}`
    : null;

  const clearMessages = () => {
    setStatus(null);
    setError(null);
  };

  const enableTwoFactor = async () => {
    clearMessages();
    setLoadingAction("enable");
    try {
      const response = await fetch("/api/auth/two-factor/enable", {
        method: "POST",
        headers: { "content-type": "application/json" },
        body: JSON.stringify({
          password,
          issuer: "Toota Art",
        }),
      });
      const data = (await response.json()) as EnableResponse;
      if (!response.ok) {
        throw new Error(data?.error?.message || "Failed to enable 2FA.");
      }

      setTotpUri(data.totpURI || null);
      setBackupCodes(data.backupCodes || []);
      setStatus("Two-factor setup initialized. Scan QR then verify your code.");
    } catch (e: any) {
      setError(e?.message || "Failed to enable 2FA.");
    } finally {
      setLoadingAction(null);
    }
  };

  const verifyTotp = async () => {
    clearMessages();
    setLoadingAction("verify");
    try {
      const response = await fetch("/api/auth/two-factor/verify-totp", {
        method: "POST",
        headers: { "content-type": "application/json" },
        body: JSON.stringify({
          code: otpCode,
          trustDevice: true,
        }),
      });
      const data = (await response.json().catch(() => ({}))) as { error?: { message?: string } };
      if (!response.ok) {
        throw new Error(data?.error?.message || "Invalid verification code.");
      }

      setStatus("Two-factor authentication enabled.");
      setOtpCode("");
      await refetch();
    } catch (e: any) {
      setError(e?.message || "Failed to verify code.");
    } finally {
      setLoadingAction(null);
    }
  };

  const disableTwoFactor = async () => {
    clearMessages();
    setLoadingAction("disable");
    try {
      const response = await fetch("/api/auth/two-factor/disable", {
        method: "POST",
        headers: { "content-type": "application/json" },
        body: JSON.stringify({
          password,
        }),
      });
      const data = (await response.json().catch(() => ({}))) as { error?: { message?: string } };
      if (!response.ok) {
        throw new Error(data?.error?.message || "Failed to disable 2FA.");
      }

      setStatus("Two-factor authentication disabled.");
      setTotpUri(null);
      setBackupCodes([]);
      setOtpCode("");
      await refetch();
    } catch (e: any) {
      setError(e?.message || "Failed to disable 2FA.");
    } finally {
      setLoadingAction(null);
    }
  };

  const regenerateBackupCodes = async () => {
    clearMessages();
    setLoadingAction("regenerate");
    try {
      const response = await fetch("/api/auth/two-factor/generate-backup-codes", {
        method: "POST",
        headers: { "content-type": "application/json" },
        body: JSON.stringify({ password }),
      });
      const data = (await response.json().catch(() => ({}))) as { backupCodes?: string[]; error?: { message?: string } };
      if (!response.ok) {
        throw new Error(data?.error?.message || "Failed to regenerate backup codes.");
      }
      setBackupCodes(data.backupCodes || []);
      setStatus("Backup codes regenerated.");
    } catch (e: any) {
      setError(e?.message || "Failed to regenerate backup codes.");
    } finally {
      setLoadingAction(null);
    }
  };

  return (
    <div className="space-y-6">
      <p className="text-sm text-muted-foreground">
        Status: <span className="font-semibold text-foreground">{isEnabled ? "Enabled" : "Disabled"}</span>
      </p>

      <div className="space-y-2">
        <label htmlFor="2fa-password" className="text-sm font-medium text-foreground">
          Account password
        </label>
        <Input
          id="2fa-password"
          type="password"
          value={password}
          onChange={(e) => setPassword(e.target.value)}
          placeholder="Required for 2FA operations"
        />
      </div>

      {!isEnabled ? (
        <Button type="button" onClick={() => void enableTwoFactor()} disabled={loadingAction === "enable" || !password}>
          {loadingAction === "enable" ? <Loader2 className="mr-2 h-4 w-4 animate-spin" /> : null}
          Enable two-factor
        </Button>
      ) : (
        <div className="flex flex-wrap gap-2">
          <Button type="button" variant="outline" onClick={() => void regenerateBackupCodes()} disabled={loadingAction === "regenerate" || !password}>
            {loadingAction === "regenerate" ? <Loader2 className="mr-2 h-4 w-4 animate-spin" /> : null}
            Regenerate backup codes
          </Button>
          <Button type="button" variant="destructive" onClick={() => void disableTwoFactor()} disabled={loadingAction === "disable" || !password}>
            {loadingAction === "disable" ? <Loader2 className="mr-2 h-4 w-4 animate-spin" /> : null}
            Disable two-factor
          </Button>
        </div>
      )}

      {totpUri ? (
        <div className="rounded-2xl border border-border bg-card/70 p-5">
          <h3 className="text-sm font-bold uppercase tracking-wider text-foreground">Authenticator setup</h3>
          <div className="mt-3 flex flex-col gap-4 md:flex-row md:items-start">
            {qrCodeUrl ? (
              <Image
                src={qrCodeUrl}
                alt="Two-factor QR code"
                width={220}
                height={220}
                className="h-52 w-52 rounded-lg border border-border bg-white p-2"
              />
            ) : null}
            <div className="space-y-2">
              <p className="text-xs text-muted-foreground break-all">{totpUri}</p>
              <div className="flex flex-col gap-2 md:flex-row">
                <Input
                  value={otpCode}
                  onChange={(e) => setOtpCode(e.target.value)}
                  placeholder="6-digit code"
                  maxLength={8}
                />
                <Button type="button" onClick={() => void verifyTotp()} disabled={loadingAction === "verify" || otpCode.length < 6}>
                  {loadingAction === "verify" ? <Loader2 className="mr-2 h-4 w-4 animate-spin" /> : null}
                  Verify code
                </Button>
              </div>
            </div>
          </div>
        </div>
      ) : null}

      {backupCodes.length > 0 ? (
        <div className="rounded-2xl border border-border bg-card/70 p-5">
          <h3 className="text-sm font-bold uppercase tracking-wider text-foreground">Backup codes</h3>
          <p className="mt-1 text-xs text-muted-foreground">Store these in a safe place. Each code can be used once.</p>
          <div className="mt-3 grid gap-2 md:grid-cols-2">
            {backupCodes.map((code) => (
              <code key={code} className="rounded-md border border-border bg-background px-3 py-2 text-xs">
                {code}
              </code>
            ))}
          </div>
        </div>
      ) : null}

      {status ? <p className="text-sm text-emerald-500">{status}</p> : null}
      {error ? <p className="text-sm text-red-500">{error}</p> : null}
    </div>
  );
}
