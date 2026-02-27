import { db } from "@/db";
import { projects, categories } from "@/db/schema";
import { eq, asc } from "drizzle-orm";
import { notFound } from "next/navigation";
import { ProjectForm } from "@/features/dashboard/ProjectForm";

export default async function EditProjectPage({
  params
}: {
  params: Promise<{ locale: string; id: string }>
}) {
  const { id } = await params;
  const projectId = parseInt(id);

  if (isNaN(projectId)) return notFound();

  const [projectData, categoryList] = await Promise.all([
    db.query.projects.findFirst({
      where: eq(projects.id, projectId),
      with: {
        images: true,
      }
    }),
    db.query.categories.findMany({
      orderBy: [asc(categories.name)],
    }),
  ]);

  if (!projectData) return notFound();

  return (
    <div className="flex flex-col gap-12">
      <div className="flex flex-col gap-1 border-l-2 border-border pl-6">
        <h1 className="text-4xl font-black text-foreground tracking-tighter uppercase">Edit Creation</h1>
        <p className="text-muted-foreground text-sm font-bold tracking-tight uppercase">
          Refine the details of your masterpiece.
        </p>
      </div>

      <div className="max-w-4xl">
        <ProjectForm categories={categoryList} project={projectData} />
      </div>
    </div>
  );
}
