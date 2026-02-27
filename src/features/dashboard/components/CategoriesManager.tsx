"use client";

import { useState } from "react";
import {
  Table,
  TableBody,
  TableCell,
  TableHead,
  TableHeader,
  TableRow
} from "@/components/ui/table";
import { Button } from "@/components/ui/button";
import {
  Dialog,
  DialogContent,
  DialogHeader,
  DialogTitle,
  DialogTrigger,
  DialogDescription,
} from "@/components/ui/dialog";
import { PlusCircle, Pencil, Trash2, Loader2 } from "lucide-react";
import { createCategory, updateCategory, deleteCategory } from "@/features/dashboard/actions/category-actions";

type Category = {
  id: number;
  name: string;
  slug: string;
  description: string | null;
  order: number | null;
};

export function CategoriesManager({ initialCategories }: { initialCategories: Category[] }) {
  const [categories, setCategories] = useState(initialCategories);
  const [isOpen, setIsOpen] = useState(false);
  const [isDeleting, setIsDeleting] = useState<number | null>(null);
  const [isLoading, setIsLoading] = useState(false);
  const [editingCategory, setEditingCategory] = useState<Category | null>(null);

  const handleOpenChange = (open: boolean) => {
    setIsOpen(open);
    if (!open) {
      setEditingCategory(null);
    }
  };

  const openEdit = (category: Category) => {
    setEditingCategory(category);
    setIsOpen(true);
  };

  const handleDelete = async (id: number) => {
    if (!confirm("Are you sure you want to delete this category? All associated projects will ALSO be deleted.")) return;

    setIsDeleting(id);
    try {
      const result = await deleteCategory(id);
      if (result.success) {
        setCategories((prev) => prev.filter((c) => c.id !== id));
      } else {
        alert("Delete failed: " + result.error);
      }
    } finally {
      setIsDeleting(null);
    }
  };

  const onSubmit = async (e: React.FormEvent<HTMLFormElement>) => {
    e.preventDefault();
    setIsLoading(true);

    const formData = new FormData(e.currentTarget);

    try {
      if (editingCategory) {
        const result = await updateCategory(editingCategory.id, null, formData);
        if (result.success) {
          setCategories((prev) =>
            prev.map((c) => c.id === editingCategory.id ?
              { ...c, name: formData.get("name") as string, slug: formData.get("slug") as string, description: formData.get("description") as string, order: parseInt(formData.get("order") as string || "0") }
              : c
            )
          );
          handleOpenChange(false);
        } else {
          alert(result.error);
        }
      } else {
        const result = await createCategory(null, formData);
        if (result.success && result.category) {
          setCategories((prev) => [...prev, result.category as Category]);
          handleOpenChange(false);
        } else {
          alert(result.error);
        }
      }
    } finally {
      setIsLoading(false);
    }
  };

  return (
    <div className="flex flex-col gap-8">
      <div className="flex justify-end">
        <Dialog open={isOpen} onOpenChange={handleOpenChange}>
          <DialogTrigger asChild>
            <Button className="h-12 rounded-2xl bg-primary px-6 font-bold text-primary-foreground hover:bg-primary/90 transition-all">
              <PlusCircle className="mr-2 h-4 w-4" />
              Add Category
            </Button>
          </DialogTrigger>
          <DialogContent className="border-border bg-card text-card-foreground sm:max-w-md">
            <DialogHeader>
              <DialogTitle className="text-2xl font-black uppercase tracking-tighter">
                {editingCategory ? "Edit Category" : "New Category"}
              </DialogTitle>
              <DialogDescription className="text-muted-foreground">
                Organize your projects effectively.
              </DialogDescription>
            </DialogHeader>
            <form onSubmit={onSubmit} className="flex flex-col gap-4 mt-4">
              <div className="flex flex-col gap-2">
                <label className="text-[10px] font-bold uppercase tracking-widest text-muted-foreground">Name</label>
                <input
                  name="name"
                  defaultValue={editingCategory?.name}
                  required
                  placeholder="e.g. Scenery"
                  className="rounded-lg border border-input bg-background px-4 py-3 text-sm text-foreground focus:outline-none focus:border-primary"
                />
              </div>
              <div className="flex flex-col gap-2">
                <label className="text-[10px] font-bold uppercase tracking-widest text-muted-foreground">Slug</label>
                <input
                  name="slug"
                  defaultValue={editingCategory?.slug}
                  required
                  placeholder="e.g. scenery"
                  className="rounded-lg border border-input bg-background px-4 py-3 text-sm text-foreground focus:outline-none focus:border-primary"
                />
              </div>
              <div className="flex flex-col gap-2">
                <label className="text-[10px] font-bold uppercase tracking-widest text-muted-foreground">Description</label>
                <textarea
                  name="description"
                  defaultValue={editingCategory?.description || ""}
                  rows={3}
                  className="resize-none rounded-lg border border-input bg-background px-4 py-3 text-sm text-foreground focus:outline-none focus:border-primary"
                />
              </div>
              <div className="flex flex-col gap-2">
                <label className="text-[10px] font-bold uppercase tracking-widest text-muted-foreground">Order</label>
                <input
                  name="order"
                  type="number"
                  defaultValue={editingCategory?.order || 0}
                  className="rounded-lg border border-input bg-background px-4 py-3 text-sm text-foreground focus:outline-none focus:border-primary"
                />
              </div>
              <Button disabled={isLoading} type="submit" className="mt-4 h-12 w-full rounded-xl bg-primary text-primary-foreground font-bold hover:bg-primary/90">
                {isLoading ? <Loader2 className="h-4 w-4 animate-spin" /> : "Save"}
              </Button>
            </form>
          </DialogContent>
        </Dialog>
      </div>

      <div className="overflow-hidden rounded-[2.5rem] border border-border bg-card/70 shadow-2xl">
        <Table>
          <TableHeader className="bg-muted/50">
            <TableRow className="border-border hover:bg-transparent">
              <TableHead className="text-muted-foreground font-bold tracking-widest uppercase text-[10px] py-6 pl-8">Name</TableHead>
              <TableHead className="text-muted-foreground font-bold tracking-widest uppercase text-[10px] py-6">Slug</TableHead>
              <TableHead className="text-muted-foreground font-bold tracking-widest uppercase text-[10px] py-6 text-right pr-8">Actions</TableHead>
            </TableRow>
          </TableHeader>
          <TableBody>
            {categories.map((category) => (
              <TableRow key={category.id} className="border-border hover:bg-accent/10 transition-colors">
                <TableCell className="font-bold text-foreground py-6 pl-8">{category.name}</TableCell>
                <TableCell className="text-muted-foreground font-mono text-xs">{category.slug}</TableCell>
                <TableCell className="text-right pr-8">
                  <div className="flex items-center justify-end gap-2">
                    <Button
                      onClick={() => openEdit(category)}
                      variant="ghost"
                      size="icon"
                      className="rounded-full text-muted-foreground hover:text-accent-foreground hover:bg-accent"
                    >
                      <Pencil className="h-4 w-4" />
                    </Button>
                    <Button
                      onClick={() => handleDelete(category.id)}
                      disabled={isDeleting === category.id}
                      variant="ghost"
                      size="icon"
                      className="rounded-full text-muted-foreground hover:text-destructive hover:bg-destructive/10"
                    >
                      {isDeleting === category.id ? <Loader2 className="h-4 w-4 animate-spin" /> : <Trash2 className="h-4 w-4" />}
                    </Button>
                  </div>
                </TableCell>
              </TableRow>
            ))}
            {categories.length === 0 && (
              <TableRow className="border-border hover:bg-transparent">
                <TableCell colSpan={3} className="py-12 text-center text-muted-foreground font-bold text-xs uppercase tracking-widest">
                  No Categories Found
                </TableCell>
              </TableRow>
            )}
          </TableBody>
        </Table>
      </div>
    </div>
  );
}
