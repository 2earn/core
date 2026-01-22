<?php

namespace App\Services;

use App\Models\countrie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CountriesService
{
    /**
     * Get country by phone code
     *
     * @param string $phoneCode
     * @return object|null
     */
    public function getByPhoneCode(string $phoneCode): ?object
    {
        try {
            return DB::table('countries')->where('phonecode', $phoneCode)->first();
        } catch (\Exception $e) {
            Log::error('Error fetching country by phone code: ' . $e->getMessage(), ['phoneCode' => $phoneCode]);
            return null;
        }
    }

    /**
     * Get country model by phone code (using Eloquent)
     *
     * @param string $phoneCode
     * @return countrie|null
     */
    public function getCountryModelByPhoneCode(string $phoneCode): ?countrie
    {
        try {
            return countrie::where('phonecode', $phoneCode)->first();
        } catch (\Exception $e) {
            Log::error('Error fetching country model by phone code: ' . $e->getMessage(), ['phoneCode' => $phoneCode]);
            return null;
        }
    }

    /**
     * Get all countries
     *
     * @return \Illuminate\Support\Collection
     */
    public function getAll(): \Illuminate\Support\Collection
    {
        try {
            return DB::table('countries')->get();
        } catch (\Exception $e) {
            Log::error('Error fetching all countries: ' . $e->getMessage());
            return collect();
        }
    }

    /**
     * Get countries for datatable with specific columns
     *
     * @param array $columns
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getForDatatable(array $columns = ['id', 'name', 'phonecode', 'langage']): \Illuminate\Database\Eloquent\Collection
    {
        try {
            return countrie::all($columns);
        } catch (\Exception $e) {
            Log::error('Error fetching countries for datatable: ' . $e->getMessage(), ['columns' => $columns]);
            return countrie::hydrate([]);
        }
    }
}

