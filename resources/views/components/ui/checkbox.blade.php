@props(['label', 'name'])

<div class="flex items-center">
  <input type="checkbox" id="{{ $name }}" {{ $attributes->merge(['class' => 'rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600']) }}>

  @if($label)
    <label for="{{ $name }}" class="ml-2 block text-sm text-gray-900 dark:text-gray-300">
      {{ $label }}
    </label>
  @endif

  @error($name)
    <div class="text-red-600 text-sm mt-1 ml-2">{{ $message }}</div>
  @enderror
</div>