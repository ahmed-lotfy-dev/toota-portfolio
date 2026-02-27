"use client";

import { FormEvent, useState } from "react";
import { useLocale } from "next-intl";
import { useRouter } from "@/i18n/navigation";
import { Loader2, Mail, Lock } from "lucide-react";
import { signIn } from "@/lib/auth-client";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";

type CredentialSignInFormProps = {
  callbackURL?: string;
};

export function CredentialSignInForm({ callbackURL }: CredentialSignInFormProps) {
  const locale = useLocale();
  const router = useRouter();
  const [email, setEmail] = useState("");
  const [password, setPassword] = useState("");
  const [error, setError] = useState<string | null>(null);
  const [isSubmitting, setIsSubmitting] = useState(false);

  const onSubmit = async (event: FormEvent<HTMLFormElement>) => {
    event.preventDefault();
    setError(null);
    setIsSubmitting(true);

    try {
      const targetCallbackURL = callbackURL ?? `/${locale}/dashboard`;
      const result = await signIn.email({
        email: email.trim(),
        password,
        callbackURL: targetCallbackURL,
      });

      if ((result as { error?: { message?: string } } | undefined)?.error) {
        setError(result?.error?.message ?? "Invalid email or password.");
        return;
      }

      router.push(targetCallbackURL);
      router.refresh();
    } catch {
      setError("Unable to sign in with credentials. Please try again.");
    } finally {
      setIsSubmitting(false);
    }
  };

  return (
    <form onSubmit={onSubmit} className="w-full space-y-4">
      <div className="relative">
        <Mail className="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-zinc-500" />
        <Input
          type="email"
          value={email}
          onChange={(event) => setEmail(event.target.value)}
          placeholder="Email"
          autoComplete="email"
          required
          className="h-11 border-zinc-800 bg-zinc-900/80 pl-10 text-zinc-100 placeholder:text-zinc-500"
        />
      </div>

      <div className="relative">
        <Lock className="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-zinc-500" />
        <Input
          type="password"
          value={password}
          onChange={(event) => setPassword(event.target.value)}
          placeholder="Password"
          autoComplete="current-password"
          required
          className="h-11 border-zinc-800 bg-zinc-900/80 pl-10 text-zinc-100 placeholder:text-zinc-500"
        />
      </div>

      {error ? (
        <p className="text-left text-xs text-red-400">{error}</p>
      ) : null}

      <Button
        type="submit"
        size="sm"
        disabled={isSubmitting}
        className="h-11 w-full rounded-full bg-white text-black hover:bg-zinc-200"
      >
        {isSubmitting ? (
          <>
            <Loader2 className="mr-2 h-4 w-4 animate-spin" />
            Signing in...
          </>
        ) : (
          "Sign in with Email"
        )}
      </Button>
    </form>
  );
}
