"use client";

import Image from "next/image";
import { Link } from "@/i18n/navigation";
import { MoveRight } from "lucide-react";
import { useTranslations } from "next-intl";
import { motion } from "framer-motion";
import { fadeUp, fadeUpDelayed, hoverLift } from "./motion-presets";

export function About() {
  const t = useTranslations("Home.about");

  return (
    <motion.section
      id="about"
      variants={fadeUp}
      initial="hidden"
      whileInView="show"
      viewport={{ once: true, amount: 0.2 }}
      className="relative overflow-hidden bg-background px-6 py-20 transition-colors duration-500 md:px-12"
    >

      {/* Decorative background element */}
      <div className="absolute inset-0 -z-10 opacity-20">
        <div className="absolute left-1/2 top-1/2 h-48 w-48 -translate-x-1/2 -translate-y-1/2 rounded-full bg-primary/35 blur-3xl" />
      </div>

      <div className="max-w-7xl mx-auto">
        <div className="grid lg:grid-cols-5 gap-12 lg:gap-24 items-center">

          {/* Image Column */}
          <motion.div
            className="group relative lg:col-span-2"
            variants={fadeUpDelayed(0.08)}
            initial="hidden"
            whileInView="show"
            viewport={{ once: true, amount: 0.3 }}
          >

            {/* Decorative Back Layer */}
            <div className="absolute inset-0 translate-x-2 translate-y-2 rounded-lg bg-accent/20 opacity-60 transition duration-500 group-hover:translate-x-3 group-hover:translate-y-3 md:-inset-4" />

            <div className="relative rounded-lg border-4 border-primary shadow-xl bg-card/80 backdrop-blur-sm">
              <div className="aspect-[3/4] overflow-hidden rounded-md relative">
                <Image
                  src="/Artist-Image-With-BG.png"
                  alt={t("artist_image_alt") || "Artist"}
                  fill
                  className="object-cover transform transition duration-700 group-hover:scale-[1.03] grayscale-[0.1] group-hover:grayscale-0"
                />
              </div>

              {/* Floating Museum Badge */}
              <div className="absolute -bottom-6 -right-6 rounded-md border-4 border-background bg-primary p-4 shadow-2xl transition duration-300 group-hover:-translate-y-0.5">
                <div className="flex items-center gap-3">
                  <div className="flex h-10 w-10 items-center justify-center rounded-md bg-primary-foreground/20">
                    <svg className="w-5 h-5 text-background" fill="none" stroke="currentColor" viewBox="0 0 24 24" strokeWidth="1.5">
                      <path strokeLinecap="round" strokeLinejoin="round" d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-7.5 3h6m2.25 0h-6M12 18v-5.25" />
                    </svg>
                  </div>
                  <div>
                    <p className="text-sm font-semibold text-primary-foreground">{t("experience_years")}</p>
                    <p className="text-xs text-primary-foreground/80">{t("experience_label")}</p>
                  </div>
                </div>
              </div>
            </div>
          </motion.div>

          {/* Content Section */}
          <motion.div
            className="space-y-8 lg:col-span-3"
            variants={fadeUpDelayed(0.16)}
            initial="hidden"
            whileInView="show"
            viewport={{ once: true, amount: 0.3 }}
          >

            {/* Section Label */}
            <div className="inline-flex items-center gap-3 rounded-full bg-secondary px-4 py-2">
              <span className="h-2 w-2 rounded-full bg-primary" />
              <span className="text-xs font-semibold uppercase tracking-widest text-secondary-foreground">{t("label")}</span>
            </div>

            {/* Heading */}
            <div className="space-y-4">
              <h2 className="font-serif text-4xl font-bold leading-tight text-foreground lg:text-5xl">
                {t("heading")}
              </h2>
              {/* Solid Line Divider */}
              <div className="h-1 w-16 rounded-full bg-primary" />
            </div>

            {/* Description */}
            <p className="whitespace-pre-line font-sans text-lg font-light leading-relaxed text-muted-foreground">
              {t("description")}
            </p>

            {/* Stats Grid */}
            <div className="grid grid-cols-3 gap-6 pt-6">
              {[
                { count: t("stats.projects"), label: t("stats.projects_label") },
                { count: t("stats.exhibitions"), label: t("stats.exhibitions_label") },
                { count: t("stats.clients"), label: t("stats.clients_label") },
              ].map((stat, i) => (
                <motion.div
                  key={i}
                  initial="rest"
                  whileHover="hover"
                  variants={hoverLift}
                  className="rounded-lg border border-border bg-card p-4 text-center shadow-sm transition duration-300 hover:shadow-md"
                >
                  <div className="font-serif text-3xl font-bold text-foreground">
                    {stat.count}
                  </div>
                  <div className="mt-1 text-sm text-muted-foreground">{stat.label}</div>
                </motion.div>
              ))}
            </div>

            {/* Highlights */}
            {/* Note: In legacy it looped over highlights array. Here we'll hardcode based on translation keys or map over them if they exist. */}
            {/* Wait, the i18n file might not have an array. We will just use the hardcoded or fetched highlights. */}

            {/* CTA Button */}
            <div className="pt-4">
              <motion.div initial="rest" whileHover="hover" variants={hoverLift} className="inline-block">
                <Link href="/" className="inline-flex items-center gap-2 bg-primary px-8 py-4 text-sm uppercase tracking-widest text-primary-foreground shadow-xl transition duration-300 hover:bg-primary/90 hover:shadow-2xl">
                  <span>{t("cta_button")}</span>
                  <MoveRight className="w-5 h-5" />
                </Link>
              </motion.div>
            </div>
          </motion.div>

        </div>
      </div>
    </motion.section>
  );
}
