"use client";

import { useEffect, useState } from "react";
import { Button } from "@/components/ui/button";

type ThemeMode = "light" | "dark" | "system";

const STORAGE_KEY = "toota-theme";

function applyTheme(mode: ThemeMode) {
  if (typeof window === "undefined") {
    return;
  }

  const root = document.documentElement;
  const systemDark = window.matchMedia("(prefers-color-scheme: dark)").matches;
  const shouldUseDark = mode === "dark" || (mode === "system" && systemDark);

  root.classList.toggle("dark", shouldUseDark);
}

export function AppearanceSettingsForm() {
  const [mode, setMode] = useState<ThemeMode>("system");

  useEffect(() => {
    const stored = localStorage.getItem(STORAGE_KEY) as ThemeMode | null;
    const nextMode = stored ?? "system";
    setMode(nextMode);
    applyTheme(nextMode);
  }, []);

  const setTheme = (nextMode: ThemeMode) => {
    setMode(nextMode);
    localStorage.setItem(STORAGE_KEY, nextMode);
    applyTheme(nextMode);
  };

  return (
    <div className="space-y-6">
      <p className="text-sm text-muted-foreground">
        Choose how the dashboard appearance should behave on this device.
      </p>

      <div className="flex flex-wrap gap-3">
        {(["light", "dark", "system"] as ThemeMode[]).map((option) => (
          <Button
            key={option}
            type="button"
            variant={mode === option ? "default" : "outline"}
            onClick={() => setTheme(option)}
            className="rounded-full capitalize"
          >
            {option}
          </Button>
        ))}
      </div>
    </div>
  );
}
