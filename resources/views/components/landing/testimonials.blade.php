@if($testimonials->isNotEmpty())
<section class="py-12 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <h2 class="text-3xl font-extrabold text-gray-900 sm:text-4xl">
                {{ __('testimonials.title') }}
            </h2>
            <p class="mt-4 text-lg text-gray-600">
                {{ __('testimonials.subtitle') }}
            </p>
        </div>

        <div class="mt-10 grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-3">
            @foreach($testimonials as $testimonial)
            <div class="flex flex-col bg-white shadow-lg rounded-lg p-6">
                <p class="text-gray-700 text-base italic">"{{ $testimonial->body }}"</p>
                <div class="mt-6 flex items-center">
                    <div class="flex-shrink-0">
                        {{-- You might add an avatar here if you collect them --}}
                        <img class="h-10 w-10 rounded-full" src="https://via.placeholder.com/150" alt="Client Avatar">
                    </div>
                    <div class="ml-3">
                        <div class="text-base font-medium text-gray-900">{{ $testimonial->name }}</div>
                        <div class="text-sm font-medium text-indigo-600">{{ $testimonial->title }}</div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif
