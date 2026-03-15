import { S3Client } from "@aws-sdk/client-s3";

function pickEnv(primary: string, fallback?: string) {
  return primary ?? fallback;
}

function requireEnv(label: string, value: string | undefined, fallbacks: string[]) {
  if (!value) {
    const fallbackNote = fallbacks.length ? ` (or ${fallbacks.join(", ")})` : "";
    throw new Error(`Missing R2 configuration: set ${label}${fallbackNote}.`);
  }
  return value;
}

const accountId = pickEnv(process.env.R2_ACCOUNT_ID, process.env.CLOUDFLARE_R2_ACCOUNT_ID);
const accessKeyId = pickEnv(process.env.R2_ACCESS_KEY_ID, process.env.CLOUDFLARE_R2_ACCESS_KEY_ID);
const secretAccessKey = pickEnv(process.env.R2_SECRET_ACCESS_KEY, process.env.CLOUDFLARE_R2_SECRET_ACCESS_KEY);
const bucketName = pickEnv(process.env.R2_BUCKET_NAME, process.env.CLOUDFLARE_R2_BUCKET);
const endpoint =
  pickEnv(process.env.R2_ENDPOINT, process.env.CLOUDFLARE_R2_ENDPOINT) ??
  (accountId ? `https://${accountId}.r2.cloudflarestorage.com` : undefined);

export const s3Client = new S3Client({
  region: "auto",
  endpoint: requireEnv("R2_ENDPOINT", endpoint, ["CLOUDFLARE_R2_ENDPOINT"]),
  credentials: {
    accessKeyId: requireEnv("R2_ACCESS_KEY_ID", accessKeyId, ["CLOUDFLARE_R2_ACCESS_KEY_ID"]),
    secretAccessKey: requireEnv("R2_SECRET_ACCESS_KEY", secretAccessKey, ["CLOUDFLARE_R2_SECRET_ACCESS_KEY"]),
  },
});

export const R2_BUCKET = requireEnv("R2_BUCKET_NAME", bucketName, ["CLOUDFLARE_R2_BUCKET"]);
