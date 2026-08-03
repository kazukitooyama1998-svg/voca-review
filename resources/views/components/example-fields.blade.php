@props(['examples' => []])

@php
    // old('examples') returns plain arrays (not model instances) after a validation
    // error, so normalize each row to an object for consistent property access below.
    $rows = collect($examples)->map(fn ($example) => is_array($example) ? (object) $example : $example)->values();
    $rows = $rows->isNotEmpty() ? $rows : collect([null]);
@endphp

<div class="space-y-2" data-example-fields data-next-index="{{ $rows->count() }}">
    <div class="space-y-2" data-example-rows>
        @foreach ($rows as $index => $example)
            <x-example-row :index="$index" :example="$example" />
        @endforeach
    </div>

    {{-- テンプレート行。ブラウザは<template>の中身を描画せず、JSでのclone専用として扱う --}}
    <template data-example-template>
        <x-example-row index="__INDEX__" />
    </template>

    <button
        type="button"
        class="rounded-lg border border-blue-300 px-3 py-1.5 text-xs font-medium text-blue-700 hover:bg-blue-50"
        data-add-example
    ><i class="fa-solid fa-plus fa-fw"></i> 例文を追加</button>
</div>
