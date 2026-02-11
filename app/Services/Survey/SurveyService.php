<?php

namespace App\Services\Survey;

use App\Enums\StatusSurvey;
use App\Models\Survey;
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

            if (!empty($search)) {
                $query->where('name', 'like', '%' . $search . '%');
            }

            $surveys = $query->get();

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

    /**
     * Get non-archived surveys with search and filtering
     *
     * @param string|null $search
     * @param bool $isSuperAdmin
     * @return Collection
     */
    public function getNonArchivedSurveysWithFilters(?string $search = null, bool $isSuperAdmin = false): Collection
    {
        try {
            $query = Survey::where('status', '!=', StatusSurvey::ARCHIVED->value);

            if ($isSuperAdmin) {
                if (!empty($search)) {
                    $query->where('name', 'like', '%' . $search . '%');
                }
                return $query->orderBy('created_at', 'DESC')->get();
            } else {
                $query->where('published', true)
                    ->where('status', '!=', StatusSurvey::NEW->value);

                if (!empty($search)) {
                    $query->where('name', 'like', '%' . $search . '%');
                }

                $surveys = $query->orderBy('created_at', 'DESC')->get();
                return $surveys->filter(function ($survey) {
                    return $survey->canShow();
                })->values();
            }
        } catch (\Exception $e) {
            Log::error('Error fetching non-archived surveys with filters: ' . $e->getMessage(), [
                'search' => $search,
                'is_super_admin' => $isSuperAdmin
            ]);
            return new Collection();
        }
    }

    /**
     * Enable a survey
     *
     * @param int $id
     * @return bool
     */
    public function enable(int $id): bool
    {
        try {
            return Survey::where('id', $id)->update([
                'enabled' => true,
                'enableDate' => \Carbon\Carbon::now()
            ]) > 0;
        } catch (\Exception $e) {
            Log::error('Error enabling survey: ' . $e->getMessage(), ['survey_id' => $id]);
            throw $e;
        }
    }

    /**
     * Disable a survey with note
     *
     * @param int $id
     * @param string $note
     * @return bool
     */
    public function disable(int $id, string $note): bool
    {
        try {
            $result = Survey::where('id', $id)->update([
                'enabled' => false,
                'disabledBtnDescription' => $note,
                'disabledate' => \Carbon\Carbon::now()
            ]);

            $survey = $this->getById($id);
            $translationModel = \App\Models\TranslaleModel::where('name',
                \App\Models\TranslaleModel::getTranslateName($survey, "disabledBtnDescription")
            )->first();

            if (!is_null($translationModel)) {
                $translationModel->update([
                    'value' => $note . ' AR',
                    'valueFr' => $note . ' FR',
                    'valueEn' => $note . ' EN'
                ]);
            } else {
                \App\Models\TranslaleModel::create([
                    'name' => \App\Models\TranslaleModel::getTranslateName($survey, 'disabledBtnDescription'),
                    'value' => $note . ' AR',
                    'valueFr' => $note . ' FR',
                    'valueEn' => $note . ' EN',
                    'valueEs' => $note . ' ES',
                    'valueTr' => $note . ' TR',
                    'valueRu' => $note . ' RU',
                    'valueDe' => $note . ' DE'
                ]);
            }

            return $result > 0;
        } catch (\Exception $e) {
            Log::error('Error disabling survey: ' . $e->getMessage(), ['survey_id' => $id]);
            throw $e;
        }
    }

    /**
     * Check if survey can be opened
     *
     * @param int $id
     * @return bool
     * @throws \Exception
     */
    public function canBeOpened(int $id): bool
    {
        try {
            $survey = $this->getById($id);

            if (!$survey->enabled) {
                throw new \Exception(\Illuminate\Support\Facades\Lang::get('This is disabled'));
            }
            if ($survey->targets->isEmpty()) {
                throw new \Exception(\Illuminate\Support\Facades\Lang::get('No target spacified'));
            }

            if (!is_null($survey->question)) {
                if ($survey->question->serveyQuestionChoice()->count() < 2) {
                    throw new \Exception(\Illuminate\Support\Facades\Lang::get('We need more choices for the question'));
                }
            } else {
                throw new \Exception(\Illuminate\Support\Facades\Lang::get('We need to add the question'));
            }

            return true;
        } catch (\Exception $e) {
            Log::error('Error checking if survey can be opened: ' . $e->getMessage(), ['survey_id' => $id]);
            throw $e;
        }
    }

    /**
     * Open a survey
     *
     * @param int $id
     * @return bool
     */
    public function open(int $id): bool
    {
        try {
            return Survey::where('id', $id)->update([
                'status' => StatusSurvey::OPEN->value,
                'openDate' => \Carbon\Carbon::now()
            ]) > 0;
        } catch (\Exception $e) {
            Log::error('Error opening survey: ' . $e->getMessage(), ['survey_id' => $id]);
            throw $e;
        }
    }

    /**
     * Close a survey
     *
     * @param int $id
     * @return bool
     */
    public function close(int $id): bool
    {
        try {
            return Survey::where('id', $id)->update([
                'status' => StatusSurvey::CLOSED->value,
                'closeDate' => \Carbon\Carbon::now()
            ]) > 0;
        } catch (\Exception $e) {
            Log::error('Error closing survey: ' . $e->getMessage(), ['survey_id' => $id]);
            throw $e;
        }
    }

    /**
     * Archive a survey
     *
     * @param int $id
     * @return bool
     */
    public function archive(int $id): bool
    {
        try {
            Survey::where('id', $id)->update([
                'status' => StatusSurvey::ARCHIVED->value,
                'archivedDate' => \Carbon\Carbon::now()
            ]);
            return true;
        } catch (\Exception $e) {
            Log::error('Error archiving survey: ' . $e->getMessage(), ['survey_id' => $id]);
            throw $e;
        }
    }

    /**
     * Publish a survey
     *
     * @param int $id
     * @return bool
     */
    public function publish(int $id): bool
    {
        try {
            return Survey::where('id', $id)->update([
                'published' => true,
                'publishDate' => \Carbon\Carbon::now()
            ]) > 0;
        } catch (\Exception $e) {
            Log::error('Error publishing survey: ' . $e->getMessage(), ['survey_id' => $id]);
            throw $e;
        }
    }

    /**
     * Unpublish a survey
     *
     * @param int $id
     * @return bool
     */
    public function unpublish(int $id): bool
    {
        try {
            return Survey::where('id', $id)->update([
                'published' => false,
                'unpublishDate' => \Carbon\Carbon::now()
            ]) > 0;
        } catch (\Exception $e) {
            Log::error('Error unpublishing survey: ' . $e->getMessage(), ['survey_id' => $id]);
            throw $e;
        }
    }

    /**
     * Change updatable property of a survey
     *
     * @param int $id
     * @param bool $updatable
     * @return bool
     */
    public function changeUpdatable(int $id, bool $updatable): bool
    {
        try {
            return Survey::where('id', $id)->update(['updatable' => $updatable]) > 0;
        } catch (\Exception $e) {
            Log::error('Error changing survey updatable property: ' . $e->getMessage(), ['survey_id' => $id]);
            throw $e;
        }
    }

    /**
     * Check if user has liked a survey
     *
     * @param int $surveyId
     * @param int $userId
     * @return bool
     */
    public function hasUserLiked(int $surveyId, int $userId): bool
    {
        try {
            return Survey::whereHas('likes', function ($q) use ($userId, $surveyId) {
                $q->where('user_id', $userId)->where('likable_id', $surveyId);
            })->exists();
        } catch (\Exception $e) {
            Log::error('Error checking if user liked survey: ' . $e->getMessage(), [
                'survey_id' => $surveyId,
                'user_id' => $userId
            ]);
            return false;
        }
    }

    /**
     * Add like to survey
     *
     * @param int $surveyId
     * @param int $userId
     * @return bool
     */
    public function addLike(int $surveyId, int $userId): bool
    {
        try {
            $survey = $this->getById($surveyId);
            $survey->likes()->create(['user_id' => $userId]);
            return true;
        } catch (\Exception $e) {
            Log::error('Error adding like to survey: ' . $e->getMessage(), [
                'survey_id' => $surveyId,
                'user_id' => $userId
            ]);
            throw $e;
        }
    }

    /**
     * Remove like from survey
     *
     * @param int $surveyId
     * @param int $userId
     * @return bool
     */
    public function removeLike(int $surveyId, int $userId): bool
    {
        try {
            $survey = $this->getById($surveyId);
            $likes = $survey->likes()->get();
            foreach ($likes as $like) {
                if ($like->user_id == $userId) {
                    $like->delete();
                }
            }
            return true;
        } catch (\Exception $e) {
            Log::error('Error removing like from survey: ' . $e->getMessage(), [
                'survey_id' => $surveyId,
                'user_id' => $userId
            ]);
            throw $e;
        }
    }

    /**
     * Add comment to survey
     *
     * @param int $surveyId
     * @param int $userId
     * @param string $content
     * @return bool
     */
    public function addComment(int $surveyId, int $userId, string $content): bool
    {
        try {
            $survey = $this->getById($surveyId);
            $survey->comments()->create([
                'user_id' => $userId,
                'content' => $content
            ]);
            return true;
        } catch (\Exception $e) {
            Log::error('Error adding comment to survey: ' . $e->getMessage(), [
                'survey_id' => $surveyId,
                'user_id' => $userId
            ]);
            throw $e;
        }
    }
}

