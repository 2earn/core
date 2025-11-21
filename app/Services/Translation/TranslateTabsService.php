<?php

namespace App\Services\Translation;

use Core\Models\translatetabs;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TranslateTabsService
{
    /**
     * Get a translation by ID
     *
     * @param int $id
     * @return translatetabs|null
     */
    public function getById(int $id): ?translatetabs
    {
        try {
            return translatetabs::find($id);
        } catch (\Exception $e) {
            Log::error('Error fetching translation by ID: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Get all translations
     *
     * @return Collection
     */
    public function getAll(): Collection
    {
        try {
            return translatetabs::orderBy('id', 'desc')->get();
        } catch (\Exception $e) {
            Log::error('Error fetching all translations: ' . $e->getMessage());
            return new Collection();
        }
    }

    /**
     * Get paginated translations with search
     *
     * @param string|null $search
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getPaginated(?string $search = null, int $perPage = 10): LengthAwarePaginator
    {
        try {
            $query = translatetabs::query();

            if ($search) {
                $searchUpper = strtoupper($search);
                $query->where(function($q) use ($searchUpper) {
                    $q->where(DB::raw('upper(name)'), 'like', '%' . $searchUpper . '%')
                      ->orWhere(DB::raw('BINARY `name`'), 'like', '%' . $searchUpper . '%')
                      ->orWhere(DB::raw('upper(value)'), 'like', '%' . $searchUpper . '%')
                      ->orWhere(DB::raw('upper(valueFr)'), 'like', '%' . $searchUpper . '%')
                      ->orWhere(DB::raw('upper(valueEn)'), 'like', '%' . $searchUpper . '%')
                      ->orWhere(DB::raw('upper(valueEs)'), 'like', '%' . $searchUpper . '%')
                      ->orWhere(DB::raw('upper(valueTr)'), 'like', '%' . $searchUpper . '%')
                      ->orWhere(DB::raw('upper(valueRu)'), 'like', '%' . $searchUpper . '%')
                      ->orWhere(DB::raw('upper(valueDe)'), 'like', '%' . $searchUpper . '%');
                });
            }

            return $query->orderBy('id', 'desc')->paginate($perPage);
        } catch (\Exception $e) {
            Log::error('Error fetching paginated translations: ' . $e->getMessage());
            return new \Illuminate\Pagination\LengthAwarePaginator([], 0, $perPage);
        }
    }

    /**
     * Check if a translation key exists (case-sensitive binary check)
     *
     * @param string $name
     * @return bool
     */
    public function exists(string $name): bool
    {
        try {
            return translatetabs::where(DB::raw('BINARY `name`'), $name)->exists();
        } catch (\Exception $e) {
            Log::error('Error checking translation existence: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Create a new translation
     *
     * @param string $name
     * @param array|null $values
     * @return translatetabs|null
     */
    public function create(string $name, ?array $values = null): ?translatetabs
    {
        try {
            $data = [
                'name' => $name,
                'value' => $values['value'] ?? $name . ' AR',
                'valueFr' => $values['valueFr'] ?? $name . ' FR',
                'valueEn' => $values['valueEn'] ?? $name . ' EN',
                'valueTr' => $values['valueTr'] ?? $name . ' TR',
                'valueEs' => $values['valueEs'] ?? $name . ' ES',
                'valueRu' => $values['valueRu'] ?? $name . ' RU',
                'valueDe' => $values['valueDe'] ?? $name . ' DE',
            ];

            return translatetabs::create($data);
        } catch (\Exception $e) {
            Log::error('Error creating translation: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Update a translation
     *
     * @param int $id
     * @param array $values
     * @return bool
     */
    public function update(int $id, array $values): bool
    {
        try {
            return translatetabs::where('id', $id)->update($values);
        } catch (\Exception $e) {
            Log::error('Error updating translation: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Delete a translation
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        try {
            return translatetabs::where('id', $id)->delete();
        } catch (\Exception $e) {
            Log::error('Error deleting translation: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Search translations by term
     *
     * @param string $search
     * @return Collection
     */
    public function search(string $search): Collection
    {
        try {
            $searchUpper = strtoupper($search);

            return translatetabs::where(DB::raw('upper(name)'), 'like', '%' . $searchUpper . '%')
                ->orWhere(DB::raw('BINARY `name`'), 'like', '%' . $searchUpper . '%')
                ->orWhere(DB::raw('upper(value)'), 'like', '%' . $searchUpper . '%')
                ->orWhere(DB::raw('upper(valueFr)'), 'like', '%' . $searchUpper . '%')
                ->orWhere(DB::raw('upper(valueEn)'), 'like', '%' . $searchUpper . '%')
                ->orWhere(DB::raw('upper(valueEs)'), 'like', '%' . $searchUpper . '%')
                ->orWhere(DB::raw('upper(valueTr)'), 'like', '%' . $searchUpper . '%')
                ->orWhere(DB::raw('upper(valueRu)'), 'like', '%' . $searchUpper . '%')
                ->orWhere(DB::raw('upper(valueDe)'), 'like', '%' . $searchUpper . '%')
                ->orderBy('id', 'desc')
                ->get();
        } catch (\Exception $e) {
            Log::error('Error searching translations: ' . $e->getMessage());
            return new Collection();
        }
    }

    /**
     * Get all translations as key-value arrays for each language
     *
     * @return array
     */
    public function getAllAsKeyValueArrays(): array
    {
        try {
            $all = $this->getAll();

            $result = [
                'ar' => [],
                'fr' => [],
                'en' => [],
                'tr' => [],
                'es' => [],
                'ru' => [],
                'de' => [],
            ];

            foreach ($all as $translation) {
                $result['ar'][$translation->name] = $translation->value;
                $result['fr'][$translation->name] = $translation->valueFr;
                $result['en'][$translation->name] = $translation->valueEn;
                $result['tr'][$translation->name] = $translation->valueTr;
                $result['es'][$translation->name] = $translation->valueEs;
                $result['ru'][$translation->name] = $translation->valueRu;
                $result['de'][$translation->name] = $translation->valueDe;
            }

            return $result;
        } catch (\Exception $e) {
            Log::error('Error getting translations as key-value arrays: ' . $e->getMessage());
            return [
                'ar' => [], 'fr' => [], 'en' => [], 'tr' => [],
                'es' => [], 'ru' => [], 'de' => []
            ];
        }
    }

    /**
     * Get total count of translations
     *
     * @return int
     */
    public function count(): int
    {
        try {
            return translatetabs::count();
        } catch (\Exception $e) {
            Log::error('Error counting translations: ' . $e->getMessage());
            return 0;
        }
    }

    /**
     * Get translations by name pattern
     *
     * @param string $pattern
     * @return Collection
     */
    public function getByNamePattern(string $pattern): Collection
    {
        try {
            return translatetabs::where('name', 'like', $pattern)->get();
        } catch (\Exception $e) {
            Log::error('Error getting translations by pattern: ' . $e->getMessage());
            return new Collection();
        }
    }

    /**
     * Bulk create translations
     *
     * @param array $translations
     * @return bool
     */
    public function bulkCreate(array $translations): bool
    {
        try {
            DB::beginTransaction();

            foreach ($translations as $translation) {
                if (!$this->exists($translation['name'])) {
                    $this->create($translation['name'], $translation);
                }
            }

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error bulk creating translations: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Get statistics about translations
     *
     * @return array
     */
    public function getStatistics(): array
    {
        try {
            return [
                'total_count' => $this->count(),
                'today_count' => translatetabs::whereDate('created_at', today())->count(),
                'this_week_count' => translatetabs::whereBetween('created_at', [
                    now()->startOfWeek(),
                    now()->endOfWeek()
                ])->count(),
                'this_month_count' => translatetabs::whereMonth('created_at', now()->month)
                    ->whereYear('created_at', now()->year)
                    ->count(),
            ];
        } catch (\Exception $e) {
            Log::error('Error getting translation statistics: ' . $e->getMessage());
            return [
                'total_count' => 0,
                'today_count' => 0,
                'this_week_count' => 0,
                'this_month_count' => 0,
            ];
        }
    }
}

