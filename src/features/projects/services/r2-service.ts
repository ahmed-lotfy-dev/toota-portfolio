import { PutObjectCommand } from "@aws-sdk/client-s3";
import { s3Client, R2_BUCKET } from "@/lib/r2";

type SanitizedImage = {
  buffer: Buffer;
  extension: string;
  contentType: string;
};

function getImageTargetFormat(mimeType: string): { extension: string; contentType: string } {
  switch (mimeType) {
    case "image/jpeg":
    case "image/jpg":
      return { extension: "jpg", contentType: "image/jpeg" };
    case "image/png":
      return { extension: "png", contentType: "image/png" };
    case "image/webp":
      return { extension: "webp", contentType: "image/webp" };
    case "image/avif":
      return { extension: "avif", contentType: "image/avif" };
    default:
      // Default to JPEG for unknown image types after sanitization.
      return { extension: "jpg", contentType: "image/jpeg" };
  }
}

async function sanitizeImageForUpload(file: File): Promise<SanitizedImage> {
  const arrayBuffer = await file.arrayBuffer();
  const inputBuffer = Buffer.from(arrayBuffer);
  const { extension, contentType } = getImageTargetFormat(file.type);

  // Re-encoding strips EXIF (including GPS metadata) by default in sharp.
  const sharpModule = await import("sharp");
  const sharp = sharpModule.default;
  let pipeline = sharp(inputBuffer, { animated: false }).rotate();

  if (extension === "png") {
    pipeline = pipeline.png();
  } else if (extension === "webp") {
    pipeline = pipeline.webp({ quality: 90 });
  } else if (extension === "avif") {
    pipeline = pipeline.avif({ quality: 55 });
  } else {
    pipeline = pipeline.jpeg({ quality: 90, mozjpeg: true });
  }

  const buffer = await pipeline.toBuffer();
  return { buffer, extension, contentType };
}

export async function uploadImageToR2(file: File) {
  if (!file.type?.startsWith("image/")) {
    throw new Error("Only image uploads are allowed.");
  }

  const { buffer, extension, contentType } = await sanitizeImageForUpload(file);

  // Generate unique filename
  const fileName = `${globalThis.crypto.randomUUID()}.${extension}`;
  const key = `projects/${fileName}`;

  const command = new PutObjectCommand({
    Bucket: R2_BUCKET,
    Key: key,
    Body: buffer,
    ContentType: contentType,
  });

  await s3Client.send(command).catch(err => {
    console.error("S3 Upload Error:", err);
    throw new Error("Failed to upload image to R2");
  });

  // Construct the public URL or return key for path generation
  // Replace pub-XXXX.r2.dev with your actual public R2 domain if needed
  return `https://${process.env.NEXT_PUBLIC_R2_PUBLIC_DOMAIN}/${key}`;
}
