"use client";

import { useTranslations } from "next-intl";
import { motion } from "framer-motion";
import { fadeUp, fadeUpDelayed, hoverLift } from "./motion-presets";

export function Process() {
  const t = useTranslations("Home.process");

  return (
    <motion.section
      id="process"
      variants={fadeUp}
      initial="hidden"
      whileInView="show"
      viewport={{ once: true, amount: 0.2 }}
      className="w-full bg-background px-6 py-20 md:px-12"
    >
      <div className="mx-auto max-w-4xl text-center">
        <span className="mb-2 block text-xs font-semibold uppercase tracking-[0.2em] text-primary">
          {t("subtitle")}
        </span>
        <h2 className="mb-16 font-serif text-4xl leading-tight text-foreground md:text-5xl">
          {t("title")}
        </h2>

        <div className="grid grid-cols-1 md:grid-cols-3 gap-12 relative">
          {/* Connector Line */}
          <div className="absolute top-1/2 -translate-y-1/2 left-0 w-full h-px hidden md:block">
            <div className="-mt-4 h-px w-full border-t border-dashed border-border" />
          </div>

          {/* Step 1 */}
          <motion.div
            className="relative z-10 flex flex-col items-center text-center md:border-l-2 md:border-dotted md:border-border"
            variants={fadeUpDelayed(0.08)}
            initial="hidden"
            whileInView="show"
            viewport={{ once: true, amount: 0.3 }}
          >
            <motion.div initial="rest" whileHover="hover" variants={hoverLift} className="my-10 flex h-16 w-16 items-center justify-center rounded-full border-2 border-primary/40 bg-card font-serif text-xl text-primary shadow-sm md:mb-10">
              01
            </motion.div>
            <h3 className="mb-7 font-serif text-lg font-semibold text-foreground">
              {t("step1_title")}
            </h3>
            <p className="px-3 font-sans text-sm leading-relaxed text-muted-foreground">
              {t("step1_description")}
            </p>
          </motion.div>

          {/* Step 2 */}
          <motion.div
            className="relative z-10 flex flex-col items-center text-center md:border-l-2 md:border-dotted md:border-border"
            variants={fadeUpDelayed(0.16)}
            initial="hidden"
            whileInView="show"
            viewport={{ once: true, amount: 0.3 }}
          >
            <motion.div initial="rest" whileHover="hover" variants={hoverLift} className="my-10 flex h-16 w-16 items-center justify-center rounded-full border-2 border-accent/40 bg-card font-serif text-xl text-accent shadow-sm md:mb-10">
              02
            </motion.div>
            <h3 className="mb-7 font-serif text-lg font-semibold text-foreground">
              {t("step2_title")}
            </h3>
            <p className="px-3 font-sans text-sm leading-relaxed text-muted-foreground">
              {t("step2_description")}
            </p>
          </motion.div>

          {/* Step 3 */}
          <motion.div
            className="relative z-10 flex flex-col items-center text-center"
            variants={fadeUpDelayed(0.24)}
            initial="hidden"
            whileInView="show"
            viewport={{ once: true, amount: 0.3 }}
          >
            <motion.div initial="rest" whileHover="hover" variants={hoverLift} className="my-10 flex h-16 w-16 items-center justify-center rounded-full border-2 border-primary/40 bg-card font-serif text-xl text-primary shadow-sm md:mb-10">
              03
            </motion.div>
            <h3 className="mb-7 font-serif text-lg font-semibold text-foreground">
              {t("step3_title")}
            </h3>
            <p className="px-3 font-sans text-sm leading-relaxed text-muted-foreground">
              {t("step3_description")}
            </p>
          </motion.div>
        </div>
      </div>
    </motion.section>
  );
}
