"use server";

import { requireAdmin } from "@/features/auth/guard";
import { db } from "@/db";
import { testimonials } from "@/db/schema";
import { eq } from "drizzle-orm";
import { revalidatePath } from "next/cache";

export async function createTestimonial(prevState: any, formData: FormData) {
  try {
    await requireAdmin();

    const clientName = formData.get("clientName") as string;
    const clientTitle = formData.get("clientTitle") as string;
    const content = formData.get("content") as string;
    const isPublished = formData.get("isPublished") === "on";

    const [newTestimonial] = await db.insert(testimonials).values({
      clientName,
      clientTitle,
      content,
      isPublished,
    }).returning();

    revalidatePath("/dashboard/testimonials");
    revalidatePath("/");
    return { success: true, testimonial: newTestimonial };
  } catch (error: any) {
    console.error("Testimonial Creation Error:", error);
    return { success: false, error: error.message || "Failed to create testimonial" };
  }
}

export async function updateTestimonial(id: number, prevState: any, formData: FormData) {
  try {
    await requireAdmin();

    const clientName = formData.get("clientName") as string;
    const clientTitle = formData.get("clientTitle") as string;
    const content = formData.get("content") as string;
    const isPublished = formData.get("isPublished") === "on";

    await db.update(testimonials).set({
      clientName,
      clientTitle,
      content,
      isPublished,
      updatedAt: new Date(),
    }).where(eq(testimonials.id, id));

    revalidatePath("/dashboard/testimonials");
    revalidatePath("/");
    return { success: true };
  } catch (error: any) {
    console.error("Testimonial Update Error:", error);
    return { success: false, error: error.message || "Failed to update testimonial" };
  }
}

export async function deleteTestimonial(id: number) {
  try {
    await requireAdmin();

    await db.delete(testimonials).where(eq(testimonials.id, id));

    revalidatePath("/dashboard/testimonials");
    revalidatePath("/");
    return { success: true };
  } catch (error: any) {
    console.error("Testimonial Deletion Error:", error);
    return { success: false, error: error.message || "Failed to delete testimonial" };
  }
}

export async function toggleTestimonialPublished(id: number, isPublished: boolean) {
  try {
    await requireAdmin();

    await db.update(testimonials)
      .set({ isPublished, updatedAt: new Date() })
      .where(eq(testimonials.id, id));

    revalidatePath("/dashboard/testimonials");
    revalidatePath("/");
    return { success: true };
  } catch (error: any) {
    console.error("Testimonial Toggle Error:", error);
    return { success: false, error: error.message || "Failed to toggle visibility" };
  }
}
