"use client";

import { useEffect, useState } from "react";
import { Button } from "@/components/ui/button";
import { useTheme } from "next-themes";
import { useTranslations } from "next-intl";

export function AppearanceSettingsForm() {
  const { theme, setTheme } = useTheme();
  const [mounted, setMounted] = useState(false);
  const tTheme = useTranslations("Theme");

  useEffect(() => {
    setMounted(true);
  }, []);

  if (!mounted) {
    return null;
  }

  return (
    <div className="space-y-6">
      <p className="text-sm text-muted-foreground">
        Choose how the dashboard appearance should behave on this device.
      </p>

      <div className="flex flex-wrap gap-3">
        {(["light", "dark", "system"] as const).map((option) => (
          <Button
            key={option}
            type="button"
            variant={theme === option ? "default" : "outline"}
            onClick={() => setTheme(option)}
            className="rounded-full capitalize"
          >
            {tTheme(option)}
          </Button>
        ))}
      </div>
    </div>
  );
}
