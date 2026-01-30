<?php

namespace Tests\Unit\Services;

use App\Enums\StatusRequest;
use App\Models\identificationuserrequest;
use App\Models\User;
use App\Services\IdentificationUserRequestService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class IdentificationUserRequestServiceTest extends TestCase
{
    use DatabaseTransactions;

    protected IdentificationUserRequestService $identificationUserRequestService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->identificationUserRequestService = new IdentificationUserRequestService();
    }

    /**
     * Test createIdentificationRequest creates request successfully
     */
    public function test_create_identification_request_works()
    {
        // Arrange
        $user = User::factory()->create();
        $status = StatusRequest::InProgressNational->value;

        // Act
        $result = $this->identificationUserRequestService->createIdentificationRequest(
            $user->idUser,
            $status
        );

        // Assert
        $this->assertTrue($result['success']);
        $this->assertEquals('Identification request created successfully', $result['message']);
        $this->assertArrayHasKey('request', $result);
        $this->assertInstanceOf(identificationuserrequest::class, $result['request']);

        $this->assertDatabaseHas('identificationuserrequest', [
            'idUser' => $user->idUser,
            'status' => $status,
            'response' => 0
        ]);
    }

    /**
     * Test createIdentificationRequest with different status
     */
    public function test_create_identification_request_with_different_status()
    {
        // Arrange
        $user = User::factory()->create();
        $status = StatusRequest::InProgressInternational->value;

        // Act
        $result = $this->identificationUserRequestService->createIdentificationRequest(
            $user->idUser,
            $status
        );

        // Assert
        $this->assertTrue($result['success']);
        $this->assertDatabaseHas('identificationuserrequest', [
            'idUser' => $user->idUser,
            'status' => $status
        ]);
    }

    /**
     * Test createIdentificationRequest sets timestamps correctly
     */
    public function test_create_identification_request_sets_timestamps()
    {
        // Arrange
        $user = User::factory()->create();
        $status = StatusRequest::InProgressNational->value;

        // Act
        $result = $this->identificationUserRequestService->createIdentificationRequest(
            $user->idUser,
            $status
        );

        // Assert
        $this->assertTrue($result['success']);
        $request = $result['request'];
        $this->assertNotNull($request->created_at);
        $this->assertNotNull($request->updated_at);
    }

    /**
     * Test hasIdentificationRequest returns true when request exists
     */
    public function test_has_identification_request_returns_true()
    {
        // Arrange
        $user = User::factory()->create();
        identificationuserrequest::factory()->create([
            'idUser' => $user->idUser,
            'response' => 0
        ]);

        // Act
        $result = $this->identificationUserRequestService->hasIdentificationRequest($user->idUser);

        // Assert
        $this->assertTrue($result);
    }

    /**
     * Test hasIdentificationRequest returns false when no request exists
     */
    public function test_has_identification_request_returns_false()
    {
        // Arrange
        $user = User::factory()->create();

        // Act
        $result = $this->identificationUserRequestService->hasIdentificationRequest($user->idUser);

        // Assert
        $this->assertFalse($result);
    }

    /**
     * Test hasIdentificationRequest returns false when request has response
     */
    public function test_has_identification_request_returns_false_when_responded()
    {
        // Arrange
        $user = User::factory()->create();
        identificationuserrequest::factory()->create([
            'idUser' => $user->idUser,
            'response' => 1
        ]);

        // Act
        $result = $this->identificationUserRequestService->hasIdentificationRequest($user->idUser);

        // Assert
        $this->assertFalse($result);
    }

    /**
     * Test hasIdentificationRequest with multiple requests
     */
    public function test_has_identification_request_with_multiple_requests()
    {
        // Arrange
        $user = User::factory()->create();
        identificationuserrequest::factory()->create([
            'idUser' => $user->idUser,
            'response' => 1 // Responded
        ]);
        identificationuserrequest::factory()->create([
            'idUser' => $user->idUser,
            'response' => 0 // Pending
        ]);

        // Act
        $result = $this->identificationUserRequestService->hasIdentificationRequest($user->idUser);

        // Assert
        $this->assertTrue($result);
    }

    /**
     * Test getLatestRejectedRequest returns latest rejected request
     */
    public function test_get_latest_rejected_request_works()
    {
        // Arrange
        $user = User::factory()->create();
        $rejectedStatus = StatusRequest::OptValidated->value;

        $oldRequest = identificationuserrequest::factory()->create([
            'idUser' => $user->idUser,
            'status' => $rejectedStatus,
            'responseDate' => Carbon::now()->subDays(5)
        ]);

        $latestRequest = identificationuserrequest::factory()->create([
            'idUser' => $user->idUser,
            'status' => $rejectedStatus,
            'responseDate' => Carbon::now()->subDays(1)
        ]);

        // Act
        $result = $this->identificationUserRequestService->getLatestRejectedRequest(
            $user->idUser,
            $rejectedStatus
        );

        // Assert
        $this->assertNotNull($result);
        $this->assertInstanceOf(identificationuserrequest::class, $result);
        $this->assertEquals($latestRequest->id, $result->id);
    }

    /**
     * Test getLatestRejectedRequest returns null when no rejected request
     */
    public function test_get_latest_rejected_request_returns_null()
    {
        // Arrange
        $user = User::factory()->create();
        $rejectedStatus = StatusRequest::OptValidated->value;

        // Act
        $result = $this->identificationUserRequestService->getLatestRejectedRequest(
            $user->idUser,
            $rejectedStatus
        );

        // Assert
        $this->assertNull($result);
    }

    /**
     * Test getLatestRejectedRequest filters by status correctly
     */
    public function test_get_latest_rejected_request_filters_by_status()
    {
        // Arrange
        $user = User::factory()->create();
        $rejectedStatus = StatusRequest::OptValidated->value;
        $otherStatus = StatusRequest::InProgressNational->value;

        identificationuserrequest::factory()->create([
            'idUser' => $user->idUser,
            'status' => $otherStatus,
            'responseDate' => Carbon::now()
        ]);

        $rejectedRequest = identificationuserrequest::factory()->create([
            'idUser' => $user->idUser,
            'status' => $rejectedStatus,
            'responseDate' => Carbon::now()->subHours(1)
        ]);

        // Act
        $result = $this->identificationUserRequestService->getLatestRejectedRequest(
            $user->idUser,
            $rejectedStatus
        );

        // Assert
        $this->assertNotNull($result);
        $this->assertEquals($rejectedRequest->id, $result->id);
        $this->assertEquals($rejectedStatus, $result->status);
    }

    /**
     * Test getLatestRejectedRequest returns only for specific user
     */
    public function test_get_latest_rejected_request_filters_by_user()
    {
        // Arrange
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $rejectedStatus = StatusRequest::OptValidated->value;

        identificationuserrequest::factory()->create([
            'idUser' => $user2->idUser,
            'status' => $rejectedStatus,
            'responseDate' => Carbon::now()
        ]);

        $user1Request = identificationuserrequest::factory()->create([
            'idUser' => $user1->idUser,
            'status' => $rejectedStatus,
            'responseDate' => Carbon::now()->subHours(1)
        ]);

        // Act
        $result = $this->identificationUserRequestService->getLatestRejectedRequest(
            $user1->idUser,
            $rejectedStatus
        );

        // Assert
        $this->assertNotNull($result);
        $this->assertEquals($user1Request->id, $result->id);
        $this->assertEquals($user1->idUser, $result->idUser);
    }
}
