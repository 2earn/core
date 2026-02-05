<?php

namespace Tests\Feature\Api\Partner;

use Tests\TestCase;
use App\Models\User;
use App\Models\Deal;
use App\Models\Platform;
use App\Models\EntityRole;
use Illuminate\Foundation\Testing\DatabaseTransactions;

/**
 * @group feature
 * @group api
 * @group controller
 */
class DealPartnerControllerTest extends TestCase
{
    use DatabaseTransactions;

    protected $user;
    protected $platform;
    protected $baseUrl = '/api/partner/deals';

    protected function setUp(): void
    {
        parent::setUp();

        // Create a test user
        $this->user = User::factory()->create([
            'name' => 'Test Partner User',
            'email' => 'partner@test.com',
        ]);

        // Create a platform for the user
        $this->platform = Platform::factory()->create([
            'created_by' => $this->user->id,
            'enabled' => true
        ]);

        // Create EntityRole to link user to platform
        EntityRole::create([
            'user_id' => $this->user->id,
            'roleable_type' => Platform::class,
            'roleable_id' => $this->platform->id,
            'name' => 'owner'
        ]);

        // Mock the check.url middleware by setting a valid IP
        $this->withServerVariables(['REMOTE_ADDR' => '127.0.0.1']);
    }

    /**
     * Test: GET /api/partner/deals/deals - List all deals for partner
     */
    public function test_can_list_deals_for_partner()
    {
        // Arrange
        Deal::factory()->count(5)->create([
            'platform_id' => $this->platform->id,
            'validated' => true
        ]);

        // Act
        $response = $this->getJson($this->baseUrl . '/deals?user_id=' . $this->user->id);

        // Assert
        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'status',
                     'data',
                 ]);
    }

    /**
     * Test: GET /api/partner/deals/deals/{id} - Show single deal
     */
    public function test_can_show_single_deal()
    {
        // Arrange
        $deal = Deal::factory()->create([
            'platform_id' => $this->platform->id,
            'validated' => true,
            'name' => 'Test Deal'
        ]);

        // Act
        $response = $this->getJson($this->baseUrl . '/deals/' . $deal->id . '?user_id=' . $this->user->id);

        // Assert
        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'status',
                     'data'
                 ]);
    }

    /**
     * Test: POST /api/partner/deals/deals - Create deal successfully
     */
    public function test_can_create_deal_successfully()
    {
        // Arrange
        $dealData = [
            'platform_id' => $this->platform->id,
            'name' => 'New Test Deal',
            'description' => 'Test deal description',
            'initial_commission' => 5.0,
            'final_commission' => 10.0,
            'type' => '0', // DealTypeEnum::public
            'status' => '1', // DealStatus::New
            'start_date' => now()->format('Y-m-d H:i:s'),
            'end_date' => now()->addDays(30)->format('Y-m-d H:i:s'),
            'created_by' => $this->user->id,
            'user_id' => $this->user->id
        ];

        // Act
        $response = $this->postJson($this->baseUrl . '/deals', $dealData);

        // Assert
        $response->assertStatus(201)
                 ->assertJsonStructure([
                     'status',
                     'message',
                     'data'
                 ]);
    }

    /**
     * Test: PUT /api/partner/deals/deals/{id} - Update deal
     */
    public function test_can_update_deal()
    {
        // Arrange
        $deal = Deal::factory()->create([
            'platform_id' => $this->platform->id,
            'name' => 'Original Title'
        ]);

        $updateData = [
            'name' => 'Updated Title',
            'description' => 'Updated description',
            'requested_by' => $this->user->id,
            'user_id' => $this->user->id
        ];

        // Act
        $response = $this->putJson($this->baseUrl . '/deals/' . $deal->id, $updateData);

        // Assert
        $response->assertStatus(201)
                 ->assertJsonStructure([
                     'status',
                     'message',
                     'data'
                 ]);
    }

    /**
     * Test: PATCH /api/partner/deals/{deal}/status - Change deal status
     */
    public function test_can_change_deal_status()
    {
        // Arrange
        $deal = Deal::factory()->create([
            'platform_id' => $this->platform->id,
            'validated' => true
        ]);

        $statusData = [
            'status' => 0,
            'user_id' => $this->user->id
        ];

        // Act
        $response = $this->patchJson($this->baseUrl . '/' . $deal->id . '/status', $statusData);

        // Assert
        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'status',
                     'message'
                 ]);
    }

    /**
     * Test: POST /api/partner/deals/validate - Validate deal request
     */
    public function test_can_validate_deal_request()
    {
        // Arrange
        $deal = Deal::factory()->create([
            'platform_id' => $this->platform->id,
            'validated' => false
        ]);

        $validationData = [
            'deal_id' => $deal->id,
            'user_id' => $this->user->id
        ];

        // Act
        $response = $this->postJson($this->baseUrl . '/validate', $validationData);

        // Assert
        $response->assertStatus(201)
                 ->assertJsonStructure([
                     'status',
                     'message'
                 ]);
    }

    /**
     * Test: POST /api/partner/deals/validation/cancel - Cancel validation request
     */
    public function test_can_cancel_validation_request()
    {
        // Arrange
        $deal = Deal::factory()->create([
            'platform_id' => $this->platform->id
        ]);

        // Create a validation request first
        $validationRequest = \App\Models\DealValidationRequest::create([
            'deal_id' => $deal->id,
            'requested_by_id' => $this->user->id,
            'status' => 'pending',
            'notes' => 'Test validation request'
        ]);

        $cancelData = [
            'validation_request_id' => $validationRequest->id,
            'user_id' => $this->user->id
        ];

        // Act
        $response = $this->postJson($this->baseUrl . '/validation/cancel', $cancelData);

        // Assert
        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'status',
                     'message'
                 ]);
    }

    /**
     * Test: POST /api/partner/deals/change/cancel - Cancel change request
     */
    public function test_can_cancel_change_request()
    {
        // Arrange
        $deal = Deal::factory()->create([
            'platform_id' => $this->platform->id
        ]);

        // Create a change request first
        $changeRequest = \App\Models\DealChangeRequest::create([
            'deal_id' => $deal->id,
            'requested_by' => $this->user->id,
            'status' => 'pending',
            'changes' => json_encode(['name' => 'Updated Deal Name'])
        ]);

        $cancelData = [
            'change_request_id' => $changeRequest->id,
            'user_id' => $this->user->id
        ];

        // Act
        $response = $this->postJson($this->baseUrl . '/change/cancel', $cancelData);

        // Assert
        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'status',
                     'message'
                 ]);
    }

    /**
     * Test: GET /api/partner/deals/dashboard/indicators - Get dashboard indicators
     */
    public function test_can_get_dashboard_indicators()
    {
        // Arrange
        Deal::factory()->count(5)->create([
            'platform_id' => $this->platform->id
        ]);

        // Act
        $response = $this->getJson($this->baseUrl . '/dashboard/indicators?user_id=' . $this->user->id);

        // Assert
        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'status',
                     'data'
                 ]);
    }

    /**
     * Test: GET /api/partner/deals/performance/chart - Get performance chart
     */
    public function test_can_get_performance_chart()
    {
        // Arrange
        $deal = Deal::factory()->create([
            'platform_id' => $this->platform->id
        ]);

        // Act
        $response = $this->getJson($this->baseUrl . '/performance/chart?user_id=' . $this->user->id . '&deal_id=' . $deal->id);

        // Assert
        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'status',
                     'data'
                 ]);
    }

    /**
     * Test: GET /api/partner/deals/deals - With pagination
     */
    public function test_can_list_deals_with_pagination()
    {
        // Arrange
        Deal::factory()->count(15)->create([
            'platform_id' => $this->platform->id
        ]);

        // Act
        $response = $this->getJson($this->baseUrl . '/deals?user_id=' . $this->user->id . '&page=1&limit=10');

        // Assert
        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'status',
                     'data',
                     'total'
                 ]);

        // Verify we got data back
        $this->assertIsArray($response->json('data'));
        $this->assertGreaterThan(0, count($response->json('data')));
    }

    /**
     * Test: GET /api/partner/deals/deals - Validation fails without user_id
     */
    public function test_list_deals_fails_without_user_id()
    {
        // Act
        $response = $this->getJson($this->baseUrl . '/deals');

        // Assert
        $response->assertStatus(422)
                 ->assertJsonStructure([
                     'status',
                     'message'
                 ]);
    }

    /**
     * Test: POST /api/partner/deals/deals - Validation fails with invalid data
     */
    public function test_create_deal_fails_with_invalid_data()
    {
        // Arrange
        $invalidData = [
            'user_id' => $this->user->id
            // Missing required fields
        ];

        // Act
        $response = $this->postJson($this->baseUrl . '/deals', $invalidData);

        // Assert
        $response->assertStatus(422);
    }

    /**
     * Test: Unauthorized access without valid IP
     */
    public function test_fails_without_valid_ip()
    {
        // Set invalid IP
        $this->withServerVariables(['REMOTE_ADDR' => '192.168.1.1']);

        // Act
        $response = $this->getJson($this->baseUrl . '/deals?user_id=' . $this->user->id);

        // Assert
        $response->assertStatus(403)
                 ->assertJson([
                     'error' => 'Unauthorized. Invalid IP.'
                 ]);
    }
}
