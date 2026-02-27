"use client";

import Image from "next/image";
import { motion } from "framer-motion";
import { ArrowRight } from "lucide-react";
import { Link } from "@/i18n/navigation";
import { useTranslations } from "next-intl";
import { fadeUp, hoverLift } from "./motion-presets";

interface HeroImage {
  id: number;
  imagePath: string;
  label?: string;
  position: number;
}

interface HeroProps {
  images: HeroImage[];
  title: string;
  subtitle: string;
}

export function Hero({ images, title, subtitle }: HeroProps) {
  const t = useTranslations("Home.hero");
  // Map images to positions based on legacy layout
  const img1 = images.find((img) => img.position === 1);
  const img2 = images.find((img) => img.position === 2);
  const img3 = images.find((img) => img.position === 3);
  const img4 = images.find((img) => img.position === 4);

  const r2Domain = process.env.NEXT_PUBLIC_R2_PUBLIC_DOMAIN;
  const getImageUrl = (path: string | undefined, defaultUrl: string) => {
    if (!path) return defaultUrl;
    if (path.startsWith('http')) return path;
    if (path.startsWith('/')) return r2Domain ? `https://${r2Domain}${path}` : path;
    return r2Domain ? `https://${r2Domain}/${path}` : `/${path}`;
  };

  return (
    <section className="relative flex min-h-[90vh] w-full items-center justify-center overflow-hidden bg-background selection:bg-primary selection:text-primary-foreground">
      {/* Background Noise & Glow */}
      <div className="absolute inset-0 pointer-events-none">
        <div className="absolute left-1/2 top-1/2 h-[600px] w-[600px] -translate-x-1/2 -translate-y-1/2 rounded-full bg-primary/15 blur-[120px]" />
        <div className="absolute left-1/3 top-1/3 h-[440px] w-[440px] rounded-full bg-accent/10 blur-[100px]" />
        <div
          className="absolute inset-0 opacity-[0.03]"
          style={{
            backgroundImage: `url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noise'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.65' numOctaves='3' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noise)' opacity='1'/%3E%3C/svg%3E")`
          }}
        />
      </div>

      {/* Scattered Images Container - Move fully to background and reduce opacity */}
      <div className="absolute inset-0 pointer-events-none -z-10 overflow-hidden opacity-40 md:opacity-100">
        {/* Image 1: Top Left */}
        <motion.div
          initial={{ opacity: 0, x: -50, rotate: -10 }}
          animate={{ opacity: 0.8, x: 0, rotate: -6 }}
          transition={{ duration: 1, delay: 0.2 }}
          className="absolute top-[-5%] left-[-5%] aspect-[3/4] w-48 border-8 border-background bg-card shadow-2xl transition duration-1000 hover:z-20 hover:scale-105 hover:rotate-0 md:left-[5%] md:top-[10%] md:w-64"
        >
          <Image
            src={getImageUrl(img1?.imagePath, "https://images.unsplash.com/photo-1595418878648-2615a133df1f?q=80&w=800&auto=format&fit=crop")}
            alt={img1?.label || "Mask Detail"}
            fill
            unoptimized
            className="object-cover grayscale-[0.2] contrast-[1.1]"
          />
        </motion.div>

        {/* Image 2: Bottom Right */}
        <motion.div
          initial={{ opacity: 0, x: 50, rotate: 10 }}
          animate={{ opacity: 0.8, x: 0, rotate: 3 }}
          transition={{ duration: 1, delay: 0.4 }}
          className="absolute bottom-[-5%] right-[-10%] aspect-video w-56 border-8 border-background bg-card shadow-xl transition duration-1000 hover:z-20 hover:scale-105 hover:rotate-0 md:bottom-[15%] md:right-[5%] md:w-80"
        >
          <Image
            src={getImageUrl(img2?.imagePath, "https://images.unsplash.com/photo-1576495149368-24eb224976c6?q=80&w=800&auto=format&fit=crop")}
            alt={img2?.label || "Workshop Tools"}
            fill
            unoptimized
            className="object-cover sepia-[0.2]"
          />
        </motion.div>

        {/* Image 3: Top Right */}
        <motion.div
          initial={{ opacity: 0, y: -50, rotate: 20 }}
          animate={{ opacity: 0.5, y: 0, rotate: 12 }}
          transition={{ duration: 1, delay: 0.6 }}
          className="absolute top-[5%] right-[-5%] hidden aspect-square w-32 border-[6px] border-background bg-card shadow-lg transition duration-1000 hover:z-20 hover:scale-105 hover:rotate-0 md:right-[15%] md:top-[15%] md:block md:w-48"
        >
          <Image
            src={getImageUrl(img3?.imagePath, "https://images.unsplash.com/photo-1542129202-b2d49c693427?q=80&w=800&auto=format&fit=crop")}
            alt={img3?.label || "Finished Prop"}
            fill
            unoptimized
            className="object-cover"
          />
        </motion.div>

        {/* Image 4: Bottom Left */}
        <motion.div
          initial={{ opacity: 0, y: 50, rotate: -20 }}
          animate={{ opacity: 0.5, y: 0, rotate: -12 }}
          transition={{ duration: 1, delay: 0.8 }}
          className="absolute bottom-[10%] left-[-8%] hidden aspect-2/3 w-32 border-[6px] border-background bg-card shadow-xl transition duration-1000 hover:z-20 hover:scale-105 hover:rotate-0 md:bottom-[10%] md:left-[12%] md:block md:w-48"
        >
          <Image
            src={getImageUrl(img4?.imagePath, "https://images.unsplash.com/photo-1596280687154-1563f68340d1?q=80&w=800&auto=format&fit=crop")}
            alt={img4?.label || "Artisan Hands"}
            fill
            unoptimized
            className="object-cover grayscale"
          />
        </motion.div>
      </div>


      {/* Hero Content - Add glassmorphism backdrop so it stands out above the scattered images */}
      <motion.div
        variants={fadeUp}
        initial="hidden"
        whileInView="show"
        viewport={{ once: true, amount: 0.3 }}
        className="pointer-events-auto relative z-40 mx-auto mb-12 max-w-3xl rounded-3xl border border-border bg-card/75 px-6 py-12 text-center shadow-2xl backdrop-blur-md md:border-transparent md:bg-transparent md:shadow-none md:backdrop-blur-none"
      >
        <motion.span
          initial={{ opacity: 0, y: 10 }}
          animate={{ opacity: 1, y: 0 }}
          transition={{ duration: 0.6 }}
          className="mb-6 inline-block rounded-full border border-primary/30 bg-background/90 px-3 py-1 text-xs font-semibold uppercase tracking-widest text-primary"
        >
          {t("badge")}
        </motion.span>

        <motion.h1
          initial={{ opacity: 0, y: 20 }}
          animate={{ opacity: 1, y: 0 }}
          transition={{ duration: 0.8 }}
          className="mb-8 font-serif text-5xl leading-[0.9] tracking-tight text-foreground md:text-7xl lg:text-8xl md:drop-shadow-sm"
        >
          <span>{title}</span>
          <span className="mt-4 block text-2xl font-normal italic text-muted-foreground md:text-4xl">
            {t("subtitle_suffix")}
          </span>
        </motion.h1>

        <motion.p
          initial={{ opacity: 0 }}
          animate={{ opacity: 1 }}
          transition={{ duration: 1, delay: 0.5 }}
          className="mx-auto mb-10 max-w-xl rounded-xl bg-background/80 p-4 font-sans text-base font-light leading-relaxed text-muted-foreground md:bg-transparent md:p-0 md:text-xl"
        >
          {subtitle}
        </motion.p>

        <motion.div
          initial={{ opacity: 0, y: 20 }}
          animate={{ opacity: 1, y: 0 }}
          transition={{ duration: 0.8, delay: 0.8 }}
          className="flex flex-col md:flex-row gap-4 justify-center items-center"
        >
          <motion.div initial="rest" whileHover="hover" variants={hoverLift} className="w-full md:w-auto">
            <Link
              href="#gallery"
              className="group relative w-full overflow-hidden rounded bg-primary px-8 py-4 text-sm uppercase tracking-widest text-primary-foreground transition-all duration-300 hover:bg-primary/90 hover:shadow-lg md:w-auto md:rounded-none"
            >
              <span className="relative z-10">{t("cta_explore")}</span>
              <span className="absolute bottom-2 left-1/2 h-px w-0 -translate-x-1/2 bg-primary-foreground/70 transition-all duration-300 group-hover:w-1/2" />
            </Link>
          </motion.div>

          <motion.div initial="rest" whileHover="hover" variants={hoverLift} className="w-full md:w-auto">
            <Link
              href="#contact"
              className="group flex w-full items-center justify-center gap-2 rounded border border-border bg-card px-8 py-4 text-sm uppercase tracking-widest text-foreground transition-colors hover:bg-accent hover:text-accent-foreground md:w-auto md:rounded-none md:border-transparent md:bg-transparent md:hover:bg-transparent md:hover:text-primary"
            >
              <span>{t("cta_commission")}</span>
              <ArrowRight className="w-4 h-4 transition-transform group-hover:translate-x-1" />
            </Link>
          </motion.div>
        </motion.div>
      </motion.div>

      {/* Scroll Indicator */}
      <div className="absolute bottom-8 left-1/2 -translate-x-1/2 flex flex-col items-center gap-2 opacity-50 animate-bounce">
        <span className="text-[10px] uppercase tracking-widest text-muted-foreground">{t("scroll")}</span>
        <div className="h-12 w-px bg-border" />
      </div>
    </section>
  );
}
