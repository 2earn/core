<?php

namespace App\Services\News;

use App\Models\News;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class NewsService
{
    /**
     * Get a News by ID with relationships
     *
     * @param int $id
     * @param array $with
     * @return News|null
     */
    public function getById(int $id, array $with = []): ?News
    {
        $query = News::query();

        if (!empty($with)) {
            $query->with($with);
        }

        return $query->find($id);
    }

    /**
     * Get a News by ID or fail
     *
     * @param int $id
     * @param array $with
     * @return News
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function getByIdOrFail(int $id, array $with = []): News
    {
        $query = News::query();

        if (!empty($with)) {
            $query->with($with);
        }

        return $query->findOrFail($id);
    }

    /**
     * Get paginated News with search and relationships
     *
     * @param string|null $search
     * @param int $perPage
     * @param array $with
     * @return LengthAwarePaginator
     */
    public function getPaginated(?string $search = null, int $perPage = 10, array $with = ['hashtags', 'mainImage']): LengthAwarePaginator
    {
        $query = News::with($with);

        if ($search) {
            $query->where('title', 'like', '%' . $search . '%');
        }

        return $query->orderBy('created_at', 'desc')->paginate($perPage);
    }

    /**
     * Get all News
     *
     * @param array $with
     * @return Collection
     */
    public function getAll(array $with = []): Collection
    {
        $query = News::query();

        if (!empty($with)) {
            $query->with($with);
        }

        return $query->orderBy('created_at', 'desc')->get();
    }

    /**
     * Create a new News
     *
     * @param array $data
     * @return News
     */
    public function create(array $data): News
    {
        return News::create($data);
    }

    /**
     * Update a News
     *
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update(int $id, array $data): bool
    {
        $news = News::findOrFail($id);
        return $news->update($data);
    }

    /**
     * Delete a News
     *
     * @param int $id
     * @return bool|null
     * @throws \Exception
     */
    public function delete(int $id): ?bool
    {
        $news = News::findOrFail($id);
        return $news->delete();
    }

    /**
     * Duplicate a News item
     *
     * @param int $id
     * @return News
     * @throws \Exception
     */
    public function duplicate(int $id): News
    {
        $original = News::findOrFail($id);
        $duplicate = $original->replicate();
        $duplicate->title = $original->title . ' (Copy)';
        $duplicate->content = $original->content . ' (Copy)';
        $duplicate->enabled = false;
        $duplicate->created_at = now();
        $duplicate->updated_at = now();
        $duplicate->save();

        createTranslaleModel($duplicate, 'title', $duplicate->title);
        createTranslaleModel($duplicate, 'content', $duplicate->content);

        if (!is_null($original->mainImage)) {
            $image = $original->mainImage->replicate();
            $image->imageable_id = $duplicate->id;
            $image->save();
        }

        return $duplicate;
    }
}

