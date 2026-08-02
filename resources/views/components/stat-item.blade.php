@props(['icon' => null, 'label', 'value'])

<div class="flex items-center gap-2">
    <div>
        <p class="text-xs text-blue-100">{{ $label }}</p>
        <p class="text-lg font-bold text-white">{{ $value }}</p>
    </div>
    @if ($icon)
        <span class="text-xl text-blue-200" aria-hidden="true">{{ $icon }}</span>
    @endif
</div>
