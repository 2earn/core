<?php

namespace App\Services\Faq;

use App\Models\Faq;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class FaqService
{
    /**
     * Get a FAQ by ID
     *
     * @param int $id
     * @return Faq|null
     */
    public function getById(int $id): ?Faq
    {
        return Faq::find($id);
    }

    /**
     * Get a FAQ by ID or fail
     *
     * @param int $id
     * @return Faq
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function getByIdOrFail(int $id): Faq
    {
        return Faq::findOrFail($id);
    }

    /**
     * Get paginated FAQs with search
     *
     * @param string|null $search
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getPaginated(?string $search = null, int $perPage = 10): LengthAwarePaginator
    {
        $query = Faq::query();

        if ($search) {
            $query->where('question', 'like', '%' . $search . '%');
        }

        return $query->orderBy('created_at', 'desc')->paginate($perPage);
    }

    /**
     * Get all FAQs
     *
     * @return Collection
     */
    public function getAll(): Collection
    {
        return Faq::orderBy('created_at', 'desc')->get();
    }

    /**
     * Create a new FAQ
     *
     * @param array $data
     * @return Faq
     */
    public function create(array $data): Faq
    {
        return Faq::create($data);
    }

    /**
     * Update a FAQ
     *
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update(int $id, array $data): bool
    {
        $faq = Faq::findOrFail($id);
        return $faq->update($data);
    }

    /**
     * Delete a FAQ
     *
     * @param int $id
     * @return bool|null
     * @throws \Exception
     */
    public function delete(int $id): ?bool
    {
        $faq = Faq::findOrFail($id);
        return $faq->delete();
    }
}

