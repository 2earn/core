<?php

namespace Tests\Unit\Services\Deals;

use App\Models\Deal;
use App\Models\DealValidationRequest;
use App\Models\User;
use App\Services\Deals\PendingDealValidationRequestsInlineService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Tests\TestCase;

class PendingDealValidationRequestsInlineServiceTest extends TestCase
{
    use DatabaseTransactions;

    protected PendingDealValidationRequestsInlineService $service;

    protected function setUp(): void
    {
        parent::setUp();

        // Ensure minimal tables exist so factories can run even if full migrations weren't applied
        if (!Schema::hasTable('users')) {
            Schema::create('users', function (Blueprint $table) {
                $table->id();
                $table->string('name')->nullable();
                $table->string('email')->unique()->nullable();
                $table->string('idUser')->nullable();
                $table->timestamp('email_verified_at')->nullable();
                $table->string('password')->nullable();
                $table->string('remember_token')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('platforms')) {
            Schema::create('platforms', function (Blueprint $table) {
                $table->id();
                $table->string('name')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('deals')) {
            Schema::create('deals', function (Blueprint $table) {
                $table->id();
                $table->string('name')->nullable();
                $table->boolean('validated')->default(false);
                $table->unsignedBigInteger('platform_id')->nullable();
                $table->unsignedBigInteger('created_by_id')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('deal_validation_requests')) {
            Schema::create('deal_validation_requests', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('deal_id')->nullable();
                $table->unsignedBigInteger('requested_by_id')->nullable();
                $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
                $table->text('rejection_reason')->nullable();
                $table->text('notes')->nullable();
                $table->unsignedBigInteger('reviewed_by')->nullable();
                $table->timestamp('reviewed_at')->nullable();
                $table->timestamps();
            });
        }

        // Clear any leftover data in these tables to avoid flaky counts
        if (Schema::hasTable('deal_validation_requests')) {
            DealValidationRequest::query()->delete();
        }
        if (Schema::hasTable('deals')) {
            Deal::query()->delete();
        }
        if (Schema::hasTable('users')) {
            User::query()->delete();
        }

        $this->service = new PendingDealValidationRequestsInlineService();
    }

    /**
     * Test getPendingRequests returns pending requests
     */
    public function test_get_pending_requests_returns_pending_requests()
    {
        // Arrange
        DealValidationRequest::factory()->pending()->count(3)->create();
        DealValidationRequest::factory()->approved()->count(2)->create();

        // Act
        $result = $this->service->getPendingRequests();

        // Assert
        $this->assertCount(3, $result);
        foreach ($result as $request) {
            $this->assertEquals('pending', $request->status);
        }
    }

    /**
     * Test getPendingRequests with limit
     */
    public function test_get_pending_requests_respects_limit()
    {
        // Arrange
        DealValidationRequest::factory()->pending()->count(10)->create();

        // Act
        $result = $this->service->getPendingRequests(5);

        // Assert
        $this->assertCount(5, $result);
    }

    /**
     * Test getPendingRequests loads relationships
     */
    public function test_get_pending_requests_loads_relationships()
    {
        // Arrange
        DealValidationRequest::factory()->pending()->create();

        // Act
        $result = $this->service->getPendingRequests();

        // Assert
        $this->assertTrue($result->first()->relationLoaded('deal'));
        $this->assertTrue($result->first()->relationLoaded('requestedBy'));
    }

    /**
     * Test getPendingRequests orders by created_at DESC
     */
    public function test_get_pending_requests_orders_by_created_at_desc()
    {
        // Arrange
        $request1 = DealValidationRequest::factory()->pending()->create(['created_at' => now()->subDays(2)]);
        $request2 = DealValidationRequest::factory()->pending()->create(['created_at' => now()]);

        // Act
        $result = $this->service->getPendingRequests();

        // Assert
        $this->assertEquals($request2->id, $result->first()->id);
    }

    /**
     * Test getTotalPending returns correct count
     */
    public function test_get_total_pending_returns_correct_count()
    {
        // Arrange
        DealValidationRequest::factory()->pending()->count(5)->create();
        DealValidationRequest::factory()->approved()->count(3)->create();

        // Act
        $result = $this->service->getTotalPending();

        // Assert
        $this->assertEquals(5, $result);
    }

    /**
     * Test getTotalPending returns zero when no pending
     */
    public function test_get_total_pending_returns_zero_when_no_pending()
    {
        // Arrange
        DealValidationRequest::factory()->approved()->count(2)->create();

        // Act
        $result = $this->service->getTotalPending();

        // Assert
        $this->assertEquals(0, $result);
    }

    /**
     * Test getPendingRequestsWithTotal returns array with both values
     */
    public function test_get_pending_requests_with_total_returns_array()
    {
        // Arrange
        DealValidationRequest::factory()->pending()->count(5)->create();

        // Act
        $result = $this->service->getPendingRequestsWithTotal();

        // Assert
        $this->assertIsArray($result);
        $this->assertArrayHasKey('pendingRequests', $result);
        $this->assertArrayHasKey('totalPending', $result);
        $this->assertCount(5, $result['pendingRequests']);
        $this->assertEquals(5, $result['totalPending']);
    }

    /**
     * Test getPendingRequestsWithTotal with limit
     */
    public function test_get_pending_requests_with_total_respects_limit()
    {
        // Arrange
        DealValidationRequest::factory()->pending()->count(10)->create();

        // Act
        $result = $this->service->getPendingRequestsWithTotal(3);

        // Assert
        $this->assertCount(3, $result['pendingRequests']);
        $this->assertEquals(10, $result['totalPending']);
    }

    /**
     * Test findRequest returns request
     */
    public function test_find_request_returns_request()
    {
        // Arrange
        $request = DealValidationRequest::factory()->create();

        // Act
        $result = $this->service->findRequest($request->id);

        // Assert
        $this->assertEquals($request->id, $result->id);
    }

    /**
     * Test findRequest throws exception when not found
     */
    public function test_find_request_throws_exception_when_not_found()
    {
        // Assert
        $this->expectException(ModelNotFoundException::class);

        // Act
        $this->service->findRequest(99999);
    }

    /**
     * Test findRequestWithRelations loads relationships
     */
    public function test_find_request_with_relations_loads_relationships()
    {
        // Arrange
        $request = DealValidationRequest::factory()->create();

        // Act
        $result = $this->service->findRequestWithRelations($request->id, ['deal', 'requestedBy']);

        // Assert
        $this->assertTrue($result->relationLoaded('deal'));
        $this->assertTrue($result->relationLoaded('requestedBy'));
    }

    /**
     * Test approveRequest approves request and validates deal
     */
    public function test_approve_request_approves_request_and_validates_deal()
    {
        // Arrange
        $reviewer = User::factory()->create();
        $deal = Deal::factory()->create(['validated' => false]);
        $request = DealValidationRequest::factory()->pending()->create(['deal_id' => $deal->id]);

        // Act
        $result = $this->service->approveRequest($request->id, $reviewer->id);

        // Assert
        $this->assertEquals('approved', $result->status);
        $this->assertEquals($reviewer->id, $result->reviewed_by);
        $this->assertNotNull($result->reviewed_at);

        $deal->refresh();
        $this->assertTrue($deal->validated);
    }

    /**
     * Test approveRequest throws exception when already processed
     */
    public function test_approve_request_throws_exception_when_already_processed()
    {
        // Arrange
        $reviewer = User::factory()->create();
        $request = DealValidationRequest::factory()->approved()->create();

        // Assert
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('This request has already been processed');

        // Act
        $this->service->approveRequest($request->id, $reviewer->id);
    }

    /**
     * Test rejectRequest rejects request
     */
    public function test_reject_request_rejects_request()
    {
        // Arrange
        $reviewer = User::factory()->create();
        $request = DealValidationRequest::factory()->pending()->create();
        $reason = 'Insufficient documentation';

        // Act
        $result = $this->service->rejectRequest($request->id, $reviewer->id, $reason);

        // Assert
        $this->assertEquals('rejected', $result->status);
        $this->assertEquals($reason, $result->rejection_reason);
        $this->assertEquals($reviewer->id, $result->reviewed_by);
        $this->assertNotNull($result->reviewed_at);
    }

    /**
     * Test rejectRequest throws exception when already processed
     */
    public function test_reject_request_throws_exception_when_already_processed()
    {
        // Arrange
        $reviewer = User::factory()->create();
        $request = DealValidationRequest::factory()->rejected()->create();

        // Assert
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('This request has already been processed');

        // Act
        $this->service->rejectRequest($request->id, $reviewer->id, 'Test');
    }

    /**
     * Test getPaginatedRequests method
     */
    public function test_get_paginated_requests_works()
    {
        // Arrange
        $pendingRequests = DealValidationRequest::factory()->pending()->count(25)->create();
        DealValidationRequest::factory()->approved()->count(5)->create();

        // Act - get paginated requests with 'pending' status filter
        $result = $this->service->getPaginatedRequests('pending', null, null, false, 10);

        // Assert
        $this->assertInstanceOf(\Illuminate\Contracts\Pagination\LengthAwarePaginator::class, $result);
        $this->assertEquals(10, $result->perPage());
        $this->assertGreaterThanOrEqual(25, $result->total());

        // All items in the paginated result should be pending
        foreach ($result->items() as $req) {
            $this->assertEquals('pending', $req->status);
        }
    }
}
