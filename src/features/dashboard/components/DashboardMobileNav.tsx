"use client";

import { useState } from "react";
import {
  FolderKanban,
  HardDriveDownload,
  Image as ImageIcon,
  LayoutDashboard,
  Link as LinkIcon,
  Lock,
  Menu,
  MessageSquareQuote,
  Settings2,
  ShieldCheck,
  Tags,
  UserCircle2,
} from "lucide-react";
import { Link, usePathname } from "@/i18n/navigation";
import { useLocale } from "next-intl";
import { Button } from "@/components/ui/button";
import { DashboardControls } from "@/features/dashboard/components/DashboardControls";
import {
  Sheet,
  SheetClose,
  SheetContent,
  SheetDescription,
  SheetHeader,
  SheetTitle,
  SheetTrigger,
} from "@/components/ui/sheet";

const mainNavItems = [
  { href: "/", label: "Public Site", icon: LinkIcon, tone: "accent" },
  { href: "/dashboard", label: "Overview", icon: LayoutDashboard, tone: "primary" },
  { href: "/dashboard/projects", label: "Projects", icon: FolderKanban, tone: "accent" },
  { href: "/dashboard/categories", label: "Categories", icon: Tags, tone: "accent" },
  { href: "/dashboard/hero-images", label: "Hero Images", icon: ImageIcon, tone: "accent" },
  { href: "/dashboard/testimonials", label: "Testimonials", icon: MessageSquareQuote, tone: "accent" },
];

const settingsNavItems = [
  { href: "/dashboard/profile", label: "Profile", icon: UserCircle2 },
  { href: "/dashboard/password", label: "Password", icon: Lock },
  { href: "/dashboard/backups", label: "Backups", icon: HardDriveDownload },
  { href: "/dashboard/two-factor", label: "Two-Factor", icon: ShieldCheck },
];

export function DashboardMobileNav() {
  const [open, setOpen] = useState(false);
  const pathname = usePathname();
  const locale = useLocale();
  const sheetSide = locale === "ar" ? "right" : "left";
  const sheetBorder = locale === "ar" ? "border-l" : "border-r";

  const normalizedPathname = pathname?.startsWith(`/${locale}`)
    ? pathname.replace(`/${locale}`, "") || "/"
    : pathname;

  return (
    <div className="md:hidden">
      <Sheet open={open} onOpenChange={setOpen}>
        <SheetTrigger asChild>
          <Button variant="outline" size="icon" className="rounded-xl border-border bg-card text-foreground">
            <Menu className="h-5 w-5" />
            <span className="sr-only">Open dashboard menu</span>
          </Button>
        </SheetTrigger>
        <SheetContent side={sheetSide} className={`w-[86vw] max-w-sm bg-card/95 backdrop-blur-xl border-border ${sheetBorder} p-6`}>
          <SheetHeader>
            <SheetTitle className="text-xl font-black uppercase tracking-tight">Dashboard</SheetTitle>
            <SheetDescription>Navigate sections and settings.</SheetDescription>
          </SheetHeader>

          <div className="mt-8 space-y-6">
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
                  <SheetClose asChild key={item.href}>
                    <Link
                      href={item.href}
                      className={`group relative flex items-center gap-3 rounded-xl px-3 py-2 text-sm font-bold transition-all duration-200 ${
                        active
                          ? "bg-primary/30 text-foreground shadow-[0_12px_28px_-20px_hsl(var(--primary))] ring-1 ring-primary/35 border border-primary/30"
                          : "text-muted-foreground hover:bg-accent/20 hover:text-foreground hover:translate-x-0.5"
                      }`}
                    >
                      <item.icon className={`h-4 w-4 transition-transform duration-200 group-hover:scale-110 ${active ? toneClass : toneInactive}`} />
                      <span>{item.label}</span>
                      <span
                        className={`absolute right-3 h-1.5 w-1.5 rounded-full transition-all ${
                          active ? "bg-primary" : "bg-transparent group-hover:bg-accent"
                        }`}
                        aria-hidden="true"
                      />
                    </Link>
                  </SheetClose>
                );
              })}
            </nav>

            <div className="border-t border-border pt-4">
              <div className="mb-3 flex items-center gap-2">
                <Settings2 className="h-4 w-4 text-primary" />
                <p className="text-xs font-black uppercase tracking-[0.25em] text-primary">Settings</p>
              </div>
              <nav className="flex flex-col gap-2">
                {settingsNavItems.map((item) => {
                  const active = normalizedPathname?.startsWith(item.href);
                  return (
                    <SheetClose asChild key={item.href}>
                      <Link
                        href={item.href}
                        className={`group flex items-center gap-3 rounded-xl px-3 py-2 text-sm font-bold transition-all duration-200 ${
                          active
                            ? "bg-accent/30 text-foreground shadow-[0_12px_28px_-20px_hsl(var(--accent))] ring-1 ring-accent/30 border border-accent/30"
                            : "text-muted-foreground hover:bg-accent/20 hover:text-foreground hover:translate-x-0.5"
                        }`}
                      >
                        <item.icon className={`h-4 w-4 transition-transform duration-200 group-hover:scale-110 ${active ? "text-accent" : "text-muted-foreground"}`} />
                        <span>{item.label}</span>
                      </Link>
                    </SheetClose>
                  );
                })}
              </nav>
            </div>

            <div className="border-t border-border pt-4">
              <DashboardControls className="gap-3" />
            </div>
          </div>
        </SheetContent>
      </Sheet>
    </div>
  );
}
