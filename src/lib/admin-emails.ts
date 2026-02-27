function normalizeEmail(email?: string | null) {
  return (email ?? "").trim().toLowerCase();
}

export function getAdminEmails() {
  const raw = process.env.ADMIN_EMAIL ?? process.env.NEXT_PUBLIC_ADMIN_EMAIL ?? "";
  return raw
    .split(",")
    .map((item) => normalizeEmail(item))
    .filter(Boolean);
}

export function isAdminEmail(email?: string | null) {
  const target = normalizeEmail(email);
  if (!target) return false;
  return getAdminEmails().includes(target);
}
