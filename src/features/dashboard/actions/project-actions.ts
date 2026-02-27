"use server";

import { requireAdmin } from "@/features/auth/guard";
import { db } from "@/db";
import { projects, projectImages } from "@/db/schema";
import { eq } from "drizzle-orm";
import { revalidatePath } from "next/cache";
import { uploadImageToR2 } from "@/features/projects/services/r2-service";

export async function createProject(prevState: any, formData: FormData) {
  try {
    await requireAdmin();

    const title = formData.get("title") as string;
    const slug = formData.get("slug") as string;
    const description = formData.get("description") as string;
    const categoryId = parseInt(formData.get("categoryId") as string);
    const isFeatured = formData.get("isFeatured") === "on";

    const [newProject] = await db.insert(projects).values({
      title,
      slug,
      description,
      categoryId,
      isPublished: true,
      isFeatured,
      order: 0,
    }).returning();

    // Handle Images
    const imageFiles = formData.getAll("images") as File[];
    if (imageFiles && imageFiles.length > 0 && imageFiles[0].size > 0) {
      for (let i = 0; i < imageFiles.length; i++) {
        const file = imageFiles[i];
        if (file.size === 0) continue;

        const imagePath = await uploadImageToR2(file);

        await db.insert(projectImages).values({
          projectId: newProject.id,
          imagePath,
          order: i,
          isPrimary: i === 0,
        });
      }
    }

    revalidatePath("/");
    revalidatePath("/dashboard/projects");

    return { success: true, project: newProject };
  } catch (error: any) {
    console.error("Project Creation Error:", error);
    return { success: false, error: error.message || "Failed to create project" };
  }
}

export async function deleteProject(id: number) {
  await requireAdmin();

  await db.delete(projects).where(eq(projects.id, id));

  revalidatePath("/");
  revalidatePath("/dashboard/projects");

  return { success: true };
}

export async function toggleProjectVisibility(id: number, isPublished: boolean) {
  await requireAdmin();

  await db.update(projects)
    .set({ isPublished })
    .where(eq(projects.id, id));

  revalidatePath("/");
  revalidatePath("/dashboard/projects");

  return { success: true };
}
export async function updateProject(id: number, prevState: any, formData: FormData) {
  try {
    await requireAdmin();

    const title = formData.get("title") as string;
    const slug = formData.get("slug") as string;
    const description = formData.get("description") as string;
    const categoryId = parseInt(formData.get("categoryId") as string);
    const isFeatured = formData.get("isFeatured") === "on";

    await db.update(projects).set({
      title,
      slug,
      description,
      categoryId,
      isFeatured,
      updatedAt: new Date(),
    }).where(eq(projects.id, id));

    // Handle Image additions if any
    const imageFiles = formData.getAll("images") as File[];
    if (imageFiles && imageFiles.length > 0 && imageFiles[0].size > 0) {
      for (let i = 0; i < imageFiles.length; i++) {
        const file = imageFiles[i];
        if (file.size === 0) continue;

        const imagePath = await uploadImageToR2(file);

        await db.insert(projectImages).values({
          projectId: id,
          imagePath,
          order: i + 10, // Append to end roughly
          isPrimary: false,
        });
      }
    }

    revalidatePath("/");
    revalidatePath("/dashboard/projects");
    revalidatePath(`/projects/${slug}`);

    return { success: true };
  } catch (error: any) {
    console.error("Project Update Error:", error);
    return { success: false, error: error.message || "Failed to update project" };
  }
}
