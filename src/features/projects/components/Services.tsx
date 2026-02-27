"use client";

import { useTranslations } from "next-intl";
import { Link } from "@/i18n/navigation";
import { ArrowRight } from "lucide-react";
import { motion } from "framer-motion";
import { fadeUp, fadeUpDelayed, hoverLift } from "./motion-presets";

export function Services() {
  const t = useTranslations("Home.services");

  const servicesList = [
    {
      key: "escape_masks",
      icon: (
        <svg className="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
      )
    },
    {
      key: "cinema_props",
      icon: (
        <svg className="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4M4 20h16a1 1 0 001-1V5a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1z" />
        </svg>
      )
    },
    {
      key: "costumes",
      icon: (
        <svg className="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
        </svg>
      )
    },
    {
      key: "educational_panels",
      icon: (
        <svg className="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
        </svg>
      )
    },
    {
      key: "other",
      icon: (
        <svg className="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
        </svg>
      )
    }
  ];

  return (
    <motion.section
      id="services"
      variants={fadeUp}
      initial="hidden"
      whileInView="show"
      viewport={{ once: true, amount: 0.2 }}
      className="relative overflow-hidden bg-background px-4 py-20 sm:px-6 lg:px-8"
    >
      {/* Decorative background elements */}
      <div className="absolute inset-0 -z-10">
        <div className="absolute left-20 top-40 h-72 w-72 rounded-full bg-primary/15 blur-3xl" />
        <div className="absolute bottom-40 right-20 h-96 w-96 rounded-full bg-accent/10 blur-3xl" />
        <div className="absolute left-1/2 top-1/2 h-96 w-96 -translate-x-1/2 -translate-y-1/2 transform rounded-full bg-secondary/40 blur-3xl" />
      </div>

      {/* Noise texture overlay */}
      <div
        className="absolute inset-0 -z-10 opacity-[0.02]"
        style={{
          backgroundImage: `url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noise'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.65' numOctaves='3' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noise)' opacity='1'/%3E%3C/svg%3E")`
        }}
      />

      <div className="max-w-7xl mx-auto">
        {/* Section Header */}
        <motion.div
          className="mb-16 text-center"
          variants={fadeUpDelayed(0.1)}
          initial="hidden"
          whileInView="show"
          viewport={{ once: true, amount: 0.3 }}
        >
          <span className="mb-8 inline-block rounded-full border border-primary/30 bg-background/70 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-primary backdrop-blur-sm">
            {t("label")}
          </span>

          <h2 className="mb-6 font-serif text-5xl leading-tight tracking-tight text-foreground md:text-6xl lg:text-7xl">
            {t("heading")}
          </h2>

          <div className="mx-auto mb-8 h-1 w-16 rounded-full bg-primary" />

          <p className="mx-auto max-w-2xl font-sans text-lg font-light leading-relaxed text-muted-foreground md:text-xl">
            {t("subheading")}
          </p>
        </motion.div>

        {/* Services Grid */}
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">

          {servicesList.map((service) => (
            <motion.div
              key={service.key}
              className="group relative"
              variants={fadeUpDelayed(0.16)}
              initial="hidden"
              whileInView="show"
              viewport={{ once: true, amount: 0.2 }}
            >
              <div className="absolute -inset-0.5 rounded-lg bg-gradient-to-r from-primary/35 to-accent/35 opacity-20 blur transition duration-500 group-hover:opacity-35" />
              <motion.div
                initial="rest"
                whileHover="hover"
                variants={hoverLift}
                className="relative h-full transform rounded-lg border border-border bg-card p-8 shadow-md transition-all duration-300 hover:shadow-lg"
              >
                <div className="mb-6 flex h-14 w-14 items-center justify-center rounded-lg bg-primary transition duration-300 group-hover:scale-110">
                  {service.icon}
                </div>
                <h3 className="mb-3 font-serif text-lg font-semibold text-foreground">{t(`${service.key}.title` as any)}</h3>
                <p className="font-sans text-sm leading-relaxed text-muted-foreground">{t(`${service.key}.description` as any)}</p>

                <div className="mt-6 flex items-center font-medium text-primary transition-all duration-300 group-hover:gap-2">
                  <span className="text-xs tracking-wide uppercase">{t("learn_more")}</span>
                  <ArrowRight className="w-4 h-4 transform group-hover:translate-x-1 transition-transform duration-300" />
                </div>
              </motion.div>
            </motion.div>
          ))}

          {/* Featured CTA Card */}
          <motion.div
            className="group relative"
            variants={fadeUpDelayed(0.22)}
            initial="hidden"
            whileInView="show"
            viewport={{ once: true, amount: 0.2 }}
          >
            <div className="absolute -inset-0.5 rounded-lg bg-gradient-to-r from-primary to-accent opacity-25 blur transition duration-500 group-hover:opacity-45" />
            <motion.div
              initial="rest"
              whileHover="hover"
              variants={hoverLift}
              className="relative flex h-full transform flex-col items-center justify-center rounded-lg border border-primary/40 bg-gradient-to-br from-primary to-accent p-8 text-center shadow-lg transition-all duration-300 hover:shadow-xl"
            >
              <div className="mb-6 flex h-14 w-14 items-center justify-center rounded-lg bg-white/20 backdrop-blur-sm transition duration-300 group-hover:scale-110">
                <svg className="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                </svg>
              </div>
              <h3 className="text-2xl font-semibold text-white mb-3 font-serif">{t("custom_project.title")}</h3>
              <p className="mb-6 font-sans text-sm leading-relaxed text-white/90">{t("custom_project.description")}</p>

              <Link href="/#contact" className="inline-flex items-center gap-2 rounded bg-white px-6 py-3 text-sm font-black uppercase tracking-wide text-primary shadow-md transition duration-300 hover:shadow-lg">
                <span>{t("contact_us")}</span>
                <ArrowRight className="w-4 h-4" />
              </Link>
            </motion.div>
          </motion.div>

        </div>
      </div>
    </motion.section>
  );
}
