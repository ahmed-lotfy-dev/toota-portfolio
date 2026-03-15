"use client";

import { Link, usePathname } from "@/i18n/navigation";
import { useLocale, useTranslations } from "next-intl";
import {
  FolderKanban,
  HardDriveDownload,
  Image as ImageIcon,
  LayoutDashboard,
  Link as LinkIcon,
  Lock,
  MessageSquareQuote,
  Settings2,
  ShieldCheck,
  Tags,
  UserCircle2,
} from "lucide-react";
import { DashboardControls } from "@/features/dashboard/components/DashboardControls";
import { cn } from "@/lib/utils";

const mainNavItems = [
  { href: "/", icon: LinkIcon, tone: "accent", labelKey: "public_site" },
  { href: "/dashboard", icon: LayoutDashboard, tone: "primary", labelKey: "overview" },
  { href: "/dashboard/projects", icon: FolderKanban, tone: "accent", labelKey: "projects" },
  { href: "/dashboard/categories", icon: Tags, tone: "accent", labelKey: "categories" },
  { href: "/dashboard/hero-images", icon: ImageIcon, tone: "accent", labelKey: "hero_images" },
  { href: "/dashboard/testimonials", icon: MessageSquareQuote, tone: "accent", labelKey: "testimonials" },
];

const settingsNavItems = [
  { href: "/dashboard/profile", icon: UserCircle2, labelKey: "profile" },
  { href: "/dashboard/password", icon: Lock, labelKey: "password" },
  { href: "/dashboard/backups", icon: HardDriveDownload, labelKey: "backups" },
  { href: "/dashboard/two-factor", icon: ShieldCheck, labelKey: "two_factor" },
];

export function DashboardSidebarNav() {
  const pathname = usePathname();
  const locale = useLocale();
  const t = useTranslations("DashboardLayout");
  const normalizedPathname = pathname?.startsWith(`/${locale}`)
    ? pathname.replace(`/${locale}`, "") || "/"
    : pathname;

  return (
    <>
      <div className="flex items-center gap-3 mb-12">
        <div className="w-10 h-10 rounded-xl bg-primary flex items-center justify-center font-black text-primary-foreground">
          T
        </div>
        <div className="flex flex-col">
          <span className="text-foreground font-black tracking-tight leading-none uppercase">{t("dashboard")}</span>
          <span className="text-[10px] text-muted-foreground font-bold tracking-widest uppercase mt-1">{t("control_panel")}</span>
        </div>
      </div>

      <div className="space-y-8">
        <nav className="flex flex-col gap-2">
          {mainNavItems.map((item) => {
            const active = item.href === "/"
              ? normalizedPathname === "/" || normalizedPathname === ""
              : item.href === "/dashboard"
                ? normalizedPathname === "/dashboard"
                : normalizedPathname?.startsWith(item.href);
            const toneClass = item.tone === "primary" ? "text-primary" : "text-accent";
            const toneInactive = item.tone === "primary" ? "text-primary/80" : "text-muted-foreground";

            return (
                <Link
                  key={item.href}
                  href={item.href}
                  className={cn(
                    "group relative flex items-center gap-3 px-4 py-3 rounded-2xl text-muted-foreground transition-all duration-200",
                    "hover:text-foreground hover:bg-accent/15 hover:translate-x-0.5",
                    active && "bg-primary/25 text-foreground shadow-[0_14px_34px_-22px_hsl(var(--primary))] ring-1 ring-primary/35 border border-primary/30 hover:bg-primary/30",
                  )}
                >
                <item.icon className={cn("h-4 w-4 transition-transform duration-200 group-hover:scale-110", active ? toneClass : toneInactive)} />
                <span className={cn("text-sm font-bold tracking-tight transition-colors", active ? "text-foreground" : toneInactive)}>
                  {t(item.labelKey)}
                </span>
                <span
                  className={cn(
                    "absolute right-3 h-1.5 w-1.5 rounded-full bg-transparent transition-all",
                    active ? "bg-primary" : "group-hover:bg-accent",
                  )}
                  aria-hidden="true"
                />
              </Link>
            );
          })}
        </nav>

        <div className="border-t border-border pt-6">
          <div className="mb-3 flex items-center gap-2 px-4">
            <Settings2 className="h-4 w-4 text-primary" />
            <p className="text-xs font-black uppercase tracking-[0.25em] text-primary">
              {t("settings")}
            </p>
          </div>
          <nav className="flex flex-col gap-2">
            {settingsNavItems.map((item) => {
              const active = normalizedPathname?.startsWith(item.href);
              return (
                <Link
                  key={item.href}
                  href={item.href}
                  className={cn(
                    "group flex items-center gap-3 px-4 py-3 rounded-2xl text-muted-foreground transition-all duration-200",
                    "hover:text-foreground hover:bg-accent/15 hover:translate-x-0.5",
                    active && "bg-accent/30 text-foreground shadow-[0_14px_34px_-22px_hsl(var(--accent))] ring-1 ring-accent/30 border border-accent/30 hover:bg-accent/35",
                  )}
                >
                  <item.icon className={cn("h-4 w-4 transition-transform duration-200 group-hover:scale-110", active ? "text-accent" : "text-muted-foreground")} />
                  <span className={cn("text-sm font-bold tracking-tight", active ? "text-foreground" : "text-muted-foreground")}>
                    {t(item.labelKey)}
                  </span>
                </Link>
              );
            })}
          </nav>
        </div>
      </div>

      <div className="mt-auto space-y-6 pt-8 border-t border-border">
        <DashboardControls />
      </div>
    </>
  );
}
