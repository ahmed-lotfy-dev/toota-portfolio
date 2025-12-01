<div x-data="{
    uploading: false,
    progress: 0,
    previews: [],
    error: null,
    init() {
        // Initialize previews from the wire model if it has values (e.g. when validation fails and re-renders)
        // Note: This is a basic initialization. For full sync, we might need to fetch URLs for paths.
        // But since these are temp uploads, we might not have easy public URLs unless we returned them.
        // For now, we assume previews are lost on full re-render unless we persist them differently.
        // However, the user flow is usually upload -> save.
    },
    removePreview(index) {
        if (!confirm('Are you sure you want to delete this image?')) {
            return;
        }

        // Call Livewire method to remove the image from the backend
        @this.call('removeImage', index);

        // Remove from local previews
        this.previews.splice(index, 1);
    },
    async uploadFiles(event) {
        this.uploading = true;
        this.error = null;
        const files = event.target.files;
        
        // Get CSRF token
        const token = document.querySelector('meta[name=\'csrf-token\']').getAttribute('content');

        for (let i = 0; i < files.length; i++) {
            const formData = new FormData();
            formData.append('image', files[i]);
            formData.append('path', 'projects'); 

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
                
                // Push path to Livewire model
                // We access the 'images' property directly via @this
                let currentImages = await @this.get('images') || [];
                currentImages.push(data.path);
                @this.set('images', currentImages);
                
                // Add to local previews
                this.previews.push(data.url);

            } catch (e) {
                console.error(e);
                this.error = 'Failed to upload ' + files[i].name;
            }
        }
        this.uploading = false;
        // Reset input
        event.target.value = '';
    }
}" x-on:reset-image-uploader.window="previews = []">
    <label class="block mb-1">Upload New Images</label>
    <input type="file" @change="uploadFiles" multiple accept="image/*"
        class="w-full p-2 rounded border dark:bg-gray-700 dark:text-white">
    
    <div x-show="uploading" class="text-sm text-blue-600 mt-1">Uploading...</div>
    <div x-show="error" x-text="error" class="text-red-600 text-sm mt-1"></div>
    
    {{-- Live preview for new images --}}
    <div class="mt-2 grid grid-cols-3 gap-4" x-show="previews.length > 0">
        <template x-for="(url, index) in previews" :key="index">
            <div class="relative group">
                <img :src="url" class="w-full h-32 object-cover rounded">
                <button type="button" @click="removePreview(index)"
                    class="absolute top-1 right-1 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-sm shadow-md hover:bg-red-600 transition-colors">
                    &times;
                </button>
            </div>
        </template>
    </div>
</div>
