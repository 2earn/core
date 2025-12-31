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

    /**
     * Get archived surveys with optional search
     *
     * @param string|null $search
     * @param bool $isSuperAdmin
     * @return Collection
     */
    public function getArchivedSurveys(?string $search = null, bool $isSuperAdmin = false): Collection
    {
        try {
            $query = Survey::where('status', '=', StatusSurvey::ARCHIVED->value);

            // Apply search filter if provided
            if (!empty($search)) {
                $query->where('name', 'like', '%' . $search . '%');
            }

            $surveys = $query->get();

            // Filter surveys that can be shown after archiving
            return $surveys->filter(function ($survey) {
                return $survey->canShowAfterArchiving();
            })->values();
        } catch (\Exception $e) {
            Log::error('Error fetching archived surveys: ' . $e->getMessage(), [
                'search' => $search,
                'is_super_admin' => $isSuperAdmin
            ]);
            return new Collection();
        }
    }

    /**
     * Get survey by ID or fail
     *
     * @param int $id
     * @return Survey
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function findOrFail(int $id): Survey
    {
        return Survey::findOrFail($id);
    }

    /**
     * Attach targets to a survey
     *
     * @param Survey $survey
     * @param array $targetIds
     * @return void
     */
    public function attachTargets(Survey $survey, array $targetIds): void
    {
        try {
            $survey->targets()->detach();
            $survey->targets()->attach($targetIds);
        } catch (\Exception $e) {
            Log::error('Error attaching targets to survey: ' . $e->getMessage(), [
                'survey_id' => $survey->id,
                'target_ids' => $targetIds
            ]);
            throw $e;
        }
    }

    /**
     * Update survey by ID using query builder
     *
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function updateById(int $id, array $data): bool
    {
        try {
            return Survey::where('id', $id)->update($data) > 0;
        } catch (\Exception $e) {
            Log::error('Error updating survey by ID: ' . $e->getMessage(), [
                'survey_id' => $id
            ]);
            return false;
        }
    }
}

