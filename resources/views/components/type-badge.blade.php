@props(['type'])

@php
    $isVocabulary = $type === 'vocabulary';
    $classes = $isVocabulary ? 'bg-blue-100 text-blue-700' : 'bg-green-100 text-green-700';
    $label = $isVocabulary ? '単語' : '文法';
@endphp

<span {{ $attributes->merge(['class' => "inline-block rounded-full px-3 py-1 text-xs font-semibold whitespace-nowrap $classes"]) }}>
    {{ $label }}
</span>
