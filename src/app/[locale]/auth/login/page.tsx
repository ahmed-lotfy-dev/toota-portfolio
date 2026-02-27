import { AuthButton } from "@/features/auth/AuthButton";
import { CredentialSignInForm } from "@/features/auth/CredentialSignInForm";
import { getAdminSession, getSession } from "@/features/auth/guard";
import { redirect } from "next/navigation";

type LoginPageProps = {
  params: Promise<{ locale: string }>;
  searchParams: Promise<{ callbackURL?: string }>;
};

function resolveCallbackURL(locale: string, callbackURL?: string) {
  const fallback = `/${locale}/dashboard`;

  if (!callbackURL) {
    return fallback;
  }

  if (!callbackURL.startsWith("/")) {
    return fallback;
  }

  return callbackURL;
}

export default async function LoginPage({ params, searchParams }: LoginPageProps) {
  const { locale } = await params;
  const query = await searchParams;
  const callbackURL = resolveCallbackURL(locale, query.callbackURL);
  const adminSession = await getAdminSession();
  const session = await getSession();

  if (adminSession) {
    redirect(callbackURL);
  }

  if (session) {
    redirect(`/${locale}/access-denied`);
  }

  return (
    <div className="min-h-screen flex items-center justify-center bg-black">
      <div className="w-full max-w-md p-12 rounded-[3rem] bg-zinc-950/50 border border-zinc-900 backdrop-blur-3xl shadow-2xl flex flex-col items-center text-center">
        <div className="w-16 h-16 rounded-2xl bg-white flex items-center justify-center font-black text-black text-3xl mb-8 shadow-[0_0_50px_rgba(255,255,255,0.2)]">
          T
        </div>
        <h1 className="text-3xl font-black text-white tracking-tighter uppercase mb-4">
          Masterpiece Access
        </h1>
        <p className="text-zinc-500 font-bold tracking-tight uppercase text-xs mb-12">
          Sign in to manage your artistic portfolio
        </p>

        <div className="w-full">
          <CredentialSignInForm callbackURL={callbackURL} />
        </div>

        <div className="my-6 flex w-full items-center gap-3">
          <div className="h-px flex-1 bg-zinc-800" />
          <span className="text-[10px] font-bold uppercase tracking-[0.2em] text-zinc-600">
            Or
          </span>
          <div className="h-px flex-1 bg-zinc-800" />
        </div>

        <div className="w-full">
          <AuthButton callbackURL={callbackURL} />
        </div>

        <div className="mt-12 pt-8 border-t border-zinc-900 w-full">
          <p className="text-[10px] text-zinc-700 font-black uppercase tracking-[0.2em]">
            Toota Art &copy; 2025
          </p>
        </div>
      </div>
    </div>
  );
}
