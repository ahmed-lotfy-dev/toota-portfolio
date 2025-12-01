<div x-init="window.scrollTo(0, 0)">
    <div class="container mx-auto px-4 py-6">
        <div class="my-4">
            <a href="{{ url('/') }}" class="text-blue-500 hover:underline">&larr; {{ __('projects.back_to_all') }}</a>
        </div>
        <h1 class="text-3xl font-bold mb-4 uppercase">{{ $project->title }}</h1>
        <div class="mb-8 text-gray-800 dark:text-gray-200 whitespace-pre-wrap first-letter:uppercase">
            {{ $project->description }}
        </div>

        <div class="grid gap-4" x-data="{ activeImage: '{{ $project->main_image ? $project->main_image->url : '' }}' }">
            {{-- Grid Images (Thumbnails) --}}
            <div class="grid grid-cols-4 md:grid-cols-8 gap-2">
                @foreach($project->images as $image)
                    <div>
                        <button @click="activeImage = '{{ $image->url }}'"
                            class="group block w-full focus:outline-none focus:ring-2 focus:ring-blue-500 rounded-lg overflow-hidden">
                            <img class="h- w-full rounded-lg object-cover aspect-square hover:opacity-75 transition-opacity"
                                src="{{ $image->url }}" alt="{{ $project->title }}">
                        </button>
                    </div>
                @endforeach
            </div>

            {{-- Featured Image --}}
            @if($project->main_image)
                <div>
                    <img class="max-h-[650px] w-full object-contain rounded-lg transition-all duration-300 bg-stone-100"
                        :src="activeImage" alt="{{ $project->title }}">
                </div>
            @endif
        </div>
    </div>
</div>