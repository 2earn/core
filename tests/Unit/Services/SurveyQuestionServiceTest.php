<?php

namespace Tests\Unit\Services;

use App\Enums\Selection;
use App\Models\Survey;
use App\Models\SurveyQuestion;
use App\Services\Survey\SurveyQuestionService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
class SurveyQuestionServiceTest extends TestCase
{
    use DatabaseTransactions;
    protected SurveyQuestionService $surveyQuestionService;
    protected function setUp(): void
    {
        parent::setUp();
        $this->surveyQuestionService = new SurveyQuestionService();
    }
    /**
     * Test getById returns survey question
     */
    public function test_get_by_id_returns_survey_question()
    {
        // Arrange
        $surveyQuestion = SurveyQuestion::factory()->create();
        // Act
        $result = $this->surveyQuestionService->getById($surveyQuestion->id);
        // Assert
        $this->assertNotNull($result);
        $this->assertInstanceOf(SurveyQuestion::class, $result);
        $this->assertEquals($surveyQuestion->id, $result->id);
    }
    /**
     * Test getById returns null for non-existent
     */
    public function test_get_by_id_returns_null_for_nonexistent()
    {
        // Act
        $result = $this->surveyQuestionService->getById(99999);
        // Assert
        $this->assertNull($result);
    }
    /**
     * Test findOrFail returns survey question
     */
    public function test_find_or_fail_returns_survey_question()
    {
        // Arrange
        $surveyQuestion = SurveyQuestion::factory()->create();
        // Act
        $result = $this->surveyQuestionService->findOrFail($surveyQuestion->id);
        // Assert
        $this->assertInstanceOf(SurveyQuestion::class, $result);
        $this->assertEquals($surveyQuestion->id, $result->id);
    }
    /**
     * Test findOrFail throws exception for non-existent
     */
    public function test_find_or_fail_throws_exception_for_nonexistent()
    {
        // Assert
        $this->expectException(\Illuminate\Database\Eloquent\ModelNotFoundException::class);
        // Act
        $this->surveyQuestionService->findOrFail(99999);
    }
    /**
     * Test getBySurvey returns question
     */
    public function test_get_by_survey_returns_question()
    {
        // Arrange
        $survey = Survey::factory()->create();
        $surveyQuestion = SurveyQuestion::factory()->create(['survey_id' => $survey->id]);
        // Act
        $result = $this->surveyQuestionService->getBySurvey($survey->id);
        // Assert
        $this->assertNotNull($result);
        $this->assertInstanceOf(SurveyQuestion::class, $result);
        $this->assertEquals($surveyQuestion->id, $result->id);
        $this->assertEquals($survey->id, $result->survey_id);
    }
    /**
     * Test getBySurvey returns null when no question
     */
    public function test_get_by_survey_returns_null_when_no_question()
    {
        // Arrange
        $survey = Survey::factory()->create();
        // Act
        $result = $this->surveyQuestionService->getBySurvey($survey->id);
        // Assert
        $this->assertNull($result);
    }
    /**
     * Test create creates survey question
     */
    public function test_create_creates_survey_question()
    {
        // Arrange
        $survey = Survey::factory()->create();
        $data = [
            'survey_id' => $survey->id,
            'content' => 'What is your favorite color?',
            'selection' => Selection::UNIQUE->value,
            'maxResponse' => 1
        ];
        // Act
        $result = $this->surveyQuestionService->create($data);
        // Assert
        $this->assertInstanceOf(SurveyQuestion::class, $result);
        $this->assertEquals($survey->id, $result->survey_id);
        $this->assertEquals('What is your favorite color?', $result->content);
        $this->assertDatabaseHas('survey_questions', [
            'survey_id' => $survey->id,
            'content' => 'What is your favorite color?'
        ]);
    }
    /**
     * Test update updates survey question
     */
    public function test_update_updates_survey_question()
    {
        // Arrange
        $surveyQuestion = SurveyQuestion::factory()->create(['content' => 'Old Content']);
        $data = ['content' => 'New Content'];
        // Act
        $result = $this->surveyQuestionService->update($surveyQuestion->id, $data);
        // Assert
        $this->assertTrue($result);
        $surveyQuestion->refresh();
        $this->assertEquals('New Content', $surveyQuestion->content);
    }
    /**
     * Test update returns false for non-existent
     */
    public function test_update_returns_false_for_nonexistent()
    {
        // Act
        $result = $this->surveyQuestionService->update(99999, ['content' => 'Test']);
        // Assert
        $this->assertFalse($result);
    }
    /**
     * Test delete deletes survey question
     */
    public function test_delete_deletes_survey_question()
    {
        // Arrange
        $surveyQuestion = SurveyQuestion::factory()->create();
        // Act
        $result = $this->surveyQuestionService->delete($surveyQuestion->id);
        // Assert
        $this->assertTrue($result);
        $this->assertDatabaseMissing('survey_questions', ['id' => $surveyQuestion->id]);
    }
    /**
     * Test delete returns false for non-existent
     */
    public function test_delete_returns_false_for_nonexistent()
    {
        // Act
        $result = $this->surveyQuestionService->delete(99999);
        // Assert
        $this->assertFalse($result);
    }
}
