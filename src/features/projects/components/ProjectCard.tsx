import { Link } from "@/i18n/navigation";
import Image from "next/image";
import { motion } from "framer-motion";
import { fadeUpDelayed, hoverLift } from "./motion-presets";

interface ProjectCardProps {
  project: any;
}

export function ProjectCard({ project }: ProjectCardProps) {
  const primaryImage = project.images?.[0]?.imagePath;
  const r2Domain = process.env.NEXT_PUBLIC_R2_PUBLIC_DOMAIN;

  const getImageUrl = (path: string | undefined) => {
    if (!path) return null;
    if (path.startsWith('http')) return path;
    if (path.startsWith('/')) return r2Domain ? `https://${r2Domain}${path}` : path;
    return r2Domain ? `https://${r2Domain}/${path}` : `/${path}`;
  };

  const safeImageSrc = getImageUrl(primaryImage);

  return (
    <motion.div
      variants={fadeUpDelayed(0.08)}
      initial="hidden"
      whileInView="show"
      viewport={{ once: true, amount: 0.25 }}
    >
    <Link
      href={`/projects/${project.slug}`}
      className="group mb-8 block break-inside-avoid focus:outline-none"
    >
      <motion.div
        initial="rest"
        whileHover="hover"
        variants={hoverLift}
        className="relative overflow-hidden rounded-lg border border-border bg-card"
      >
        <div className="overflow-hidden">
          {safeImageSrc ? (
            <Image
              src={safeImageSrc}
              alt={project.title}
              width={800}
              height={1200}
              unoptimized={!!safeImageSrc}
              className="w-full h-auto object-cover transform transition-transform duration-700 group-hover:scale-105 grayscale-[0.1] group-hover:grayscale-0"
              sizes="(max-width: 768px) 100vw, (max-width: 1200px) 50vw, 33vw"
            />
          ) : (
            <div className="flex aspect-[4/5] w-full items-center justify-center bg-secondary text-secondary-foreground/70">
              No Image
            </div>
          )}
        </div>
        <div className="absolute inset-0 bg-primary/0 transition-colors duration-500 group-hover:bg-primary/10" />
      </motion.div>

      <div className="mt-4 flex justify-between items-start">
        <div className="flex-1">
          <h3 className="line-clamp-1 font-serif text-xl italic text-foreground transition-colors group-hover:text-primary">
            {project.title}
          </h3>
          <p className="mt-1 font-sans text-xs uppercase tracking-widest text-muted-foreground">
            {project.category?.name}
          </p>
          <span className="mt-2 block font-sans text-sm text-primary underline transition-colors group-hover:text-accent">
            Read More
          </span>
        </div>
        <span className="ml-4 -translate-x-2 text-foreground opacity-0 transition-all duration-300 group-hover:translate-x-0 group-hover:opacity-100">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth={1.5} stroke="currentColor" className="w-5 h-5">
            <path strokeLinecap="round" strokeLinejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
          </svg>
        </span>
      </div>
    </Link>
    </motion.div>
  );
}
