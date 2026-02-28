"use client";

import { useState, useEffect } from "react";
import { useTranslations, useLocale } from "next-intl";
import { Link } from "@/i18n/navigation";
import { usePathname } from "next/navigation";
import { Menu, User } from "lucide-react";
import { useSession } from "@/lib/auth-client";
import { SignOutAction } from "@/features/auth/SignOutAction";
import { Button } from "@/components/ui/button";
import { motion } from "framer-motion";
import {
  Sheet,
  SheetClose,
  SheetContent,
  SheetDescription,
  SheetHeader,
  SheetTitle,
  SheetTrigger,
} from "@/components/ui/sheet";

export function Nav() {
  const t = useTranslations("Navigation");
  const locale = useLocale();
  const currentPathname = usePathname();
  const { data: session } = useSession();
  const [isOpen, setIsOpen] = useState(false);
  const [scrolled, setScrolled] = useState(false);

  const toggleLocale = locale === "en" ? "ar" : "en";

  // next-intl's Link automatically adds the locale prefix
  // We just need to give it the pathname WITHOUT the current locale prefix
  const getToggleLink = () => {
    if (!currentPathname) return "/";

    // Remove the current locale from the beginning of the pathname if it exists
    if (currentPathname.startsWith(`/${locale}`)) {
      const stripped = currentPathname.replace(`/${locale}`, "");
      return stripped === "" ? "/" : stripped;
    }

    return currentPathname;
  };

  useEffect(() => {
    const handleScroll = () => {
      setScrolled(window.scrollY > 20);
    };
    window.addEventListener("scroll", handleScroll);
    return () => window.removeEventListener("scroll", handleScroll);
  }, []);

  const navLinks = [
    { href: "/", label: t("home") || "Home" },
    { href: "/#projects", label: t("projects") || "Projects" },
    { href: "/#services", label: t("services") || "Services" },
    { href: "/#about", label: t("about") || "About" },
    { href: "/#process", label: t("process") || "Process" },
    { href: "/#faq", label: t("faq") || "FAQ" },
  ];

  const isDashboard = currentPathname?.includes("/dashboard");
  const isAuth = currentPathname?.includes("/auth");
  const adminEmails = (process.env.NEXT_PUBLIC_ADMIN_EMAIL ?? "")
    .split(",")
    .map((email) => email.trim().toLowerCase())
    .filter(Boolean);
  const sessionEmail = session?.user?.email?.trim().toLowerCase();
  const isAdminUser = Boolean(sessionEmail && adminEmails.includes(sessionEmail));
  const showSignOutInsteadOfDashboard = Boolean(session && !isAdminUser);

  if (isDashboard || isAuth) return null;

  return (
    <>
      <nav
        className={`fixed z-50 w-full border-b transition-all duration-300 ${scrolled
            ? "border-border bg-background/90 backdrop-blur-md"
            : "border-transparent bg-background/60 backdrop-blur-sm"
          }`}
      >
        <div className="mx-auto max-w-7xl px-6 md:px-12">
          <div className="flex h-24 items-center justify-between">
            <div className="shrink-0">
              <Link href="/" className="group flex items-center gap-3">
                <div className="flex h-10 w-10 items-center justify-center border border-primary bg-transparent transition-colors duration-500 group-hover:bg-primary">
                  <svg
                    className="h-5 w-5 text-primary transition-colors duration-500 group-hover:text-primary-foreground"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                    strokeWidth="1.5"
                  >
                    <path
                      strokeLinecap="round"
                      strokeLinejoin="round"
                      d="M9.53 16.122a3 3 0 00-5.78 1.128 2.25 2.25 0 01-2.4 2.245 4.5 4.5 0 008.4-2.245c0-.399-.078-.78-.22-1.128zm0 0a15.998 15.998 0 003.388-1.62m-5.043-.025a15.994 15.994 0 011.622-3.395m3.42 3.42a15.995 15.995 0 004.764-4.648l3.876-5.814a1.151 1.151 0 00-1.597-1.597L14.146 6.32a16.001 16.001 0 00-4.649 4.763m3.42 3.42a6.776 6.776 0 00-3.42-3.42"
                    />
                  </svg>
                </div>
                <span className="font-serif text-2xl font-bold tracking-tight text-foreground">
                  {t("brand") || "Toota Art"}
                </span>
              </Link>
            </div>

            <div className="hidden items-center gap-8 rtl:space-x-reverse lg:flex">
              {navLinks.map((link) => (
                <Link
                  key={link.href}
                  href={link.href}
                  className="group relative font-sans text-xs font-medium uppercase tracking-[0.15em] text-muted-foreground transition-colors duration-300 hover:text-foreground"
                >
                  {link.label}
                  <span className="absolute -bottom-2 left-1/2 h-px w-0 -translate-x-1/2 bg-primary transition-all duration-300 group-hover:w-full" />
                </Link>
              ))}
            </div>

            <div className="hidden items-center gap-6 rtl:space-x-reverse lg:flex">
              <div className="flex items-center font-sans text-xs font-bold tracking-widest text-muted-foreground">
                <Link
                  href={getToggleLink()}
                  locale={toggleLocale as "en" | "ar"}
                  className="hover:text-foreground transition uppercase"
                >
                  {toggleLocale}
                </Link>
              </div>

              {showSignOutInsteadOfDashboard ? (
                <SignOutAction
                  showLabel={false}
                  className="text-muted-foreground transition-colors hover:text-primary disabled:opacity-60"
                />
              ) : (
                <Link href="/dashboard" className="text-muted-foreground transition-colors hover:text-primary">
                  <User className="h-5 w-5" />
                </Link>
              )}

              <motion.div whileHover={{ y: -2, scale: 1.02 }} transition={{ duration: 0.2 }}>
                <Link
                  href="/#contact"
                  className="rounded-full bg-primary px-7 py-3 font-sans text-xs font-black uppercase tracking-[0.16em] text-primary-foreground shadow-[0_10px_30px_hsl(var(--primary)/0.25)] transition-colors hover:bg-accent"
                >
                  {t("contact") || "Contact"}
                </Link>
              </motion.div>
            </div>

            <div className="flex items-center gap-3 lg:hidden">
              <Sheet open={isOpen} onOpenChange={setIsOpen}>
                <SheetTrigger asChild>
                  <Button variant="ghost" size="icon" className="text-foreground hover:text-primary">
                    <Menu className="h-6 w-6" strokeWidth={1.8} />
                    <span className="sr-only">Open menu</span>
                  </Button>
                </SheetTrigger>
                <SheetContent side="right" className="w-[88vw] max-w-sm bg-card p-6">
                  <SheetHeader>
                    <SheetTitle className="font-serif text-2xl">{t("brand") || "Toota Art"}</SheetTitle>
                    <SheetDescription>Navigate sections and switch language.</SheetDescription>
                  </SheetHeader>

                  <div className="mt-8 flex flex-col gap-4">
                    {navLinks.map((link) => (
                      <SheetClose asChild key={link.href}>
                        <Link
                          href={link.href}
                          className="rounded-xl px-3 py-2 font-serif text-lg text-foreground transition hover:bg-accent/20 hover:text-primary"
                        >
                          {link.label}
                        </Link>
                      </SheetClose>
                    ))}
                  </div>

                  <div className="mt-8 border-t border-border pt-6">
                    <div className="mb-4 flex items-center gap-3 text-sm font-bold uppercase tracking-wide text-muted-foreground">
                      <span>Language</span>
                      <SheetClose asChild>
                        <Link
                          href={getToggleLink()}
                          locale={toggleLocale as "en" | "ar"}
                          className="rounded-lg border border-border px-3 py-1 text-foreground hover:bg-accent/20 uppercase"
                        >
                          {toggleLocale}
                        </Link>
                      </SheetClose>
                    </div>
                    <div className="flex flex-col gap-3">
                      {showSignOutInsteadOfDashboard ? (
                        <SignOutAction
                          onDone={() => setIsOpen(false)}
                          className="rounded-xl border border-border px-4 py-3 text-sm font-bold uppercase tracking-wide text-foreground hover:bg-accent/20 text-start inline-flex items-center gap-2"
                        />
                      ) : (
                        <SheetClose asChild>
                          <Link href="/dashboard" className="rounded-xl border border-border px-4 py-3 text-sm font-bold uppercase tracking-wide text-foreground hover:bg-accent/20">
                            {t("dashboard") || "Dashboard"}
                          </Link>
                        </SheetClose>
                      )}
                      <SheetClose asChild>
                        <motion.div whileHover={{ y: -2, scale: 1.01 }} transition={{ duration: 0.2 }}>
                          <Link href="/#contact" className="rounded-xl bg-primary px-4 py-3 text-center text-sm font-black uppercase tracking-wide text-primary-foreground shadow-[0_10px_30px_hsl(var(--primary)/0.25)] transition-colors hover:bg-accent">
                            {t("contact") || "Contact"}
                          </Link>
                        </motion.div>
                      </SheetClose>
                    </div>
                  </div>
                </SheetContent>
              </Sheet>
            </div>
          </div>
        </div>
      </nav>

      <div className="h-24 w-full bg-background" />
    </>
  );
}
