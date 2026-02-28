import { db } from "@/db";
import { projects, categories, testimonials } from "@/db/schema";
import { count, eq } from "drizzle-orm";
import { FolderKanban, Tags, Globe, Eye, MessageSquareQuote } from "lucide-react";
import { getTranslations } from "next-intl/server";
import { Link } from "@/i18n/navigation";

export default async function DashboardOverview({ params }: { params: Promise<{ locale: string }> }) {
  await params;

  // Fetch real stats
  const [
    projectsCount,
    categoriesCount,
    testimonialsCount,
    publishedCount
  ] = await Promise.all([
    db.select({ value: count() }).from(projects),
    db.select({ value: count() }).from(categories),
    db.select({ value: count() }).from(testimonials),
    db.select({ value: count() }).from(projects).where(eq(projects.isPublished, true)),
  ]);

  const t = await getTranslations("DashboardOverview");
  const layoutTarget = await getTranslations("DashboardLayout");

  const stats = [
    { label: t("total_projects"), value: projectsCount[0].value, icon: FolderKanban, color: "text-primary", href: "/dashboard/projects" },
    { label: t("categories"), value: categoriesCount[0].value, icon: Tags, color: "text-accent", href: "/dashboard/categories" },
    { label: t("client_feedback"), value: testimonialsCount[0].value, icon: MessageSquareQuote, color: "text-primary", href: "/dashboard/testimonials" },
    { label: t("published_works"), value: publishedCount[0].value, icon: Globe, color: "text-accent", href: "/dashboard/projects" },
  ];

  return (
    <div className="flex flex-col gap-12">
      <div className="flex flex-col gap-1 border-l-2 border-border pl-6">
        <h1 className="text-4xl font-black text-foreground tracking-tighter uppercase">{layoutTarget("dashboard")}</h1>
        <p className="text-muted-foreground text-sm font-bold tracking-tight uppercase">
          {t("welcome_back")}
        </p>
      </div>

      <div className="grid grid-cols-1 md:grid-cols-3 gap-8">
        {stats.map((stat) => (
          <Link
            key={stat.label}
            href={stat.href}
            className="group relative block p-8 rounded-[2.5rem] bg-card/60 border border-border overflow-hidden shadow-xl transition-all duration-500 hover:border-ring/40 hover:bg-card hover:cursor-pointer"
          >
            <div className="flex items-center justify-between mb-4">
              <div className={`p-3 rounded-2xl bg-accent/50 ${stat.color} border border-border transition-transform group-hover:scale-110`}>
                <stat.icon className="h-6 w-6" />
              </div>
              <Eye className="h-4 w-4 text-muted-foreground group-hover:text-primary transition-colors" />
            </div>
            <h3 className="text-muted-foreground text-xs font-black tracking-widest uppercase mb-2">{stat.label}</h3>
            <p className="text-5xl font-black text-foreground tracking-tighter">{stat.value}</p>

            {/* Interior Glow */}
            <div className="absolute top-0 right-0 -translate-y-1/2 translate-x-1/2 w-32 h-32 bg-primary/5 rounded-full blur-3xl group-hover:bg-primary/10 transition-colors" />
          </Link>
        ))}
      </div>

      {/* Visual Accent */}
      <div className="w-full h-px bg-linear-to-r from-transparent via-border to-transparent my-4" />
    </div>
  );
}
