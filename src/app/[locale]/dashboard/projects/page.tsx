import { db } from "@/db";
import { projects, categories } from "@/db/schema";
import { desc } from "drizzle-orm";
import {
  Table,
  TableBody,
  TableCell,
  TableHead,
  TableHeader,
  TableRow
} from "@/components/ui/table";
import { Button } from "@/components/ui/button"
import { PlusCircle } from "lucide-react";
import { Link } from "@/i18n/navigation";
import { ProjectActions } from "@/features/dashboard/components/ProjectActions";
import { getTranslations } from "next-intl/server";

export default async function DashboardProjectsPage({ params }: { params: Promise<{ locale: string }> }) {
  await params;
  const projectList = await db.query.projects.findMany({
    orderBy: [desc(projects.createdAt)],
    with: {
      category: true,
    }
  });

  const t = await getTranslations("DashboardProjects");

  return (
    <div className="flex flex-col gap-12">
      <div className="flex items-center justify-between">
        <div className="flex flex-col gap-1 border-l-2 border-border pl-6">
          <h1 className="text-4xl font-black text-foreground tracking-tighter uppercase">{t("title")}</h1>
          <p className="text-muted-foreground text-sm font-bold tracking-tight uppercase">
            {t("description", { count: projectList.length })}
          </p>
        </div>
        <Button asChild className="h-12 rounded-2xl bg-primary px-6 font-bold text-primary-foreground hover:bg-primary/90 transition-all">
          <Link href="/dashboard/projects/new">
            <PlusCircle className="mr-2 h-4 w-4" />
            {t("new_creation")}
          </Link>
        </Button>
      </div>

      <div className="overflow-hidden rounded-[2.5rem] border border-border bg-card/70 shadow-2xl">
        <Table>
          <TableHeader className="bg-muted/50">
            <TableRow className="border-border hover:bg-transparent">
              <TableHead className="text-muted-foreground font-bold tracking-widest uppercase text-[10px] py-6 pl-8">{t("columns.title")}</TableHead>
              <TableHead className="text-muted-foreground font-bold tracking-widest uppercase text-[10px] py-6">{t("columns.category")}</TableHead>
              <TableHead className="text-muted-foreground font-bold tracking-widest uppercase text-[10px] py-6">{t("columns.status")}</TableHead>
              <TableHead className="text-muted-foreground font-bold tracking-widest uppercase text-[10px] py-6">{t("columns.created")}</TableHead>
              <TableHead className="text-muted-foreground font-bold tracking-widest uppercase text-[10px] py-6 text-right pr-8">{t("columns.actions")}</TableHead>
            </TableRow>
          </TableHeader>
          <TableBody>
            {projectList.map((project) => (
              <TableRow key={project.id} className="border-border hover:bg-accent/10 transition-colors">
                <TableCell className="font-bold text-foreground py-6 pl-8">{project.title}</TableCell>
                <TableCell className="text-muted-foreground font-medium">{project.category.name}</TableCell>
                <TableCell>
                  <span className={`px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest border ${project.isPublished
                    ? "border-emerald-500/20 bg-emerald-500/10 text-emerald-400"
                    : "border-border bg-muted text-muted-foreground"
                    }`}>
                    {project.isPublished ? t("status.published") : t("status.draft")}
                  </span>
                </TableCell>
                <TableCell className="text-muted-foreground font-mono text-xs">
                  {new Date(project.createdAt).toLocaleDateString()}
                </TableCell>
                <TableCell className="text-right pr-8">
                  <ProjectActions project={project} />
                </TableCell>
              </TableRow>
            ))}
          </TableBody>
        </Table>
      </div>
    </div>
  );
}
