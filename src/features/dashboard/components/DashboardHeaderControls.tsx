"use client";

import { useEffect, useState } from "react";
import { useLocale } from "next-intl";
import { Languages, LaptopMinimal, LogOut, Moon, Sun } from "lucide-react";
import { Link, usePathname, useRouter } from "@/i18n/navigation";
import { signOut } from "@/lib/auth-client";
import { Button } from "@/components/ui/button";

type ThemeMode = "light" | "dark" | "system";
const THEME_KEY = "toota-theme";

function applyTheme(mode: ThemeMode) {
  if (typeof window === "undefined") {
    return;
  }

  const prefersDark = window.matchMedia("(prefers-color-scheme: dark)").matches;
  const useDark = mode === "dark" || (mode === "system" && prefersDark);
  document.documentElement.classList.toggle("dark", useDark);
}

export function DashboardHeaderControls() {
  const locale = useLocale();
  const pathname = usePathname();
  const router = useRouter();
  const [theme, setTheme] = useState<ThemeMode>("system");

  const href = pathname || "/dashboard";

  useEffect(() => {
    const saved = (localStorage.getItem(THEME_KEY) as ThemeMode | null) ?? "system";
    setTheme(saved);
    applyTheme(saved);
  }, []);

  const setThemeMode = (mode: ThemeMode) => {
    setTheme(mode);
    localStorage.setItem(THEME_KEY, mode);
    applyTheme(mode);
  };

  return (
    <div className="flex items-center gap-2">
      <div className="inline-flex items-center gap-2 rounded-full border border-border bg-card/80 px-3 py-1.5">
        <Sun className="h-3.5 w-3.5 text-muted-foreground" />
        <button
          type="button"
          onClick={() => setThemeMode("light")}
          className={`rounded-full px-2.5 py-1 text-xs font-bold tracking-wide transition ${
            theme === "light"
              ? "bg-primary text-primary-foreground"
              : "text-muted-foreground hover:bg-accent/15 hover:text-foreground"
          }`}
        >
          Light
        </button>
        <span className="text-muted-foreground/50">/</span>
        <button
          type="button"
          onClick={() => setThemeMode("system")}
          className={`rounded-full px-2.5 py-1 text-xs font-bold tracking-wide transition ${
            theme === "system"
              ? "bg-accent text-accent-foreground"
              : "text-muted-foreground hover:bg-accent/15 hover:text-foreground"
          }`}
        >
          <span className="inline-flex items-center gap-1">
            <LaptopMinimal className="h-3.5 w-3.5" />
            System
          </span>
        </button>
        <span className="text-muted-foreground/50">/</span>
        <button
          type="button"
          onClick={() => setThemeMode("dark")}
          className={`rounded-full px-2.5 py-1 text-xs font-bold tracking-wide transition ${
            theme === "dark"
              ? "bg-primary text-primary-foreground"
              : "text-muted-foreground hover:bg-accent/15 hover:text-foreground"
          }`}
        >
          Dark
        </button>
        <Moon className="h-3.5 w-3.5 text-muted-foreground" />
      </div>

      <div className="inline-flex items-center gap-2 rounded-full border border-border bg-card/80 px-3 py-1.5">
        <Languages className="h-3.5 w-3.5 text-muted-foreground" />
        <Link
          href={href}
          locale="en"
          className={`text-xs font-bold tracking-wide transition ${
            locale === "en" ? "text-foreground" : "text-muted-foreground hover:text-foreground"
          }`}
        >
          EN
        </Link>
        <span className="text-muted-foreground/50">/</span>
        <Link
          href={href}
          locale="ar"
          className={`text-xs font-bold tracking-wide transition ${
            locale === "ar" ? "text-foreground" : "text-muted-foreground hover:text-foreground"
          }`}
        >
          AR
        </Link>
      </div>

      <Button
        variant="outline"
        size="sm"
        className="rounded-full border-border bg-card/80 text-foreground hover:bg-accent hover:text-accent-foreground"
        onClick={async () => {
          await signOut();
          router.replace("/auth/login", { locale });
          router.refresh();
        }}
      >
        <LogOut className="mr-2 h-4 w-4" />
        Sign Out
      </Button>
    </div>
  );
}
