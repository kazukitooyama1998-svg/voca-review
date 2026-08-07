<?php

namespace Tests\Feature;

use App\Models\StudyLog;
use App\Models\Vocabulary;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class StudiedFlagTest extends TestCase
{
    use RefreshDatabase;

    protected function tearDown(): void
    {
        Carbon::setTestNow();

        parent::tearDown();
    }

    private function makeVocabulary(): Vocabulary
    {
        $vocabulary = Vocabulary::create([
            'word' => 'example',
            'parts_of_speech' => ['noun'],
        ]);
        $vocabulary->meanings()->create(['meaning' => '例']);

        return $vocabulary;
    }

    public function test_checking_studied_persists_after_the_day_changes(): void
    {
        Carbon::setTestNow('2026-08-07 10:00');
        $vocabulary = $this->makeVocabulary();

        $this->patch(route('vocabularies.toggle-studied', $vocabulary));

        $vocabulary->refresh();
        $this->assertTrue($vocabulary->is_studied);
        $this->assertSame(1, StudyLog::todayReviewCount());

        // 日付が変わっても「学習した」チェックは自動的に外れない（覚えたと同じ永続フラグ）。
        Carbon::setTestNow('2026-08-08 09:00');

        $vocabulary->refresh();
        $this->assertTrue($vocabulary->is_studied);
    }

    public function test_unchecking_on_the_same_day_undoes_the_review_count(): void
    {
        Carbon::setTestNow('2026-08-07 10:00');
        $vocabulary = $this->makeVocabulary();

        $this->patch(route('vocabularies.toggle-studied', $vocabulary));
        $this->patch(route('vocabularies.toggle-studied', $vocabulary));

        $vocabulary->refresh();
        $this->assertFalse($vocabulary->is_studied);
        $this->assertSame(0, StudyLog::todayReviewCount());
    }

    public function test_unchecking_on_a_later_day_does_not_undo_a_previous_days_count(): void
    {
        Carbon::setTestNow('2026-08-07 10:00');
        $vocabulary = $this->makeVocabulary();
        $this->patch(route('vocabularies.toggle-studied', $vocabulary));

        Carbon::setTestNow('2026-08-08 09:00');
        $this->patch(route('vocabularies.toggle-studied', $vocabulary));

        $vocabulary->refresh();
        $this->assertFalse($vocabulary->is_studied);
        // 前日分の復習数はそのまま残る。
        $this->assertSame(1, StudyLog::totalReviewCount());
        $this->assertSame(0, StudyLog::todayReviewCount());
    }

    public function test_checking_again_on_a_later_day_counts_as_a_new_review(): void
    {
        Carbon::setTestNow('2026-08-07 10:00');
        $vocabulary = $this->makeVocabulary();
        $this->patch(route('vocabularies.toggle-studied', $vocabulary));

        Carbon::setTestNow('2026-08-08 09:00');
        // 前日から既にチェック済みのものを一旦外して、再度チェックし直す。
        $this->patch(route('vocabularies.toggle-studied', $vocabulary));
        $this->patch(route('vocabularies.toggle-studied', $vocabulary));

        $vocabulary->refresh();
        $this->assertTrue($vocabulary->is_studied);
        $this->assertSame(1, StudyLog::todayReviewCount());
        $this->assertSame(2, StudyLog::totalReviewCount());
    }

    public function test_bulk_clear_only_undoes_todays_contribution(): void
    {
        Carbon::setTestNow('2026-08-07 10:00');
        $studiedYesterday = $this->makeVocabulary();
        $this->patch(route('vocabularies.toggle-studied', $studiedYesterday));

        Carbon::setTestNow('2026-08-08 09:00');
        $studiedToday = $this->makeVocabulary();
        $this->patch(route('vocabularies.toggle-studied', $studiedToday));

        $this->patch(route('vocabularies.clear-studied'));

        $this->assertFalse($studiedYesterday->refresh()->is_studied);
        $this->assertFalse($studiedToday->refresh()->is_studied);
        $this->assertSame(0, StudyLog::todayReviewCount());
        // 前日分の復習数は一括解除の対象外なので残る（今日の分だけ取り消される）。
        $this->assertSame(1, StudyLog::totalReviewCount());
    }
}
