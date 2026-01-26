<?php

namespace App\Services;

use App\Enums\LanguageEnum;
use App\Models\countrie;
use Illuminate\Support\Facades\Log;

class CountryService
{
    /**
     * Update country language settings
     *
     * @param int $countryId Country ID
     * @param string $languageName Language name
     * @return array Result array with success status and message
     */
    public function updateCountryLanguage(int $countryId, string $languageName): array
    {
        try {
            // Find country by ID
            $country = countrie::find($countryId);

            if (!$country) {
                return [
                    'success' => false,
                    'message' => 'Country not found'
                ];
            }

            // Update language settings
            $country->langage = $languageName;
            $country->lang = LanguageEnum::fromName($languageName);
            $country->save();

            return [
                'success' => true,
                'message' => 'Country language updated successfully',
                'country' => $country
            ];

        } catch (\Exception $exception) {
            Log::error('Error updating country language: ' . $exception->getMessage());
            return [
                'success' => false,
                'message' => 'Error updating country language'
            ];
        }
    }

    /**
     * Get country by ID
     *
     * @param int $countryId Country ID
     * @return countrie|null
     */
    public function getCountryById(int $countryId): ?countrie
    {
        return countrie::find($countryId);
    }
}
