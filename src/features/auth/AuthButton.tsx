"use client";

import { useState } from "react";
import { signIn, signOut, useSession } from "@/lib/auth-client";
import { Button } from "@/components/ui/button";
import { useLocale, useTranslations } from "next-intl";
import { LogIn, LogOut, Loader2, ShieldCheck } from "lucide-react";
import { Link } from "@/i18n/navigation";

type AuthButtonProps = {
  callbackURL?: string;
};

export function AuthButton({ callbackURL }: AuthButtonProps) {
  const { data: session, isPending } = useSession();
  const t = useTranslations("Auth");
  const locale = useLocale();
  const [isSigningIn, setIsSigningIn] = useState(false);
  const adminEmail = (process.env.NEXT_PUBLIC_ADMIN_EMAIL ?? "").trim().toLowerCase();

  if (isPending) {
    return (
      <Button variant="ghost" disabled size="sm">
        <Loader2 className="h-4 w-4 animate-spin" />
      </Button>
    );
  }

  if (session) {
    return (
      <div className="flex items-center gap-4">
        {session.user.email.trim().toLowerCase() === adminEmail && (
          <Button variant="outline" size="sm" asChild className="hidden md:flex border-blue-200 dark:border-blue-900 bg-blue-50/50 dark:bg-blue-950/20 text-blue-700 dark:text-blue-300">
            <Link href="/dashboard">
              <ShieldCheck className="mr-2 h-4 w-4" />
              {t("adminOnly")}
            </Link>
          </Button>
        )}
        <Button
          variant="ghost"
          size="sm"
          onClick={async () => await signOut()}
          className="text-zinc-500 hover:text-red-600 transition-colors"
        >
          <LogOut className="mr-2 h-4 w-4" />
          {t("signOut")}
        </Button>
      </div>
    );
  }

  return (
    <Button
      variant="default"
      size="sm"
      disabled={isSigningIn}
      onClick={async () => {
        try {
          setIsSigningIn(true);
          const targetCallbackURL = callbackURL ?? `/${locale}/dashboard`;
          const result = await signIn.social({
            provider: "google",
            callbackURL: targetCallbackURL,
            newUserCallbackURL: targetCallbackURL,
            errorCallbackURL: `/${locale}/auth/login?callbackURL=${encodeURIComponent(targetCallbackURL)}`,
          });

          if ((result as { error?: unknown } | undefined)?.error) {
            console.error("Google sign-in failed", result);
            alert("Google sign-in failed. Check OAuth redirect URI and app URL.");
          }
        } catch (error) {
          console.error("Google sign-in request failed", error);
          alert("Google sign-in failed. Check OAuth redirect URI and app URL.");
        } finally {
          setIsSigningIn(false);
        }
      }}
      className="bg-zinc-900 dark:bg-zinc-100 text-white dark:text-zinc-900 rounded-full px-6"
    >
      {isSigningIn ? (
        <>
          <Loader2 className="mr-2 h-4 w-4 animate-spin" />
          Signing in...
        </>
      ) : (
        <>
          <LogIn className="mr-2 h-4 w-4" />
          {t("signIn")}
        </>
      )}
    </Button>
  );
}
