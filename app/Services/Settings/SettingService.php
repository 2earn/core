<?php

namespace App\Services\Settings;

use Core\Models\Setting;

class SettingService
{
    public function getIntegerValues(array $settingIds): array
    {
        return Setting::whereIn('idSETTINGS', $settingIds)
            ->pluck('IntegerValue', 'idSETTINGS')
            ->map(fn ($value) => $value !== null ? (int)$value : null)
            ->toArray();
    }

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

    public function getById(int|string $settingId): ?Setting
    {
        return Setting::where('idSETTINGS', $settingId)->first();
    }

    public function getByIds(array $settingIds)
    {
        return Setting::whereIn('idSETTINGS', $settingIds)->orderBy('idSETTINGS')->get();
    }

    /**
     * Update setting by parameter name
     *
     * @param string $parameterName
     * @param array $data Array of field values to update (e.g., ['IntegerValue' => 100])
     * @return int Number of rows updated
     */
    public function updateByParameterName(string $parameterName, array $data): int
    {
        return Setting::where('ParameterName', $parameterName)->update($data);
    }

    /**
     * Update integer value by parameter name
     *
     * @param string $parameterName
     * @param int|string $value
     * @return int Number of rows updated
     */
    public function updateIntegerByParameterName(string $parameterName, int|string $value): int
    {
        return $this->updateByParameterName($parameterName, ['IntegerValue' => $value]);
    }

    /**
     * Update decimal value by parameter name
     *
     * @param string $parameterName
     * @param float|string $value
     * @return int Number of rows updated
     */
    public function updateDecimalByParameterName(string $parameterName, float|string $value): int
    {
        return $this->updateByParameterName($parameterName, ['DecimalValue' => $value]);
    }

    /**
     * Update string value by parameter name
     *
     * @param string $parameterName
     * @param string $value
     * @return int Number of rows updated
     */
    public function updateStringByParameterName(string $parameterName, string $value): int
    {
        return $this->updateByParameterName($parameterName, ['StringValue' => $value]);
    }
}
