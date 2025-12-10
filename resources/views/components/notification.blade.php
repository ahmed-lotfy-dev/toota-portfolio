@props([
    'status' => 'success',
    'message' => '',
])

<div x-data="{
    show: false,
    status: '{{ $status }}',
    message: '{{ $message }}',
    timeout: null,
}"
    x-init="
        window.addEventListener('show-notification', event => {
            show = true;
            status = event.detail.status;
            message = event.detail.message;
            clearTimeout(timeout);
            timeout = setTimeout(() => {
                show = false;
            }, 5000);
        });
    "
    x-show="show"
    x-transition:enter="transform ease-out duration-300 transition"
    x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
    x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
    x-transition:leave="transition ease-in duration-100"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    :class="{
        'bg-green-500': status === 'success',
        'bg-red-500': status === 'danger',
        'bg-yellow-500': status === 'warning',
        'bg-blue-500': status === 'info',
    }"
    class="fixed bottom-0 right-0 m-8 p-4 rounded-lg text-white shadow-lg"
    style="display: none;"
>
    <div class="flex items-center">
        <div class="mr-2">
            <!-- Icons can be added here based on status -->
        </div>
        <div x-text="message"></div>
        <button @click="show = false" class="ml-4">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    </div>
</div>
