import { requireAdmin } from "@/features/auth/guard";
import { DashboardMobileNav } from "@/features/dashboard/components/DashboardMobileNav";
import { DashboardSidebarNav } from "@/features/dashboard/components/DashboardSidebarNav";
import { getTranslations } from "next-intl/server";

export default async function DashboardLayout({
  children,
  params,
}: {
  children: React.ReactNode;
  params: Promise<{ locale: string }>;
}) {
  const { locale } = await params;
  await requireAdmin(locale, `/${locale}/dashboard`);
  const t = await getTranslations("DashboardLayout");

  return (
    <div className="flex min-h-screen bg-background text-foreground selection:bg-primary selection:text-primary-foreground">
      {/* Sidebar */}
      <aside className="w-64 hidden md:flex flex-col border-r border-border bg-card/60 backdrop-blur-xl p-8 sticky top-0 h-screen">
        <DashboardSidebarNav />
      </aside>

      {/* Main Content */}
      <main className="flex-1 p-8 md:p-16 max-w-7xl mx-auto w-full">
        <div className="mb-8 flex items-center justify-between gap-4 border-b border-border pb-6 sticky top-0 -mt-8 pt-8 z-40 bg-background/95 backdrop-blur md:static md:mt-0 md:pt-0 md:bg-transparent md:backdrop-blur-0">
          <div className="flex items-center gap-3">
            <DashboardMobileNav />
            <h1 className="text-lg font-bold uppercase tracking-wider text-foreground">
              {t("dashboard")}
            </h1>
          </div>
        </div>
        {children}
      </main>
    </div>
  );
}
