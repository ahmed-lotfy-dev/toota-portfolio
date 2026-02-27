"use client";

import { FormEvent, useState } from "react";
import { useTranslations } from "next-intl";
import { Link } from "@/i18n/navigation";
import { usePathname } from "next/navigation";
import { MoveRight } from "lucide-react";

export function Footer() {
  const t = useTranslations("Home");
  const tNav = useTranslations("Navigation");
  const pathname = usePathname();
  const [newsletterEmail, setNewsletterEmail] = useState("");
  const [newsletterStatus, setNewsletterStatus] = useState<"idle" | "loading" | "success" | "already" | "error">("idle");
  const [newsletterError, setNewsletterError] = useState<string | null>(null);

  const isDashboard = pathname?.includes("/dashboard");
  const isAuth = pathname?.includes("/auth");

  if (isDashboard || isAuth) return null;

  const submitNewsletter = async (event: FormEvent<HTMLFormElement>) => {
    event.preventDefault();
    setNewsletterError(null);
    setNewsletterStatus("loading");

    try {
      const response = await fetch("/api/newsletter", {
        method: "POST",
        headers: {
          "content-type": "application/json",
        },
        body: JSON.stringify({ email: newsletterEmail.trim() }),
      });

      const data = (await response.json().catch(() => ({}))) as { status?: string; error?: string };
      if (!response.ok) {
        throw new Error(data.error || "Subscription failed.");
      }

      if (data.status === "already_subscribed") {
        setNewsletterStatus("already");
      } else {
        setNewsletterStatus("success");
      }

      setNewsletterEmail("");
    } catch (error: any) {
      setNewsletterStatus("error");
      setNewsletterError(error?.message || "Subscription failed.");
    }
  };

  return (
    <footer className="relative overflow-hidden border-t border-primary/40 bg-primary pb-12 pt-24 text-primary-foreground">

      {/* Subtle Grain Texture for the "Paper/Stone" feel */}
      <div
        className="absolute inset-0 opacity-[0.03] pointer-events-none"
        style={{
          backgroundImage: `url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noise'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.8' numOctaves='3' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noise)' opacity='1'/%3E%3C/svg%3E")`
        }}
      />

      <div className="max-w-7xl mx-auto px-6 md:px-12 relative z-10">

        {/* Top Section: Grid */}
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-12 gap-12 lg:gap-8 mb-20">

          {/* 1. Brand & Manifesto */}
          <div className="lg:col-span-4 space-y-8">
            <div className="flex items-center gap-3">
              <div className="flex h-10 w-10 items-center justify-center border border-primary/40 bg-background/5">
                <svg className="h-5 w-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24" strokeWidth="1.5">
                  <path strokeLinecap="round" strokeLinejoin="round" d="M9.53 16.122a3 3 0 00-5.78 1.128 2.25 2.25 0 01-2.4 2.245 4.5 4.5 0 008.4-2.245c0-.399-.078-.78-.22-1.128zm0 0a15.998 15.998 0 003.388-1.62m-5.043-.025a15.994 15.994 0 011.622-3.395m3.42 3.42a15.995 15.995 0 004.764-4.648l3.876-5.814a1.151 1.151 0 00-1.597-1.597L14.146 6.32a16.001 16.001 0 00-4.649 4.763m3.42 3.42a6.776 6.776 0 00-3.42-3.42" />
                </svg>
              </div>
              <h3 className="text-3xl font-bold tracking-tight font-serif">
                {tNav("brand") || "Toota Art"}
              </h3>
            </div>

            <p className="max-w-sm font-sans font-light leading-relaxed text-primary-foreground/80">
              {t("footer.about_text") || "Mastering the art of handcrafted creations, bringing imagination into reality through dedicated craftsmanship."}
            </p>

            {/* Social Icons */}
            <div className="flex gap-6">
              <Link href="#" className="text-primary-foreground/70 transition-colors duration-300 hover:text-primary-foreground">
                <span className="sr-only">Twitter</span>
                <svg className="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z" /></svg>
              </Link>
              <Link href="#" className="text-primary-foreground/70 transition-colors duration-300 hover:text-primary-foreground">
                <span className="sr-only">Instagram</span>
                <svg className="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z" /></svg>
              </Link>
            </div>
          </div>

          {/* 2. Sitemap (Studio) */}
          <div className="lg:col-span-2">
            <h4 className="mb-8 font-sans text-xs font-bold uppercase tracking-[0.2em] text-primary-foreground/80">
              {t("footer.sitemap") || "Sitemap"}
            </h4>
            <ul className="space-y-4 text-sm font-medium tracking-wide">
              <li><Link href="/" className="block font-sans transition-all duration-300 hover:translate-x-1 hover:text-primary-foreground">{tNav("home") || "Home"}</Link></li>
              <li><Link href="/#projects" className="block font-sans transition-all duration-300 hover:translate-x-1 hover:text-primary-foreground">{tNav("projects") || "Projects"}</Link></li>
              <li><Link href="/#services" className="block font-sans transition-all duration-300 hover:translate-x-1 hover:text-primary-foreground">{tNav("services") || "Services"}</Link></li>
              <li><Link href="/#about" className="block font-sans transition-all duration-300 hover:translate-x-1 hover:text-primary-foreground">{tNav("about") || "About"}</Link></li>
            </ul>
          </div>

          {/* 3. Contact (Enquiries) */}
          <div className="lg:col-span-2">
            <h4 className="mb-8 font-sans text-xs font-bold uppercase tracking-[0.2em] text-primary-foreground/80">
              {t("footer.contact_info") || "Enquiries"}
            </h4>
            <ul className="space-y-6">
              <li className="group">
                <p className="mb-1 font-sans text-xs uppercase tracking-wider text-primary-foreground/65">{t("footer.email_label")}</p>
                <a href="mailto:info@totaart.com" className="block font-serif text-lg italic transition hover:text-primary-foreground">info@totaart.com</a>
              </li>
              <li className="group">
                <p className="mb-1 font-sans text-xs uppercase tracking-wider text-primary-foreground/65">{t("footer.phone_label")}</p>
                <a href="tel:+201012345678" className="block font-sans text-sm transition hover:text-primary-foreground">+20 101 234 5678</a>
              </li>
            </ul>
          </div>

          {/* 4. Newsletter (Journal) */}
          <div className="lg:col-span-4 lg:pl-8">
            <h4 className="mb-8 font-sans text-xs font-bold uppercase tracking-[0.2em] text-primary-foreground/80">
              {t("footer.newsletter.title") || "The Journal"}
            </h4>
            <p className="mb-6 font-sans text-sm leading-relaxed text-primary-foreground/80">
              {t("footer.newsletter.description") || "Subscribe to our occasionally dispatched missives covering new projects and artistic musings."}
            </p>

            <form className="flex group" onSubmit={submitNewsletter}>
              <input
                type="email"
                value={newsletterEmail}
                onChange={(event) => setNewsletterEmail(event.target.value)}
                placeholder={t("footer.newsletter.email_placeholder")}
                required
                className="flex-1 rounded-l bg-white/15 px-4 py-3 font-sans text-sm text-primary-foreground placeholder:text-primary-foreground/65 focus:outline-none focus:ring-1 focus:ring-primary-foreground"
              />
              <button
                disabled={newsletterStatus === "loading"}
                className="flex items-center justify-center rounded-r bg-background px-6 py-3 font-sans text-xs font-bold uppercase tracking-widest text-foreground transition-colors hover:bg-background/90 disabled:cursor-not-allowed disabled:opacity-70"
              >
                {newsletterStatus === "loading" ? t("footer.newsletter.submit_loading") : t("footer.newsletter.submit_idle")} <MoveRight className="w-4 h-4 ml-2" />
              </button>
            </form>
            {newsletterStatus === "success" ? <p className="mt-3 text-xs text-emerald-200">{t("footer.newsletter.submit_success")}</p> : null}
            {newsletterStatus === "already" ? <p className="mt-3 text-xs text-amber-200">{t("footer.newsletter.submit_already")}</p> : null}
            {newsletterError ? <p className="mt-3 text-xs text-red-200">{newsletterError}</p> : null}
          </div>
        </div>

        {/* Bottom Bar */}
        <div className="flex flex-col items-center justify-between gap-4 border-t border-primary-foreground/20 pt-8 font-sans text-xs tracking-wide text-primary-foreground/65 md:flex-row">
          <p>
            &copy; {new Date().getFullYear()} {tNav("brand") || "Toota Art"}. {t("footer.rights")}
          </p>
          <div className="flex gap-8">
            <Link href="#" className="font-sans transition hover:text-primary-foreground">{t("footer.privacy_policy")}</Link>
            <Link href="#" className="font-sans transition hover:text-primary-foreground">{t("footer.terms_of_service")}</Link>
          </div>
        </div>
      </div>
    </footer>
  );
}
