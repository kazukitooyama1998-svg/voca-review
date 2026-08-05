@props(['meanings' => []])

@php
    // old('meanings') は生の文字列配列、Eloquentのコレクションは VocabularyMeaning
    // モデルの配列なので、どちらも表示用の単純な文字列配列に揃える。
    $rows = collect($meanings)->map(fn ($meaning) => is_object($meaning) ? $meaning->meaning : $meaning)->values();
    $rows = $rows->isNotEmpty() ? $rows : collect([null]);
@endphp

<div class="space-y-2" data-repeatable-fields data-next-index="{{ $rows->count() }}">
    <div class="space-y-2" data-repeatable-rows>
        @foreach ($rows as $index => $meaning)
            <x-meaning-row :index="$index" :meaning="$meaning" />
        @endforeach
    </div>

    {{-- テンプレート行。ブラウザは<template>の中身を描画せず、JSでのclone専用として扱う --}}
    <template data-repeatable-template>
        <x-meaning-row index="__INDEX__" />
    </template>

    <button
        type="button"
        class="rounded-lg border border-blue-300 px-3 py-1.5 text-xs font-medium text-blue-700 hover:bg-blue-50"
        data-add-row
    ><i class="fa-solid fa-plus fa-fw"></i> 意味を追加</button>
</div>
