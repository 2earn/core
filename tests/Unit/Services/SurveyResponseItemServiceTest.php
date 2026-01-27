<?php

namespace Tests\Unit\Services;

use App\Models\SurveyResponse;
use App\Models\SurveyResponseItem;
use App\Models\SurveyQuestion;
use App\Models\SurveyQuestionChoice;
use App\Services\SurveyResponseItemService;
use Tests\TestCase;

class SurveyResponseItemServiceTest extends TestCase
{

    protected SurveyResponseItemService $surveyResponseItemService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->surveyResponseItemService = new SurveyResponseItemService();
    }

    /**
     * Test getById method
     */
    public function test_get_by_id_works()
    {
        // Arrange
        $item = SurveyResponseItem::factory()->create();

        // Act
        $result = $this->surveyResponseItemService->getById($item->id);

        // Assert
        $this->assertNotNull($result);
        $this->assertInstanceOf(SurveyResponseItem::class, $result);
        $this->assertEquals($item->id, $result->id);
    }

    /**
     * Test getBySurveyResponse method
     */
    public function test_get_by_survey_response_works()
    {
        // Arrange
        $surveyResponse = SurveyResponse::factory()->create();
        SurveyResponseItem::factory()->count(5)->create(['surveyResponse_id' => $surveyResponse->id]);
        SurveyResponseItem::factory()->count(3)->create();

        // Act
        $result = $this->surveyResponseItemService->getBySurveyResponse($surveyResponse->id);

        // Assert
        $this->assertCount(5, $result);
        $this->assertTrue($result->every(fn($item) => $item->surveyResponse_id === $surveyResponse->id));
    }

    /**
     * Test countByResponseAndQuestion method
     */
    public function test_count_by_response_and_question_works()
    {
        // Arrange
        $surveyResponse = SurveyResponse::factory()->create();
        $surveyQuestion = SurveyQuestion::factory()->create();
        SurveyResponseItem::factory()->count(3)->create([
            'surveyResponse_id' => $surveyResponse->id,
            'surveyQuestion_id' => $surveyQuestion->id,
        ]);

        // Act
        $result = $this->surveyResponseItemService->countByResponseAndQuestion(
            $surveyResponse->id,
            $surveyQuestion->id
        );

        // Assert
        $this->assertEquals(3, $result);
    }

    /**
     * Test deleteByResponseAndQuestion method
     */
    public function test_delete_by_response_and_question_works()
    {
        // Arrange
        $surveyResponse = SurveyResponse::factory()->create();
        $surveyQuestion = SurveyQuestion::factory()->create();
        SurveyResponseItem::factory()->count(3)->create([
            'surveyResponse_id' => $surveyResponse->id,
            'surveyQuestion_id' => $surveyQuestion->id,
        ]);

        // Act
        $result = $this->surveyResponseItemService->deleteByResponseAndQuestion(
            $surveyResponse->id,
            $surveyQuestion->id
        );

        // Assert
        $this->assertTrue($result);
        $this->assertEquals(0, SurveyResponseItem::where('surveyResponse_id', $surveyResponse->id)
            ->where('surveyQuestion_id', $surveyQuestion->id)
            ->count());
    }

    /**
     * Test create method
     */
    public function test_create_works()
    {
        // Arrange
        $surveyResponse = SurveyResponse::factory()->create();
        $surveyQuestion = SurveyQuestion::factory()->create();
        $surveyQuestionChoice = SurveyQuestionChoice::factory()->create();

        $data = [
            'surveyResponse_id' => $surveyResponse->id,
            'surveyQuestion_id' => $surveyQuestion->id,
            'surveyQuestionChoice_id' => $surveyQuestionChoice->id,
        ];

        // Act
        $result = $this->surveyResponseItemService->create($data);

        // Assert
        $this->assertNotNull($result);
        $this->assertInstanceOf(SurveyResponseItem::class, $result);
        $this->assertDatabaseHas('survey_response_items', $data);
    }

    /**
     * Test createMultiple method
     */
    public function test_create_multiple_works()
    {
        // Arrange
        $surveyResponse = SurveyResponse::factory()->create();
        $surveyQuestion = SurveyQuestion::factory()->create();
        $choices = SurveyQuestionChoice::factory()->count(3)->create();
        $choiceIds = $choices->pluck('id')->toArray();

        // Act
        $result = $this->surveyResponseItemService->createMultiple(
            $surveyResponse->id,
            $surveyQuestion->id,
            $choiceIds
        );

        // Assert
        $this->assertTrue($result);
        $this->assertEquals(3, SurveyResponseItem::where('surveyResponse_id', $surveyResponse->id)
            ->where('surveyQuestion_id', $surveyQuestion->id)
            ->count());
    }

    /**
     * Test update method
     */
    public function test_update_works()
    {
        // Arrange
        $item = SurveyResponseItem::factory()->create();
        $newChoice = SurveyQuestionChoice::factory()->create();
        $updateData = ['surveyQuestionChoice_id' => $newChoice->id];

        // Act
        $result = $this->surveyResponseItemService->update($item->id, $updateData);

        // Assert
        $this->assertTrue($result);
        $item->refresh();
        $this->assertEquals($newChoice->id, $item->surveyQuestionChoice_id);
    }

    /**
     * Test delete method
     */
    public function test_delete_works()
    {
        // Arrange
        $item = SurveyResponseItem::factory()->create();

        // Act
        $result = $this->surveyResponseItemService->delete($item->id);

        // Assert
        $this->assertTrue($result);
        $this->assertDatabaseMissing('survey_response_items', ['id' => $item->id]);
    }

    /**
     * Test countByQuestion method
     */
    public function test_count_by_question_works()
    {
        // Arrange
        $surveyQuestion = SurveyQuestion::factory()->create();
        SurveyResponseItem::factory()->count(4)->create(['surveyQuestion_id' => $surveyQuestion->id]);

        // Act
        $result = $this->surveyResponseItemService->countByQuestion($surveyQuestion->id);

        // Assert
        $this->assertEquals(4, $result);
    }

    /**
     * Test countByQuestionAndChoice method
     */
    public function test_count_by_question_and_choice_works()
    {
        // Arrange
        $surveyQuestion = SurveyQuestion::factory()->create();
        $surveyQuestionChoice = SurveyQuestionChoice::factory()->create();
        SurveyResponseItem::factory()->count(5)->create([
            'surveyQuestion_id' => $surveyQuestion->id,
            'surveyQuestionChoice_id' => $surveyQuestionChoice->id,
        ]);

        // Act
        $result = $this->surveyResponseItemService->countByQuestionAndChoice(
            $surveyQuestion->id,
            $surveyQuestionChoice->id
        );

        // Assert
        $this->assertEquals(5, $result);
    }
}
