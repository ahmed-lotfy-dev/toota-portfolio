"use server";

import { requireAdmin } from "@/features/auth/guard";
import { db } from "@/db";
import { categories } from "@/db/schema";
import { eq } from "drizzle-orm";
import { revalidatePath } from "next/cache";

export async function createCategory(prevState: any, formData: FormData) {
  try {
    await requireAdmin();

    const name = formData.get("name") as string;
    const slug = formData.get("slug") as string;
    const description = formData.get("description") as string;
    const order = parseInt(formData.get("order") as string || "0");

    const [newCategory] = await db.insert(categories).values({
      name,
      slug,
      description,
      order,
    }).returning();

    revalidatePath("/dashboard/categories");
    return { success: true, category: newCategory };
  } catch (error: any) {
    console.error("Category Creation Error:", error);
    return { success: false, error: error.message || "Failed to create category" };
  }
}

export async function updateCategory(id: number, prevState: any, formData: FormData) {
  try {
    await requireAdmin();

    const name = formData.get("name") as string;
    const slug = formData.get("slug") as string;
    const description = formData.get("description") as string;
    const order = parseInt(formData.get("order") as string || "0");

    await db.update(categories).set({
      name,
      slug,
      description,
      order,
      updatedAt: new Date(),
    }).where(eq(categories.id, id));

    revalidatePath("/dashboard/categories");
    return { success: true };
  } catch (error: any) {
    console.error("Category Update Error:", error);
    return { success: false, error: error.message || "Failed to update category" };
  }
}

export async function deleteCategory(id: number) {
  try {
    await requireAdmin();

    await db.delete(categories).where(eq(categories.id, id));

    revalidatePath("/dashboard/categories");
    return { success: true };
  } catch (error: any) {
    console.error("Category Deletion Error:", error);
    return { success: false, error: error.message || "Failed to delete category" };
  }
}
