"use server";

import { requireAdmin } from "@/features/auth/guard";
import { db } from "@/db";
import { heroImages } from "@/db/schema";
import { eq } from "drizzle-orm";
import { revalidatePath } from "next/cache";
import { uploadImageToR2 } from "@/features/projects/services/r2-service";

export async function updateHeroImage(position: number, formData: FormData) {
  try {
    await requireAdmin();

    const file = formData.get("image") as File;
    const label = formData.get("label") as string;
    const width = parseInt(formData.get("width") as string);
    const height = parseInt(formData.get("height") as string);

    if (!file || file.size === 0) {
      throw new Error("No image file provided");
    }

    const imagePath = await uploadImageToR2(file);

    await db.insert(heroImages).values({
      imagePath,
      label,
      position,
      width,
      height,
      ratioMode: "original",
    }).onConflictDoUpdate({
      target: heroImages.position,
      set: {
        imagePath,
        label,
        width,
        height,
        updatedAt: new Date(),
      }
    });

    revalidatePath("/");
    revalidatePath("/dashboard/hero-images");
    return { success: true, path: imagePath };
  } catch (error: any) {
    console.error("Hero Image Update Error:", error);
    return { success: false, error: error.message || "Failed to update hero image" };
  }
}

export async function deleteHeroImage(position: number) {
  try {
    await requireAdmin();

    await db.delete(heroImages).where(eq(heroImages.position, position));

    revalidatePath("/");
    revalidatePath("/dashboard/hero-images");
    return { success: true };
  } catch (error: any) {
    console.error("Hero Image Deletion Error:", error);
    return { success: false, error: error.message || "Failed to delete hero image" };
  }
}

export async function setHeroRatioMode(position: number, ratioMode: string) {
  try {
    await requireAdmin();

    await db.update(heroImages)
      .set({ ratioMode })
      .where(eq(heroImages.position, position));

    revalidatePath("/");
    revalidatePath("/dashboard/hero-images");
    return { success: true };
  } catch (error: any) {
    console.error("Hero Ratio Mode Error:", error);
    return { success: false, error: error.message || "Failed to update ratio mode" };
  }
}
