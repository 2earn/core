<?php

namespace App\Services\Translation;

use App\Models\TranslaleModel;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class TranslaleModelService
{
    /**
     * Get a translation by ID
     *
     * @param int $id
     * @return TranslaleModel|null
     */
    public function getById(int $id): ?TranslaleModel
    {
        return TranslaleModel::find($id);
    }

    /**
     * Get all translations
     *
     * @return Collection
     */
    public function getAll(): Collection
    {
        return TranslaleModel::all();
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
        $query = TranslaleModel::query();

        if ($search) {
            $searchUpper = strtoupper($search);
            $query->where(function($q) use ($searchUpper) {
                $q->where(DB::raw('upper(name)'), 'like', '%' . $searchUpper . '%')
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
    }

    /**
     * Check if a translation key exists
     *
     * @param string $name
     * @return bool
     */
    public function exists(string $name): bool
    {
        return TranslaleModel::where(DB::raw('upper(name)'), strtoupper($name))->exists();
    }

    /**
     * Create a new translation
     *
     * @param string $name
     * @param array|null $values
     * @return TranslaleModel
     */
    public function create(string $name, ?array $values = null): TranslaleModel
    {
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

        return TranslaleModel::create($data);
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
        return TranslaleModel::where('id', $id)->update($values);
    }

    /**
     * Delete a translation
     *
     * @param int $id
     * @return bool|null
     */
    public function delete(int $id): ?bool
    {
        return TranslaleModel::where('id', $id)->delete();
    }

    /**
     * Get all translations as key-value arrays for each language
     *
     * @return array
     */
    public function getAllAsKeyValueArrays(): array
    {
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
    }

    /**
     * Search translations by term
     *
     * @param string $search
     * @return Collection
     */
    public function search(string $search): Collection
    {
        $searchUpper = strtoupper($search);

        return TranslaleModel::where(DB::raw('upper(name)'), 'like', '%' . $searchUpper . '%')
            ->orWhere(DB::raw('upper(value)'), 'like', '%' . $searchUpper . '%')
            ->orWhere(DB::raw('upper(valueFr)'), 'like', '%' . $searchUpper . '%')
            ->orWhere(DB::raw('upper(valueEn)'), 'like', '%' . $searchUpper . '%')
            ->orWhere(DB::raw('upper(valueEs)'), 'like', '%' . $searchUpper . '%')
            ->orWhere(DB::raw('upper(valueTr)'), 'like', '%' . $searchUpper . '%')
            ->orWhere(DB::raw('upper(valueRu)'), 'like', '%' . $searchUpper . '%')
            ->orWhere(DB::raw('upper(valueDe)'), 'like', '%' . $searchUpper . '%')
            ->orderBy('id', 'desc')
            ->get();
    }

    /**
     * Get total count of translations
     *
     * @return int
     */
    public function count(): int
    {
        return TranslaleModel::count();
    }

    /**
     * Get translations by name pattern
     *
     * @param string $pattern
     * @return Collection
     */
    public function getByNamePattern(string $pattern): Collection
    {
        return TranslaleModel::where('name', 'like', $pattern)->get();
    }
}

