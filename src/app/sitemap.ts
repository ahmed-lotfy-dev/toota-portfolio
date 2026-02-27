import type { MetadataRoute } from "next";
import { db } from "@/db";
import { projects } from "@/db/schema";
import { eq } from "drizzle-orm";

const locales = ["en", "ar"] as const;

function getBaseUrl() {
  return (process.env.NEXT_PUBLIC_APP_URL || "http://localhost:3000").replace(/\/+$/, "");
}

export default async function sitemap(): Promise<MetadataRoute.Sitemap> {
  const baseUrl = getBaseUrl();
  let publishedProjects: Array<{ slug: string; updatedAt: Date | null; createdAt: Date | null }> = [];

  try {
    publishedProjects = await db.query.projects.findMany({
      where: eq(projects.isPublished, true),
      columns: {
        slug: true,
        updatedAt: true,
        createdAt: true,
      },
    });
  } catch (error) {
    // Build-time environments may not have DB access (e.g. Dokploy image build).
    // Return static locale URLs and let project URLs be resolved at runtime deploys.
    console.warn("sitemap: could not load project URLs from database", error);
  }

  const staticUrls: MetadataRoute.Sitemap = locales.flatMap((locale) => ([
    {
      url: `${baseUrl}/${locale}`,
      lastModified: new Date(),
      changeFrequency: "weekly",
      priority: 1,
    },
  ]));

  const projectUrls: MetadataRoute.Sitemap = publishedProjects.flatMap((project) =>
    locales.map((locale) => ({
      url: `${baseUrl}/${locale}/projects/${project.slug}`,
      lastModified: project.updatedAt || project.createdAt || new Date(),
      changeFrequency: "monthly" as const,
      priority: 0.8,
    })),
  );

  return [...staticUrls, ...projectUrls];
}
