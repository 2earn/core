<?php

namespace App\Services;

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

            return false;
            Log::error('Error updating survey response: ' . $e->getMessage());
        } catch (\Exception $e) {
            return $surveyResponse->update($data);
            $surveyResponse = SurveyResponse::findOrFail($id);
        try {
    {
    public function update(int $id, array $data): bool
     */
     * @return bool
     * @param array $data
     * @param int $id
     *
     * Update a survey response
    /**

    }
        }
            return 0;
            ]);
                'survey_id' => $surveyId
            Log::error('Error counting survey responses: ' . $e->getMessage(), [
        } catch (\Exception $e) {
            return SurveyResponse::where('survey_id', $surveyId)->count();
        try {
    {
    public function countBySurvey(int $surveyId): int
     */
     * @return int
     * @param int $surveyId
     *
     * Count survey responses by survey ID
    /**

    }
        }
            throw $e;
            Log::error('Error creating survey response: ' . $e->getMessage());
        } catch (\Exception $e) {
            return SurveyResponse::create($data);
        try {
    {
    public function create(array $data): ?SurveyResponse
     */
     * @return SurveyResponse|null
     * @param array $data
     *
     * Create a new survey response
    /**

    }
        }
            return false;
            ]);
                'survey_id' => $surveyId
                'user_id' => $userId,
            Log::error('Error checking survey participation: ' . $e->getMessage(), [
        } catch (\Exception $e) {
                ->exists();
                ->where('survey_id', $surveyId)
            return SurveyResponse::where('user_id', $userId)
        try {
    {
    public function isParticipated(int $userId, int $surveyId): bool
     */
     * @return bool
     * @param int $surveyId
     * @param int $userId
     *
     * Check if user has participated in a survey
    /**

    }
        }
            return null;
            ]);
                'survey_id' => $surveyId
                'user_id' => $userId,
            Log::error('Error fetching survey response by user and survey: ' . $e->getMessage(), [
        } catch (\Exception $e) {
                ->first();
                ->where('survey_id', $surveyId)
            return SurveyResponse::where('user_id', $userId)
        try {
    {
    public function getByUserAndSurvey(int $userId, int $surveyId): ?SurveyResponse
     */
     * @return SurveyResponse|null
     * @param int $surveyId
     * @param int $userId
     *
     * Get survey response by user and survey ID
    /**

    }
        }
            return null;
            Log::error('Error fetching survey response by ID: ' . $e->getMessage());
        } catch (\Exception $e) {
            return SurveyResponse::find($id);
        try {
    {
    public function getById(int $id): ?SurveyResponse
     */
     * @return SurveyResponse|null
     * @param int $id
     *
     * Get survey response by ID
    /**
{
class SurveyResponseService

use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Collection;
use App\Models\SurveyResponse;

namespace App\Services;


