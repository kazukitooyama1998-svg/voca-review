@extends('layouts.app')

@section('content')
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-[1fr_320px]">
        <div class="space-y-6">
            @include('partials.toolbar')
            @include('partials.registration-form')
            @include('partials.entries-table')
        </div>

        <div>
            @include('partials.filter-sidebar')
        </div>
    </div>
@endsection
