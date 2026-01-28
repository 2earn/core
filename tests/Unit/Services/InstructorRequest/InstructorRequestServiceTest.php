<?php

namespace Tests\Unit\Services\InstructorRequest;

use App\Enums\BeInstructorRequestStatus;
use App\Models\InstructorRequest;
use App\Models\User;
use App\Services\InstructorRequest\InstructorRequestService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class InstructorRequestServiceTest extends TestCase
{
    use DatabaseTransactions;

    protected InstructorRequestService $instructorRequestService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->instructorRequestService = new InstructorRequestService();
    }

    /**
     * Test getLastInstructorRequest returns most recent request for user
     */
    public function test_get_last_instructor_request_returns_most_recent()
    {
        // Arrange
        $user = User::factory()->create();
        $oldRequest = InstructorRequest::factory()->create([
            'user_id' => $user->id,
            'created_at' => now()->subDays(2),
        ]);
        $newRequest = InstructorRequest::factory()->create([
            'user_id' => $user->id,
            'created_at' => now(),
        ]);

        // Act
        $result = $this->instructorRequestService->getLastInstructorRequest($user->id);

        // Assert
        $this->assertNotNull($result);
        $this->assertEquals($newRequest->id, $result->id);
    }

    /**
     * Test getLastInstructorRequest returns null when no requests exist
     */
    public function test_get_last_instructor_request_returns_null_when_no_requests()
    {
        // Arrange
        $user = User::factory()->create();

        // Act
        $result = $this->instructorRequestService->getLastInstructorRequest($user->id);

        // Assert
        $this->assertNull($result);
    }

    /**
     * Test getLastInstructorRequest returns only user's requests
     */
    public function test_get_last_instructor_request_returns_only_user_requests()
    {
        // Arrange
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $request1 = InstructorRequest::factory()->create(['user_id' => $user1->id]);
        $request2 = InstructorRequest::factory()->create(['user_id' => $user2->id]);

        // Act
        $result = $this->instructorRequestService->getLastInstructorRequest($user1->id);

        // Assert
        $this->assertEquals($request1->id, $result->id);
        $this->assertNotEquals($request2->id, $result->id);
    }

    /**
     * Test getLastInstructorRequestByStatus returns correct request
     */
    public function test_get_last_instructor_request_by_status_returns_correct_request()
    {
        // Arrange
        $user = User::factory()->create();
        InstructorRequest::factory()->rejected()->create(['user_id' => $user->id]);
        $inProgressRequest = InstructorRequest::factory()->inProgress()->create([
            'user_id' => $user->id,
            'created_at' => now(),
        ]);

        // Act
        $result = $this->instructorRequestService->getLastInstructorRequestByStatus(
            $user->id,
            BeInstructorRequestStatus::InProgress->value
        );

        // Assert
        $this->assertNotNull($result);
        $this->assertEquals($inProgressRequest->id, $result->id);
        $this->assertEquals(BeInstructorRequestStatus::InProgress->value, $result->status);
    }

    /**
     * Test getLastInstructorRequestByStatus returns null when no matching status
     */
    public function test_get_last_instructor_request_by_status_returns_null_when_no_match()
    {
        // Arrange
        $user = User::factory()->create();
        InstructorRequest::factory()->rejected()->create(['user_id' => $user->id]);

        // Act
        $result = $this->instructorRequestService->getLastInstructorRequestByStatus(
            $user->id,
            BeInstructorRequestStatus::Validated->value
        );

        // Assert
        $this->assertNull($result);
    }

    /**
     * Test createInstructorRequest creates new request successfully
     */
    public function test_create_instructor_request_creates_new_request()
    {
        // Arrange
        $user = User::factory()->create();
        $data = [
            'user_id' => $user->id,
            'status' => BeInstructorRequestStatus::InProgress->value,
            'note' => 'Test note',
            'request_date' => now()->format('Y-m-d'),
        ];

        // Act
        $result = $this->instructorRequestService->createInstructorRequest($data);

        // Assert
        $this->assertNotNull($result);
        $this->assertInstanceOf(InstructorRequest::class, $result);
        $this->assertEquals($user->id, $result->user_id);
        $this->assertEquals(BeInstructorRequestStatus::InProgress->value, $result->status);
        $this->assertDatabaseHas('instructor_requests', [
            'user_id' => $user->id,
            'status' => BeInstructorRequestStatus::InProgress->value,
        ]);
    }

    /**
     * Test createInstructorRequest with all fields
     */
    public function test_create_instructor_request_with_all_fields()
    {
        // Arrange
        $user = User::factory()->create();
        $examiner = User::factory()->create();
        $data = [
            'user_id' => $user->id,
            'status' => BeInstructorRequestStatus::Validated->value,
            'note' => 'Approved request',
            'request_date' => now()->format('Y-m-d'),
            'examination_date' => now()->format('Y-m-d'),
            'examiner_id' => $examiner->id,
        ];

        // Act
        $result = $this->instructorRequestService->createInstructorRequest($data);

        // Assert
        $this->assertNotNull($result);
        $this->assertEquals($examiner->id, $result->examiner_id);
        $this->assertEquals(BeInstructorRequestStatus::Validated->value, $result->status);
    }

    /**
     * Test hasInProgressRequest returns true when in-progress request exists
     */
    public function test_has_in_progress_request_returns_true_when_exists()
    {
        // Arrange
        $user = User::factory()->create();
        InstructorRequest::factory()->inProgress()->create(['user_id' => $user->id]);

        // Act
        $result = $this->instructorRequestService->hasInProgressRequest($user->id);

        // Assert
        $this->assertTrue($result);
    }

    /**
     * Test hasInProgressRequest returns false when no in-progress request
     */
    public function test_has_in_progress_request_returns_false_when_no_request()
    {
        // Arrange
        $user = User::factory()->create();

        // Act
        $result = $this->instructorRequestService->hasInProgressRequest($user->id);

        // Assert
        $this->assertFalse($result);
    }

    /**
     * Test hasInProgressRequest returns false when only other status requests exist
     */
    public function test_has_in_progress_request_returns_false_when_only_other_statuses()
    {
        // Arrange
        $user = User::factory()->create();
        InstructorRequest::factory()->validated()->create(['user_id' => $user->id]);
        InstructorRequest::factory()->rejected()->create(['user_id' => $user->id]);

        // Act
        $result = $this->instructorRequestService->hasInProgressRequest($user->id);

        // Assert
        $this->assertFalse($result);
    }

    /**
     * Test getInstructorRequestById returns correct request
     */
    public function test_get_instructor_request_by_id_returns_request()
    {
        // Arrange
        $request = InstructorRequest::factory()->create();

        // Act
        $result = $this->instructorRequestService->getInstructorRequestById($request->id);

        // Assert
        $this->assertNotNull($result);
        $this->assertEquals($request->id, $result->id);
        $this->assertEquals($request->user_id, $result->user_id);
    }

    /**
     * Test getInstructorRequestById returns null when not found
     */
    public function test_get_instructor_request_by_id_returns_null_when_not_found()
    {
        // Act
        $result = $this->instructorRequestService->getInstructorRequestById(99999);

        // Assert
        $this->assertNull($result);
    }

    /**
     * Test updateInstructorRequest updates request successfully
     */
    public function test_update_instructor_request_updates_successfully()
    {
        // Arrange
        $request = InstructorRequest::factory()->inProgress()->create();
        $examiner = User::factory()->create();
        $data = [
            'status' => BeInstructorRequestStatus::Validated->value,
            'examiner_id' => $examiner->id,
            'examination_date' => now()->format('Y-m-d'),
            'note' => 'Approved',
        ];

        // Act
        $result = $this->instructorRequestService->updateInstructorRequest($request->id, $data);

        // Assert
        $this->assertTrue($result);
        $this->assertDatabaseHas('instructor_requests', [
            'id' => $request->id,
            'status' => BeInstructorRequestStatus::Validated->value,
            'examiner_id' => $examiner->id,
        ]);
    }

    /**
     * Test updateInstructorRequest returns false when request not found
     */
    public function test_update_instructor_request_returns_false_when_not_found()
    {
        // Act
        $result = $this->instructorRequestService->updateInstructorRequest(99999, ['note' => 'Test']);

        // Assert
        $this->assertFalse($result);
    }

    /**
     * Test updateInstructorRequest can change status
     */
    public function test_update_instructor_request_can_change_status()
    {
        // Arrange
        $request = InstructorRequest::factory()->inProgress()->create();

        // Act
        $result = $this->instructorRequestService->updateInstructorRequest($request->id, [
            'status' => BeInstructorRequestStatus::Rejected->value,
            'note' => 'Rejected due to insufficient qualifications',
        ]);

        // Assert
        $this->assertTrue($result);
        $request->refresh();
        $this->assertEquals(BeInstructorRequestStatus::Rejected->value, $request->status);
    }
}
