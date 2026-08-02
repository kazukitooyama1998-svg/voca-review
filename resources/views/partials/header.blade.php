{{-- 学習記録の集計値。StudyLog の実装後、Controller から渡すようにする --}}
@php
    $todayReviewCount = 35;
    $todayReviewGoal = 100;
    $streakDays = 12;
    $totalReviewCount = '1,245';
    $lastStudyDate = '2024/05/20';
@endphp

<header class="bg-blue-700">
    <div class="mx-auto flex w-full max-w-7xl flex-wrap items-center justify-between gap-x-6 gap-y-3 px-4 py-4 sm:px-6 lg:px-8">
        <a href="#" class="flex items-center gap-2 text-white">
            <span class="text-2xl" aria-hidden="true">📖</span>
            <span class="flex items-baseline gap-2">
                <span class="text-xl font-bold">VocaReview</span>
                <span class="text-xs text-blue-100">英語自己学習アプリ</span>
            </span>
        </a>

        <div class="flex flex-wrap items-center gap-x-6 gap-y-3">
            <x-stat-item label="今日の復習数" value="{{ $todayReviewCount }} / {{ $todayReviewGoal }}" icon="📅" />
            <x-stat-item label="連続学習日数" value="{{ $streakDays }}日" icon="🔥" />
            <x-stat-item label="総復習回数" value="{{ $totalReviewCount }}件" />
            <x-stat-item label="最終学習日" value="{{ $lastStudyDate }}" icon="📅" />
        </div>
    </div>
</header>
