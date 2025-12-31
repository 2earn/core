<?php

namespace App\Services;

use Core\Models\Amount;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;

class AmountService
{
    /**
     * Get amount by ID
     *
     * @param int $id
     * @return Amount|null
     */
    public function getById(int $id): ?Amount
    {
        try {
            return Amount::find($id);
        } catch (\Exception $e) {
            Log::error('Error fetching amount by ID: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Get paginated amounts with search
     *
     * @param string|null $search
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getPaginated(?string $search = null, int $perPage = 10): LengthAwarePaginator
    {
        try {
            return Amount::query()
                ->when($search, function ($query) use ($search) {
                    $query->where('amountsname', 'like', '%' . $search . '%')
                          ->orWhere('amountsshortname', 'like', '%' . $search . '%');
                })
                ->paginate($perPage);
        } catch (\Exception $e) {
            Log::error('Error fetching paginated amounts: ' . $e->getMessage());
            return Amount::paginate($perPage);
        }
    }

    /**
     * Update an amount
     *
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update(int $id, array $data): bool
    {
        try {
            $amount = Amount::find($id);
            if (!$amount) {
                return false;
            }

            foreach ($data as $key => $value) {
                $amount->$key = $value;
            }

            return $amount->save();
        } catch (\Exception $e) {
            Log::error('Error updating amount: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Get all amounts
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAll()
    {
        try {
            return Amount::all();
        } catch (\Exception $e) {
            Log::error('Error fetching all amounts: ' . $e->getMessage());
            return new \Illuminate\Database\Eloquent\Collection();
        }
    }
}

