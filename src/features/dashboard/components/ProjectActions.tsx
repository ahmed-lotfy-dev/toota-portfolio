"use client";

import { useState } from "react";
import { Button } from "@/components/ui/button";
import { Pencil, Trash2, ExternalLink, Eye, EyeOff, Loader2 } from "lucide-react";
import { Link, useRouter } from "@/i18n/navigation";
import { toggleProjectVisibility, deleteProject } from "@/features/dashboard/actions/project-actions";
import { ConfirmDialog } from "@/components/ui/confirm-dialog";

export function ProjectActions({ project }: { project: any }) {
  const router = useRouter();
  const [isPending, setIsPending] = useState(false);
  const [isPublished, setIsPublished] = useState(project.isPublished);

  const handleToggleVisibility = async () => {
    setIsPending(true);
    const newStatus = !isPublished;
    const result = await toggleProjectVisibility(project.id, newStatus);
    if (result.success) {
      setIsPublished(newStatus);
    } else {
      alert("Failed to update visibility");
    }
    setIsPending(false);
  };

  const handleDelete = async () => {
    setIsPending(true);
    const result = await deleteProject(project.id);
    if (result.success) {
      router.refresh();
    } else {
      alert("Failed to delete project");
      setIsPending(false);
    }
  };

  return (
    <div className="flex items-center justify-end gap-2">
      <Button
        variant="ghost"
        size="icon"
        onClick={handleToggleVisibility}
        disabled={isPending}
        className="rounded-full text-muted-foreground hover:bg-accent hover:text-accent-foreground"
        title={isPublished ? "Hide from public" : "Show to public"}
      >
        {isPending ? (
          <Loader2 className="h-4 w-4 animate-spin" />
        ) : isPublished ? (
          <Eye className="h-4 w-4" />
        ) : (
          <EyeOff className="h-4 w-4" />
        )}
      </Button>

      <Button variant="ghost" size="icon" asChild className="rounded-full text-muted-foreground hover:bg-accent hover:text-accent-foreground" title="Edit">
        <Link href={`/dashboard/projects/${project.id}`}>
          <Pencil className="h-4 w-4" />
        </Link>
      </Button>

      <ConfirmDialog
        title="Delete project?"
        description="This will remove the project and all related images."
        confirmText="Delete"
        onConfirm={handleDelete}
        trigger={
          <Button
            variant="ghost"
            size="icon"
            disabled={isPending}
            className="rounded-full text-muted-foreground hover:bg-destructive/10 hover:text-destructive"
            title="Delete"
          >
            <Trash2 className="h-4 w-4" />
          </Button>
        }
      />

      <Button variant="ghost" size="icon" asChild className="rounded-full text-muted-foreground hover:bg-accent hover:text-accent-foreground" title="View Public">
        <Link href={`/projects/${project.slug}`} target="_blank">
          <ExternalLink className="h-4 w-4" />
        </Link>
      </Button>
    </div>
  );
}
