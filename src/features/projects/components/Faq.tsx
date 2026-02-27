"use client";

import { useTranslations } from "next-intl";
import * as Accordion from "@radix-ui/react-accordion";
import { ChevronDown } from "lucide-react";
import { motion } from "framer-motion";
import { fadeUp, fadeUpDelayed, hoverLift } from "./motion-presets";

export function Faq() {
  const t = useTranslations("Home.faq");

  const faqs = [
    { value: "item-1", q: t("q1"), a: t("a1") },
    { value: "item-2", q: t("q2"), a: t("a2") },
    { value: "item-3", q: t("q3"), a: t("a3") },
    { value: "item-4", q: t("q4"), a: t("a4") },
  ];

  return (
    <motion.section
      id="faq"
      variants={fadeUp}
      initial="hidden"
      whileInView="show"
      viewport={{ once: true, amount: 0.2 }}
      className="w-full bg-background px-6 py-20 md:px-12"
    >
      <div className="mx-auto max-w-4xl">
        <motion.div
          className="mb-16 text-center"
          variants={fadeUpDelayed(0.1)}
          initial="hidden"
          whileInView="show"
          viewport={{ once: true, amount: 0.3 }}
        >
          <span className="mb-2 block text-xs font-semibold uppercase tracking-[0.2em] text-primary">
            {t("subtitle")}
          </span>
          <h2 className="font-serif text-4xl leading-tight text-foreground md:text-5xl">
            {t("title")}
          </h2>
        </motion.div>

        <Accordion.Root
          type="single"
          defaultValue="item-1"
          collapsible
          className="space-y-4"
        >
          {faqs.map((faq) => (
            <motion.div key={faq.value} initial="rest" whileHover="hover" variants={hoverLift}>
            <Accordion.Item
              key={faq.value}
              value={faq.value}
              className="overflow-hidden rounded-xl border border-border bg-card px-5 pb-4 pt-3"
            >
              <Accordion.Header className="flex">
                <Accordion.Trigger className="w-full flex justify-between items-center text-left group">
                  <h3 className="font-serif text-lg font-medium text-foreground">
                    {faq.q}
                  </h3>
                  <div className="text-muted-foreground transition-colors group-data-[state=open]:text-primary">
                    <ChevronDown className="w-5 h-5 transition-transform duration-300 group-data-[state=open]:rotate-180" />
                  </div>
                </Accordion.Trigger>
              </Accordion.Header>
              <Accordion.Content className="overflow-hidden font-sans text-sm leading-relaxed text-muted-foreground data-[state=closed]:animate-slideUp data-[state=open]:animate-slideDown">
                <div className="pt-4">{faq.a}</div>
              </Accordion.Content>
            </Accordion.Item>
            </motion.div>
          ))}
        </Accordion.Root>
      </div>
    </motion.section>
  );
}
