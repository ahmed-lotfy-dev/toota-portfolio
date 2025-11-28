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

            @foreach ($this->categories as $category)
                <button wire:click="filter('{{ $category->slug }}')"
                    class="pb-1 transition-all {{ $activeCategory === $category->slug ? 'text-stone-900 border-b border-stone-900' : 'text-stone-400 hover:text-stone-900 hover:border-stone-300 border-b border-transparent' }}">
                    {{ $category->name }}
                </button>
            @endforeach
        </nav>
    </div>

    @if($this->filteredProjects->isEmpty())
        <div class="max-w-7xl mx-auto py-24 text-center" data-aos="fade-up">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-stone-100 mb-6">
                <svg class="w-8 h-8 text-stone-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                </svg>
            </div>
            <h3 class="text-xl font-medium text-stone-900 mb-2" style="font-family: 'Playfair Display', serif;">
                {{ __('No Projects Yet') }}
            </h3>
            <p class="text-stone-500 max-w-md mx-auto">
                {{ __('We are currently curating our portfolio. Please check back soon for updates.') }}
            </p>
        </div>
    @else
        <div class="max-w-7xl mx-auto columns-1 md:columns-2 lg:columns-3 gap-8 space-y-8">
            @foreach ($this->filteredProjects as $project)
                <a href="#" class="group block break-inside-avoid mb-8">
                    <div class="relative overflow-hidden bg-stone-200">
                        <div class="overflow-hidden">
                            @if($project->main_image)
                                <img src="{{ $project->main_image->image_path }}" alt="{{ $project->title }}"
                                    class="w-full h-auto object-cover transform transition-transform duration-700 group-hover:scale-105 grayscale-10 group-hover:grayscale-0">
                            @else
                                <div class="w-full h-64 bg-stone-200 flex items-center justify-center text-stone-400">
                                    No Image
                                </div>
                            @endif
                        </div>
                        <div class="absolute inset-0 bg-stone-900/0 group-hover:bg-stone-900/10 transition-colors duration-500">
                        </div>
                    </div>


                    <div class="mt-4 flex justify-between items-start">
                        <div>
                            <h3 class="text-xl text-stone-800 italic group-hover:text-black transition-colors"
                                style="font-family: 'Playfair Display', serif;">
                                {{ $project->title }}
                            </h3>
                            <p class="text-stone-500 text-xs uppercase tracking-widest mt-1">
                                {{ $project->category->name }}
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
    @endif


    <div class="mt-16 text-center">
        <a href="#"
            class="inline-block border border-stone-300 px-8 py-3 text-sm text-stone-600 uppercase tracking-widest hover:bg-stone-900 hover:text-white hover:border-stone-900 transition-all duration-300">
            View All Archives
        </a>
    </div>
</section>