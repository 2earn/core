<?php

namespace App\Services;

use App\Models\SurveyResponseItem;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;

class SurveyResponseItemService
{
    /**
     * Get survey response item by ID
     *
     * @param int $id
     * @return SurveyResponseItem|null
     */
    public function getById(int $id): ?SurveyResponseItem
    {
        try {
            return SurveyResponseItem::find($id);
        } catch (\Exception $e) {
            Log::error('Error fetching survey response item by ID: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Get all survey response items by survey response ID
     *
     * @param int $surveyResponseId
     * @return Collection
     */
    public function getBySurveyResponse(int $surveyResponseId): Collection
    {
        try {
            return SurveyResponseItem::where('surveyResponse_id', $surveyResponseId)->get();
        } catch (\Exception $e) {
            Log::error('Error fetching survey response items: ' . $e->getMessage(), [
                'survey_response_id' => $surveyResponseId
            ]);
            return new Collection();
        }
    }

    /**
     * Count survey response items by survey response and question
     *
     * @param int $surveyResponseId
     * @param int $surveyQuestionId
     * @return int
     */
    public function countByResponseAndQuestion(int $surveyResponseId, int $surveyQuestionId): int
    {
        try {
            return SurveyResponseItem::where('surveyResponse_id', $surveyResponseId)
                ->where('surveyQuestion_id', $surveyQuestionId)
                ->count();
        } catch (\Exception $e) {
            Log::error('Error counting survey response items: ' . $e->getMessage(), [
                'survey_response_id' => $surveyResponseId,
                'survey_question_id' => $surveyQuestionId
            ]);
            return 0;
        }
    }

    /**
     * Delete survey response items by survey response and question
     *
     * @param int $surveyResponseId
     * @param int $surveyQuestionId
     * @return bool
     */
    public function deleteByResponseAndQuestion(int $surveyResponseId, int $surveyQuestionId): bool
    {
        try {
            SurveyResponseItem::where('surveyResponse_id', $surveyResponseId)
                ->where('surveyQuestion_id', $surveyQuestionId)
                ->delete();
            return true;
        } catch (\Exception $e) {
            Log::error('Error deleting survey response items: ' . $e->getMessage(), [
                'survey_response_id' => $surveyResponseId,
                'survey_question_id' => $surveyQuestionId
            ]);
            throw $e;
        }
    }

    /**
     * Create a new survey response item
     *
     * @param array $data
     * @return SurveyResponseItem|null
     */
    public function create(array $data): ?SurveyResponseItem
    {
        try {
            return SurveyResponseItem::create($data);
        } catch (\Exception $e) {
            Log::error('Error creating survey response item: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Create multiple survey response items
     *
     * @param int $surveyResponseId
     * @param int $surveyQuestionId
     * @param array $choiceIds
     * @return bool
     */
    public function createMultiple(int $surveyResponseId, int $surveyQuestionId, array $choiceIds): bool
    {
        try {
            foreach ($choiceIds as $choiceId) {
                SurveyResponseItem::create([
                    'surveyResponse_id' => $surveyResponseId,
                    'surveyQuestion_id' => $surveyQuestionId,
                    'surveyQuestionChoice_id' => $choiceId
                ]);
            }
            return true;
        } catch (\Exception $e) {
            Log::error('Error creating multiple survey response items: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Update a survey response item
     *
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update(int $id, array $data): bool
    {
        try {
            $item = SurveyResponseItem::findOrFail($id);
            return $item->update($data);
        } catch (\Exception $e) {
            Log::error('Error updating survey response item: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Delete a survey response item
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        try {
            $item = SurveyResponseItem::findOrFail($id);
            return $item->delete();
        } catch (\Exception $e) {
            Log::error('Error deleting survey response item: ' . $e->getMessage());
            return false;
        }
    }
}

