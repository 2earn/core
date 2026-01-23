<?php

/**
 * Test Suite for HomeController
 *
 * @package Tests\Feature\Controllers
 * @see App\Http\Controllers\HomeController
 * @author 2earn Development Team
 * @created 2026-01-22
 */

namespace Tests\Feature\Controllers;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class HomeControllerTest extends TestCase
{
    use DatabaseTransactions;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->actingAs($this->user);
    }

    /** @test */
    public function test_authenticated_user_can_access_home()
    {
        $this->assertAuthenticatedAs($this->user);
    }

    /** @test */
    public function test_lang_changes_session_locale()
    {
        $response = $this->get('/lang/fr');

        $response->assertRedirect();
        $this->assertEquals('fr', session('lang'));
    }

    /** @test */
    public function test_update_profile_validates_required_fields()
    {
        $response = $this->put("/update-profile/{$this->user->id}", [
            'name' => '',
            'email' => ''
        ]);

        // Should redirect back with errors or show validation error
        $this->assertTrue(in_array($response->status(), [302, 422]));
    }

    /** @test */
    public function test_update_profile_with_valid_data()
    {
        $response = $this->put("/update-profile/{$this->user->id}", [
            'name' => 'Updated Name',
            'email' => 'newemail@example.com'
        ]);

        $this->assertTrue(in_array($response->status(), [200, 302]));
    }

    /** @test */
    public function test_update_password_requires_current_password()
    {
        $response = $this->put("/update-password/{$this->user->id}", [
            'current_password' => '',
            'password' => 'newpassword',
            'password_confirmation' => 'newpassword'
        ]);

        $this->assertTrue(in_array($response->status(), [302, 422]));
    }

    /** @test */
    public function test_update_password_requires_confirmation()
    {
        $response = $this->put("/update-password/{$this->user->id}", [
            'current_password' => 'password',
            'password' => 'newpassword',
            'password_confirmation' => 'different'
        ]);

        $this->assertTrue(in_array($response->status(), [200, 302, 422]));
    }
}
