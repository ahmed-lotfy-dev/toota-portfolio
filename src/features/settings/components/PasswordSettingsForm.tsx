"use client";

import { FormEvent, useState } from "react";
import { Loader2 } from "lucide-react";
import { authClient } from "@/lib/auth-client";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";

export function PasswordSettingsForm() {
  const [currentPassword, setCurrentPassword] = useState("");
  const [newPassword, setNewPassword] = useState("");
  const [confirmPassword, setConfirmPassword] = useState("");
  const [status, setStatus] = useState<string | null>(null);
  const [error, setError] = useState<string | null>(null);
  const [isSaving, setIsSaving] = useState(false);

  const resetFields = () => {
    setCurrentPassword("");
    setNewPassword("");
    setConfirmPassword("");
  };

  const onSubmit = async (event: FormEvent<HTMLFormElement>) => {
    event.preventDefault();
    setStatus(null);
    setError(null);

    if (newPassword !== confirmPassword) {
      setError("New password and confirmation do not match.");
      return;
    }

    setIsSaving(true);

    try {
      if (currentPassword.trim().length > 0) {
        const result = await authClient.changePassword({
          currentPassword,
          newPassword,
          revokeOtherSessions: true,
        });

        if (result.error) {
          setError(result.error.message ?? "Failed to change password.");
          return;
        }

        setStatus("Password changed successfully.");
        resetFields();
        return;
      }

      const result = await authClient.$fetch("/set-password", {
        method: "POST",
        body: { newPassword },
      });

      if ((result as { error?: { message?: string } } | undefined)?.error) {
        setError((result as { error?: { message?: string } }).error?.message ?? "Failed to set password.");
        return;
      }

      setStatus("Password set successfully.");
      resetFields();
    } catch {
      setError("Failed to update password.");
    } finally {
      setIsSaving(false);
    }
  };

  return (
    <form onSubmit={onSubmit} className="space-y-6">
      <p className="text-sm text-muted-foreground">
        If you signed up with Google only, leave current password empty to set one for the first time.
      </p>

      <div className="space-y-2">
        <Label htmlFor="current-password" className="text-foreground">Current password</Label>
        <Input
          id="current-password"
          type="password"
          value={currentPassword}
          onChange={(event) => setCurrentPassword(event.target.value)}
          className="border-border bg-background text-foreground"
          placeholder="Current password (optional for first setup)"
        />
      </div>

      <div className="space-y-2">
        <Label htmlFor="new-password" className="text-foreground">New password</Label>
        <Input
          id="new-password"
          type="password"
          value={newPassword}
          onChange={(event) => setNewPassword(event.target.value)}
          className="border-border bg-background text-foreground"
          minLength={8}
          required
        />
      </div>

      <div className="space-y-2">
        <Label htmlFor="confirm-password" className="text-foreground">Confirm new password</Label>
        <Input
          id="confirm-password"
          type="password"
          value={confirmPassword}
          onChange={(event) => setConfirmPassword(event.target.value)}
          className="border-border bg-background text-foreground"
          minLength={8}
          required
        />
      </div>

      {status ? <p className="text-sm text-emerald-400">{status}</p> : null}
      {error ? <p className="text-sm text-red-400">{error}</p> : null}

      <Button type="submit" disabled={isSaving} className="rounded-full">
        {isSaving ? (
          <>
            <Loader2 className="mr-2 h-4 w-4 animate-spin" />
            Saving...
          </>
        ) : (
          "Update Password"
        )}
      </Button>
    </form>
  );
}
