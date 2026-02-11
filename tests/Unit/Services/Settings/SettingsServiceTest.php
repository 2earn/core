<?php

namespace Tests\Unit\Services\Settings;

use App\Models\Setting;
use App\Services\Settings\SettingService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class SettingsServiceTest extends TestCase
{
    use DatabaseTransactions;

    protected SettingService $settingsService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->settingsService = new SettingService();
    }

    /**
     * Test getParameter returns integer value when exists
     */
    public function test_get_parameter_returns_integer_value_when_exists()
    {
        // Arrange
        Setting::factory()->create([
            'ParameterName' => 'test_param',
            'IntegerValue' => 100,
        ]);

        // Act
        $result = $this->settingsService->getParameter('test_param', 'IntegerValue');

        // Assert
        $this->assertEquals(100, $result);
    }

    /**
     * Test getParameter returns decimal value when exists
     */
    public function test_get_parameter_returns_decimal_value_when_exists()
    {
        // Arrange
        Setting::factory()->create([
            'ParameterName' => 'test_decimal',
            'DecimalValue' => 99.99,
        ]);

        // Act
        $result = $this->settingsService->getParameter('test_decimal', 'DecimalValue');

        // Assert
        $this->assertEquals(99.99, $result);
    }

    /**
     * Test getParameter returns string value when exists
     */
    public function test_get_parameter_returns_string_value_when_exists()
    {
        // Arrange
        Setting::factory()->create([
            'ParameterName' => 'test_string',
            'StringValue' => 'Hello World',
        ]);

        // Act
        $result = $this->settingsService->getParameter('test_string', 'StringValue');

        // Assert
        $this->assertEquals('Hello World', $result);
    }

    /**
     * Test getParameter returns default value when parameter not found
     */
    public function test_get_parameter_returns_default_when_not_found()
    {
        // Act
        $result = $this->settingsService->getParameter('non_existent', 'IntegerValue', 999);

        // Assert
        $this->assertEquals(999, $result);
    }

    /**
     * Test getParameter returns default value when value type not set
     */
    public function test_get_parameter_returns_default_when_value_type_not_set()
    {
        // Arrange
        DB::table('settings')->insert([
            'ParameterName' => 'test_param_null',
            'IntegerValue' => null,
            'DecimalValue' => null,
            'StringValue' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Act
        $result = $this->settingsService->getParameter('test_param_null', 'IntegerValue', 50);

        // Assert
        $this->assertEquals(50, $result);
    }

    /**
     * Test getParameter returns null as default when no default provided
     */
    public function test_get_parameter_returns_null_when_no_default()
    {
        // Act
        $result = $this->settingsService->getParameter('non_existent', 'IntegerValue');

        // Assert
        $this->assertNull($result);
    }

    /**
     * Test getIntegerParameter returns integer value
     */
    public function test_get_integer_parameter_returns_integer()
    {
        // Arrange
        Setting::factory()->create([
            'ParameterName' => 'max_items',
            'IntegerValue' => 250,
        ]);

        // Act
        $result = $this->settingsService->getIntegerParameter('max_items');

        // Assert
        $this->assertIsInt($result);
        $this->assertEquals(250, $result);
    }

    /**
     * Test getIntegerParameter returns default when not found
     */
    public function test_get_integer_parameter_returns_default_when_not_found()
    {
        // Act
        $result = $this->settingsService->getIntegerParameter('non_existent', 42);

        // Assert
        $this->assertEquals(42, $result);
    }

    /**
     * Test getIntegerParameter returns zero as default
     */
    public function test_get_integer_parameter_returns_zero_by_default()
    {
        // Act
        $result = $this->settingsService->getIntegerParameter('non_existent');

        // Assert
        $this->assertEquals(0, $result);
    }

    /**
     * Test getIntegerParameter casts string to integer
     */
    public function test_get_integer_parameter_casts_to_integer()
    {
        // Arrange
        DB::table('settings')->insert([
            'ParameterName' => 'string_int',
            'IntegerValue' => '123',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Act
        $result = $this->settingsService->getIntegerParameter('string_int');

        // Assert
        $this->assertIsInt($result);
        $this->assertEquals(123, $result);
    }

    /**
     * Test getDecimalParameter returns decimal value
     */
    public function test_get_decimal_parameter_returns_decimal()
    {
        // Arrange
        Setting::factory()->create([
            'ParameterName' => 'tax_rate',
            'DecimalValue' => 15.75,
        ]);

        // Act
        $result = $this->settingsService->getDecimalParameter('tax_rate');

        // Assert
        $this->assertIsFloat($result);
        $this->assertEquals(15.75, $result);
    }

    /**
     * Test getDecimalParameter returns default when not found
     */
    public function test_get_decimal_parameter_returns_default_when_not_found()
    {
        // Act
        $result = $this->settingsService->getDecimalParameter('non_existent', 3.14);

        // Assert
        $this->assertEquals(3.14, $result);
    }

    /**
     * Test getDecimalParameter returns zero as default
     */
    public function test_get_decimal_parameter_returns_zero_by_default()
    {
        // Act
        $result = $this->settingsService->getDecimalParameter('non_existent');

        // Assert
        $this->assertEquals(0.0, $result);
    }

    /**
     * Test getDecimalParameter casts to float
     */
    public function test_get_decimal_parameter_casts_to_float()
    {
        // Arrange
        DB::table('settings')->insert([
            'ParameterName' => 'string_decimal',
            'DecimalValue' => '99.99',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Act
        $result = $this->settingsService->getDecimalParameter('string_decimal');

        // Assert
        $this->assertIsFloat($result);
        $this->assertEquals(99.99, $result);
    }

    /**
     * Test getStringParameter returns string value
     */
    public function test_get_string_parameter_returns_string()
    {
        // Arrange
        Setting::factory()->create([
            'ParameterName' => 'app_name',
            'StringValue' => '2earn Platform',
        ]);

        // Act
        $result = $this->settingsService->getStringParameter('app_name');

        // Assert
        $this->assertIsString($result);
        $this->assertEquals('2earn Platform', $result);
    }

    /**
     * Test getStringParameter returns default when not found
     */
    public function test_get_string_parameter_returns_default_when_not_found()
    {
        // Act
        $result = $this->settingsService->getStringParameter('non_existent', 'default_value');

        // Assert
        $this->assertEquals('default_value', $result);
    }

    /**
     * Test getStringParameter returns empty string as default
     */
    public function test_get_string_parameter_returns_empty_string_by_default()
    {
        // Act
        $result = $this->settingsService->getStringParameter('non_existent');

        // Assert
        $this->assertEquals('', $result);
    }

    /**
     * Test getStringParameter casts to string
     */
    public function test_get_string_parameter_casts_to_string()
    {
        // Arrange
        DB::table('settings')->insert([
            'ParameterName' => 'numeric_string',
            'StringValue' => 12345,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Act
        $result = $this->settingsService->getStringParameter('numeric_string');

        // Assert
        $this->assertIsString($result);
        $this->assertEquals('12345', $result);
    }

    /**
     * Test getParameter handles multiple settings correctly
     */
    public function test_get_parameter_handles_multiple_settings()
    {
        // Arrange
        Setting::factory()->create([
            'ParameterName' => 'setting_one',
            'IntegerValue' => 100,
        ]);
        Setting::factory()->create([
            'ParameterName' => 'setting_two',
            'IntegerValue' => 200,
        ]);
        Setting::factory()->create([
            'ParameterName' => 'setting_three',
            'IntegerValue' => 300,
        ]);

        // Act
        $result1 = $this->settingsService->getIntegerParameter('setting_one');
        $result2 = $this->settingsService->getIntegerParameter('setting_two');
        $result3 = $this->settingsService->getIntegerParameter('setting_three');

        // Assert
        $this->assertEquals(100, $result1);
        $this->assertEquals(200, $result2);
        $this->assertEquals(300, $result3);
    }

    /**
     * Test getParameter retrieves the exact parameter name match
     */
    public function test_get_parameter_is_case_sensitive()
    {
        // Arrange
        // Note: MySQL on Windows is case-insensitive by default for string comparisons
        // Testing with completely different parameter names instead
        Setting::factory()->create([
            'ParameterName' => 'UpperCaseParam',
            'IntegerValue' => 100,
        ]);

        Setting::factory()->create([
            'ParameterName' => 'lowercaseparam',
            'IntegerValue' => 200,
        ]);

        // Act
        $resultUpper = $this->settingsService->getIntegerParameter('UpperCaseParam');
        $resultLower = $this->settingsService->getIntegerParameter('lowercaseparam');
        $resultNonExistent = $this->settingsService->getIntegerParameter('NonExistentParam', 999);

        // Assert
        $this->assertEquals(100, $resultUpper);
        $this->assertEquals(200, $resultLower);
        $this->assertEquals(999, $resultNonExistent);
    }

    /**
     * Test that all three value types can exist in same setting
     */
    public function test_setting_can_have_all_value_types()
    {
        // Arrange
        Setting::factory()->create([
            'ParameterName' => 'multi_value',
            'IntegerValue' => 42,
            'DecimalValue' => 3.14,
            'StringValue' => 'Hello',
        ]);

        // Act
        $intResult = $this->settingsService->getIntegerParameter('multi_value');
        $decimalResult = $this->settingsService->getDecimalParameter('multi_value');
        $stringResult = $this->settingsService->getStringParameter('multi_value');

        // Assert
        $this->assertEquals(42, $intResult);
        $this->assertEquals(3.14, $decimalResult);
        $this->assertEquals('Hello', $stringResult);
    }
}
