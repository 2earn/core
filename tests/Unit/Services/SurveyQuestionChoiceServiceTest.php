<?php
namespace Tests\Unit\Services;
use App\Models\SurveyQuestion;
use App\Models\SurveyQuestionChoice;
use App\Services\SurveyQuestionChoiceService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
class SurveyQuestionChoiceServiceTest extends TestCase
{
    use DatabaseTransactions;
    protected SurveyQuestionChoiceService $surveyQuestionChoiceService;
    protected function setUp(): void
    {
        parent::setUp();
        $this->surveyQuestionChoiceService = new SurveyQuestionChoiceService();
    }
    /**
     * Test getById returns survey question choice
     */
    public function test_get_by_id_returns_survey_question_choice()
    {
        // Arrange
        $choice = SurveyQuestionChoice::factory()->create();
        // Act
        $result = $this->surveyQuestionChoiceService->getById($choice->id);
        // Assert
        $this->assertNotNull($result);
        $this->assertInstanceOf(SurveyQuestionChoice::class, $result);
        $this->assertEquals($choice->id, $result->id);
    }
    /**
     * Test getById returns null for non-existent
     */
    public function test_get_by_id_returns_null_for_nonexistent()
    {
        // Act
        $result = $this->surveyQuestionChoiceService->getById(99999);
        // Assert
        $this->assertNull($result);
    }
    /**
     * Test findOrFail returns survey question choice
     */
    public function test_find_or_fail_returns_survey_question_choice()
    {
        // Arrange
        $choice = SurveyQuestionChoice::factory()->create();
        // Act
        $result = $this->surveyQuestionChoiceService->findOrFail($choice->id);
        // Assert
        $this->assertInstanceOf(SurveyQuestionChoice::class, $result);
        $this->assertEquals($choice->id, $result->id);
    }
    /**
     * Test findOrFail throws exception for non-existent
     */
    public function test_find_or_fail_throws_exception_for_nonexistent()
    {
        // Assert
        $this->expectException(\Illuminate\Database\Eloquent\ModelNotFoundException::class);
        // Act
        $this->surveyQuestionChoiceService->findOrFail(99999);
    }
    /**
     * Test getByQuestion returns choices
     */
    public function test_get_by_question_returns_choices()
    {
        // Arrange
        $question = SurveyQuestion::factory()->create();
        SurveyQuestionChoice::factory()->count(5)->create(['question_id' => $question->id]);
        SurveyQuestionChoice::factory()->count(3)->create(); // Other choices
        // Act
        $result = $this->surveyQuestionChoiceService->getByQuestion($question->id);
        // Assert
        $this->assertCount(5, $result);
        $this->assertTrue($result->every(fn($choice) => $choice->question_id === $question->id));
    }
    /**
     * Test getByQuestion returns empty collection when no choices
     */
    public function test_get_by_question_returns_empty_when_no_choices()
    {
        // Arrange
        $question = SurveyQuestion::factory()->create();
        // Act
        $result = $this->surveyQuestionChoiceService->getByQuestion($question->id);
        // Assert
        $this->assertCount(0, $result);
    }
    /**
     * Test create creates survey question choice
     */
    public function test_create_creates_survey_question_choice()
    {
        // Arrange
        $question = SurveyQuestion::factory()->create();
        $data = [
            'question_id' => $question->id,
            'title' => 'Option Title A',
            'order' => 1
        ];
        // Act
        $result = $this->surveyQuestionChoiceService->create($data);
        // Assert
        $this->assertInstanceOf(SurveyQuestionChoice::class, $result);
        $this->assertEquals($question->id, $result->question_id);
        $this->assertEquals('Option Title A', $result->title);
        $this->assertDatabaseHas('survey_question_choices', [
            'question_id' => $question->id,
            'title' => 'Option Title A'
        ]);
    }
    /**
     * Test update updates survey question choice
     */
    public function test_update_updates_survey_question_choice()
    {
        // Arrange
        $choice = SurveyQuestionChoice::factory()->create(['title' => 'Old Title']);
        $data = ['title' => 'New Title'];
        // Act
        $result = $this->surveyQuestionChoiceService->update($choice->id, $data);
        // Assert
        $this->assertTrue($result);
        $choice->refresh();
        $this->assertEquals('New Title', $choice->title);
    }
    /**
     * Test update returns false for non-existent
     */
    public function test_update_returns_false_for_nonexistent()
    {
        // Act
        $result = $this->surveyQuestionChoiceService->update(99999, ['title' => 'Test']);
        // Assert
        $this->assertFalse($result);
    }
    /**
     * Test updateById updates survey question choice
     */
    public function test_update_by_id_updates_survey_question_choice()
    {
        // Arrange
        $choice = SurveyQuestionChoice::factory()->create(['title' => 'Old Title']);
        // Act
        $result = $this->surveyQuestionChoiceService->updateById($choice->id, ['title' => 'Updated Title']);
        // Assert
        $this->assertTrue($result);
        $choice->refresh();
        $this->assertEquals('Updated Title', $choice->title);
    }
    /**
     * Test updateById returns false for non-existent
     */
    public function test_update_by_id_returns_false_for_nonexistent()
    {
        // Act
        $result = $this->surveyQuestionChoiceService->updateById(99999, ['title' => 'Test']);
        // Assert
        $this->assertFalse($result);
    }
    /**
     * Test delete deletes survey question choice
     */
    public function test_delete_deletes_survey_question_choice()
    {
        // Arrange
        $choice = SurveyQuestionChoice::factory()->create();
        // Act
        $result = $this->surveyQuestionChoiceService->delete($choice->id);
        // Assert
        $this->assertTrue($result);
        $this->assertDatabaseMissing('survey_question_choices', ['id' => $choice->id]);
    }
    /**
     * Test delete returns false for non-existent
     */
    public function test_delete_returns_false_for_nonexistent()
    {
        // Act
        $result = $this->surveyQuestionChoiceService->delete(99999);
        // Assert
        $this->assertFalse($result);
    }
    /**
     * Test countByQuestion counts choices
     */
    public function test_count_by_question_counts_choices()
    {
        // Arrange
        $question = SurveyQuestion::factory()->create();
        SurveyQuestionChoice::factory()->count(7)->create(['question_id' => $question->id]);
        SurveyQuestionChoice::factory()->count(3)->create(); // Other choices
        // Act
        $result = $this->surveyQuestionChoiceService->countByQuestion($question->id);
        // Assert
        $this->assertEquals(7, $result);
    }
    /**
     * Test countByQuestion returns zero when no choices
     */
    public function test_count_by_question_returns_zero_when_no_choices()
    {
        // Arrange
        $question = SurveyQuestion::factory()->create();
        // Act
        $result = $this->surveyQuestionChoiceService->countByQuestion($question->id);
        // Assert
        $this->assertEquals(0, $result);
    }
}


