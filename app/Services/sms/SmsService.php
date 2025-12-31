<?php

namespace App\Services\sms;

use App\Models\Sms;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SmsService
{
    /**
     * Get SMS statistics
     *
     * @return array
     */
    public function getStatistics(): array
    {
        try {
            return [
                'today' => Sms::whereDate('created_at', today())->count(),
                'week' => Sms::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
                'month' => Sms::whereMonth('created_at', now()->month)
                    ->whereYear('created_at', now()->year)
                    ->count(),
                'total' => Sms::count(),
            ];
        } catch (\Exception $e) {
            Log::error('Error loading SMS statistics: ' . $e->getMessage());
            return [
                'today' => 0,
                'week' => 0,
                'month' => 0,
                'total' => 0,
            ];
        }
    }

    /**
     * Get filtered SMS data with pagination
     *
     * @param array $filters
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getSmsData(array $filters = [], int $perPage = 25): LengthAwarePaginator
    {
        try {
            $query = Sms::query()
                ->select(
                    'sms.id',
                    'sms.message',
                    'sms.destination_number',
                    'sms.source_number',
                    'sms.created_at',
                    'sms.updated_at',
                    'sms.created_by',
                    'sms.updated_by',
                    DB::raw('(SELECT CONCAT(IFNULL(mu.enFirstName, ""), " ", IFNULL(mu.enLastName, "")) FROM users u LEFT JOIN metta_users mu ON u.idUser = mu.idUser WHERE u.id = sms.created_by LIMIT 1) as user_name')
                );

            // Apply filters
            if (!empty($filters['date_from'])) {
                $query->whereDate('sms.created_at', '>=', $filters['date_from']);
            }

            if (!empty($filters['date_to'])) {
                $query->whereDate('sms.created_at', '<=', $filters['date_to']);
            }

            if (!empty($filters['destination_number'])) {
                $query->where('sms.destination_number', 'like', '%' . $filters['destination_number'] . '%');
            }

            if (!empty($filters['message'])) {
                $query->where('sms.message', 'like', '%' . $filters['message'] . '%');
            }

            if (!empty($filters['user_id'])) {
                $query->where('sms.created_by', $filters['user_id']);
            }

            return $query->orderBy('created_at', 'desc')
                ->paginate($perPage);
        } catch (\Exception $e) {
            Log::error('Error fetching SMS data: ' . $e->getMessage());
            return new LengthAwarePaginator([], 0, $perPage);
        }
    }

    /**
     * Get SMS query builder for DataTables
     *
     * @param array $filters
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function getSmsDataQuery(array $filters = []): \Illuminate\Database\Eloquent\Builder
    {
        $query = Sms::query()
            ->select(
                'sms.id',
                'sms.message',
                'sms.destination_number',
                'sms.source_number',
                'sms.created_at',
                'sms.updated_at',
                'sms.created_by',
                'sms.updated_by',
                DB::raw('(SELECT CONCAT(IFNULL(mu.enFirstName, ""), " ", IFNULL(mu.enLastName, "")) FROM users u LEFT JOIN metta_users mu ON u.idUser = mu.idUser WHERE u.id = sms.created_by LIMIT 1) as user_name')
            );

        // Apply filters
        if (!empty($filters['date_from'])) {
            $query->whereDate('sms.created_at', '>=', $filters['date_from']);
        }

        if (!empty($filters['date_to'])) {
            $query->whereDate('sms.created_at', '<=', $filters['date_to']);
        }

        if (!empty($filters['destination_number'])) {
            $query->where('sms.destination_number', 'like', '%' . $filters['destination_number'] . '%');
        }

        if (!empty($filters['message'])) {
            $query->where('sms.message', 'like', '%' . $filters['message'] . '%');
        }

        if (!empty($filters['user_id'])) {
            $query->where('sms.created_by', $filters['user_id']);
        }

        return $query;
    }

    /**
     * Find SMS by ID
     *
     * @param int $id
     * @return Sms|null
     */
    public function findById(int $id): ?Sms
    {
        try {
            return Sms::findOrFail($id);
        } catch (\Exception $e) {
            Log::error('Error finding SMS by ID: ' . $e->getMessage(), ['id' => $id]);
            return null;
        }
    }
}

