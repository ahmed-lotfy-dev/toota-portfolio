"use client";

import { useState } from "react";
import Image from "next/image";
import { updateHeroImage, deleteHeroImage, setHeroRatioMode } from "@/features/dashboard/actions/hero-actions";
import { Trash2, Upload, Loader2, Image as ImageIcon } from "lucide-react";

const POSITIONS = [
  { id: 1, label: "Mask Detail", desc: "Top left - Portrait", defaultRatio: "4:5" },
  { id: 2, label: "Workshop Tools", desc: "Bottom right - Landscape", defaultRatio: "16:9" },
  { id: 3, label: "Finished Prop", desc: "Top right - Square", defaultRatio: "1:1" },
  { id: 4, label: "Artisan Hands", desc: "Bottom left - Portrait", defaultRatio: "4:5" },
];

export function HeroImagesManager({ initialImages }: { initialImages: any[] }) {
  const [images, setImages] = useState(initialImages);
  const [uploadingPos, setUploadingPos] = useState<number | null>(null);

  const r2Domain = process.env.NEXT_PUBLIC_R2_PUBLIC_DOMAIN;

  const getImageUrl = (path: string | undefined) => {
    if (!path) return null;
    if (path.startsWith('http')) return path;
    if (path.startsWith('/')) return r2Domain ? `https://${r2Domain}${path}` : path;
    return r2Domain ? `https://${r2Domain}/${path}` : `/${path}`;
  };

  const handleUpload = async (e: React.ChangeEvent<HTMLInputElement>, position: number, label: string) => {
    const file = e.target.files?.[0];
    if (!file) return;

    setUploadingPos(position);

    try {
      // Create an image object to get dimensions
      const img = document.createElement("img");
      img.src = URL.createObjectURL(file);
      await new Promise((resolve) => {
        img.onload = resolve;
      });

      const formData = new FormData();
      formData.append("image", file);
      formData.append("label", label);
      formData.append("width", img.width.toString());
      formData.append("height", img.height.toString());

      const result = await updateHeroImage(position, formData);

      if (result.success) {
        // Update local state temporarily, or rely on server revalidation to refresh
        setImages((prev) => {
          const newImages = [...prev];
          const index = newImages.findIndex(i => i.position === position);
          const newImgData = {
            position,
            label,
            imagePath: result.path,
            width: img.width,
            height: img.height,
            ratioMode: "original"
          };
          if (index >= 0) {
            newImages[index] = { ...newImages[index], ...newImgData };
          } else {
            newImages.push(newImgData);
          }
          return newImages;
        });
      } else {
        alert("Upload failed: " + result.error);
      }
    } catch (error) {
      console.error(error);
      alert("Failed to process upload");
    } finally {
      setUploadingPos(null);
      e.target.value = ""; // Reset input
    }
  };

  const handleDelete = async (position: number) => {
    if (!confirm("Are you sure you want to delete this hero image?")) return;

    const result = await deleteHeroImage(position);
    if (result.success) {
      setImages((prev) => prev.filter(img => img.position !== position));
    } else {
      alert("Failed to delete: " + result.error);
    }
  };

  const handleRatioMode = async (position: number, mode: string) => {
    const result = await setHeroRatioMode(position, mode);
    if (result.success) {
      setImages((prev) => prev.map(img => img.position === position ? { ...img, ratioMode: mode } : img));
    } else {
      alert("Failed to update ratio mode: " + result.error);
    }
  }

  return (
    <div className="flex flex-col gap-8">
      {POSITIONS.map((pos) => {
        const currentImage = images.find((i) => i.position === pos.id);
        const resolvedSrc = getImageUrl(currentImage?.imagePath);

        return (
          <div key={pos.id} className="flex flex-col items-center gap-8 rounded-2xl border border-border bg-card/70 p-6 transition-colors hover:bg-card md:flex-row">
            {/* Preview */}
            <div className="relative flex h-32 w-32 shrink-0 items-center justify-center overflow-hidden rounded-xl border border-border bg-muted">
              {resolvedSrc ? (
                <Image
                  src={resolvedSrc}
                  alt={pos.label}
                  fill
                  className="object-cover"
                  unoptimized
                />
              ) : (
                <div className="flex flex-col items-center text-muted-foreground">
                  <ImageIcon strokeWidth={1.5} className="w-8 h-8 mb-2" />
                  <span className="text-[10px] uppercase tracking-widest font-bold">Empty</span>
                </div>
              )}
            </div>

            {/* Info */}
            <div className="flex-1 text-center md:text-left">
              <h3 className="text-lg font-black text-foreground tracking-tight">{pos.label}</h3>
              <p className="mt-1 text-xs font-bold text-muted-foreground uppercase tracking-widest">{pos.desc}</p>

              {currentImage && currentImage.width && (
                <div className="mt-3 inline-flex items-center rounded-full border border-border bg-muted px-3 py-1 text-[10px] font-mono text-muted-foreground">
                  {currentImage.width} x {currentImage.height}
                </div>
              )}

              {currentImage && (
                <div className="mt-4 flex flex-wrap gap-2">
                  <span className="mr-2 flex items-center text-[10px] font-bold text-muted-foreground uppercase tracking-widest">Aspect Ratio:</span>
                  {["original", "preset", "1:1", "4:5", "16:9"].map(mode => (
                    <button
                      key={mode}
                      onClick={() => handleRatioMode(pos.id, mode)}
                      className={`rounded-full px-3 py-1 text-[10px] font-bold uppercase tracking-widest transition-colors ${currentImage.ratioMode === mode ? "bg-primary text-primary-foreground" : "border border-border bg-muted text-muted-foreground hover:bg-accent hover:text-accent-foreground"}`}
                    >
                      {mode === "preset" ? `Slot (${pos.defaultRatio})` : mode}
                    </button>
                  ))}
                </div>
              )}
            </div>

            {/* Actions */}
            <div className="shrink-0 flex items-center gap-4 w-full md:w-auto">
              <div className="relative flex-1 md:w-48 group">
                <input
                  type="file"
                  accept="image/*"
                  onChange={(e) => handleUpload(e, pos.id, pos.label)}
                  disabled={uploadingPos !== null}
                  className="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10 disabled:cursor-not-allowed"
                />
                <button
                  disabled={uploadingPos !== null}
                  className="flex w-full items-center justify-center gap-2 rounded-xl bg-primary px-6 py-3 text-xs font-bold text-primary-foreground uppercase tracking-widest transition-colors group-hover:bg-primary/90 disabled:opacity-50"
                >
                  {uploadingPos === pos.id ? (
                    <><Loader2 className="w-4 h-4 animate-spin" /> Uploading...</>
                  ) : (
                    <><Upload className="w-4 h-4" /> Upload</>
                  )}
                </button>
              </div>

              {currentImage && (
                <button
                  onClick={() => handleDelete(pos.id)}
                  disabled={uploadingPos !== null}
                  className="rounded-xl bg-destructive/10 p-3 text-destructive transition-colors hover:bg-destructive hover:text-destructive-foreground disabled:opacity-50"
                  aria-label="Delete image"
                >
                  <Trash2 className="w-5 h-5" />
                </button>
              )}
            </div>
          </div>
        );
      })}
    </div>
  );
}
