"use client";

import { useActionState, useState } from "react";
import { createProject, updateProject } from "./actions/project-actions";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import {
  Select,
  SelectContent,
  SelectItem,
  SelectTrigger,
  SelectValue
} from "@/components/ui/select";
import { Checkbox } from "@/components/ui/checkbox";
import { Card, CardContent, CardHeader, CardTitle } from "@/components/ui/card";
import { Loader2, Upload, X } from "lucide-react";

interface ProjectFormProps {
  categories: { id: number; name: string }[];
  project?: any;
}

export function ProjectForm({ categories, project }: ProjectFormProps) {
  const [state, formAction, isPending] = useActionState(
    project ? updateProject.bind(null, project.id) : createProject,
    null
  );
  const [images, setImages] = useState<File[]>([]);
  const [existingImages, setExistingImages] = useState<any[]>(project?.images || []);

  const handleImageChange = (e: React.ChangeEvent<HTMLInputElement>) => {
    if (e.target.files) {
      setImages([...images, ...Array.from(e.target.files)]);
    }
  };

  const removeImage = (index: number) => {
    setImages(images.filter((_, i) => i !== index));
  };

  return (
    <div className="w-full max-w-4xl mx-auto">
      <form action={formAction} className="space-y-12">
        {/* Core Info Section */}
        <section className="bg-zinc-950/30 border border-zinc-900 rounded-[2.5rem] p-8 md:p-12 shadow-2xl">
          <h2 className="text-xl font-black text-white tracking-tight uppercase mb-8 border-b border-zinc-900 pb-4">
            General Information
          </h2>
          <div className="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div className="space-y-3">
              <Label htmlFor="title" className="text-zinc-500 font-bold uppercase text-[10px] tracking-widest pl-1">Project Title</Label>
              <Input
                id="title"
                name="title"
                defaultValue={project?.title}
                placeholder="e.g. Masterwork Mask"
                required
                className="h-14 bg-zinc-900/50 border-zinc-800 rounded-2xl focus:ring-1 focus:ring-white transition-all text-white font-medium"
              />
            </div>
            <div className="space-y-3">
              <Label htmlFor="slug" className="text-zinc-500 font-bold uppercase text-[10px] tracking-widest pl-1">Slug (URL)</Label>
              <Input
                id="slug"
                name="slug"
                defaultValue={project?.slug}
                placeholder="e.g. masterwork-mask"
                required
                className="h-14 bg-zinc-900/50 border-zinc-800 rounded-2xl focus:ring-1 focus:ring-white transition-all text-white font-mono text-sm"
              />
            </div>
          </div>

          <div className="mt-8 space-y-3">
            <Label htmlFor="categoryId" className="text-zinc-500 font-bold uppercase text-[10px] tracking-widest pl-1">Category</Label>
            <Select name="categoryId" required defaultValue={project?.categoryId?.toString()}>
              <SelectTrigger className="h-14 bg-zinc-900/50 border-zinc-800 rounded-2xl text-white font-medium">
                <SelectValue placeholder="Select a category" />
              </SelectTrigger>
              <SelectContent className="bg-zinc-900 border-zinc-800 rounded-2xl text-white">
                {categories.map((cat) => (
                  <SelectItem key={cat.id} value={cat.id.toString()} className="focus:bg-zinc-800 rounded-xl">
                    {cat.name}
                  </SelectItem>
                ))}
              </SelectContent>
            </Select>
          </div>

          <div className="mt-8 space-y-3">
            <Label htmlFor="description" className="text-zinc-500 font-bold uppercase text-[10px] tracking-widest pl-1">Project Story</Label>
            <textarea
              id="description"
              name="description"
              defaultValue={project?.description || ""}
              rows={5}
              className="w-full rounded-2xl border border-zinc-800 bg-zinc-900/50 px-4 py-4 text-sm text-white font-medium focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-white transition-all"
              placeholder="Tell the story of this creation..."
            ></textarea>
          </div>
        </section>

        {/* Media Section */}
        <section className="bg-zinc-950/30 border border-zinc-900 rounded-[2.5rem] p-8 md:p-12 shadow-2xl">
          <h2 className="text-xl font-black text-white tracking-tight uppercase mb-8 border-b border-zinc-900 pb-4">
            Masterpiece Gallery
          </h2>
          <div className="grid grid-cols-2 md:grid-cols-4 gap-6">
            {existingImages.map((img, i) => (
              <div key={`existing-${i}`} className="relative aspect-[3/4] rounded-3xl border border-zinc-800 bg-zinc-900/30 overflow-hidden group shadow-lg">
                <img
                  src={img.imagePath}
                  className="w-full h-full object-cover grayscale group-hover:grayscale-0 transition-all duration-700"
                  alt="Existing"
                />
                <div className="absolute top-4 right-4 z-10">
                  <span className="px-2 py-1 rounded-md bg-zinc-950 text-[8px] font-black uppercase text-zinc-500 border border-zinc-800">Legacy</span>
                </div>
              </div>
            ))}
            {images.map((img, i) => (
              <div key={i} className="relative aspect-[3/4] rounded-3xl border border-zinc-800 bg-zinc-900/30 overflow-hidden group shadow-lg">
                <img
                  src={URL.createObjectURL(img)}
                  className="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
                  alt="Preview"
                />
                <div className="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center backdrop-blur-sm">
                  <button
                    type="button"
                    onClick={() => removeImage(i)}
                    className="bg-white/10 hover:bg-red-500/20 text-white rounded-full p-3 border border-white/20 transition-all hover:border-red-500/50"
                  >
                    <X className="h-5 w-5 text-red-400" />
                  </button>
                </div>
              </div>
            ))}
            <label className="aspect-[3/4] rounded-3xl border-2 border-dashed border-zinc-800 bg-zinc-900/20 flex flex-col items-center justify-center cursor-pointer hover:bg-zinc-900/50 transition-all group">
              <div className="p-4 rounded-2xl bg-zinc-800/50 border border-zinc-700 mb-4 transition-transform group-hover:scale-110">
                <Upload className="h-6 w-6 text-zinc-400" />
              </div>
              <span className="text-[10px] font-black uppercase tracking-widest text-zinc-500">Add Artwork</span>
              <input
                type="file"
                name="images"
                multiple
                className="hidden"
                onChange={handleImageChange}
                accept="image/*"
              />
            </label>
          </div>
        </section>

        {/* Settings Section */}
        <section className="bg-zinc-950/30 border border-zinc-900 rounded-[2.5rem] p-8 md:p-12 shadow-2xl">
          <div className="flex items-center gap-4">
            <Checkbox
              id="isFeatured"
              name="isFeatured"
              defaultChecked={project?.isFeatured}
              className="w-6 h-6 rounded-lg border-zinc-800 bg-zinc-900"
            />
            <Label htmlFor="isFeatured" className="text-white font-bold tracking-tight">Feature on the main gallery</Label>
          </div>
        </section>

        {/* Actions */}
        <div className="flex justify-end gap-4 p-4">
          <Button
            variant="ghost"
            type="button"
            onClick={() => window.history.back()}
            className="h-14 px-8 rounded-2xl font-bold text-zinc-500 hover:text-white"
          >
            Cancel
          </Button>
          <Button
            type="submit"
            disabled={isPending}
            className="h-14 px-10 rounded-2xl font-black bg-white text-black hover:bg-zinc-200 transition-all shadow-[0_0_50px_rgba(255,255,255,0.1)] uppercase tracking-widest"
          >
            {isPending && <Loader2 className="mr-2 h-4 w-4 animate-spin" />}
            {project ? "Update Achievement" : "Publish Creation"}
          </Button>
        </div>

        {state?.error && (
          <div className="p-4 rounded-2xl border border-red-500/20 bg-red-500/10 text-red-400 text-sm font-bold text-center">
            {state.error}
          </div>
        )}
      </form>
    </div>
  );
}
