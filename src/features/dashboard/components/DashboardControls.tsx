"use client";

import { useEffect, useState } from "react";
import { useLocale, useTranslations } from "next-intl";
import { LogOut, LaptopMinimal, Moon, Sun } from "lucide-react";
import { Link, usePathname, useRouter } from "@/i18n/navigation";
import { signOut } from "@/lib/auth-client";
import { Button } from "@/components/ui/button";
import { useTheme } from "next-themes";
import { cn } from "@/lib/utils";

type DashboardControlsProps = {
  className?: string;
};

export function DashboardControls({ className }: DashboardControlsProps) {
  const locale = useLocale();
  const pathname = usePathname();
  const router = useRouter();
  const { theme, setTheme } = useTheme();
  const [mounted, setMounted] = useState(false);
  const tTheme = useTranslations("Theme");
  const tAuth = useTranslations("Auth");

  const href = pathname || "/dashboard";

  useEffect(() => {
    setMounted(true);
  }, []);

  if (!mounted) {
    return null;
  }

  const activeLangClass = "bg-primary text-primary-foreground shadow-sm ring-1 ring-primary/40";
  const inactiveLangClass = "text-muted-foreground hover:text-foreground hover:bg-accent/15";

  return (
    <div className={cn("flex flex-col gap-4", className)}>
      <div className="flex flex-col gap-2">
        <p className="text-[10px] font-bold uppercase tracking-[0.25em] text-muted-foreground">
          Theme
        </p>
        <div className="inline-flex w-full items-center justify-start gap-2 rounded-xl border border-border bg-card p-2">
          {[
            { value: "light", label: tTheme("light"), icon: Sun },
            { value: "dark", label: tTheme("dark"), icon: Moon },
            { value: "system", label: tTheme("system"), icon: LaptopMinimal },
          ].map((item) => {
            const active = (theme ?? "system") === item.value;
            const Icon = item.icon;
            return (
              <button
                key={item.value}
                type="button"
                onClick={() => setTheme(item.value)}
                className={cn(
                  "h-9 w-9 rounded-full border border-transparent transition",
                  active
                    ? "bg-primary text-primary-foreground shadow-sm ring-1 ring-primary/40"
                    : "text-muted-foreground hover:text-foreground hover:bg-accent/15"
                )}
                aria-label={item.label}
                title={item.label}
              >
                <Icon className="h-4 w-4 mx-auto" />
              </button>
            );
          })}
        </div>
      </div>

      <div className="flex flex-col gap-2">
        <p className="text-[10px] font-bold uppercase tracking-[0.25em] text-muted-foreground">
          Language
        </p>
        <div className="inline-flex w-full items-center justify-start gap-2 rounded-xl border border-border bg-card p-2">
          <Link
            href={href}
            locale="en"
            className={cn(
              "h-9 w-9 rounded-full border border-transparent transition flex items-center justify-center text-[11px] font-black uppercase tracking-widest",
              locale === "en" ? activeLangClass : inactiveLangClass,
            )}
            aria-label="English"
            title="English"
          >
            EN
          </Link>
          <Link
            href={href}
            locale="ar"
            className={cn(
              "h-9 w-9 rounded-full border border-transparent transition flex items-center justify-center text-[11px] font-black uppercase tracking-widest",
              locale === "ar" ? activeLangClass : inactiveLangClass,
            )}
            aria-label="Arabic"
            title="Arabic"
          >
            AR
          </Link>
        </div>
      </div>

      <Button
        variant="outline"
        className="h-11 w-full rounded-xl border-destructive/30 bg-destructive/15 text-destructive hover:bg-destructive/25 hover:text-destructive"
        onClick={async () => {
          await signOut();
          router.replace("/auth/login", { locale });
          router.refresh();
        }}
      >
        <LogOut className="mr-2 h-4 w-4" />
        {tAuth("signOut")}
      </Button>
    </div>
  );
}
