<?php
/**
 * Test Suite for SettingsController
 *
 * @package Tests\Feature\Controllers
 * @see App\Http\Controllers\SettingsController
 * @author 2earn Development Team
 * @created 2026-01-22
 */
namespace Tests\Feature\Controllers;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;
use App\Models\User;
use App\Models\Setting;
use Illuminate\Foundation\Testing\DatabaseTransactions;
class SettingsControllerTest extends TestCase
{
    use DatabaseTransactions;
    protected $user;
    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->actingAs($this->user);
    }
    #[Test]
    public function test_user_is_authenticated()
    {
        $this->assertAuthenticatedAs($this->user);
    }
    #[Test]
    public function test_controller_methods_exist()
    {
        $this->assertTrue(method_exists(\App\Http\Controllers\SettingsController::class, 'index'));
        $this->assertTrue(method_exists(\App\Http\Controllers\SettingsController::class, 'getAmounts'));
    }
    #[Test]
    public function test_user_factory_creates_valid_user()
    {
        $this->assertInstanceOf(User::class, $this->user);
        $this->assertNotNull($this->user->id);
    }
    #[Test]
    public function test_setting_model_exists()
    {
        $this->assertTrue(class_exists(Setting::class));
    }

}
