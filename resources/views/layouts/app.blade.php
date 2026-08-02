<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'VocaReview') }} - 英語自己学習アプリ</title>

        @fonts

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="flex min-h-screen flex-col bg-amber-50 text-gray-800 antialiased">
        @include('partials.header')

        <main class="mx-auto w-full min-w-0 max-w-7xl flex-1 px-4 py-6 sm:px-6 lg:px-8">
            @yield('content')
        </main>

        @include('partials.footer')
    </body>
</html>
