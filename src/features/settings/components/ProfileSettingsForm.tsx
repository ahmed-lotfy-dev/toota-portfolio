"use client";

import { FormEvent, useEffect, useState } from "react";
import { Loader2 } from "lucide-react";
import { authClient, useSession } from "@/lib/auth-client";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";

export function ProfileSettingsForm() {
  const { data: session, isPending, refetch } = useSession();
  const [name, setName] = useState("");
  const [status, setStatus] = useState<string | null>(null);
  const [error, setError] = useState<string | null>(null);
  const [isSaving, setIsSaving] = useState(false);

  useEffect(() => {
    if (session?.user?.name) {
      setName(session.user.name);
    }
  }, [session]);

  const onSubmit = async (event: FormEvent<HTMLFormElement>) => {
    event.preventDefault();
    setError(null);
    setStatus(null);
    setIsSaving(true);

    try {
      const result = await authClient.updateUser({ name: name.trim() });

      if (result.error) {
        setError(result.error.message ?? "Failed to update profile.");
        return;
      }

      await refetch();
      setStatus("Profile updated successfully.");
    } catch {
      setError("Failed to update profile.");
    } finally {
      setIsSaving(false);
    }
  };

  if (isPending) {
    return (
      <div className="flex items-center gap-2 text-sm text-muted-foreground">
        <Loader2 className="h-4 w-4 animate-spin" />
        Loading profile...
      </div>
    );
  }

  return (
    <form onSubmit={onSubmit} className="space-y-6">
      <div className="space-y-2">
        <Label htmlFor="profile-name" className="text-foreground">Name</Label>
        <Input
          id="profile-name"
          value={name}
          onChange={(event) => setName(event.target.value)}
          placeholder="Your name"
          required
          className="border-border bg-background text-foreground"
        />
      </div>

      <div className="space-y-2">
        <Label htmlFor="profile-email" className="text-foreground">Email</Label>
        <Input
          id="profile-email"
          value={session?.user?.email ?? ""}
          disabled
          className="border-border bg-muted text-muted-foreground"
        />
        <p className="text-xs text-muted-foreground">Email change is currently disabled.</p>
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
          "Save Profile"
        )}
      </Button>
    </form>
  );
}
