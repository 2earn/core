<?php

namespace App\Services\Survey;

use App\Models\SurveyQuestion;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;

class SurveyQuestionService
{
    /**
     * Get survey question by ID
     *
     * @param int $id
     * @return SurveyQuestion|null
     */
    public function getById(int $id): ?SurveyQuestion
    {
        try {
            return SurveyQuestion::find($id);
        } catch (\Exception $e) {
            Log::error('Error fetching survey question by ID: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Get survey question by ID or fail
     *
     * @param int $id
     * @return SurveyQuestion
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function findOrFail(int $id): SurveyQuestion
    {
        return SurveyQuestion::findOrFail($id);
    }

    /**
     * Get question by survey ID
     *
     * @param int $surveyId
     * @return SurveyQuestion|null
     */
    public function getBySurvey(int $surveyId): ?SurveyQuestion
    {
        try {
            return SurveyQuestion::where('survey_id', $surveyId)->first();
        } catch (\Exception $e) {
            Log::error('Error fetching survey question by survey: ' . $e->getMessage(), [
                'survey_id' => $surveyId
            ]);
            return null;
        }
    }

    /**
     * Create a new survey question
     *
     * @param array $data
     * @return SurveyQuestion|null
     */
    public function create(array $data): ?SurveyQuestion
    {
        try {
            return SurveyQuestion::create($data);
        } catch (\Exception $e) {
            Log::error('Error creating survey question: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Update a survey question
     *
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update(int $id, array $data): bool
    {
        try {
            $question = SurveyQuestion::findOrFail($id);
            return $question->update($data);
        } catch (\Exception $e) {
            Log::error('Error updating survey question: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Delete a survey question
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        try {
            $question = SurveyQuestion::findOrFail($id);
            return $question->delete();
        } catch (\Exception $e) {
            Log::error('Error deleting survey question: ' . $e->getMessage());
            return false;
        }
    }
}


