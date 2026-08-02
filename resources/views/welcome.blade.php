@extends('layouts.app')

@php
    // タブ（単語・フレーズ／文法）はURLで分かれているため、現在のルート名からどちらを表示中か判定する。
    $activeTab = request()->routeIs('grammars.index') ? 'grammar' : 'vocabulary';
    $entries = $activeTab === 'grammar' ? $grammars : $vocabularies;
@endphp

@section('content')
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-[1fr_320px]">
        {{-- min-w-0: grid item はデフォルトで中身（幅固定のテーブル）より縮まないため、
             指定しないとページ全体が横にはみ出してしまう --}}
        <div class="min-w-0 space-y-6">
            @include('partials.toolbar', ['activeTab' => $activeTab])
            @include('partials.registration-form', ['activeTab' => $activeTab])
            @include('partials.entries-table', ['activeTab' => $activeTab, 'entries' => $entries])
        </div>

        <div class="min-w-0">
            @include('partials.filter-sidebar', ['activeTab' => $activeTab])
        </div>
    </div>
@endsection
