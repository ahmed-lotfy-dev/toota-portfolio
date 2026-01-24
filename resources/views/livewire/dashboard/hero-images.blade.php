<div x-data="{
    uploadingPos: null,
    error: null,
    async uploadFile(event, position, label) {
        const file = event.target.files[0];
        if (!file) return;

        this.uploadingPos = position;
        this.error = null;

        const formData = new FormData();
        formData.append('image', file);
        formData.append('path', 'hero-images');
        formData.append('title', label);

        const token = document.querySelector('meta[name=\'csrf-token\']').getAttribute('content');

        try {
            const response = await fetch('{{ route('image.upload') }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json',
                },
                body: formData
            });

            if (!response.ok) throw new Error('Upload failed');

            const data = await response.json();
            
            // Call Livewire to save the path and dimensions
            await @this.call('saveImage', position, data.path, data.width, data.height);
            
            // Reset input
            event.target.value = '';
        } catch (e) {
            console.error(e);
            this.error = 'Failed to upload ' + file.name;
        } finally {
            this.uploadingPos = null;
        }
    }
}">
    <div class="p-6 md:p-10">
        <div class="mb-8 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-stone-900">Hero Section Images</h1>
                <p class="mt-2 text-sm text-stone-500">Manage the 4 floating images that appear in the hero section</p>
            </div>
        </div>

        @if (session()->has('message'))
            <div class="mb-6 rounded-lg border border-green-200 bg-green-50 p-4 text-green-800">
                {{ session('message') }}
            </div>
        @endif

        <div x-show="error" x-text="error" class="mb-6 rounded-lg border border-red-200 bg-red-50 p-4 text-red-800">
        </div>

        <div class="overflow-hidden rounded-xl border border-stone-200 bg-white shadow-sm">
            @php
                $positions = [
                    1 => ['label' => 'Mask Detail', 'desc' => 'Top left - Portrait'],
                    2 => ['label' => 'Workshop Tools', 'desc' => 'Bottom right - Landscape'],
                    3 => ['label' => 'Finished Prop', 'desc' => 'Top right - Square'],
                    4 => ['label' => 'Artisan Hands', 'desc' => 'Bottom left - Portrait'],
                ];
            @endphp

            <div class="divide-y divide-stone-100">
                @foreach ($positions as $position => $info)
                    <div class="flex flex-col items-center gap-6 p-6 transition hover:bg-stone-50/50 md:flex-row">
                        <!-- Preview / Thumbnail -->
                        <div class="relative shrink-0">
                            @if ($heroImages[$position])
                                <div
                                    class="h-20 w-20 overflow-hidden rounded-lg border border-stone-200 bg-stone-100 shadow-sm md:h-24 md:w-24">
                                    <img src="{{ $heroImages[$position]->image_url }}" alt="{{ $info['label'] }}"
                                        class="h-full w-full object-cover">
                                </div>
                                <div class="absolute -right-2 -top-2 rounded-full bg-green-100 p-1 text-green-600 shadow-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                                        class="h-4 w-4">
                                        <path fill-rule="evenodd"
                                            d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                            @else
                                <div
                                    class="flex h-20 w-20 items-center justify-center rounded-lg border-2 border-dashed border-stone-200 bg-stone-50 md:h-24 md:w-24">
                                    <span class="text-xs font-medium text-stone-400">Empty</span>
                                </div>
                            @endif
                        </div>

                        <!-- Info -->
                        <div class="flex-1 text-center md:text-left">
                            <h3 class="text-base font-bold text-stone-900">{{ $info['label'] }}</h3>
                            <p class="text-sm text-stone-500">{{ $info['desc'] }}</p>

                            @if($heroImages[$position] && $heroImages[$position]->width)
                                <div class="mt-2 flex items-center justify-center gap-2 md:justify-start">
                                    <span class="rounded bg-stone-100 px-2 py-0.5 text-[10px] font-medium text-stone-600">
                                        {{ $heroImages[$position]->width }} x {{ $heroImages[$position]->height }}
                                    </span>
                                </div>
                            @endif
                        </div>

                        <!-- Ratio Mode Selection -->
                        @if($heroImages[$position])
                            <div class="flex flex-col items-center gap-2">
                                <span class="text-[10px] font-bold uppercase tracking-widest text-stone-400">Aspect Ratio</span>
                                <div class="inline-flex rounded-lg border border-stone-200 bg-stone-50 p-1">
                                    <button wire:click="setRatioMode({{ $position }}, 'original')"
                                        class="rounded-md px-3 py-1 text-xs font-medium transition {{ $heroImages[$position]->ratio_mode === 'original' ? 'bg-white text-stone-900 shadow-sm' : 'text-stone-500 hover:text-stone-700' }}">
                                        Original
                                    </button>
                                    <button wire:click="setRatioMode({{ $position }}, 'preset')"
                                        class="rounded-md px-3 py-1 text-xs font-medium transition {{ $heroImages[$position]->ratio_mode === 'preset' ? 'bg-white text-stone-900 shadow-sm' : 'text-stone-500 hover:text-stone-700' }}">
                                        Preset
                                    </button>
                                </div>
                            </div>
                        @endif

                        <!-- Actions -->
                        <div class="flex w-full shrink-0 flex-col gap-3 md:w-auto md:flex-row md:items-center">
                            <div class="relative flex-1 md:w-64">
                                <input type="file" @change="uploadFile($event, {{ $position }}, '{{ $info['label'] }}')"
                                    accept="image/*" :disabled="uploadingPos !== null"
                                    class="peer w-full text-sm text-stone-500 file:mr-4 file:rounded-full file:border-0 file:bg-stone-100 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-stone-700 hover:file:bg-stone-200 disabled:opacity-50">

                                <div x-show="uploadingPos === {{ $position }}"
                                    class="mt-2 text-sm text-stone-600 flex items-center gap-2">
                                    <svg class="animate-spin h-4 w-4 text-stone-900" xmlns="http://www.w3.org/2000/svg"
                                        fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                            stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor"
                                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                        </path>
                                    </svg>
                                    Optimizing & Uploading...
                                </div>
                            </div>

                            @if ($heroImages[$position])
                                <div
                                    class="flex items-center justify-center border-t border-stone-100 pt-3 md:border-l md:border-t-0 md:pl-4 md:pt-0">
                                    <button wire:click="deleteImage({{ $position }})"
                                        wire:confirm="Are you sure you want to delete this image?"
                                        :disabled="uploadingPos !== null"
                                        class="group rounded-lg p-2 text-stone-400 hover:bg-red-50 hover:text-red-600 transition disabled:opacity-50"
                                        title="Delete Image">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="h-5 w-5">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                        </svg>
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="mt-8">
            <h3 class="mb-3 text-xs font-bold uppercase tracking-widest text-stone-500">Requirements</h3>
            <div class="flex flex-wrap gap-4 text-sm text-stone-600">
                <div class="flex items-center gap-2">
                    <span class="flex h-1.5 w-1.5 rounded-full bg-stone-400"></span>
                    Max 15MB file size
                </div>
                <div class="flex items-center gap-2">
                    <span class="flex h-1.5 w-1.5 rounded-full bg-stone-400"></span>
                    Auto-converted to WebP
                </div>
                <div class="flex items-center gap-2">
                    <span class="flex h-1.5 w-1.5 rounded-full bg-stone-400"></span>
                    Max width 2500px
                </div>
            </div>
        </div>
    </div>
</div>