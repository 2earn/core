<?php

namespace Tests\Unit\Services\Settings;

use App\Models\Setting;
use App\Services\Settings\SettingService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class SettingServiceTest extends TestCase
{
    use DatabaseTransactions;

    protected SettingService $settingService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->settingService = new SettingService();
    }

    /**
     * Test getIntegerValues method returns integer values for multiple settings
     */
    public function test_get_integer_values_works()
    {
        // Arrange
        $setting1 = Setting::factory()->create(['IntegerValue' => 100]);
        $setting2 = Setting::factory()->create(['IntegerValue' => 200]);
        $setting3 = Setting::factory()->create(['IntegerValue' => null]);

        // Act
        $result = $this->settingService->getIntegerValues([
            $setting1->idSETTINGS,
            $setting2->idSETTINGS,
            $setting3->idSETTINGS
        ]);

        // Assert
        $this->assertIsArray($result);
        $this->assertEquals(100, $result[$setting1->idSETTINGS]);
        $this->assertEquals(200, $result[$setting2->idSETTINGS]);
        $this->assertNull($result[$setting3->idSETTINGS]);
    }

    /**
     * Test getDecimalValues method returns decimal values for multiple settings
     */
    public function test_get_decimal_values_works()
    {
        // Arrange
        $setting1 = Setting::factory()->create(['DecimalValue' => 10.50]);
        $setting2 = Setting::factory()->create(['DecimalValue' => 20.75]);
        $setting3 = Setting::factory()->create(['DecimalValue' => null]);

        // Act
        $result = $this->settingService->getDecimalValues([
            $setting1->idSETTINGS,
            $setting2->idSETTINGS,
            $setting3->idSETTINGS
        ]);

        // Assert
        $this->assertIsArray($result);
        $this->assertEquals(10.50, $result[$setting1->idSETTINGS]);
        $this->assertEquals(20.75, $result[$setting2->idSETTINGS]);
        $this->assertNull($result[$setting3->idSETTINGS]);
    }

    /**
     * Test getIntegerValue method returns single integer value
     */
    public function test_get_integer_value_works()
    {
        // Arrange
        $setting = Setting::factory()->create(['IntegerValue' => 150]);

        // Act
        $result = $this->settingService->getIntegerValue($setting->idSETTINGS);

        // Assert
        $this->assertEquals(150, $result);
    }

    /**
     * Test getIntegerValue returns null when not found
     */
    public function test_get_integer_value_returns_null_when_not_found()
    {
        // Act
        $result = $this->settingService->getIntegerValue(99999);

        // Assert
        $this->assertNull($result);
    }

    /**
     * Test getDecimalValue method returns single decimal value
     */
    public function test_get_decimal_value_works()
    {
        // Arrange
        $setting = Setting::factory()->create(['DecimalValue' => 99.99]);

        // Act
        $result = $this->settingService->getDecimalValue($setting->idSETTINGS);

        // Assert
        $this->assertEquals(99.99, $result);
    }

    /**
     * Test getDecimalValue returns null when not found
     */
    public function test_get_decimal_value_returns_null_when_not_found()
    {
        // Act
        $result = $this->settingService->getDecimalValue(99999);

        // Assert
        $this->assertNull($result);
    }

    /**
     * Test getSettingByParameterName method returns setting by name
     */
    public function test_get_setting_by_parameter_name_works()
    {
        // Arrange
        $paramName = 'TEST_PARAM_' . uniqid();
        $setting = Setting::factory()->create(['ParameterName' => $paramName]);

        // Act
        $result = $this->settingService->getSettingByParameterName($paramName);

        // Assert
        $this->assertNotNull($result);
        $this->assertInstanceOf(Setting::class, $result);
        $this->assertEquals($paramName, $result->ParameterName);
    }

    /**
     * Test getSettingByParameterName returns null when not found
     */
    public function test_get_setting_by_parameter_name_returns_null_when_not_found()
    {
        // Act
        $result = $this->settingService->getSettingByParameterName('NON_EXISTENT');

        // Assert
        $this->assertNull($result);
    }

    /**
     * Test getIntegerByParameterName method works
     */
    public function test_get_integer_by_parameter_name_works()
    {
        // Arrange
        $paramName = 'MAX_USERS_' . uniqid();
        Setting::factory()->create([
            'ParameterName' => $paramName,
            'IntegerValue' => 1000
        ]);

        // Act
        $result = $this->settingService->getIntegerByParameterName($paramName);

        // Assert
        $this->assertEquals(1000, $result);
    }

    /**
     * Test getIntegerByParameterName returns null when not found
     */
    public function test_get_integer_by_parameter_name_returns_null_when_not_found()
    {
        // Act
        $result = $this->settingService->getIntegerByParameterName('NON_EXISTENT');

        // Assert
        $this->assertNull($result);
    }

    /**
     * Test getDecimalByParameterName method works
     */
    public function test_get_decimal_by_parameter_name_works()
    {
        // Arrange
        $paramName = 'TAX_RATE_' . uniqid();
        $expectedValue = 15.5;
        Setting::factory()->create([
            'ParameterName' => $paramName,
            'DecimalValue' => $expectedValue
        ]);

        // Act
        $result = $this->settingService->getDecimalByParameterName($paramName);

        // Assert
        $this->assertEquals($expectedValue, $result);
    }

    /**
     * Test getDecimalByParameterName returns null when not found
     */
    public function test_get_decimal_by_parameter_name_returns_null_when_not_found()
    {
        // Act
        $result = $this->settingService->getDecimalByParameterName('NON_EXISTENT');

        // Assert
        $this->assertNull($result);
    }

    /**
     * Test getStringByParameterName method works
     */
    public function test_get_string_by_parameter_name_works()
    {
        // Arrange
        $paramName = 'APP_NAME_' . uniqid();
        $expectedValue = 'My Application';
        Setting::factory()->create([
            'ParameterName' => $paramName,
            'StringValue' => $expectedValue
        ]);

        // Act
        $result = $this->settingService->getStringByParameterName($paramName);

        // Assert
        $this->assertEquals($expectedValue, $result);
    }

    /**
     * Test getStringByParameterName returns null when not found
     */
    public function test_get_string_by_parameter_name_returns_null_when_not_found()
    {
        // Act
        $result = $this->settingService->getStringByParameterName('NON_EXISTENT');

        // Assert
        $this->assertNull($result);
    }

    /**
     * Test getById method returns setting by ID
     */
    public function test_get_by_id_works()
    {
        // Arrange
        $setting = Setting::factory()->create();

        // Act
        $result = $this->settingService->getById($setting->idSETTINGS);

        // Assert
        $this->assertNotNull($result);
        $this->assertInstanceOf(Setting::class, $result);
        $this->assertEquals($setting->idSETTINGS, $result->idSETTINGS);
    }

    /**
     * Test getById returns null when not found
     */
    public function test_get_by_id_returns_null_when_not_found()
    {
        // Act
        $result = $this->settingService->getById(99999);

        // Assert
        $this->assertNull($result);
    }

    /**
     * Test getByIds method returns multiple settings
     */
    public function test_get_by_ids_works()
    {
        // Arrange
        $setting1 = Setting::factory()->create();
        $setting2 = Setting::factory()->create();
        $setting3 = Setting::factory()->create();

        // Act
        $result = $this->settingService->getByIds([
            $setting1->idSETTINGS,
            $setting2->idSETTINGS,
            $setting3->idSETTINGS
        ]);

        // Assert
        $this->assertCount(3, $result);
        $this->assertEquals($setting1->idSETTINGS, $result[0]->idSETTINGS);
    }

    /**
     * Test updateByParameterName method updates setting
     */
    public function test_update_by_parameter_name_works()
    {
        // Arrange
        $paramName = 'UPDATE_TEST_' . uniqid();
        $setting = Setting::factory()->create([
            'ParameterName' => $paramName,
            'IntegerValue' => 100
        ]);

        // Act
        $result = $this->settingService->updateByParameterName($paramName, [
            'IntegerValue' => 200
        ]);

        // Assert
        $this->assertEquals(1, $result);
        $setting->refresh();
        $this->assertEquals(200, $setting->IntegerValue);
    }

    /**
     * Test updateByParameterName returns 0 when not found
     */
    public function test_update_by_parameter_name_returns_zero_when_not_found()
    {
        // Act
        $result = $this->settingService->updateByParameterName('NON_EXISTENT', [
            'IntegerValue' => 200
        ]);

        // Assert
        $this->assertEquals(0, $result);
    }

    /**
     * Test updateIntegerByParameterName method works
     */
    public function test_update_integer_by_parameter_name_works()
    {
        // Arrange
        $paramName = 'INT_UPDATE_' . uniqid();
        $setting = Setting::factory()->create([
            'ParameterName' => $paramName,
            'IntegerValue' => 50
        ]);

        // Act
        $result = $this->settingService->updateIntegerByParameterName($paramName, 150);

        // Assert
        $this->assertEquals(1, $result);
        $setting->refresh();
        $this->assertEquals(150, $setting->IntegerValue);
    }

    /**
     * Test updateDecimalByParameterName method works
     */
    public function test_update_decimal_by_parameter_name_works()
    {
        // Arrange
        $paramName = 'DEC_UPDATE_' . uniqid();
        $setting = Setting::factory()->create([
            'ParameterName' => $paramName,
            'DecimalValue' => 10.5
        ]);

        // Act
        $result = $this->settingService->updateDecimalByParameterName($paramName, 25.75);

        // Assert
        $this->assertEquals(1, $result);
        $setting->refresh();
        $this->assertEquals(25.75, $setting->DecimalValue);
    }

    /**
     * Test updateStringByParameterName method works
     */
    public function test_update_string_by_parameter_name_works()
    {
        // Arrange
        $paramName = 'STR_UPDATE_' . uniqid();
        $setting = Setting::factory()->create([
            'ParameterName' => $paramName,
            'StringValue' => 'Old Value'
        ]);

        // Act
        $result = $this->settingService->updateStringByParameterName($paramName, 'New Value');

        // Assert
        $this->assertEquals(1, $result);
        $setting->refresh();
        $this->assertEquals('New Value', $setting->StringValue);
    }

    /**
     * Test getPaginatedSettings method returns paginated results
     */
    public function test_get_paginated_settings_works()
    {
        // Arrange
        $countBefore = Setting::count();
        Setting::factory()->count(15)->create();

        // Act
        $result = $this->settingService->getPaginatedSettings(null, 'idSETTINGS', 'desc', 10);

        // Assert
        $this->assertNotNull($result);
        $this->assertGreaterThanOrEqual($countBefore + 15, $result->total());
        $this->assertEquals(10, $result->perPage());
        $this->assertCount(10, $result->items());
    }

    /**
     * Test getPaginatedSettings with search filter
     */
    public function test_get_paginated_settings_with_search_works()
    {
        // Arrange
        Setting::factory()->create(['ParameterName' => 'UNIQUE_SEARCH_PARAM']);
        Setting::factory()->count(5)->create();

        // Act
        $result = $this->settingService->getPaginatedSettings('UNIQUE_SEARCH_PARAM');

        // Assert
        $this->assertNotNull($result);
        $this->assertGreaterThanOrEqual(1, $result->total());
    }

    /**
     * Test getPaginatedSettings with sorting
     */
    public function test_get_paginated_settings_with_sorting_works()
    {
        // Arrange
        Setting::factory()->count(5)->create();

        // Act
        $result = $this->settingService->getPaginatedSettings(null, 'ParameterName', 'asc');

        // Assert
        $this->assertNotNull($result);
        $this->assertGreaterThanOrEqual(5, $result->total());
    }

    /**
     * Test updateSetting method updates a setting
     */
    public function test_update_setting_works()
    {
        // Arrange
        $setting = Setting::factory()->create([
            'IntegerValue' => 100,
            'StringValue' => 'Original',
            'DecimalValue' => 10.5
        ]);

        // Act
        $result = $this->settingService->updateSetting($setting->idSETTINGS, [
            'IntegerValue' => 200,
            'StringValue' => 'Updated',
            'DecimalValue' => 20.75
        ]);

        // Assert
        $this->assertTrue($result);
        $setting->refresh();
        $this->assertEquals(200, $setting->IntegerValue);
        $this->assertEquals('Updated', $setting->StringValue);
        $this->assertEquals(20.75, $setting->DecimalValue);
    }

    /**
     * Test updateSetting returns false when setting not found
     */
    public function test_update_setting_returns_false_when_not_found()
    {
        // Act
        $result = $this->settingService->updateSetting(99999, [
            'IntegerValue' => 200
        ]);

        // Assert
        $this->assertFalse($result);
    }

    /**
     * Test updateSetting with partial data
     */
    public function test_update_setting_with_partial_data_works()
    {
        // Arrange
        $setting = Setting::factory()->create([
            'IntegerValue' => 100,
            'StringValue' => 'Original'
        ]);

        // Act
        $result = $this->settingService->updateSetting($setting->idSETTINGS, [
            'IntegerValue' => 300
        ]);

        // Assert
        $this->assertTrue($result);
        $setting->refresh();
        $this->assertEquals(300, $setting->IntegerValue);
        $this->assertEquals('Original', $setting->StringValue);
    }
}
