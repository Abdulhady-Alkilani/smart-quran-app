@props(['value'])

<label {{ $attributes->merge(['class' => 'block text-sm font-medium text-[#C9A84C] mb-2']) }}>
    {{ $value ?? $slot }}
</label>
