<?php

namespace App\Services;

use App\Models\Survey;
use Core\Enum\StatusSurvey;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;

class SurveyService
{
    /**
     * Get survey by ID
     *
     * @param int $id
     * @return Survey|null
     */
    public function getById(int $id): ?Survey
    {
        try {
            return Survey::find($id);
        } catch (\Exception $e) {
            Log::error('Error fetching survey by ID: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Get all non-archived surveys ordered by newest first
     *
     * @return Collection
     */
    public function getNonArchivedSurveys(): Collection
    {
        try {
            return Survey::where('status', '<', StatusSurvey::ARCHIVED->value)
                ->orderBy('id', 'desc')
                ->get();
        } catch (\Exception $e) {
            Log::error('Error fetching non-archived surveys: ' . $e->getMessage());
            return new Collection();
        }
    }

    /**
     * Get all surveys
     *
     * @return Collection
     */
    public function getAll(): Collection
    {
        try {
            return Survey::orderBy('id', 'desc')->get();
        } catch (\Exception $e) {
            Log::error('Error fetching all surveys: ' . $e->getMessage());
            return new Collection();
        }
    }

    /**
     * Create a new survey
     *
     * @param array $data
     * @return Survey|null
     */
    public function create(array $data): ?Survey
    {
        try {
            return Survey::create($data);
        } catch (\Exception $e) {
            Log::error('Error creating survey: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Update a survey
     *
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update(int $id, array $data): bool
    {
        try {
            $survey = Survey::findOrFail($id);
            return $survey->update($data);
        } catch (\Exception $e) {
            Log::error('Error updating survey: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Delete a survey
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        try {
            $survey = Survey::findOrFail($id);
            return $survey->delete();
        } catch (\Exception $e) {
            Log::error('Error deleting survey: ' . $e->getMessage());
            return false;
        }
    }
}

