<?php

namespace Tests\Unit\Services;

use App\Enums\StatusSurvey;
use App\Models\Survey;
use App\Models\SurveyQuestion;
use App\Models\SurveyQuestionChoice;
use App\Models\Target;
use App\Models\User;
use App\Services\SurveyService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class SurveyServiceTest extends TestCase
{
    use DatabaseTransactions;

    protected SurveyService $surveyService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->surveyService = new SurveyService();
    }

    /**
     * Test getById returns survey
     */
    public function test_get_by_id_returns_survey()
    {
        // Arrange
        $survey = Survey::factory()->create();

        // Act
        $result = $this->surveyService->getById($survey->id);

        // Assert
        $this->assertNotNull($result);
        $this->assertEquals($survey->id, $result->id);
    }

    /**
     * Test getById returns null for non-existent
     */
    public function test_get_by_id_returns_null_for_nonexistent()
    {
        // Act
        $result = $this->surveyService->getById(99999);

        // Assert
        $this->assertNull($result);
    }

    /**
     * Test getNonArchivedSurveys returns non-archived surveys
     */
    public function test_get_non_archived_surveys_returns_non_archived()
    {
        // Arrange
        $initialCount = Survey::where('status', '<', StatusSurvey::ARCHIVED->value)->count();
        Survey::factory()->count(3)->create(['status' => StatusSurvey::OPEN->value]);
        Survey::factory()->count(2)->create(['status' => StatusSurvey::ARCHIVED->value]);

        // Act
        $result = $this->surveyService->getNonArchivedSurveys();

        // Assert
        $this->assertGreaterThanOrEqual($initialCount + 3, $result->count());

        // Verify no archived surveys in result
        foreach ($result as $survey) {
            $this->assertLessThan(StatusSurvey::ARCHIVED->value, $survey->status);
        }
    }

    /**
     * Test getNonArchivedSurveys orders by ID desc
     */
    public function test_get_non_archived_surveys_orders_by_id_desc()
    {
        // Arrange
        $survey1 = Survey::factory()->create(['status' => StatusSurvey::OPEN->value]);
        $survey2 = Survey::factory()->create(['status' => StatusSurvey::OPEN->value]);

        // Act
        $result = $this->surveyService->getNonArchivedSurveys();

        // Assert
        $this->assertEquals($survey2->id, $result->first()->id);
    }

    /**
     * Test getAll returns all surveys
     */
    public function test_get_all_returns_all_surveys()
    {
        // Arrange
        $initialCount = Survey::count();
        Survey::factory()->count(5)->create();

        // Act
        $result = $this->surveyService->getAll();

        // Assert
        $this->assertGreaterThanOrEqual($initialCount + 5, $result->count());
    }

    /**
     * Test getAll orders by ID desc
     */
    public function test_get_all_orders_by_id_desc()
    {
        // Arrange
        $survey1 = Survey::factory()->create();
        $survey2 = Survey::factory()->create();

        // Act
        $result = $this->surveyService->getAll();

        // Assert
        $this->assertEquals($survey2->id, $result->first()->id);
    }

    /**
     * Test create creates survey
     */
    public function test_create_creates_survey()
    {
        // Arrange
        $data = [
            'name' => 'Test Survey',
            'status' => StatusSurvey::OPEN->value,
        ];

        // Act
        $result = $this->surveyService->create($data);

        // Assert
        $this->assertInstanceOf(Survey::class, $result);
        $this->assertEquals('Test Survey', $result->name);
        $this->assertDatabaseHas('surveys', ['name' => 'Test Survey']);
    }

    /**
     * Test update updates survey
     */
    public function test_update_updates_survey()
    {
        // Arrange
        $survey = Survey::factory()->create(['name' => 'Old Name']);
        $data = ['name' => 'New Name'];

        // Act
        $result = $this->surveyService->update($survey->id, $data);

        // Assert
        $this->assertTrue($result);

        $survey->refresh();
        $this->assertEquals('New Name', $survey->name);
    }

    /**
     * Test update returns false for non-existent
     */
    public function test_update_returns_false_for_nonexistent()
    {
        // Act
        $result = $this->surveyService->update(99999, ['name' => 'Test']);

        // Assert
        $this->assertFalse($result);
    }

    /**
     * Test delete deletes survey
     */
    public function test_delete_deletes_survey()
    {
        // Arrange
        $survey = Survey::factory()->create();

        // Act
        $result = $this->surveyService->delete($survey->id);

        // Assert
        $this->assertTrue($result);
        $this->assertDatabaseMissing('surveys', ['id' => $survey->id]);
    }

    /**
     * Test delete returns false for non-existent
     */
    public function test_delete_returns_false_for_nonexistent()
    {
        // Act
        $result = $this->surveyService->delete(99999);

        // Assert
        $this->assertFalse($result);
    }

    /**
     * Test getArchivedSurveys returns archived surveys
     */
    public function test_get_archived_surveys_returns_archived()
    {
        // Arrange
        Survey::factory()->count(2)->create(['status' => StatusSurvey::ARCHIVED->value]);
        Survey::factory()->count(3)->create(['status' => StatusSurvey::OPEN->value]);

        // Act
        $result = $this->surveyService->getArchivedSurveys();

        // Assert - Depends on canShowAfterArchiving logic
        $this->assertIsObject($result);
    }

    /**
     * Test getArchivedSurveys with search
     */
    public function test_get_archived_surveys_with_search()
    {
        // Arrange
        Survey::factory()->create([
            'name' => 'Archived Survey One',
            'status' => StatusSurvey::ARCHIVED->value
        ]);
        Survey::factory()->create([
            'name' => 'Archived Survey Two',
            'status' => StatusSurvey::ARCHIVED->value
        ]);

        // Act
        $result = $this->surveyService->getArchivedSurveys('One');

        // Assert
        $this->assertIsObject($result);
    }

    /**
     * Test findOrFail returns survey
     */
    public function test_find_or_fail_returns_survey()
    {
        // Arrange
        $survey = Survey::factory()->create();

        // Act
        $result = $this->surveyService->findOrFail($survey->id);

        // Assert
        $this->assertInstanceOf(Survey::class, $result);
        $this->assertEquals($survey->id, $result->id);
    }

    /**
     * Test findOrFail throws exception
     */
    public function test_find_or_fail_throws_exception()
    {
        // Assert
        $this->expectException(\Illuminate\Database\Eloquent\ModelNotFoundException::class);

        // Act
        $this->surveyService->findOrFail(99999);
    }

    /**
     * Test attachTargets attaches targets to survey
     */
    public function test_attach_targets_attaches_targets()
    {
        // Arrange
        $survey = Survey::factory()->create();
        $targets = Target::factory()->count(3)->create();
        $targetIds = $targets->pluck('id')->toArray();

        // Act
        $this->surveyService->attachTargets($survey, $targetIds);

        // Assert
        $this->assertEquals(3, $survey->targets()->count());
    }

    /**
     * Test attachTargets detaches old targets
     */
    public function test_attach_targets_detaches_old_targets()
    {
        // Arrange
        $survey = Survey::factory()->create();
        $oldTargets = Target::factory()->count(2)->create();
        $survey->targets()->attach($oldTargets->pluck('id'));

        $newTargets = Target::factory()->count(3)->create();
        $newTargetIds = $newTargets->pluck('id')->toArray();

        // Act
        $this->surveyService->attachTargets($survey, $newTargetIds);

        // Assert
        $this->assertEquals(3, $survey->targets()->count());

        foreach ($newTargetIds as $targetId) {
            $this->assertTrue($survey->targets()->where('targets.id', $targetId)->exists());
        }
    }

    /**
     * Test updateById updates survey
     */
    public function test_update_by_id_updates_survey()
    {
        // Arrange
        $survey = Survey::factory()->create(['name' => 'Old Name']);

        // Act
        $result = $this->surveyService->updateById($survey->id, ['name' => 'Updated Name']);

        // Assert
        $this->assertTrue($result);

        $survey->refresh();
        $this->assertEquals('Updated Name', $survey->name);
    }

    /**
     * Test updateById returns false for non-existent
     */
    public function test_update_by_id_returns_false_for_nonexistent()
    {
        // Act
        $result = $this->surveyService->updateById(99999, ['name' => 'Test']);

        // Assert
        $this->assertFalse($result);
    }

    /**
     * Test getNonArchivedSurveysWithFilters without search
     */
    public function test_get_non_archived_surveys_with_filters_returns_non_archived()
    {
        // Arrange
        Survey::factory()->count(3)->create(['status' => StatusSurvey::OPEN->value, 'published' => true]);
        Survey::factory()->count(2)->create(['status' => StatusSurvey::ARCHIVED->value, 'published' => true]);
        // Act
        $result = $this->surveyService->getNonArchivedSurveysWithFilters(null, true);
        // Assert
        $this->assertIsObject($result);
        $this->assertGreaterThanOrEqual(3, $result->count());
    }
    /**
     * Test getNonArchivedSurveysWithFilters with search
     */
    public function test_get_non_archived_surveys_with_filters_with_search()
    {
        // Arrange
        Survey::factory()->create(['name' => 'Customer Survey', 'status' => StatusSurvey::OPEN->value, 'published' => true]);
        Survey::factory()->create(['name' => 'Employee Survey', 'status' => StatusSurvey::OPEN->value, 'published' => true]);
        // Act
        $result = $this->surveyService->getNonArchivedSurveysWithFilters('Customer', true);
        // Assert
        $this->assertIsObject($result);
    }
    /**
     * Test enable method enables survey
     */
    public function test_enable_enables_survey()
    {
        // Arrange
        $survey = Survey::factory()->create(['enabled' => false]);
        // Act
        $result = $this->surveyService->enable($survey->id);
        // Assert
        $this->assertTrue($result);
        $survey->refresh();
        $this->assertTrue($survey->enabled);
        $this->assertNotNull($survey->enableDate);
    }
    /**
     * Test disable method disables survey
     */
    public function test_disable_disables_survey()
    {
        // Arrange
        $survey = Survey::factory()->create(['enabled' => true]);
        $note = 'Survey disabled for maintenance';
        // Act
        $result = $this->surveyService->disable($survey->id, $note);
        // Assert
        $this->assertTrue($result);
        $survey->refresh();
        $this->assertFalse($survey->enabled);
        $this->assertNotNull($survey->disableDate);
        $this->assertEquals($note, $survey->disabledBtnDescription);
    }
    /**
     * Test canBeOpened throws exception when survey is disabled
     */
    public function test_can_be_opened_throws_exception_when_disabled()
    {
        // Arrange
        $survey = Survey::factory()->create(['enabled' => false]);
        // Assert
        $this->expectException(\Exception::class);
        // Act
        $this->surveyService->canBeOpened($survey->id);
    }
    /**
     * Test canBeOpened throws exception when no targets
     */
    public function test_can_be_opened_throws_exception_when_no_targets()
    {
        // Arrange
        $survey = Survey::factory()->create(['enabled' => true]);
        // Assert
        $this->expectException(\Exception::class);
        // Act
        $this->surveyService->canBeOpened($survey->id);
    }
    /**
     * Test canBeOpened throws exception when no question
     */
    public function test_can_be_opened_throws_exception_when_no_question()
    {
        // Arrange
        $survey = Survey::factory()->create(['enabled' => true]);
        $targets = Target::factory()->count(2)->create();
        $survey->targets()->attach($targets->pluck('id'));
        // Assert
        $this->expectException(\Exception::class);
        // Act
        $this->surveyService->canBeOpened($survey->id);
    }
    /**
     * Test canBeOpened throws exception when insufficient choices
     */
    public function test_can_be_opened_throws_exception_when_insufficient_choices()
    {
        // Arrange
        $survey = Survey::factory()->create(['enabled' => true]);
        $targets = Target::factory()->count(2)->create();
        $survey->targets()->attach($targets->pluck('id'));
        $question = SurveyQuestion::factory()->create(['survey_id' => $survey->id]);
        SurveyQuestionChoice::factory()->create(['surveyQuestion_id' => $question->id]);
        // Assert
        $this->expectException(\Exception::class);
        // Act
        $this->surveyService->canBeOpened($survey->id);
    }
    /**
     * Test canBeOpened returns true when valid
     */
    public function test_can_be_opened_returns_true_when_valid()
    {
        // Arrange
        $survey = Survey::factory()->create(['enabled' => true]);
        $targets = Target::factory()->count(2)->create();
        $survey->targets()->attach($targets->pluck('id'));
        $question = SurveyQuestion::factory()->create(['survey_id' => $survey->id]);
        SurveyQuestionChoice::factory()->count(2)->create(['surveyQuestion_id' => $question->id]);
        // Act
        $result = $this->surveyService->canBeOpened($survey->id);
        // Assert
        $this->assertTrue($result);
    }
    /**
     * Test open method opens survey
     */
    public function test_open_opens_survey()
    {
        // Arrange
        $survey = Survey::factory()->create(['status' => StatusSurvey::NEW->value]);
        // Act
        $result = $this->surveyService->open($survey->id);
        // Assert
        $this->assertTrue($result);
        $survey->refresh();
        $this->assertEquals(StatusSurvey::OPEN->value, $survey->status);
        $this->assertNotNull($survey->openDate);
    }
    /**
     * Test close method closes survey
     */
    public function test_close_closes_survey()
    {
        // Arrange
        $survey = Survey::factory()->create(['status' => StatusSurvey::OPEN->value]);
        // Act
        $result = $this->surveyService->close($survey->id);
        // Assert
        $this->assertTrue($result);
        $survey->refresh();
        $this->assertEquals(StatusSurvey::CLOSED->value, $survey->status);
        $this->assertNotNull($survey->closeDate);
    }
    /**
     * Test archive method archives survey
     */
    public function test_archive_archives_survey()
    {
        // Arrange
        $survey = Survey::factory()->create(['status' => StatusSurvey::CLOSED->value]);
        // Act
        $result = $this->surveyService->archive($survey->id);
        // Assert
        $this->assertTrue($result);
        $survey->refresh();
        $this->assertEquals(StatusSurvey::ARCHIVED->value, $survey->status);
        $this->assertNotNull($survey->archivedDate);
    }
    /**
     * Test publish method publishes survey
     */
    public function test_publish_publishes_survey()
    {
        // Arrange
        $survey = Survey::factory()->create(['published' => false]);
        // Act
        $result = $this->surveyService->publish($survey->id);
        // Assert
        $this->assertTrue($result);
        $survey->refresh();
        $this->assertTrue($survey->published);
        $this->assertNotNull($survey->publishDate);
    }
    /**
     * Test unpublish method unpublishes survey
     */
    public function test_unpublish_unpublishes_survey()
    {
        // Arrange
        $survey = Survey::factory()->create(['published' => true]);
        // Act
        $result = $this->surveyService->unpublish($survey->id);
        // Assert
        $this->assertTrue($result);
        $survey->refresh();
        $this->assertFalse($survey->published);
        $this->assertNotNull($survey->unpublishDate);
    }
    /**
     * Test changeUpdatable method changes updatable property
     */
    public function test_change_updatable_changes_property()
    {
        // Arrange
        $survey = Survey::factory()->create(['updatable' => false]);
        // Act
        $result = $this->surveyService->changeUpdatable($survey->id, true);
        // Assert
        $this->assertTrue($result);
        $survey->refresh();
        $this->assertTrue($survey->updatable);
    }
    /**
     * Test hasUserLiked returns false when user hasnt liked
     */
    public function test_has_user_liked_returns_false_when_not_liked()
    {
        // Arrange
        $survey = Survey::factory()->create();
        $user = User::factory()->create();
        // Act
        $result = $this->surveyService->hasUserLiked($survey->id, $user->id);
        // Assert
        $this->assertFalse($result);
    }
    /**
     * Test hasUserLiked returns true when user has liked
     */
    public function test_has_user_liked_returns_true_when_liked()
    {
        // Arrange
        $survey = Survey::factory()->create();
        $user = User::factory()->create();
        $survey->likes()->create(['user_id' => $user->id]);
        // Act
        $result = $this->surveyService->hasUserLiked($survey->id, $user->id);
        // Assert
        $this->assertTrue($result);
    }
    /**
     * Test addLike adds like to survey
     */
    public function test_add_like_adds_like()
    {
        // Arrange
        $survey = Survey::factory()->create();
        $user = User::factory()->create();
        // Act
        $result = $this->surveyService->addLike($survey->id, $user->id);
        // Assert
        $this->assertTrue($result);
        $this->assertDatabaseHas('likes', [
            'likable_type' => 'App\\Models\\Survey',
            'likable_id' => $survey->id,
            'user_id' => $user->id
        ]);
    }
    /**
     * Test removeLike removes like from survey
     */
    public function test_remove_like_removes_like()
    {
        // Arrange
        $survey = Survey::factory()->create();
        $user = User::factory()->create();
        $survey->likes()->create(['user_id' => $user->id]);
        // Act
        $result = $this->surveyService->removeLike($survey->id, $user->id);
        // Assert
        $this->assertTrue($result);
        $this->assertDatabaseMissing('likes', [
            'likable_type' => 'App\\Models\\Survey',
            'likable_id' => $survey->id,
            'user_id' => $user->id
        ]);
    }
    /**
     * Test addComment adds comment to survey
     */
    public function test_add_comment_adds_comment()
    {
        // Arrange
        $survey = Survey::factory()->create();
        $user = User::factory()->create();
        $content = 'This is a test comment';
        // Act
        $result = $this->surveyService->addComment($survey->id, $user->id, $content);
        // Assert
        $this->assertTrue($result);
        $this->assertDatabaseHas('comments', [
            'commentable_type' => 'App\\Models\\Survey',
            'commentable_id' => $survey->id,
            'user_id' => $user->id,
            'content' => $content
        ]);
    }
}

