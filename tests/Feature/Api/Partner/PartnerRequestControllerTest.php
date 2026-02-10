<?php

namespace Tests\Feature\Api\Partner;

use Tests\TestCase;
use App\Models\User;
use App\Models\PartnerRequest;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use PHPUnit\Framework\Attributes\Group;

#[Group('api')]
class PartnerRequestControllerTest extends TestCase
{
    use DatabaseTransactions;

    protected $user;
    protected $baseUrl = '/api/partner/partner-requests';

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->withServerVariables(['REMOTE_ADDR' => '127.0.0.1']);
    }

    public function test_can_list_partner_requests()
    {
        PartnerRequest::factory()->count(5)->create(['user_id' => $this->user->id]);
        $response = $this->getJson($this->baseUrl . '?user_id=' . $this->user->id);
        $response->assertStatus(200)->assertJsonStructure(['status', 'data']);
    }

    public function test_can_show_single_partner_request()
    {
        $request = PartnerRequest::factory()->create(['user_id' => $this->user->id]);
        $response = $this->getJson($this->baseUrl . '/' . $request->id . '?user_id=' . $this->user->id);
        $response->assertStatus(200)->assertJsonStructure(['status', 'data']);
    }

    public function test_can_create_partner_request()
    {
        $data = [
            'user_id' => $this->user->id,
            'company_name' => 'Test Business',
            'business_sector_id' => 1,
            'platform_description' => 'Test description'
        ];
        $response = $this->postJson($this->baseUrl, $data);
        $response->assertStatus(201)->assertJsonStructure(['status', 'message', 'data']);
    }

    public function test_can_update_partner_request()
    {
        $request = PartnerRequest::factory()->create(['user_id' => $this->user->id]);
        $data = [
            'company_name' => 'Updated Business',
            'status' => 1, // Use integer for status
            'user_id' => $this->user->id
        ];
        $response = $this->putJson($this->baseUrl . '/' . $request->id, $data);
        $response->assertStatus(200)->assertJsonStructure(['status', 'message', 'data']);
    }

    public function test_fails_without_valid_ip()
    {
        $this->withServerVariables(['REMOTE_ADDR' => '192.168.1.1']);
        $response = $this->getJson($this->baseUrl . '?user_id=' . $this->user->id);
        $response->assertStatus(403)->assertJson(['error' => 'Unauthorized. Invalid IP.']);
    }
}
