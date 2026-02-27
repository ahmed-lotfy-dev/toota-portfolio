import { db } from "@/db";
import { testimonials } from "@/db/schema";
import { desc } from "drizzle-orm";
import { TestimonialsManager } from "@/features/dashboard/components/TestimonialsManager";

export default async function DashboardTestimonialsPage({ params }: { params: Promise<{ locale: string }> }) {
  await params;

  const testimonialList = await db.query.testimonials.findMany({
    orderBy: [desc(testimonials.createdAt)],
  });

  return (
    <div className="flex flex-col gap-12">
      <div className="flex items-center justify-between">
        <div className="flex flex-col gap-1 border-l-2 border-border pl-6">
          <h1 className="text-4xl font-black text-foreground tracking-tighter uppercase">Testimonials</h1>
          <p className="text-muted-foreground text-sm font-bold tracking-tight uppercase">
            Curate what the world thinks of your art.
          </p>
        </div>
      </div>

      <TestimonialsManager initialTestimonials={testimonialList as any} />
    </div>
  );
}
