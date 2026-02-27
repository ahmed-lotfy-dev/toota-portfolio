import type { Metadata } from "next";
import { Playfair_Display, Inter, Tajawal } from "next/font/google";
import "../globals.css";
import { NextIntlClientProvider } from "next-intl";
import { getMessages } from "next-intl/server";
import { notFound } from "next/navigation";
import { routing } from "@/i18n/routing";
import { Nav } from "@/features/projects/components/Nav";
import { Footer } from "@/features/projects/components/Footer";

const serif = Playfair_Display({
  subsets: ["latin"],
  variable: "--font-serif",
});

const sans = Inter({
  subsets: ["latin"],
  variable: "--font-sans",
});

const arabic = Tajawal({
  subsets: ["arabic"],
  weight: ["300", "400", "500", "700", "800", "900"],
  variable: "--font-arabic",
});

export const metadata: Metadata = {
  title: "Toota Art Portfolio",
  description: "Next.js Portfolio for Toota Art",
};

export default async function RootLayout({
  children,
  params,
}: Readonly<{
  children: React.ReactNode;
  params: Promise<{ locale: string }>;
}>) {
  const { locale } = await params;

  if (!routing.locales.includes(locale as any)) {
    notFound();
  }

  const messages = await getMessages();
  const dir = locale === "ar" ? "rtl" : "ltr";

  return (
    <html lang={locale} dir={dir} suppressHydrationWarning>
      <head>
        <script
          dangerouslySetInnerHTML={{
            __html: `
              (() => {
                try {
                  const key = "toota-theme";
                  const saved = localStorage.getItem(key) || "system";
                  const prefersDark = window.matchMedia("(prefers-color-scheme: dark)").matches;
                  const useDark = saved === "dark" || (saved === "system" && prefersDark);
                  document.documentElement.classList.toggle("dark", useDark);
                } catch {}
              })();
            `,
          }}
        />
      </head>
      <body
        className={`${serif.variable} ${sans.variable} ${arabic.variable} antialiased`}
      >
        <NextIntlClientProvider locale={locale} messages={messages}>
          <Nav />
          {children}
          <Footer />
        </NextIntlClientProvider>
      </body>
    </html>
  );
}
