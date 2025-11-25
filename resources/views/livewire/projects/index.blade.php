<section id="gallery" class="w-full min-h-screen bg-[#FDFCF8] py-24 px-6 md:px-12 relative">
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-px h-24 bg-linear-to-b from-transparent to-stone-300">
    </div>
    <div class="max-w-7xl mx-auto mb-16 flex flex-col md:flex-row justify-between items-end gap-8">
        <div>
            <span class="text-stone-500 uppercase tracking-[0.2em] text-xs font-medium mb-2 block">Portfolio</span>
            <h2 class="text-4xl md:text-5xl text-stone-900 leading-tight"
                style="font-family: 'Playfair Display', serif;">
                Projects
            </h2>
        </div>
        <nav class="flex flex-wrap gap-6 text-sm font-medium tracking-wide">
            <button wire:click="filter('all')"
                class="pb-1 transition-all {{ $activeCategory === 'all' ? 'text-stone-900 border-b border-stone-900' : 'text-stone-400 hover:text-stone-900 hover:border-stone-300 border-b border-transparent' }}">All</button>

            @foreach ($categories as $category)
                <button wire:click="filter('{{ $category['slug'] }}')"
                    class="pb-1 transition-all {{ $activeCategory === $category['slug'] ? 'text-stone-900 border-b border-stone-900' : 'text-stone-400 hover:text-stone-900 hover:border-stone-300 border-b border-transparent' }}">
                    {{ $category['name'] }}
                </button>
            @endforeach
        </nav>
    </div>

    <div class="max-w-7xl mx-auto columns-1 md:columns-2 lg:columns-3 gap-8 space-y-8">
        @foreach ($this->filteredProjects as $project)
            <?php    $image = collect($project['images'])->firstWhere('is_primary', true); ?>
            <a href="#" class="group block break-inside-avoid mb-8">
                <div class="relative overflow-hidden bg-stone-200">
                    <div class="overflow-hidden">
                        <img src="{{ $image['image_path'] }}" alt="{{ $project['title'] }}"
                            class="w-full h-auto object-cover transform transition-transform duration-700 group-hover:scale-105 grayscale-10 group-hover:grayscale-0">
                    </div>
                    <div class="absolute inset-0 bg-stone-900/0 group-hover:bg-stone-900/10 transition-colors duration-500">
                    </div>
                </div>


                <div class="mt-4 flex justify-between items-start">
                    <div>
                        <h3 class="text-xl text-stone-800 italic group-hover:text-black transition-colors"
                            style="font-family: 'Playfair Display', serif;">
                            {{ $project['title'] }}
                        </h3>
                        <p class="text-stone-500 text-xs uppercase tracking-widest mt-1">
                            {{ collect($categories)->firstWhere('id', $project['category_id'])['name'] }}
                        </p>
                    </div>
                    <span
                        class="opacity-0 -translate-x-2 group-hover:opacity-100 group-hover:translate-x-0 transition-all duration-300 text-stone-800">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                        </svg>
                    </span>
                </div>
            </a>
        @endforeach
    </div>


    <div class="mt-16 text-center">
        <a href="#"
            class="inline-block border border-stone-300 px-8 py-3 text-sm text-stone-600 uppercase tracking-widest hover:bg-stone-900 hover:text-white hover:border-stone-900 transition-all duration-300">
            View All Archives
        </a>
    </div>
</section>