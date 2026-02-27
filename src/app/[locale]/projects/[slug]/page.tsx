import { getProjectBySlug } from "@/features/projects/services/project-service";
import { ProjectDetail } from "@/features/projects/components/ProjectDetail";
import { notFound } from "next/navigation";
import { getTranslations } from "next-intl/server";

export default async function ProjectPage({
  params,
}: {
  params: Promise<{ locale: string; slug: string }>;
}) {
  const { slug, locale } = await params;
  const project = await getProjectBySlug(slug);

  if (!project) {
    notFound();
  }

  return <ProjectDetail project={project} />;
}
