<div class="p-6">
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Categories</h1>
            <p class="text-gray-600 dark:text-gray-400">Manage your project categories</p>
        </div>
        <x-ui.button wire:click="showModal">Add Category</x-ui.button>
    </div>

    @if (session()->has('message'))

        <div
            class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-md dark:bg-green-900/20 dark:border-green-800 dark:text-green-400">

            {{ session('message') }}

        </div>

    @endif

    @if (session()->has('error'))

        <div
            class="mb-4 p-4 bg-red-50 border border-red-200 text-red-700 rounded-md dark:bg-red-900/20 dark:border-red-800 dark:text-red-400">

            {{ session('error') }}

        </div>

    @endif



    <x-ui.modal name="category-modal" title="Add Category">
        <x-slot name="body">
            <livewire:dashboard.forms.category-form-modal-content />
        </x-slot>
    </x-ui.modal>





    <!-- Categories Table -->

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">

                <thead class="bg-gray-50 dark:bg-gray-900">

                    <tr>

                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">

                            Name</th>

                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">

                            Slug</th>

                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">

                            Projects</th>

                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">

                            Order</th>

                        <th
                            class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">

                            Actions</th>

                    </tr>

                </thead>

                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">

                    @forelse($categories as $category)

                        <tr>

                            <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-white">
                                <div class="truncate" title="{{ $category->name }}">
                                    {{ $category->name }}
                                </div>
                            </td>

                            <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                <div class="truncate" title="{{ $category->slug }}">
                                    {{ $category->slug }}
                                </div>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">

                                {{ $category->projects_count }}

                            </td>

                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">

                                {{ $category->order }}

                            </td>

                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">

                                <x-ui.button variant="link" wire:click="edit({{ $category->id }})" class="mr-3">
                                    Edit
                                </x-ui.button>

                                <x-ui.button variant="link"
                                    class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300"
                                    wire:click="delete({{ $category->id }})"
                                    onclick="return confirm('This action cannot be undone. Are you sure?')">
                                    Delete
                                </x-ui.button>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="5" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">

                                No categories found. Create your first category above.

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>