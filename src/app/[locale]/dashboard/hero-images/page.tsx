import { db } from "@/db";
import { heroImages } from "@/db/schema";
import { asc } from "drizzle-orm";
import { HeroImagesManager } from "@/features/dashboard/components/HeroImagesManager";

export default async function DashboardHeroImagesPage({ params }: { params: Promise<{ locale: string }> }) {
  await params;

  // Fetch current hero images
  const images = await db.query.heroImages.findMany({
    orderBy: [asc(heroImages.position)],
  });

  return (
    <div className="flex flex-col gap-12">
      <div className="flex items-center justify-between">
        <div className="flex flex-col gap-1 border-l-2 border-border pl-6">
          <h1 className="text-4xl font-black text-foreground tracking-tighter uppercase">Hero Images</h1>
          <p className="text-muted-foreground text-sm font-bold tracking-tight uppercase">
            Manage the 4 focal images on the landing page.
          </p>
        </div>
      </div>

      <div className="rounded-[2.5rem] border border-border bg-card/70 overflow-hidden shadow-2xl p-8">
        <HeroImagesManager initialImages={images} />
      </div>
    </div>
  );
}
