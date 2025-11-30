<div>
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-4">{{ $project->title }}</h1>
        <div class="prose max-w-full mb-8">
            {!! $project->description !!}
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
            @foreach($project->images as $image)
                <div class="aspect-w-1 aspect-h-1">
                    <img src="{{ \Illuminate\Support\Facades\Storage::disk('r2')->url($image->image_path) }}"
                        alt="{{ $project->title }}" class="object-cover w-full h-full rounded-lg">
                </div>
            @endforeach
        </div>

        <div class="mt-8">
            <a href="{{ url('/') }}" class="text-blue-500 hover:underline">&larr; Back to all projects</a>
        </div>
    </div>
</div>