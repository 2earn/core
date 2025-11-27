<?php

namespace App\Services\Settings;

use Core\Models\Setting;

/**
 * Thin service wrapper around the Setting model to keep callers decoupled
 * from query details.
 */
class SettingService
{
    /**
     * Fetch integer values keyed by setting id.
     *
     * @param array<int|string> $settingIds
     * @return array<int|string,int|null>
     */
    public function getIntegerValues(array $settingIds): array
    {
        return Setting::whereIn('idSETTINGS', $settingIds)
            ->pluck('IntegerValue', 'idSETTINGS')
            ->map(fn ($value) => $value !== null ? (int)$value : null)
            ->toArray();
    }

    /**
     * Fetch decimal values keyed by setting id.
     *
     * @param array<int|string> $settingIds
     * @return array<int|string,float|null>
     */
    public function getDecimalValues(array $settingIds): array
    {
        return Setting::whereIn('idSETTINGS', $settingIds)
            ->pluck('DecimalValue', 'idSETTINGS')
            ->map(fn ($value) => $value !== null ? (float)$value : null)
            ->toArray();
    }

    public function getIntegerValue(int|string $settingId): ?int
    {
        $values = $this->getIntegerValues([$settingId]);

        return $values[$settingId] ?? null;
    }

    public function getDecimalValue(int|string $settingId): ?float
    {
        $values = $this->getDecimalValues([$settingId]);

        return $values[$settingId] ?? null;
    }

    public function getSettingByParameterName(string $parameterName): ?Setting
    {
        return Setting::where('ParameterName', $parameterName)->first();
    }

    public function getIntegerByParameterName(string $parameterName): ?int
    {
        $setting = $this->getSettingByParameterName($parameterName);
        return $setting?->IntegerValue !== null ? (int) $setting->IntegerValue : null;
    }

    public function getDecimalByParameterName(string $parameterName): ?float
    {
        $setting = $this->getSettingByParameterName($parameterName);
        return $setting?->DecimalValue !== null ? (float) $setting->DecimalValue : null;
    }

    public function getStringByParameterName(string $parameterName): ?string
    {
        $setting = $this->getSettingByParameterName($parameterName);
        return $setting?->StringValue;
    }

    /**
     * Get a single setting by ID
     *
     * @param int|string $settingId
     * @return Setting|null
     */
    public function getById(int|string $settingId): ?Setting
    {
        return Setting::where('idSETTINGS', $settingId)->first();
    }

    /**
     * Get multiple settings by IDs
     *
     * @param array<int|string> $settingIds
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getByIds(array $settingIds)
    {
        return Setting::whereIn('idSETTINGS', $settingIds)->orderBy('idSETTINGS')->get();
    }
}
