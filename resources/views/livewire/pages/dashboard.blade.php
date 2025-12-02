<div class="p-6 md:p-8">
    <h1 class="text-3xl md:text-4xl text-stone-900 mb-8" style="font-family: 'Playfair Display', serif;">
        Dashboard Overview
    </h1>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Projects Card -->
        <div
            class="bg-white rounded-lg border border-stone-200 p-6 transition-all hover:shadow-lg hover:border-stone-300">
            <div class="flex items-start justify-between">
                <div class="space-y-1">
                    <h2 class="text-base font-medium text-stone-500 uppercase tracking-wider">Projects</h2>
                    <p class="text-5xl text-stone-900" style="font-family: 'Playfair Display', serif;">
                        {{ $projectCount }}</p>
                </div>
                <div class="text-stone-300">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="1">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Categories Card -->
        <div
            class="bg-white rounded-lg border border-stone-200 p-6 transition-all hover:shadow-lg hover:border-stone-300">
            <div class="flex items-start justify-between">
                <div class="space-y-1">
                    <h2 class="text-base font-medium text-stone-500 uppercase tracking-wider">Categories</h2>
                    <p class="text-5xl text-stone-900" style="font-family: 'Playfair Display', serif;">
                        {{ $categoryCount }}</p>
                </div>
                <div class="text-stone-300">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="1">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Testimonials Card -->
        <div
            class="bg-white rounded-lg border border-stone-200 p-6 transition-all hover:shadow-lg hover:border-stone-300">
            <div class="flex items-start justify-between">
                <div class="space-y-1">
                    <h2 class="text-base font-medium text-stone-500 uppercase tracking-wider">Testimonials</h2>
                    <p class="text-5xl text-stone-900" style="font-family: 'Playfair Display', serif;">
                        {{ $testimonialCount }}</p>
                </div>
                <div class="text-stone-300">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="1">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                    </svg>
                </div>
            </div>
        </div>
    </div>
</div>