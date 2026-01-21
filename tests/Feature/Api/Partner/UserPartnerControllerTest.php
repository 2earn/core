<?php

namespace Tests\Feature\Api\Partner;

use Tests\TestCase;
use App\Models\User;
use App\Models\Platform;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UserPartnerControllerTest extends TestCase
{
    use DatabaseTransactions;

    protected $user;
    protected $platform;
    protected $baseUrl = '/api/partner';

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->platform = Platform::factory()->create(['created_by' => $this->user->id]);
        $this->withServerVariables(['REMOTE_ADDR' => '127.0.0.1']);
    }

    public function test_can_add_role_to_user()
    {
        $data = [
            'user_id' => $this->user->id,
            'platform_id' => $this->platform->id,
            'role' => 'admin'
        ];
        $response = $this->postJson($this->baseUrl . '/users/add-role', $data);
        $response->assertStatus(200)->assertJsonStructure(['status', 'message']);
    }

    public function test_can_get_partner_platforms()
    {
        Platform::factory()->count(3)->create(['created_by' => $this->user->id]);
        $response = $this->getJson($this->baseUrl . '/users/platforms?user_id=' . $this->user->id);
        $response->assertStatus(200)->assertJsonStructure(['status', 'data']);
    }

    public function test_add_role_fails_with_invalid_data()
    {
        $data = ['user_id' => $this->user->id];
        $response = $this->postJson($this->baseUrl . '/users/add-role', $data);
        $response->assertStatus(422);
    }

    public function test_get_platforms_fails_without_user_id()
    {
        $response = $this->getJson($this->baseUrl . '/users/platforms');
        $response->assertStatus(422);
    }

    public function test_fails_without_valid_ip()
    {
        $this->withServerVariables(['REMOTE_ADDR' => '192.168.1.1']);
        $response = $this->getJson($this->baseUrl . '/users/platforms?user_id=' . $this->user->id);
        $response->assertStatus(403)->assertJson(['error' => 'Unauthorized. Invalid IP.']);
    }
}
