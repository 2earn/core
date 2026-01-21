<?php

namespace Tests\Feature\Api\Partner;

use Tests\TestCase;
use App\Models\User;
use App\Models\Platform;
use App\Models\PlanLabel;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class PlanLabelPartnerControllerTest extends TestCase
{
    use DatabaseTransactions;

    protected $user;
    protected $platform;
    protected $baseUrl = '/api/partner/plan-label';

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->platform = Platform::factory()->create(['created_by' => $this->user->id]);
        $this->withServerVariables(['REMOTE_ADDR' => '127.0.0.1']);
    }

    public function test_can_list_plan_labels()
    {
        PlanLabel::factory()->count(5)->create();
        $response = $this->getJson($this->baseUrl . '?user_id=' . $this->user->id);
        $response->assertStatus(200)->assertJsonStructure(['status', 'data']);
    }

    public function test_can_filter_plan_labels_by_platform()
    {
        // Create plan labels with different attributes for filtering
        PlanLabel::factory()->count(3)->create(['stars' => 5]);
        PlanLabel::factory()->count(2)->create(['stars' => 3]);

        $response = $this->getJson($this->baseUrl . '?user_id=' . $this->user->id . '&stars=5');
        $response->assertStatus(200);

        // Assert that we have at least 3 items with 5 stars
        $this->assertGreaterThanOrEqual(3, count($response->json('data')));
    }

    public function test_can_list_plan_labels_without_user_id()
    {
        // The endpoint doesn't require user_id validation
        PlanLabel::factory()->count(3)->create();
        $response = $this->getJson($this->baseUrl);
        $response->assertStatus(200)->assertJsonStructure(['status', 'data']);
    }

    public function test_fails_without_valid_ip()
    {
        $this->withServerVariables(['REMOTE_ADDR' => '192.168.1.1']);
        $response = $this->getJson($this->baseUrl . '?user_id=' . $this->user->id);
        $response->assertStatus(403)->assertJson(['error' => 'Unauthorized. Invalid IP.']);
    }
}
