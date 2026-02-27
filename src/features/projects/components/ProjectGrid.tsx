"use client";

import { useMemo, useState } from "react";
import { useTranslations } from "next-intl";
import { motion } from "framer-motion";
import { ProjectCard } from "./ProjectCard";
import { fadeUp, fadeUpDelayed } from "./motion-presets";

interface ProjectGridProps {
  projects: any[];
}

export function ProjectGrid({ projects }: ProjectGridProps) {
  const t = useTranslations("Home.projects");
  const [activeCategory, setActiveCategory] = useState<string>("all");

  const categoryOptions = useMemo(() => {
    const map = new Map<string, { slug: string; name: string; count: number }>();

    for (const project of projects) {
      const slug = project.category?.slug ?? "uncategorized";
      const name = project.category?.name ?? t("uncategorized");
      const existing = map.get(slug);
      if (existing) {
        existing.count += 1;
      } else {
        map.set(slug, { slug, name, count: 1 });
      }
    }

    return [
      { slug: "all", name: t("all"), count: projects.length },
      ...Array.from(map.values()),
    ];
  }, [projects, t]);

  const filteredProjects = useMemo(() => {
    if (activeCategory === "all") {
      return projects;
    }
    return projects.filter((project) => (project.category?.slug ?? "uncategorized") === activeCategory);
  }, [projects, activeCategory]);

  return (
    <motion.section
      id="projects"
      variants={fadeUp}
      initial="hidden"
      whileInView="show"
      viewport={{ once: true, amount: 0.2 }}
      className="relative w-full bg-background px-6 py-20 md:px-12"
    >
      <div className="absolute left-1/2 top-0 h-24 w-px -translate-x-1/2 bg-gradient-to-b from-transparent to-border" />

      <div className="max-w-7xl mx-auto mb-16 flex flex-col md:flex-row justify-between items-end gap-8">
        <div>
          <span className="mb-2 block font-sans text-xs font-semibold uppercase tracking-[0.2em] text-primary">
            {t("label") || "Portfolio"}
          </span>
          <h2 className="font-serif text-4xl leading-tight text-foreground md:text-5xl">
            {t("heading") || "Projects"}
          </h2>
        </div>
      </div>

      {!!projects.length && (
        <motion.div
          className="mx-auto mb-12 flex max-w-7xl flex-wrap items-center gap-2"
          variants={fadeUpDelayed(0.1)}
          initial="hidden"
          whileInView="show"
          viewport={{ once: true, amount: 0.3 }}
        >
          {categoryOptions.map((category) => {
            const isActive = category.slug === activeCategory;
            return (
              <button
                key={category.slug}
                type="button"
                onClick={() => setActiveCategory(category.slug)}
                className={`rounded-full border px-4 py-2 text-xs font-bold uppercase tracking-wider transition ${
                  isActive
                    ? "border-primary bg-primary text-primary-foreground"
                    : "border-border bg-card text-muted-foreground hover:border-primary/40 hover:text-foreground"
                }`}
              >
                {category.name} ({category.count})
              </button>
            );
          })}
        </motion.div>
      )}

      {!projects.length ? (
        <div className="max-w-7xl mx-auto py-24 text-center">
          <div className="mb-6 inline-flex h-16 w-16 items-center justify-center rounded-full bg-secondary">
            <svg className="w-8 h-8 text-secondary-foreground/70" fill="none" stroke="currentColor" viewBox="0 0 24 24" strokeWidth="1.5">
              <path strokeLinecap="round" strokeLinejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
            </svg>
          </div>
          <h3 className="mb-2 font-serif text-xl font-medium text-foreground">
            {t("empty_title") || "No Projects Yet"}
          </h3>
          <p className="mx-auto max-w-md font-sans text-muted-foreground">
            {t("empty_desc") || "We are currently curating our portfolio. Please check back soon for updates."}
          </p>
        </div>
      ) : !filteredProjects.length ? (
        <div className="mx-auto max-w-7xl py-20 text-center">
          <p className="font-sans text-sm uppercase tracking-widest text-muted-foreground">
            {t("empty_filtered")}
          </p>
        </div>
      ) : (
        <div className="max-w-7xl mx-auto columns-1 md:columns-2 lg:columns-3 gap-8 space-y-8">
          {filteredProjects.map((project) => (
            <ProjectCard key={project.id} project={project} />
          ))}
        </div>
      )}
    </motion.section>
  );
}
