import { requireAdmin } from "@/features/auth/guard";
import { Link } from "@/i18n/navigation";
import { DashboardHeaderControls } from "@/features/dashboard/components/DashboardHeaderControls";
import { DashboardMobileNav } from "@/features/dashboard/components/DashboardMobileNav";
import { LayoutDashboard, FolderKanban, Tags, Link as LinkIcon, Image as ImageIcon, MessageSquareQuote, UserCircle2, Lock, Palette, HardDriveDownload, ShieldCheck } from "lucide-react";

export default async function DashboardLayout({
  children,
  params,
}: {
  children: React.ReactNode;
  params: Promise<{ locale: string }>;
}) {
  const { locale } = await params;
  await requireAdmin(locale, `/${locale}/dashboard`);

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

  return (
    <div className="flex min-h-screen bg-background text-foreground selection:bg-primary selection:text-primary-foreground">
      {/* Sidebar */}
      <aside className="w-72 hidden md:flex flex-col border-r border-border bg-card/60 backdrop-blur-xl p-8 sticky top-0 h-screen">
        <div className="flex items-center gap-3 mb-12">
          <div className="w-10 h-10 rounded-xl bg-primary flex items-center justify-center font-black text-primary-foreground">
            T
          </div>
          <div className="flex flex-col">
            <span className="text-foreground font-black tracking-tight leading-none uppercase">Dashboard</span>
            <span className="text-[10px] text-muted-foreground font-bold tracking-widest uppercase mt-1">Control Panel</span>
          </div>
        </div>

        <div className="space-y-8">
          <nav className="flex flex-col gap-2">
            {mainNavItems.map((item) => (
              <Link
                key={item.href}
                href={item.href}
                className="flex items-center gap-3 px-4 py-3 rounded-2xl text-muted-foreground hover:text-foreground hover:bg-accent transition-all duration-300 group"
              >
                <item.icon className="h-4 w-4 transition-transform group-hover:scale-110" />
                <span className="text-sm font-bold tracking-tight">{item.label}</span>
              </Link>
            ))}
          </nav>

          <div className="border-t border-border pt-6">
            <p className="px-4 text-[10px] uppercase tracking-[0.25em] font-bold text-muted-foreground mb-3">
              Settings
            </p>
            <nav className="flex flex-col gap-2">
              {settingsNavItems.map((item) => (
                <Link
                  key={item.href}
                  href={item.href}
                  className="flex items-center gap-3 px-4 py-3 rounded-2xl text-muted-foreground hover:text-foreground hover:bg-accent transition-all duration-300 group"
                >
                  <item.icon className="h-4 w-4 transition-transform group-hover:scale-110" />
                  <span className="text-sm font-bold tracking-tight">{item.label}</span>
                </Link>
              ))}
            </nav>
          </div>
        </div>

        <div className="mt-auto pt-8 border-t border-border">
          <Link
            href="/"
            className="flex items-center gap-3 px-4 py-3 rounded-2xl text-muted-foreground hover:text-foreground hover:bg-accent transition-all duration-300"
          >
            <LinkIcon className="h-4 w-4" />
            <span className="text-sm font-bold tracking-tight">Public Site</span>
          </Link>
        </div>
      </aside>

      {/* Main Content */}
      <main className="flex-1 p-8 md:p-16 max-w-7xl mx-auto w-full">
        <div className="mb-8 flex items-center justify-between gap-4 border-b border-border pb-6">
          <div className="flex items-center gap-3">
            <DashboardMobileNav />
            <h1 className="text-lg font-bold uppercase tracking-wider text-foreground">
              Dashboard
            </h1>
          </div>
          <div className="hidden sm:block">
            <DashboardHeaderControls />
          </div>
        </div>
        <div className="mb-6 sm:hidden">
          <DashboardHeaderControls />
        </div>
        {children}
      </main>
    </div>
  );
}
