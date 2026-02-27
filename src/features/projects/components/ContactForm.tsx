"use client";

import { useState } from "react";
import { motion } from "framer-motion";
import { useTranslations } from "next-intl";
import { fadeUp, fadeUpDelayed, hoverLift } from "./motion-presets";

export function ContactForm() {
  const t = useTranslations("Home.contact");
  const [status, setStatus] = useState<"idle" | "loading" | "success" | "error">("idle");
  const [errorMessage, setErrorMessage] = useState<string | null>(null);

  const handleSubmit = async (e: React.FormEvent<HTMLFormElement>) => {
    e.preventDefault();
    setErrorMessage(null);
    setStatus("loading");

    const formData = new FormData(e.currentTarget);

    const payload = {
      name: String(formData.get("name") ?? ""),
      email: String(formData.get("email") ?? ""),
      phone: String(formData.get("phone") ?? ""),
      message: String(formData.get("message") ?? ""),
    };

    try {
      const response = await fetch("/api/contact", {
        method: "POST",
        headers: {
          "content-type": "application/json",
        },
        body: JSON.stringify(payload),
      });

      if (!response.ok) {
        const data = (await response.json().catch(() => ({}))) as { error?: string };
        throw new Error(data.error || "Failed to submit message.");
      }

      setStatus("success");
      e.currentTarget.reset();
    } catch (error: any) {
      setStatus("error");
      setErrorMessage(error?.message || "Failed to send message.");
    }
  };

  return (
    <motion.section
      id="contact"
      variants={fadeUp}
      initial="hidden"
      whileInView="show"
      viewport={{ once: true, amount: 0.2 }}
      className="relative w-full overflow-hidden bg-gradient-to-br from-primary to-accent px-6 py-24 md:px-12"
    >
      {/* Decorative Blur */}
      <div className="absolute inset-0 pointer-events-none overflow-hidden">
        <div className="absolute left-1/2 top-1/2 h-[800px] w-[800px] -translate-x-1/2 -translate-y-1/2 rounded-full bg-foreground/20 blur-[120px]" />
      </div>

      <div className="max-w-4xl mx-auto relative z-10">
        <motion.div
          className="mb-16 text-center"
          variants={fadeUpDelayed(0.1)}
          initial="hidden"
          whileInView="show"
          viewport={{ once: true, amount: 0.3 }}
        >
          <span className="mb-4 block font-sans text-xs font-semibold uppercase tracking-[0.2em] text-primary-foreground/80">
            {t("label")}
          </span>
          <h2 className="mb-6 font-serif text-4xl leading-tight text-primary-foreground md:text-5xl">
            {t("heading")}
          </h2>
          <p className="mx-auto max-w-2xl font-sans text-lg font-light text-primary-foreground/85">
            {t("description")}
          </p>
        </motion.div>

        <motion.form
          onSubmit={handleSubmit}
          variants={fadeUpDelayed(0.2)}
          initial="hidden"
          whileInView="show"
          viewport={{ once: true, amount: 0.3 }}
          className="rounded-2xl border border-white/25 bg-card/90 p-8 shadow-2xl backdrop-blur-md md:p-12"
        >
          <div className="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
            <div className="space-y-2">
              <label htmlFor="name" className="font-sans text-sm font-medium text-foreground">{t("name_label")}</label>
              <input
                type="text"
                id="name"
                name="name"
                required
                className="w-full rounded-lg border border-input bg-background px-4 py-3 text-foreground placeholder:text-muted-foreground focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary transition-colors"
                placeholder={t("name_placeholder")}
              />
            </div>
            <div className="space-y-2">
              <label htmlFor="email" className="font-sans text-sm font-medium text-foreground">{t("email_label")}</label>
              <input
                type="email"
                id="email"
                name="email"
                required
                className="w-full rounded-lg border border-input bg-background px-4 py-3 text-foreground placeholder:text-muted-foreground focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary transition-colors"
                placeholder={t("email_placeholder")}
              />
            </div>
          </div>

          <div className="space-y-2 mb-8">
            <label htmlFor="phone" className="font-sans text-sm font-medium text-foreground">{t("phone_label")}</label>
            <input
              type="text"
              id="phone"
              name="phone"
              className="w-full rounded-lg border border-input bg-background px-4 py-3 text-foreground placeholder:text-muted-foreground focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary transition-colors"
              placeholder={t("phone_placeholder")}
            />
          </div>

          <div className="space-y-2 mb-8">
            <label htmlFor="message" className="font-sans text-sm font-medium text-foreground">{t("message_label")}</label>
            <textarea
              id="message"
              name="message"
              required
              rows={5}
              className="w-full resize-none rounded-lg border border-input bg-background px-4 py-3 text-foreground placeholder:text-muted-foreground transition-colors focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary"
              placeholder={t("message_placeholder")}
            />
          </div>

          <motion.button
            type="submit"
            disabled={status === "loading" || status === "success"}
            initial="rest"
            whileHover="hover"
            variants={hoverLift}
            className="flex w-full items-center justify-center gap-2 rounded-lg bg-primary py-4 text-sm font-semibold uppercase tracking-widest text-primary-foreground transition-colors duration-300 hover:bg-primary/90 disabled:cursor-not-allowed disabled:opacity-50"
          >
            {status === "idle" && t("submit_idle")}
            {status === "loading" && t("submit_loading")}
            {status === "success" && t("submit_success")}
            {status === "error" && t("submit_error")}
          </motion.button>
          {errorMessage ? <p className="mt-4 text-sm text-red-500">{errorMessage}</p> : null}
        </motion.form>
      </div>
    </motion.section>
  );
}
