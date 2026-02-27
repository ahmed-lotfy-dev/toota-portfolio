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
  const publishedProjects = await db.query.projects.findMany({
    where: eq(projects.isPublished, true),
    columns: {
      slug: true,
      updatedAt: true,
      createdAt: true,
    },
  });

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
