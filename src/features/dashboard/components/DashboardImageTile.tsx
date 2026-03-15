"use client";

import { ReactNode } from "react";
import { Loader2, X } from "lucide-react";
import { cn } from "@/lib/utils";
import { ConfirmDialog } from "@/components/ui/confirm-dialog";

type DashboardImageTileProps = {
  src?: string | null;
  alt: string;
  onDelete?: () => Promise<void> | void;
  deleting?: boolean;
  badge?: string;
  className?: string;
  imageClassName?: string;
  placeholder?: ReactNode;
  confirmTitle?: string;
  confirmDescription?: string;
  confirmText?: string;
};

export function DashboardImageTile({
  src,
  alt,
  onDelete,
  deleting,
  badge,
  className,
  imageClassName,
  placeholder,
  confirmTitle = "Delete image?",
  confirmDescription = "This cannot be undone.",
  confirmText = "Delete",
}: DashboardImageTileProps) {
  return (
    <div className={cn("relative overflow-hidden rounded-3xl border border-border bg-card/60 shadow-lg", className)}>
      {src ? (
        <img
          src={src}
          alt={alt}
          className={cn("h-full w-full object-cover", imageClassName)}
        />
      ) : (
        <div className="flex h-full w-full items-center justify-center">
          {placeholder}
        </div>
      )}

      {badge ? (
        <div className="absolute top-3 right-3 z-10">
          <span className="px-2 py-1 rounded-md bg-zinc-950 text-[8px] font-black uppercase text-zinc-500 border border-zinc-800">
            {badge}
          </span>
        </div>
      ) : null}

      {onDelete ? (
        <ConfirmDialog
          title={confirmTitle}
          description={confirmDescription}
          confirmText={confirmText}
          onConfirm={onDelete}
          trigger={
            <button
              type="button"
              disabled={deleting}
              className="absolute top-3 left-3 z-10 rounded-full bg-black/70 p-2 text-white border border-white/20 shadow-lg"
              title="Delete image"
            >
              {deleting ? (
                <Loader2 className="h-4 w-4 animate-spin" />
              ) : (
                <X className="h-4 w-4 text-red-300" />
              )}
            </button>
          }
        />
      ) : null}
    </div>
  );
}
