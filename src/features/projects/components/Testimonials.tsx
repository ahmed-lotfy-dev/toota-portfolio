"use client";

import { useTranslations, useLocale } from "next-intl";
import { motion } from "framer-motion";
import { fadeUp, fadeUpDelayed, hoverLift } from "./motion-presets";

type PublishedTestimonial = {
  id: number;
  clientName: string;
  clientTitle: string | null;
  content: string;
};

export function Testimonials({ testimonials = [] }: { testimonials?: PublishedTestimonial[] }) {
  const t = useTranslations("Home.testimonials");
  const locale = useLocale();
  const isRtl = locale === "ar";

  if (!testimonials.length) return null;

  return (
    <motion.section
      className="overflow-hidden border-t border-border bg-background py-20"
      variants={fadeUp}
      initial="hidden"
      whileInView="show"
      viewport={{ once: true, amount: 0.2 }}
    >
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <motion.div
          className="mb-16 text-center"
          variants={fadeUpDelayed(0.1)}
          initial="hidden"
          whileInView="show"
          viewport={{ once: true, amount: 0.3 }}
        >
          <span className="mb-2 block text-xs font-semibold uppercase tracking-[0.2em] text-primary">
            {t("subtitle") || "Client Stories"}
          </span>
          <h2 className="font-serif text-4xl font-extrabold text-foreground md:text-5xl">
            {t("title") || "What They Say"}
          </h2>
        </motion.div>

        {testimonials.length > 3 ? (
          <div className="relative w-full overflow-hidden flex">
            {/* 
              We use two identical motion divs side-by-side to create an infinite scroll effect.
              The direction is controlled by the x translation based on RTL/LTR.
             */}
            <motion.div
              className="flex gap-8 w-max pr-8 hover:cursor-grab active:cursor-grabbing"
              animate={{
                x: isRtl ? ["0%", "50%"] : ["0%", "-50%"],
              }}
              transition={{
                duration: 40,
                ease: "linear",
                repeat: Infinity,
              }}
            >
              {/* Render both the original and duplicate set inside the same animated div */}
              {[...testimonials, ...testimonials].map((testimonial, idx) => (
                <motion.div
                  key={`${testimonial.id}-${idx}`}
                  initial="rest"
                  whileHover="hover"
                  variants={hoverLift}
                  className="flex w-[350px] shrink-0 flex-col rounded-lg border border-border bg-card p-8 shadow-sm transition-shadow duration-300 hover:shadow-md"
                >
                  <p className="grow font-serif text-base italic leading-relaxed text-muted-foreground">
                    "{testimonial.content}"
                  </p>
                  <div className="mt-8 flex items-center">
                    <div className="shrink-0">
                      <div className="flex h-10 w-10 items-center justify-center rounded-full bg-secondary font-serif font-bold uppercase text-secondary-foreground">
                        {testimonial.clientName.charAt(0)}
                      </div>
                    </div>
                    <div className="ml-3">
                      <div className="font-sans text-base font-medium text-foreground">{testimonial.clientName}</div>
                      <div className="font-sans text-sm text-muted-foreground">{testimonial.clientTitle || "Client"}</div>
                    </div>
                  </div>
                </motion.div>
              ))}
            </motion.div>
          </div>
        ) : (
          <div className="grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-3">
            {testimonials.map((testimonial) => (
              <motion.div
                key={testimonial.id}
                initial="rest"
                whileHover="hover"
                variants={hoverLift}
                className="flex flex-col rounded-lg border border-border bg-card p-8 shadow-sm transition-shadow duration-300 hover:shadow-md"
              >
                <p className="grow font-serif text-base italic leading-relaxed text-muted-foreground">
                  "{testimonial.content}"
                </p>
                <div className="mt-8 flex items-center">
                  <div className="shrink-0">
                    <div className="flex h-10 w-10 items-center justify-center rounded-full bg-secondary font-serif font-bold uppercase text-secondary-foreground">
                      {testimonial.clientName.charAt(0)}
                    </div>
                  </div>
                  <div className="ml-3">
                    <div className="font-sans text-base font-medium text-foreground">{testimonial.clientName}</div>
                    <div className="font-sans text-sm text-muted-foreground">{testimonial.clientTitle || "Client"}</div>
                  </div>
                </div>
              </motion.div>
            ))}
          </div>
        )}
      </div>
    </motion.section>
  );
}
