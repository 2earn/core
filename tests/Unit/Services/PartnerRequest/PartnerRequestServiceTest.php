<?php

namespace Tests\Unit\Services\PartnerRequest;

use App\Enums\BePartnerRequestStatus;
use App\Models\PartnerRequest;
use App\Models\User;
use App\Models\BusinessSector;
use App\Services\PartnerRequest\PartnerRequestService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class PartnerRequestServiceTest extends TestCase
{
    use DatabaseTransactions;

    protected PartnerRequestService $partnerRequestService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->partnerRequestService = new PartnerRequestService();
    }

    /**
     * Test getLastPartnerRequest returns most recent request
     */
    public function test_get_last_partner_request_returns_most_recent()
    {
        // Arrange
        $user = User::factory()->create();
        $older = PartnerRequest::factory()->create([
            'user_id' => $user->id,
            'created_at' => now()->subDays(2)
        ]);
        $newer = PartnerRequest::factory()->create([
            'user_id' => $user->id,
            'created_at' => now()->subDay()
        ]);

        // Act
        $result = $this->partnerRequestService->getLastPartnerRequest($user->id);

        // Assert
        $this->assertNotNull($result);
        $this->assertEquals($newer->id, $result->id);
    }

    /**
     * Test getLastPartnerRequest returns null when no requests exist
     */
    public function test_get_last_partner_request_returns_null_when_no_requests()
    {
        // Arrange
        $user = User::factory()->create();

        // Act
        $result = $this->partnerRequestService->getLastPartnerRequest($user->id);

        // Assert
        $this->assertNull($result);
    }

    /**
     * Test getLastPartnerRequestByStatus returns correct request
     */
    public function test_get_last_partner_request_by_status_returns_correct_request()
    {
        // Arrange
        $user = User::factory()->create();
        PartnerRequest::factory()->create([
            'user_id' => $user->id,
            'status' => BePartnerRequestStatus::Validated->value,
            'created_at' => now()->subDays(2)
        ]);
        $inProgress = PartnerRequest::factory()->create([
            'user_id' => $user->id,
            'status' => BePartnerRequestStatus::InProgress->value,
            'created_at' => now()->subDay()
        ]);

        // Act
        $result = $this->partnerRequestService->getLastPartnerRequestByStatus(
            $user->id,
            BePartnerRequestStatus::InProgress->value
        );

        // Assert
        $this->assertNotNull($result);
        $this->assertEquals($inProgress->id, $result->id);
        $this->assertEquals(BePartnerRequestStatus::InProgress->value, $result->status);
    }

    /**
     * Test createPartnerRequest creates new request
     */
    public function test_create_partner_request_creates_new_request()
    {
        // Arrange
        $user = User::factory()->create();
        $sector = BusinessSector::factory()->create();
        $data = [
            'user_id' => $user->id,
            'company_name' => 'Test Company',
            'business_sector_id' => $sector->id,
            'platform_url' => 'https://example.com',
            'platform_description' => 'Test description',
            'partnership_reason' => 'Test reason',
            'status' => BePartnerRequestStatus::InProgress->value,
        ];

        // Act
        $result = $this->partnerRequestService->createPartnerRequest($data);

        // Assert
        $this->assertNotNull($result);
        $this->assertEquals($data['company_name'], $result->company_name);
        $this->assertEquals($data['user_id'], $result->user_id);
        $this->assertDatabaseHas('partner_requests', [
            'company_name' => 'Test Company',
            'user_id' => $user->id
        ]);
    }

    /**
     * Test hasInProgressRequest returns true when request exists
     */
    public function test_has_in_progress_request_returns_true_when_exists()
    {
        // Arrange
        $user = User::factory()->create();
        PartnerRequest::factory()->create([
            'user_id' => $user->id,
            'status' => BePartnerRequestStatus::InProgress->value
        ]);

        // Act
        $result = $this->partnerRequestService->hasInProgressRequest($user->id);

        // Assert
        $this->assertTrue($result);
    }

    /**
     * Test hasInProgressRequest returns false when no request exists
     */
    public function test_has_in_progress_request_returns_false_when_not_exists()
    {
        // Arrange
        $user = User::factory()->create();
        PartnerRequest::factory()->create([
            'user_id' => $user->id,
            'status' => BePartnerRequestStatus::Validated->value
        ]);

        // Act
        $result = $this->partnerRequestService->hasInProgressRequest($user->id);

        // Assert
        $this->assertFalse($result);
    }

    /**
     * Test getPartnerRequestById returns correct request
     */
    public function test_get_partner_request_by_id_returns_request()
    {
        // Arrange
        $request = PartnerRequest::factory()->create();

        // Act
        $result = $this->partnerRequestService->getPartnerRequestById($request->id);

        // Assert
        $this->assertNotNull($result);
        $this->assertEquals($request->id, $result->id);
    }

    /**
     * Test getPartnerRequestById returns null when not found
     */
    public function test_get_partner_request_by_id_returns_null_when_not_found()
    {
        // Act
        $result = $this->partnerRequestService->getPartnerRequestById(99999);

        // Assert
        $this->assertNull($result);
    }

    /**
     * Test updatePartnerRequest updates request
     */
    public function test_update_partner_request_updates_request()
    {
        // Arrange
        $request = PartnerRequest::factory()->create([
            'company_name' => 'Original Name',
            'status' => BePartnerRequestStatus::InProgress->value
        ]);
        $updateData = [
            'company_name' => 'Updated Name',
            'status' => BePartnerRequestStatus::Validated->value
        ];

        // Act
        $result = $this->partnerRequestService->updatePartnerRequest($request->id, $updateData);

        // Assert
        $this->assertNotNull($result);
        $this->assertEquals('Updated Name', $result->company_name);
        $this->assertEquals(BePartnerRequestStatus::Validated->value, $result->status);
    }

    /**
     * Test updatePartnerRequest returns null when request not found
     */
    public function test_update_partner_request_returns_null_when_not_found()
    {
        // Act
        $result = $this->partnerRequestService->updatePartnerRequest(99999, ['company_name' => 'Test']);

        // Assert
        $this->assertNull($result);
    }

    /**
     * Test getPartnerRequestsByStatus returns correct requests
     */
    public function test_get_partner_requests_by_status_returns_correct_requests()
    {
        // Arrange
        PartnerRequest::factory()->count(3)->create([
            'status' => BePartnerRequestStatus::InProgress->value
        ]);
        PartnerRequest::factory()->count(2)->create([
            'status' => BePartnerRequestStatus::Validated->value
        ]);

        // Act
        $result = $this->partnerRequestService->getPartnerRequestsByStatus(
            BePartnerRequestStatus::InProgress->value
        );

        // Assert
        $this->assertCount(3, $result);
        $this->assertTrue($result->every(fn($req) => $req->status == BePartnerRequestStatus::InProgress->value));
    }

    /**
     * Test getFilteredPartnerRequests returns paginated results
     */
    public function test_get_filtered_partner_requests_returns_paginated_results()
    {
        // Arrange
        PartnerRequest::factory()->count(20)->create();

        // Act
        $result = $this->partnerRequestService->getFilteredPartnerRequests('', '', 15);

        // Assert
        $this->assertCount(15, $result);
        $this->assertEquals(20, $result->total());
    }

    /**
     * Test getFilteredPartnerRequests filters by search term
     */
    public function test_get_filtered_partner_requests_filters_by_search()
    {
        // Arrange
        PartnerRequest::factory()->create(['company_name' => 'Unique Company Name']);
        PartnerRequest::factory()->count(3)->create(['company_name' => 'Other Company']);

        // Act
        $result = $this->partnerRequestService->getFilteredPartnerRequests('Unique', '', 15);

        // Assert
        $this->assertCount(1, $result);
        $this->assertStringContainsString('Unique', $result->items()[0]->company_name);
    }

    /**
     * Test getFilteredPartnerRequests filters by status
     */
    public function test_get_filtered_partner_requests_filters_by_status()
    {
        // Arrange
        PartnerRequest::factory()->count(3)->create([
            'status' => BePartnerRequestStatus::InProgress->value
        ]);
        PartnerRequest::factory()->count(2)->create([
            'status' => BePartnerRequestStatus::Validated->value
        ]);

        // Act
        $result = $this->partnerRequestService->getFilteredPartnerRequests(
            '',
            BePartnerRequestStatus::Validated->value,
            15
        );

        // Assert
        $this->assertCount(2, $result);
        foreach ($result->items() as $req) {
            $this->assertEquals(BePartnerRequestStatus::Validated->value, $req->status);
        }
    }
}
