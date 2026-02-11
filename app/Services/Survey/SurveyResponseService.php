<?php

namespace App\Services\Survey;

use App\Models\SurveyResponse;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;

class SurveyResponseService
{
    /**
     * Get survey response by ID
     *
     * @param int $id
     * @return SurveyResponse|null
     */
    public function getById(int $id): ?SurveyResponse
    {
        try {
            return SurveyResponse::find($id);
        } catch (\Exception $e) {
            Log::error('Error fetching survey response by ID: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Get survey response by user and survey ID
     *
     * @param int $userId
     * @param int $surveyId
     * @return SurveyResponse|null
     */
    public function getByUserAndSurvey(int $userId, int $surveyId): ?SurveyResponse
    {
        try {
            return SurveyResponse::where('user_id', $userId)
                ->where('survey_id', $surveyId)
                ->first();
        } catch (\Exception $e) {
            Log::error('Error fetching survey response by user and survey: ' . $e->getMessage(), [
                'user_id' => $userId,
                'survey_id' => $surveyId
            ]);
            return null;
        }
    }

    /**
     * Check if user has participated in a survey
     *
     * @param int $userId
     * @param int $surveyId
     * @return bool
     */
    public function isParticipated(int $userId, int $surveyId): bool
    {
        try {
            return SurveyResponse::where('user_id', $userId)
                ->where('survey_id', $surveyId)
                ->exists();
        } catch (\Exception $e) {
            Log::error('Error checking survey participation: ' . $e->getMessage(), [
                'user_id' => $userId,
                'survey_id' => $surveyId
            ]);
            return false;
        }
    }

    /**
     * Create a new survey response
     *
     * @param array $data
     * @return SurveyResponse|null
     */
    public function create(array $data): ?SurveyResponse
    {
        try {
            return SurveyResponse::create($data);
        } catch (\Exception $e) {
            Log::error('Error creating survey response: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Count survey responses by survey ID
     *
     * @param int $surveyId
     * @return int
     */
    public function countBySurvey(int $surveyId): int
    {
        try {
            return SurveyResponse::where('survey_id', $surveyId)->count();
        } catch (\Exception $e) {
            Log::error('Error counting survey responses: ' . $e->getMessage(), [
                'survey_id' => $surveyId
            ]);
            return 0;
        }
    }

    /**
     * Update a survey response
     *
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update(int $id, array $data): bool
    {
        try {
            $surveyResponse = SurveyResponse::findOrFail($id);
            return $surveyResponse->update($data);
        } catch (\Exception $e) {
            Log::error('Error updating survey response: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Delete a survey response
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        try {
            $surveyResponse = SurveyResponse::findOrFail($id);
            return $surveyResponse->delete();
        } catch (\Exception $e) {
            Log::error('Error deleting survey response: ' . $e->getMessage());
            return false;
        }
    }
}


