import { ProjectForm } from "@/features/dashboard/ProjectForm";
import { db } from "@/db";
import { categories } from "@/db/schema";
import { asc } from "drizzle-orm";

export default async function NewProjectPage({ params }: { params: Promise<{ locale: string }> }) {
  await params;
  const categoryList = await db.query.categories.findMany({
    orderBy: [asc(categories.name)],
  });

  return (
    <div className="flex flex-col gap-12">
      <div className="flex flex-col gap-1 border-l-2 border-border pl-6">
        <h1 className="text-4xl font-black text-foreground tracking-tighter uppercase">New Creation</h1>
        <p className="text-muted-foreground text-sm font-bold tracking-tight uppercase">
          Add a new masterpiece to your portfolio.
        </p>
      </div>

      <div className="max-w-4xl">
        <ProjectForm categories={categoryList} />
      </div>
    </div>
  );
}
