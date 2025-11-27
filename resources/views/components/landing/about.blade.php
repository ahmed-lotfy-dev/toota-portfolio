{{-- resources/views/components/about-artist.blade.php --}}
<section id="about"
    class="relative py-24 px-6 md:px-12 overflow-hidden bg-[#FDFCF8] transition-colors duration-500">
    
    {{-- Decorative background element (Subtle, non-distracting) --}}
    <div class="absolute inset-0 -z-10 opacity-5">
        <div class="absolute top-1/2 left-1/2 w-48 h-48 bg-stone-300 rounded-full blur-3xl transform -translate-x-1/2 -translate-y-1/2"></div>
    </div>

    <div class="max-w-7xl mx-auto">
        <div class="grid lg:grid-cols-2 gap-12 lg:gap-24 items-center">
            
            {{-- 1. Image Column: Framed Art Look --}}
            <div class="relative group" data-aos="fade-right">
                
                {{-- Decorative Back Layer (Contrast Block) --}}
                <div class="absolute inset-0 md:-inset-4 bg-stone-200 rounded-lg transform translate-x-2 translate-y-2 opacity-50 transition duration-500 group-hover:translate-x-3 group-hover:translate-y-3">
                </div>

                <div class="relative border-4 border-stone-900 rounded-lg shadow-xl bg-white/50 backdrop-blur-sm">
                    <div class="aspect-3/4 overflow-hidden rounded-md">
                        <img src="{{ $image ?? asset('images/Artist-Image.png') }}"
                            alt="{{ __('about.artist_image_alt') }}"
                            class="w-full h-full object-cover transform transition duration-700 group-hover:scale-[1.03] grayscale-10 group-hover:grayscale-0">
                    </div>
                    
                    {{-- Floating Museum Tag/Badge (High Contrast) --}}
                    <div
                        class="absolute -bottom-6 -right-6 bg-stone-900 rounded-md border-4 border-[#FDFCF8] shadow-2xl p-4 transform group-hover:-translate-y-0.5 transition duration-300">
                        <div class="flex items-center gap-3">
                            {{-- Icon: Simple Tool (Chisel/Brush) --}}
                            <div
                                class="w-10 h-10 bg-stone-700 rounded-md flex items-center justify-center">
                                <svg class="w-5 h-5 text-[#FDFCF8]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-7.5 3h6m2.25 0h-6M12 18v-5.25" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-[#FDFCF8]">{{ __('about.experience_years') }}</p>
                                <p class="text-xs text-stone-300">{{ __('about.experience_label') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 2. Content Section --}}
            <div class="space-y-8" data-aos="fade-left">
                
                {{-- Section Label: Subtle Stone Colors --}}
                <div class="inline-flex items-center gap-3 px-4 py-2 bg-stone-100 rounded-full">
                    <span class="w-2 h-2 bg-stone-600 rounded-full"></span>
                    <span class="text-xs uppercase tracking-widest font-medium text-stone-900">{{ __('about.label') }}</span>
                </div>

                {{-- Heading: Serif Font --}}
                <div class="space-y-4">
                    <h2 class="text-4xl lg:text-5xl font-bold text-stone-900 leading-tight" style="font-family: 'Playfair Display', serif;">
                        {{ __('about.heading') }}
                    </h2>
                    {{-- Solid Line Divider --}}
                    <div class="w-16 h-1 bg-stone-900 rounded-full"></div>
                </div>

                {{-- Description --}}
                <p class="text-lg text-stone-600 leading-relaxed font-light">
                    {{ __('about.description') }}
                </p>

                {{-- Stats Grid: Clean & Minimal --}}
                <div class="grid grid-cols-3 gap-6 pt-6">
                    <div class="text-center p-4 rounded-lg bg-white/80 border border-stone-100 shadow-sm transition duration-300 hover:shadow-md">
                        <div class="text-3xl font-bold text-stone-900" style="font-family: 'Playfair Display', serif;">
                            {{ __('about.stats.projects') }}
                        </div>
                        <div class="text-sm text-stone-500 mt-1">{{ __('about.stats.projects_label') }}</div>
                    </div>
                    <div class="text-center p-4 rounded-lg bg-white/80 border border-stone-100 shadow-sm transition duration-300 hover:shadow-md">
                        <div class="text-3xl font-bold text-stone-900" style="font-family: 'Playfair Display', serif;">
                            {{ __('about.stats.exhibitions') }}
                        </div>
                        <div class="text-sm text-stone-500 mt-1">{{ __('about.stats.exhibitions_label') }}</div>
                    </div>
                    <div class="text-center p-4 rounded-lg bg-white/80 border border-stone-100 shadow-sm transition duration-300 hover:shadow-md">
                        <div class="text-3xl font-bold text-stone-900" style="font-family: 'Playfair Display', serif;">
                            {{ __('about.stats.clients') }}
                        </div>
                        <div class="text-sm text-stone-500 mt-1">{{ __('about.stats.clients_label') }}</div>
                    </div>
                </div>

                {{-- Highlights: Monochrome Checkmarks --}}
                <div class="space-y-4 pt-4">
                    @foreach(__('about.highlights') as $highlight)
                        <div class="flex items-start gap-4 group">
                            <div
                                class="shrink-0 w-8 h-8 bg-stone-200 rounded-full flex items-center justify-center transition duration-300">
                                <svg class="w-4 h-4 text-stone-900" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"></path>
                                </svg>
                            </div>
                            <p class="text-stone-700 pt-1">{{ $highlight }}</p>
                        </div>
                    @endforeach
                </div>

                {{-- CTA Button: Solid Stone Block --}}
                <div class="pt-4">
                    <a href="/"
                        class="inline-flex items-center gap-2 px-8 py-4 bg-stone-900 text-white uppercase tracking-widest text-sm shadow-xl hover:shadow-2xl hover:bg-stone-700 transform hover:-translate-y-0.5 transition duration-300">
                        <span>{{ __('about.cta_button') }}</span>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M17.25 8.25L21 12m0 0l-3.75 3.75M21 12H3"></path>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>