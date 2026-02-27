"use client";

import { useState } from "react";
import { Button } from "@/components/ui/button";
import { Pencil, Trash2, ExternalLink, Eye, EyeOff, Loader2 } from "lucide-react";
import { Link } from "@/i18n/navigation";
import { toggleProjectVisibility, deleteProject } from "@/features/dashboard/actions/project-actions";

export function ProjectActions({ project }: { project: any }) {
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
    if (!confirm("Are you sure you want to delete this project?")) return;
    setIsPending(true);
    const result = await deleteProject(project.id);
    if (result.success) {
      // Refresh page or update state
      window.location.reload();
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

      <Button
        variant="ghost"
        size="icon"
        onClick={handleDelete}
        disabled={isPending}
        className="rounded-full text-muted-foreground hover:bg-destructive/10 hover:text-destructive"
        title="Delete"
      >
        <Trash2 className="h-4 w-4" />
      </Button>

      <Button variant="ghost" size="icon" asChild className="rounded-full text-muted-foreground hover:bg-accent hover:text-accent-foreground" title="View Public">
        <Link href={`/projects/${project.slug}`} target="_blank">
          <ExternalLink className="h-4 w-4" />
        </Link>
      </Button>
    </div>
  );
}
