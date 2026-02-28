"use client";

import { useState } from "react";
import { LogOut } from "lucide-react";
import { useLocale, useTranslations } from "next-intl";
import { signOut } from "@/lib/auth-client";

type SignOutActionProps = {
  className?: string;
  showLabel?: boolean;
  onDone?: () => void;
};

export function SignOutAction({ className, showLabel = true, onDone }: SignOutActionProps) {
  const t = useTranslations("Auth");
  const locale = useLocale();
  const [isLoading, setIsLoading] = useState(false);

  const handleSignOut = async () => {
    if (isLoading) return;
    setIsLoading(true);
    try {
      await signOut();
      onDone?.();
      window.location.href = `/${locale}`;
    } finally {
      setIsLoading(false);
    }
  };

  return (
    <button
      type="button"
      onClick={() => void handleSignOut()}
      disabled={isLoading}
      className={className}
      aria-label={t("signOut")}
      title={t("signOut")}
    >
      <LogOut className="h-5 w-5" />
      {showLabel ? <span>{t("signOut")}</span> : null}
    </button>
  );
}
