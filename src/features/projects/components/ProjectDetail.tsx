"use client";

import { useState } from "react";
import Image from "next/image";
import { Link } from "@/i18n/navigation";
import { ArrowLeft } from "lucide-react";

interface ProjectDetailProps {
  project: any;
}

export function ProjectDetail({ project }: ProjectDetailProps) {
  const r2Domain = process.env.NEXT_PUBLIC_R2_PUBLIC_DOMAIN;

  const getImageUrl = (path: string | undefined) => {
    if (!path) return "/placeholder.jpg";
    if (path.startsWith('http')) return path;
    if (path.startsWith('/')) return r2Domain ? `https://${r2Domain}${path}` : path;
    return r2Domain ? `https://${r2Domain}/${path}` : `/${path}`;
  };

  const [activeImage, setActiveImage] = useState(getImageUrl(project.images?.[0]?.imagePath));

  return (
    <div className="bg-[#FDFCF8] min-h-screen pt-32 pb-20 px-6 md:px-12 selection:bg-stone-800 selection:text-white">
      <div className="max-w-7xl mx-auto">
        {/* Back Link */}
        <div className="mb-12">
          <Link
            href="/"
            className="inline-flex items-center gap-2 text-stone-500 hover:text-stone-900 transition-colors uppercase tracking-widest text-xs font-medium group"
          >
            <ArrowLeft className="w-4 h-4 transition-transform group-hover:-translate-x-1" />
            Back to Collection
          </Link>
        </div>

        <div className="grid lg:grid-cols-2 gap-16 lg:gap-24">
          {/* Content Column */}
          <div className="space-y-8">
            <div>
              <span className="text-stone-500 uppercase tracking-[0.2em] text-xs font-medium mb-4 block font-sans">
                {project.category?.name}
              </span>
              <h1 className="text-5xl md:text-6xl font-serif text-stone-900 leading-tight italic">
                {project.title}
              </h1>
              <div className="w-20 h-1.5 bg-stone-900 mt-8 rounded-full" />
            </div>

            <p className="text-lg text-stone-600 leading-relaxed font-light font-sans whitespace-pre-line first-letter:text-4xl first-letter:font-serif first-letter:mr-1 first-letter:float-left first-letter:text-stone-900">
              {project.description}
            </p>

            {/* Gallery Thumbnails */}
            <div className="pt-8 border-t border-stone-200">
              <h3 className="text-xs uppercase tracking-widest font-medium text-stone-400 mb-6">Detail Shots</h3>
              <div className="grid grid-cols-4 sm:grid-cols-6 gap-3">
                {project.images?.map((img: any, idx: number) => {
                  const src = getImageUrl(img.imagePath);
                  return (
                    <button
                      key={idx}
                      onClick={() => setActiveImage(src)}
                      className={`relative aspect-square rounded-md overflow-hidden border-2 transition-all duration-300 ${activeImage === src ? "border-stone-900 scale-95 shadow-inner" : "border-transparent grayscale hover:grayscale-0 opacity-60 hover:opacity-100"
                        }`}
                    >
                      <Image
                        src={src}
                        alt={`${project.title} detail ${idx + 1}`}
                        fill
                        unoptimized
                        className="object-cover"
                      />
                    </button>
                  );
                })}
              </div>
            </div>
          </div>

          {/* Featured Image Column */}
          <div className="relative">
            <div className="sticky top-32">
              <div className="relative aspect-[3/4] md:aspect-[4/5] bg-stone-100 rounded-lg overflow-hidden shadow-2xl border-8 border-white group">
                <Image
                  src={activeImage}
                  alt={project.title}
                  fill
                  unoptimized
                  className="object-cover transition-all duration-700"
                  priority
                />
                <div className="absolute inset-0 bg-stone-900/5 group-hover:bg-transparent transition-colors duration-500" />
              </div>

              {/* Decorative accent */}
              <div className="absolute -bottom-8 -left-8 w-32 h-32 border-l-2 border-b-2 border-stone-200 -z-10" />
            </div>
          </div>
        </div>
      </div>
    </div>
  );
}
