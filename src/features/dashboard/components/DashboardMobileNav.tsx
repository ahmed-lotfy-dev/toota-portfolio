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
  Palette,
  ShieldCheck,
  Tags,
  UserCircle2,
} from "lucide-react";
import { Link, usePathname } from "@/i18n/navigation";
import { Button } from "@/components/ui/button";
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
  { href: "/dashboard", label: "Overview", icon: LayoutDashboard },
  { href: "/dashboard/projects", label: "Projects", icon: FolderKanban },
  { href: "/dashboard/categories", label: "Categories", icon: Tags },
  { href: "/dashboard/hero-images", label: "Hero Images", icon: ImageIcon },
  { href: "/dashboard/testimonials", label: "Testimonials", icon: MessageSquareQuote },
];

const settingsNavItems = [
  { href: "/dashboard/profile", label: "Profile", icon: UserCircle2 },
  { href: "/dashboard/password", label: "Password", icon: Lock },
  { href: "/dashboard/appearance", label: "Appearance", icon: Palette },
  { href: "/dashboard/backups", label: "Backups", icon: HardDriveDownload },
  { href: "/dashboard/two-factor", label: "Two-Factor", icon: ShieldCheck },
];

export function DashboardMobileNav() {
  const [open, setOpen] = useState(false);
  const pathname = usePathname();

  return (
    <div className="md:hidden">
      <Sheet open={open} onOpenChange={setOpen}>
        <SheetTrigger asChild>
          <Button variant="outline" size="icon" className="rounded-xl border-border bg-card text-foreground">
            <Menu className="h-5 w-5" />
            <span className="sr-only">Open dashboard menu</span>
          </Button>
        </SheetTrigger>
        <SheetContent side="left" className="w-[86vw] max-w-sm bg-card p-6">
          <SheetHeader>
            <SheetTitle className="text-xl font-black uppercase tracking-tight">Dashboard</SheetTitle>
            <SheetDescription>Navigate sections and settings.</SheetDescription>
          </SheetHeader>

          <div className="mt-8 space-y-6">
            <nav className="flex flex-col gap-2">
              {mainNavItems.map((item) => {
                const active = pathname === item.href;
                return (
                  <SheetClose asChild key={item.href}>
                    <Link
                      href={item.href}
                      className={`flex items-center gap-3 rounded-xl px-3 py-2 text-sm font-bold transition ${
                        active
                          ? "bg-primary/15 text-primary"
                          : "text-muted-foreground hover:bg-accent/20 hover:text-foreground"
                      }`}
                    >
                      <item.icon className="h-4 w-4" />
                      <span>{item.label}</span>
                    </Link>
                  </SheetClose>
                );
              })}
            </nav>

            <div className="border-t border-border pt-4">
              <p className="mb-2 text-[10px] font-bold uppercase tracking-[0.25em] text-muted-foreground">Settings</p>
              <nav className="flex flex-col gap-2">
                {settingsNavItems.map((item) => {
                  const active = pathname === item.href;
                  return (
                    <SheetClose asChild key={item.href}>
                      <Link
                        href={item.href}
                        className={`flex items-center gap-3 rounded-xl px-3 py-2 text-sm font-bold transition ${
                          active
                            ? "bg-primary/15 text-primary"
                            : "text-muted-foreground hover:bg-accent/20 hover:text-foreground"
                        }`}
                      >
                        <item.icon className="h-4 w-4" />
                        <span>{item.label}</span>
                      </Link>
                    </SheetClose>
                  );
                })}
              </nav>
            </div>

            <div className="border-t border-border pt-4">
              <SheetClose asChild>
                <Link
                  href="/"
                  className="flex items-center gap-3 rounded-xl px-3 py-2 text-sm font-bold text-muted-foreground transition hover:bg-accent/20 hover:text-foreground"
                >
                  <LinkIcon className="h-4 w-4" />
                  <span>Public Site</span>
                </Link>
              </SheetClose>
            </div>
          </div>
        </SheetContent>
      </Sheet>
    </div>
  );
}
