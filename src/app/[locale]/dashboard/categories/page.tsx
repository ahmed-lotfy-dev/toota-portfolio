import { db } from "@/db";
import { categories } from "@/db/schema";
import { asc } from "drizzle-orm";
import { CategoriesManager } from "@/features/dashboard/components/CategoriesManager";
import { getTranslations } from "next-intl/server";

export default async function AdminCategoriesPage({ params }: { params: Promise<{ locale: string }> }) {
  await params;
  const categoryList = await db.query.categories.findMany({
    orderBy: [asc(categories.name)],
  });

  const t = await getTranslations("DashboardCategories");

  return (
    <div className="flex flex-col gap-12">
      <div className="flex items-center justify-between">
        <div className="flex flex-col gap-1 border-l-2 border-border pl-6">
          <h1 className="text-4xl font-black text-foreground tracking-tighter uppercase">{t("title")}</h1>
          <p className="text-muted-foreground text-sm font-bold tracking-tight uppercase">
            {t("description")}
          </p>
        </div>
      </div>

      <CategoriesManager initialCategories={categoryList} />
    </div>
  );
}
