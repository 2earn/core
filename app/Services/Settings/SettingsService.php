<?php

namespace App\Services\Settings;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SettingsService
{
    /**
     * Get a setting parameter value by name
     *
     * @param string $parameterName
     * @param string $valueType 'IntegerValue', 'DecimalValue', 'StringValue'
     * @param mixed $defaultValue
     * @return mixed
     */
    public function getParameter(string $parameterName, string $valueType = 'IntegerValue', $defaultValue = null)
    {
        try {
            $param = DB::table('settings')
                ->where('ParameterName', '=', $parameterName)
                ->first();

            if (!is_null($param) && isset($param->{$valueType})) {
                return $param->{$valueType};
            }

            return $defaultValue;
        } catch (\Exception $e) {
            Log::error('Error fetching setting parameter: ' . $e->getMessage());
            return $defaultValue;
        }
    }

    /**
     * Get an integer setting parameter
     *
     * @param string $parameterName
     * @param int $defaultValue
     * @return int
     */
    public function getIntegerParameter(string $parameterName, int $defaultValue = 0): int
    {
        return (int) $this->getParameter($parameterName, 'IntegerValue', $defaultValue);
    }

    /**
     * Get a decimal setting parameter
     *
     * @param string $parameterName
     * @param float $defaultValue
     * @return float
     */
    public function getDecimalParameter(string $parameterName, float $defaultValue = 0.0): float
    {
        return (float) $this->getParameter($parameterName, 'DecimalValue', $defaultValue);
    }

    /**
     * Get a string setting parameter
     *
     * @param string $parameterName
     * @param string $defaultValue
     * @return string
     */
    public function getStringParameter(string $parameterName, string $defaultValue = ''): string
    {
        return (string) $this->getParameter($parameterName, 'StringValue', $defaultValue);
    }
}

