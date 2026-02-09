<?php

namespace App\Services\Survey;

use App\Models\SurveyQuestionChoice;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;

class SurveyQuestionChoiceService
{
    /**
     * Get survey question choice by ID
     *
     * @param int $id
     * @return SurveyQuestionChoice|null
     */
    public function getById(int $id): ?SurveyQuestionChoice
    {
        try {
            return SurveyQuestionChoice::find($id);
        } catch (\Exception $e) {
            Log::error('Error fetching survey question choice by ID: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Get survey question choice by ID or fail
     *
     * @param int $id
     * @return SurveyQuestionChoice
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function findOrFail(int $id): SurveyQuestionChoice
    {
        return SurveyQuestionChoice::findOrFail($id);
    }

    /**
     * Get all choices for a question
     *
     * @param int $questionId
     * @return Collection
     */
    public function getByQuestion(int $questionId): Collection
    {
        try {
            return SurveyQuestionChoice::where('question_id', $questionId)->get();
        } catch (\Exception $e) {
            Log::error('Error fetching survey question choices by question: ' . $e->getMessage(), [
                'question_id' => $questionId
            ]);
            return new Collection();
        }
    }

    /**
     * Create a new survey question choice
     *
     * @param array $data
     * @return SurveyQuestionChoice|null
     */
    public function create(array $data): ?SurveyQuestionChoice
    {
        try {
            return SurveyQuestionChoice::create($data);
        } catch (\Exception $e) {
            Log::error('Error creating survey question choice: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Update a survey question choice
     *
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update(int $id, array $data): bool
    {
        try {
            $choice = SurveyQuestionChoice::findOrFail($id);
            return $choice->update($data);
        } catch (\Exception $e) {
            Log::error('Error updating survey question choice: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Update survey question choice by ID using query builder
     *
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function updateById(int $id, array $data): bool
    {
        try {
            return SurveyQuestionChoice::where('id', $id)->update($data) > 0;
        } catch (\Exception $e) {
            Log::error('Error updating survey question choice by ID: ' . $e->getMessage(), [
                'choice_id' => $id
            ]);
            return false;
        }
    }

    /**
     * Delete a survey question choice
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        try {
            $choice = SurveyQuestionChoice::findOrFail($id);
            return $choice->delete();
        } catch (\Exception $e) {
            Log::error('Error deleting survey question choice: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Count choices for a question
     *
     * @param int $questionId
     * @return int
     */
    public function countByQuestion(int $questionId): int
    {
        try {
            return SurveyQuestionChoice::where('question_id', $questionId)->count();
        } catch (\Exception $e) {
            Log::error('Error counting survey question choices: ' . $e->getMessage(), [
                'question_id' => $questionId
            ]);
            return 0;
        }
    }
}

