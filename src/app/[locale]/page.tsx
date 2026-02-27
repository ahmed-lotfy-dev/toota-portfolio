import { getPortfolioData, getHeroImages, getPublishedTestimonials } from "@/features/projects/services/project-service";
import { ProjectGrid } from "@/features/projects/components/ProjectGrid";
import { Hero } from "@/features/projects/components/Hero";
import { About } from "@/features/projects/components/About";
import { Services } from "@/features/projects/components/Services";
import { Process } from "@/features/projects/components/Process";
import { Faq } from "@/features/projects/components/Faq";
import { ContactForm } from "@/features/projects/components/ContactForm";
import { Testimonials } from "@/features/projects/components/Testimonials";
import { getTranslations } from "next-intl/server";

export default async function Home({ params }: { params: Promise<{ locale: string }> }) {
  const { locale } = await params;
  const t = await getTranslations({ locale, namespace: "Home" });

  const [{ projects }, heroImages, testimonials] = await Promise.all([
    getPortfolioData(),
    getHeroImages(),
    getPublishedTestimonials(),
  ]);

  return (
    <main className="flex min-h-screen flex-col items-center bg-background selection:bg-primary selection:text-primary-foreground">
      {/* Hero Section */}
      <Hero
        images={heroImages as any}
        title={t("title")}
        subtitle={t("description")}
      />

      {/* Masterpieces Section (Legacy 'Projects') */}
      <section id="gallery" className="w-full max-w-7xl bg-background px-6 py-20 text-foreground">
        <div className="flex flex-col gap-2 mb-20">
          <h2 className="text-sm font-semibold uppercase tracking-[0.2em] text-primary">{t("projects.label")}</h2>
          <h3 className="font-serif text-4xl font-black tracking-tighter text-foreground md:text-5xl">{t("projects.heading")}</h3>
        </div>

        <ProjectGrid projects={projects} />
      </section>

      {/* About Section */}
      <About />

      {/* Services Section */}
      <Services />

      {/* Process Section */}
      <Process />

      {/* FAQ Section */}
      <Faq />

      {/* Contact Section */}
      <ContactForm />

      {/* Testimonials Section */}
      <Testimonials testimonials={testimonials} />
    </main>
  );
}
