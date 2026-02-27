import { db } from "@/db";
import { projects, categories, projectImages, heroImages, testimonials } from "@/db/schema";
import { and, eq, desc } from "drizzle-orm";

export async function getPublishedProjects() {
  return await db.query.projects.findMany({
    where: eq(projects.isPublished, true),
    orderBy: [desc(projects.order), desc(projects.createdAt)],
    with: {
      category: true,
      images: {
        where: eq(projectImages.isPrimary, true),
        limit: 1,
      },
    },
  });
}

export async function getCategoriesWithCounts() {
  return await db.query.categories.findMany({
    with: {
      projects: {
        columns: {
          id: true,
        },
      },
    },
  });
}

export async function getPortfolioData() {
  const [projectList, categoryList] = await Promise.all([
    getPublishedProjects(),
    getCategoriesWithCounts(),
  ]);

  return { projects: projectList, categories: categoryList };
}

export async function getProjectBySlug(slug: string) {
  return await db.query.projects.findFirst({
    where: and(eq(projects.slug, slug), eq(projects.isPublished, true)),
    with: {
      category: true,
      images: {
        orderBy: [desc(projectImages.isPrimary)],
      },
    },
  });
}

export async function getHeroImages() {
  return await db.query.heroImages.findMany({
    orderBy: [desc(heroImages.position)],
  });
}

export async function getPublishedTestimonials() {
  return await db.query.testimonials.findMany({
    where: eq(testimonials.isPublished, true),
    orderBy: [desc(testimonials.createdAt)],
  });
}
