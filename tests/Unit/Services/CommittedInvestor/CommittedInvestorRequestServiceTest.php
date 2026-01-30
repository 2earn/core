<?php

namespace Tests\Unit\Services\CommittedInvestor;

use App\Enums\RequestStatus;
use App\Models\CommittedInvestorRequest;
use App\Models\User;
use App\Services\CommittedInvestor\CommittedInvestorRequestService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class CommittedInvestorRequestServiceTest extends TestCase
{
    use DatabaseTransactions;

    protected CommittedInvestorRequestService $committedInvestorRequestService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->committedInvestorRequestService = new CommittedInvestorRequestService();
    }

    /**
     * Test getLastCommittedInvestorRequest returns last request
     */
    public function test_get_last_committed_investor_request_works()
    {
        // Arrange
        $user = User::factory()->create();
        $request1 = CommittedInvestorRequest::factory()->create([
            'user_id' => $user->id,
            'created_at' => now()->subDays(2)
        ]);
        $request2 = CommittedInvestorRequest::factory()->create([
            'user_id' => $user->id,
            'created_at' => now()
        ]);

        // Act
        $result = $this->committedInvestorRequestService->getLastCommittedInvestorRequest($user->id);

        // Assert
        $this->assertNotNull($result);
        $this->assertEquals($request2->id, $result->id);
    }

    /**
     * Test getLastCommittedInvestorRequestByStatus returns last request with status
     */
    public function test_get_last_committed_investor_request_by_status_works()
    {
        // Arrange
        $user = User::factory()->create();
        CommittedInvestorRequest::factory()->validated()->create([
            'user_id' => $user->id,
            'created_at' => now()->subDays(3)
        ]);
        $inProgressRequest = CommittedInvestorRequest::factory()->inProgress()->create([
            'user_id' => $user->id,
            'created_at' => now()
        ]);

        // Act
        $result = $this->committedInvestorRequestService->getLastCommittedInvestorRequestByStatus(
            $user->id,
            RequestStatus::InProgress->value
        );

        // Assert
        $this->assertNotNull($result);
        $this->assertEquals($inProgressRequest->id, $result->id);
        $this->assertEquals(RequestStatus::InProgress->value, $result->status);
    }

    /**
     * Test createCommittedInvestorRequest creates request
     */
    public function test_create_committed_investor_request_works()
    {
        // Arrange
        $user = User::factory()->create();
        $data = [
            'user_id' => $user->id,
            'status' => RequestStatus::InProgress->value,
            'note' => 'Test request',
            'request_date' => now(),
        ];

        // Act
        $result = $this->committedInvestorRequestService->createCommittedInvestorRequest($data);

        // Assert
        $this->assertNotNull($result);
        $this->assertEquals($user->id, $result->user_id);
        $this->assertDatabaseHas('committed_investor_requests', [
            'user_id' => $user->id,
            'note' => 'Test request'
        ]);
    }

    /**
     * Test hasInProgressRequest returns true when request exists
     */
    public function test_has_in_progress_request_works()
    {
        // Arrange
        $user = User::factory()->create();
        CommittedInvestorRequest::factory()->inProgress()->create(['user_id' => $user->id]);

        // Act
        $result = $this->committedInvestorRequestService->hasInProgressRequest($user->id);

        // Assert
        $this->assertTrue($result);
    }

    /**
     * Test hasInProgressRequest returns false when no in-progress request exists
     */
    public function test_has_in_progress_request_returns_false_when_no_request()
    {
        // Arrange
        $user = User::factory()->create();
        CommittedInvestorRequest::factory()->validated()->create(['user_id' => $user->id]);

        // Act
        $result = $this->committedInvestorRequestService->hasInProgressRequest($user->id);

        // Assert
        $this->assertFalse($result);
    }

    /**
     * Test getCommittedInvestorRequestById returns request
     */
    public function test_get_committed_investor_request_by_id_works()
    {
        // Arrange
        $request = CommittedInvestorRequest::factory()->create();

        // Act
        $result = $this->committedInvestorRequestService->getCommittedInvestorRequestById($request->id);

        // Assert
        $this->assertNotNull($result);
        $this->assertEquals($request->id, $result->id);
    }

    /**
     * Test getCommittedInvestorRequestById returns null when not found
     */
    public function test_get_committed_investor_request_by_id_returns_null_when_not_found()
    {
        // Act
        $result = $this->committedInvestorRequestService->getCommittedInvestorRequestById(99999);

        // Assert
        $this->assertNull($result);
    }

    /**
     * Test updateCommittedInvestorRequest updates request
     */
    public function test_update_committed_investor_request_works()
    {
        // Arrange
        $request = CommittedInvestorRequest::factory()->inProgress()->create();
        $data = [
            'status' => RequestStatus::Validated->value,
            'note' => 'Updated note'
        ];

        // Act
        $result = $this->committedInvestorRequestService->updateCommittedInvestorRequest($request->id, $data);

        // Assert
        $this->assertTrue($result);
        $request->refresh();
        $this->assertEquals(RequestStatus::Validated->value, $request->status);
        $this->assertEquals('Updated note', $request->note);
    }

    /**
     * Test getInProgressRequests returns in-progress requests
     */
    public function test_get_in_progress_requests_works()
    {
        // Arrange
        CommittedInvestorRequest::factory()->inProgress()->count(3)->create();
        CommittedInvestorRequest::factory()->validated()->count(2)->create();

        // Act
        $result = $this->committedInvestorRequestService->getInProgressRequests();

        // Assert
        $this->assertGreaterThanOrEqual(3, $result->count());
        foreach ($result as $request) {
            $this->assertEquals(RequestStatus::InProgress->value, $request->status);
        }
    }

    /**
     * Test getUserCommittedInvestorRequests returns user's requests
     */
    public function test_get_user_committed_investor_requests_works()
    {
        // Arrange
        $user = User::factory()->create();
        CommittedInvestorRequest::factory()->count(3)->create(['user_id' => $user->id]);
        CommittedInvestorRequest::factory()->count(2)->create(); // Other users

        // Act
        $result = $this->committedInvestorRequestService->getUserCommittedInvestorRequests($user->id);

        // Assert
        $this->assertGreaterThanOrEqual(3, $result->count());
        foreach ($result as $request) {
            $this->assertEquals($user->id, $request->user_id);
        }
    }
}
