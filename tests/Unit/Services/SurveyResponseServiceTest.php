<?php
namespace Tests\Unit\Services;
use App\Models\Survey;
use App\Models\SurveyResponse;
use App\Models\User;
use App\Services\Survey\SurveyResponseService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
class SurveyResponseServiceTest extends TestCase
{
    use DatabaseTransactions;
    protected SurveyResponseService $surveyResponseService;
    protected function setUp(): void
    {
        parent::setUp();
        $this->surveyResponseService = new SurveyResponseService();
    }
    /**
     * Test getById returns survey response
     */
    public function test_get_by_id_returns_survey_response()
    {
        // Arrange
        $surveyResponse = SurveyResponse::factory()->create();
        // Act
        $result = $this->surveyResponseService->getById($surveyResponse->id);
        // Assert
        $this->assertNotNull($result);
        $this->assertInstanceOf(SurveyResponse::class, $result);
        $this->assertEquals($surveyResponse->id, $result->id);
    }
    /**
     * Test getById returns null for non-existent
     */
    public function test_get_by_id_returns_null_for_nonexistent()
    {
        // Act
        $result = $this->surveyResponseService->getById(99999);
        // Assert
        $this->assertNull($result);
    }
    /**
     * Test getByUserAndSurvey returns response
     */
    public function test_get_by_user_and_survey_returns_response()
    {
        // Arrange
        $user = User::factory()->create();
        $survey = Survey::factory()->create();
        $surveyResponse = SurveyResponse::factory()->create([
            'user_id' => $user->id,
            'survey_id' => $survey->id
        ]);
        // Act
        $result = $this->surveyResponseService->getByUserAndSurvey($user->id, $survey->id);
        // Assert
        $this->assertNotNull($result);
        $this->assertInstanceOf(SurveyResponse::class, $result);
        $this->assertEquals($surveyResponse->id, $result->id);
        $this->assertEquals($user->id, $result->user_id);
        $this->assertEquals($survey->id, $result->survey_id);
    }
    /**
     * Test getByUserAndSurvey returns null when not found
     */
    public function test_get_by_user_and_survey_returns_null_when_not_found()
    {
        // Arrange
        $user = User::factory()->create();
        $survey = Survey::factory()->create();
        // Act
        $result = $this->surveyResponseService->getByUserAndSurvey($user->id, $survey->id);
        // Assert
        $this->assertNull($result);
    }
    /**
     * Test isParticipated returns true when user participated
     */
    public function test_is_participated_returns_true_when_participated()
    {
        // Arrange
        $user = User::factory()->create();
        $survey = Survey::factory()->create();
        SurveyResponse::factory()->create([
            'user_id' => $user->id,
            'survey_id' => $survey->id
        ]);
        // Act
        $result = $this->surveyResponseService->isParticipated($user->id, $survey->id);
        // Assert
        $this->assertTrue($result);
    }
    /**
     * Test isParticipated returns false when user not participated
     */
    public function test_is_participated_returns_false_when_not_participated()
    {
        // Arrange
        $user = User::factory()->create();
        $survey = Survey::factory()->create();
        // Act
        $result = $this->surveyResponseService->isParticipated($user->id, $survey->id);
        // Assert
        $this->assertFalse($result);
    }
    /**
     * Test create creates survey response
     */
    public function test_create_creates_survey_response()
    {
        // Arrange
        $user = User::factory()->create();
        $survey = Survey::factory()->create();
        $data = [
            'user_id' => $user->id,
            'survey_id' => $survey->id
        ];
        // Act
        $result = $this->surveyResponseService->create($data);
        // Assert
        $this->assertInstanceOf(SurveyResponse::class, $result);
        $this->assertEquals($user->id, $result->user_id);
        $this->assertEquals($survey->id, $result->survey_id);
        $this->assertDatabaseHas('survey_responses', [
            'user_id' => $user->id,
            'survey_id' => $survey->id
        ]);
    }
    /**
     * Test countBySurvey counts responses
     */
    public function test_count_by_survey_counts_responses()
    {
        // Arrange
        $survey = Survey::factory()->create();
        SurveyResponse::factory()->count(5)->create(['survey_id' => $survey->id]);
        SurveyResponse::factory()->count(3)->create(); // Other survey responses
        // Act
        $result = $this->surveyResponseService->countBySurvey($survey->id);
        // Assert
        $this->assertEquals(5, $result);
    }
    /**
     * Test countBySurvey returns zero when no responses
     */
    public function test_count_by_survey_returns_zero_when_no_responses()
    {
        // Arrange
        $survey = Survey::factory()->create();
        // Act
        $result = $this->surveyResponseService->countBySurvey($survey->id);
        // Assert
        $this->assertEquals(0, $result);
    }
    /**
     * Test update updates survey response
     */
    public function test_update_updates_survey_response()
    {
        // Arrange
        $surveyResponse = SurveyResponse::factory()->create();
        $newSurvey = Survey::factory()->create();
        $data = ['survey_id' => $newSurvey->id];
        // Act
        $result = $this->surveyResponseService->update($surveyResponse->id, $data);
        // Assert
        $this->assertTrue($result);
        $surveyResponse->refresh();
        $this->assertEquals($newSurvey->id, $surveyResponse->survey_id);
    }
    /**
     * Test update returns false for non-existent
     */
    public function test_update_returns_false_for_nonexistent()
    {
        // Act
        $result = $this->surveyResponseService->update(99999, ['survey_id' => 1]);
        // Assert
        $this->assertFalse($result);
    }
    /**
     * Test delete deletes survey response
     */
    public function test_delete_deletes_survey_response()
    {
        // Arrange
        $surveyResponse = SurveyResponse::factory()->create();
        // Act
        $result = $this->surveyResponseService->delete($surveyResponse->id);
        // Assert
        $this->assertTrue($result);
        $this->assertDatabaseMissing('survey_responses', ['id' => $surveyResponse->id]);
    }
    /**
     * Test delete returns false for non-existent
     */
    public function test_delete_returns_false_for_nonexistent()
    {
        // Act
        $result = $this->surveyResponseService->delete(99999);
        // Assert
        $this->assertFalse($result);
    }
}
