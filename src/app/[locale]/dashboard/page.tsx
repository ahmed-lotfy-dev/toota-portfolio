import { db } from "@/db";
import { projects, categories, testimonials } from "@/db/schema";
import { count, eq } from "drizzle-orm";
import { FolderKanban, Tags, Globe, Eye, MessageSquareQuote } from "lucide-react";

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

  const stats = [
    { label: "Total Projects", value: projectsCount[0].value, icon: FolderKanban, color: "text-primary" },
    { label: "Categories", value: categoriesCount[0].value, icon: Tags, color: "text-accent" },
    { label: "Client Feedback", value: testimonialsCount[0].value, icon: MessageSquareQuote, color: "text-primary" },
    { label: "Published Works", value: publishedCount[0].value, icon: Globe, color: "text-accent" },
  ];

  return (
    <div className="flex flex-col gap-12">
      <div className="flex flex-col gap-1 border-l-2 border-border pl-6">
        <h1 className="text-4xl font-black text-foreground tracking-tighter uppercase">Dashboard</h1>
        <p className="text-muted-foreground text-sm font-bold tracking-tight uppercase">
          Welcome back. Here&apos;s your portfolio activity at a glance.
        </p>
      </div>

      <div className="grid grid-cols-1 md:grid-cols-3 gap-8">
        {stats.map((stat) => (
          <div
            key={stat.label}
            className="group relative p-8 rounded-[2.5rem] bg-card/60 border border-border overflow-hidden shadow-xl transition-all duration-500 hover:border-ring/40 hover:bg-card"
          >
            <div className="flex items-center justify-between mb-4">
              <div className={`p-3 rounded-2xl bg-accent/50 ${stat.color} border border-border transition-transform group-hover:scale-110`}>
                <stat.icon className="h-6 w-6" />
              </div>
              <Eye className="h-4 w-4 text-muted-foreground group-hover:text-foreground transition-colors" />
            </div>
            <h3 className="text-muted-foreground text-xs font-black tracking-widest uppercase mb-2">{stat.label}</h3>
            <p className="text-5xl font-black text-foreground tracking-tighter">{stat.value}</p>

            {/* Interior Glow */}
            <div className="absolute top-0 right-0 -translate-y-1/2 translate-x-1/2 w-32 h-32 bg-primary/5 rounded-full blur-3xl group-hover:bg-primary/10 transition-colors" />
          </div>
        ))}
      </div>

      {/* Visual Accent */}
      <div className="w-full h-px bg-linear-to-r from-transparent via-border to-transparent my-4" />
    </div>
  );
}
