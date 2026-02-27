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
import { PlusCircle, Pencil, Trash2, Loader2, MessageSquareQuote, Eye, EyeOff } from "lucide-react";
import { createTestimonial, updateTestimonial, deleteTestimonial, toggleTestimonialPublished } from "@/features/dashboard/actions/testimonial-actions";

type Testimonial = {
  id: number;
  clientName: string;
  clientTitle: string | null;
  content: string;
  isPublished: boolean | null;
};

export function TestimonialsManager({ initialTestimonials }: { initialTestimonials: Testimonial[] }) {
  const [items, setItems] = useState(initialTestimonials);
  const [isOpen, setIsOpen] = useState(false);
  const [isProcessing, setIsProcessing] = useState<number | null>(null);
  const [isLoading, setIsLoading] = useState(false);
  const [editingItem, setEditingItem] = useState<Testimonial | null>(null);

  const handleOpenChange = (open: boolean) => {
    setIsOpen(open);
    if (!open) {
      setEditingItem(null);
    }
  };

  const openEdit = (item: Testimonial) => {
    setEditingItem(item);
    setIsOpen(true);
  };

  const handleDelete = async (id: number) => {
    if (!confirm("Are you sure you want to delete this testimonial?")) return;

    setIsProcessing(id);
    try {
      const result = await deleteTestimonial(id);
      if (result.success) {
        setItems((prev) => prev.filter((i) => i.id !== id));
      } else {
        alert("Delete failed: " + result.error);
      }
    } finally {
      setIsProcessing(null);
    }
  };

  const handleToggle = async (id: number, current: boolean) => {
    setIsProcessing(id);
    try {
      const result = await toggleTestimonialPublished(id, !current);
      if (result.success) {
        setItems((prev) => prev.map(i => i.id === id ? { ...i, isPublished: !current } : i));
      } else {
        alert(result.error);
      }
    } finally {
      setIsProcessing(null);
    }
  }

  const onSubmit = async (e: React.FormEvent<HTMLFormElement>) => {
    e.preventDefault();
    setIsLoading(true);

    const formData = new FormData(e.currentTarget);

    try {
      if (editingItem) {
        const result = await updateTestimonial(editingItem.id, null, formData);
        if (result.success) {
          setItems((prev) =>
            prev.map((i) => i.id === editingItem.id ?
              {
                ...i,
                clientName: formData.get("clientName") as string,
                clientTitle: formData.get("clientTitle") as string,
                content: formData.get("content") as string,
                isPublished: formData.get("isPublished") === "on"
              }
              : i
            )
          );
          handleOpenChange(false);
        } else {
          alert(result.error);
        }
      } else {
        const result = await createTestimonial(null, formData);
        if (result.success && result.testimonial) {
          setItems((prev) => [...prev, result.testimonial as Testimonial]);
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
              Add Testimonial
            </Button>
          </DialogTrigger>
          <DialogContent className="border-border bg-card text-card-foreground sm:max-w-lg">
            <DialogHeader>
              <DialogTitle className="text-2xl font-black uppercase tracking-tighter text-foreground">
                {editingItem ? "Edit Testimonial" : "New Testimonial"}
              </DialogTitle>
              <DialogDescription className="text-muted-foreground">
                Share what your clients say about your masterpieces.
              </DialogDescription>
            </DialogHeader>
            <form onSubmit={onSubmit} className="flex flex-col gap-6 mt-6">
              <div className="flex flex-col gap-2">
                <label className="text-[10px] font-bold uppercase tracking-widest text-muted-foreground pl-1">Client Name</label>
                <input
                  name="clientName"
                  defaultValue={editingItem?.clientName}
                  required
                  placeholder="e.g. John Smith"
                  className="rounded-xl border border-input bg-background px-4 py-3 text-sm text-foreground focus:outline-none focus:border-primary transition-all"
                />
              </div>
              <div className="flex flex-col gap-2">
                <label className="text-[10px] font-bold uppercase tracking-widest text-muted-foreground pl-1">Client Title/Role</label>
                <input
                  name="clientTitle"
                  defaultValue={editingItem?.clientTitle || ""}
                  placeholder="e.g. Art Collector"
                  className="rounded-xl border border-input bg-background px-4 py-3 text-sm text-foreground focus:outline-none focus:border-primary transition-all"
                />
              </div>
              <div className="flex flex-col gap-2">
                <label className="text-[10px] font-bold uppercase tracking-widest text-muted-foreground pl-1">Testimonial Content</label>
                <textarea
                  name="content"
                  defaultValue={editingItem?.content || ""}
                  required
                  rows={4}
                  className="resize-none rounded-xl border border-input bg-background px-4 py-3 text-sm text-foreground focus:outline-none focus:border-primary transition-all"
                  placeholder="The craftsmanship was beyond words..."
                />
              </div>
              <div className="flex items-center gap-3 rounded-xl border border-border bg-muted/30 p-4">
                <input
                  type="checkbox"
                  id="isPublished"
                  name="isPublished"
                  defaultChecked={editingItem?.isPublished || false}
                  className="h-5 w-5 rounded-md border-input bg-background accent-primary"
                />
                <label htmlFor="isPublished" className="cursor-pointer text-xs font-bold text-foreground uppercase tracking-tight">Published on Site</label>
              </div>
              <Button disabled={isLoading} type="submit" className="h-14 w-full rounded-2xl bg-primary text-primary-foreground font-black uppercase tracking-widest hover:bg-primary/90 transition-all">
                {isLoading ? <Loader2 className="h-4 w-4 animate-spin" /> : "Save Milestone"}
              </Button>
            </form>
          </DialogContent>
        </Dialog>
      </div>

      <div className="overflow-hidden rounded-[2.5rem] border border-border bg-card/70 shadow-2xl">
        <Table>
          <TableHeader className="bg-muted/50">
            <TableRow className="border-border hover:bg-transparent">
              <TableHead className="text-muted-foreground font-bold tracking-widest uppercase text-[10px] py-6 pl-8">Client</TableHead>
              <TableHead className="text-muted-foreground font-bold tracking-widest uppercase text-[10px] py-6">Message</TableHead>
              <TableHead className="text-muted-foreground font-bold tracking-widest uppercase text-[10px] py-6">Status</TableHead>
              <TableHead className="text-muted-foreground font-bold tracking-widest uppercase text-[10px] py-6 text-right pr-8">Actions</TableHead>
            </TableRow>
          </TableHeader>
          <TableBody>
            {items.map((item) => (
              <TableRow key={item.id} className="border-border hover:bg-accent/10 transition-colors">
                <TableCell className="py-6 pl-8">
                  <div className="flex flex-col">
                    <span className="font-black text-foreground uppercase tracking-tight">{item.clientName}</span>
                    <span className="mt-1 text-[10px] font-bold text-muted-foreground uppercase tracking-widest">{item.clientTitle || "Customer"}</span>
                  </div>
                </TableCell>
                <TableCell className="max-w-xs transition-opacity duration-300 group">
                  <p className="text-muted-foreground text-xs font-medium italic line-clamp-2 leading-relaxed">
                    &ldquo;{item.content}&rdquo;
                  </p>
                </TableCell>
                <TableCell>
                  <Button
                    variant="ghost"
                    size="sm"
                    onClick={() => handleToggle(item.id, !!item.isPublished)}
                    disabled={isProcessing === item.id}
                    className={`rounded-full px-4 h-8 text-[9px] font-black uppercase tracking-widest border transition-all ${item.isPublished
                      ? "border-emerald-500/20 bg-emerald-500/10 text-emerald-400 hover:bg-emerald-500/20"
                      : "border-border bg-muted text-muted-foreground hover:bg-accent"}`}
                  >
                    {isProcessing === item.id ? <Loader2 className="h-3 w-3 animate-spin" /> : item.isPublished ? <><Eye className="w-3 h-3 mr-1.5" /> Published</> : <><EyeOff className="w-3 h-3 mr-1.5" /> Draft</>}
                  </Button>
                </TableCell>
                <TableCell className="text-right pr-8">
                  <div className="flex items-center justify-end gap-2">
                    <Button
                      onClick={() => openEdit(item)}
                      variant="ghost"
                      size="icon"
                      className="rounded-full text-muted-foreground hover:text-accent-foreground hover:bg-accent transition-all"
                    >
                      <Pencil className="h-4 w-4" />
                    </Button>
                    <Button
                      onClick={() => handleDelete(item.id)}
                      disabled={isProcessing === item.id}
                      variant="ghost"
                      size="icon"
                      className="rounded-full text-muted-foreground hover:text-destructive hover:bg-destructive/10 transition-all"
                    >
                      {isProcessing === item.id ? <Loader2 className="h-4 w-4 animate-spin" /> : <Trash2 className="h-4 w-4" />}
                    </Button>
                  </div>
                </TableCell>
              </TableRow>
            ))}
            {items.length === 0 && (
              <TableRow className="border-border hover:bg-transparent">
                <TableCell colSpan={4} className="py-24 text-center">
                  <MessageSquareQuote className="mx-auto mb-4 h-12 w-12 text-muted-foreground" />
                  <span className="font-black text-xs text-muted-foreground uppercase tracking-[0.2em]">Silence is golden, but feedback is better.</span>
                </TableCell>
              </TableRow>
            )}
          </TableBody>
        </Table>
      </div>
    </div>
  );
}
