<?php

namespace Tests\Unit\Services;

use App\Enums\StatusRequest;
use App\Enums\TypeEventNotificationEnum;
use App\Enums\TypeNotificationEnum;
use App\Models\identificationuserrequest;
use App\Models\MettaUser;
use App\Models\User;
use App\Services\IdentificationRequestService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class IdentificationRequestServiceTest extends TestCase
{
    use DatabaseTransactions;

    protected IdentificationRequestService $identificationRequestService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->identificationRequestService = new IdentificationRequestService();
    }

    /**
     * Test getInProgressRequests returns in-progress requests
     */
    public function test_get_in_progress_requests_works()
    {
        // Arrange
        $user = User::factory()->create();
        $mettaUser = MettaUser::factory()->create([
            'idUser' => $user->idUser,
            'enFirstName' => 'Test',
            'enLastName' => 'User'
        ]);

        identificationuserrequest::factory()->count(3)->create([
            'idUser' => $user->idUser,
            'status' => StatusRequest::InProgressNational->value
        ]);

        // Act
        $result = $this->identificationRequestService->getInProgressRequests();

        // Assert
        $this->assertIsObject($result);
        $this->assertGreaterThanOrEqual(3, $result->count());
    }

    /**
     * Test getInProgressRequests returns empty collection on no data
     */
    public function test_get_in_progress_requests_returns_empty_on_no_data()
    {
        // Act
        $result = $this->identificationRequestService->getInProgressRequests();

        // Assert
        $this->assertIsObject($result);
    }

    /**
     * Test getRequestsByStatus returns filtered requests
     */
    public function test_get_requests_by_status_works()
    {
        // Arrange
        $user = User::factory()->create();
        $mettaUser = MettaUser::factory()->create([
            'idUser' => $user->idUser,
            'enFirstName' => 'John',
            'enLastName' => 'Doe'
        ]);

        identificationuserrequest::factory()->create([
            'idUser' => $user->idUser,
            'status' => StatusRequest::InProgressNational->value
        ]);

        identificationuserrequest::factory()->create([
            'idUser' => $user->idUser,
            'status' => StatusRequest::InProgressInternational->value
        ]);

        // Act
        $result = $this->identificationRequestService->getRequestsByStatus([
            StatusRequest::InProgressNational->value,
            StatusRequest::InProgressInternational->value
        ]);

        // Assert
        $this->assertIsObject($result);
        $this->assertGreaterThanOrEqual(2, $result->count());
    }

    /**
     * Test getRequestsByStatus with single status
     */
    public function test_get_requests_by_status_with_single_status()
    {
        // Arrange
        $user = User::factory()->create();
        $mettaUser = MettaUser::factory()->create(['idUser' => $user->idUser]);

        identificationuserrequest::factory()->create([
            'idUser' => $user->idUser,
            'status' => StatusRequest::InProgressNational->value
        ]);

        // Act
        $result = $this->identificationRequestService->getRequestsByStatus([
            StatusRequest::InProgressNational->value
        ]);

        // Assert
        $this->assertIsObject($result);
        $this->assertGreaterThanOrEqual(1, $result->count());
    }

    /**
     * Test getById returns request by ID
     */
    public function test_get_by_id_works()
    {
        // Arrange
        $user = User::factory()->create();
        $mettaUser = MettaUser::factory()->create([
            'idUser' => $user->idUser,
            'enFirstName' => 'Jane',
            'enLastName' => 'Smith'
        ]);

        $request = identificationuserrequest::factory()->create([
            'idUser' => $user->idUser,
            'status' => StatusRequest::InProgressNational->value
        ]);

        // Act
        $result = $this->identificationRequestService->getById($request->id);

        // Assert
        $this->assertNotNull($result);
        $this->assertIsObject($result);
        $this->assertEquals($request->id, $result->irid);
    }

    /**
     * Test getById returns null for non-existent request
     */
    public function test_get_by_id_returns_null_for_nonexistent()
    {
        // Act
        $result = $this->identificationRequestService->getById(999999);

        // Assert
        $this->assertNull($result);
    }

    /**
     * Test getInProgressRequestByUserId returns request
     */
    public function test_get_in_progress_request_by_user_id_works()
    {
        // Arrange
        $user = User::factory()->create();
        $request = identificationuserrequest::factory()->create([
            'idUser' => $user->idUser,
            'status' => StatusRequest::InProgressNational->value
        ]);

        // Act
        $result = $this->identificationRequestService->getInProgressRequestByUserId($user->idUser);

        // Assert
        $this->assertNotNull($result);
        $this->assertInstanceOf(identificationuserrequest::class, $result);
        $this->assertEquals($user->idUser, $result->idUser);
    }

    /**
     * Test getInProgressRequestByUserId returns null when no request
     */
    public function test_get_in_progress_request_by_user_id_returns_null()
    {
        // Arrange
        $user = User::factory()->create();

        // Act
        $result = $this->identificationRequestService->getInProgressRequestByUserId($user->idUser);

        // Assert
        $this->assertNull($result);
    }

    /**
     * Test updateIdentity updates identification request
     */
    public function test_update_identity_works()
    {
        // Arrange
        $authenticatedUser = User::factory()->create();
        Auth::login($authenticatedUser);

        $user = User::factory()->create();
        $request = identificationuserrequest::factory()->create([
            'idUser' => $user->idUser,
            'status' => StatusRequest::InProgressNational->value
        ]);

        // Act
        $result = $this->identificationRequestService->updateIdentity(
            $request,
            StatusRequest::OptValidated->value,
            1,
            'Request approved'
        );

        // Assert
        $this->assertTrue($result);
        $request->refresh();
        $this->assertEquals(StatusRequest::OptValidated->value, $request->status);
        $this->assertEquals(1, $request->response);
        $this->assertEquals('Request approved', $request->note);
        $this->assertNotNull($request->responseDate);

        Auth::logout();
    }

    /**
     * Test rejectIdentity rejects identification request
     */
    public function test_reject_identity_works()
    {
        // Arrange
        $authenticatedUser = User::factory()->create();
        Auth::login($authenticatedUser);

        $user = User::factory()->create(['iden_notif' => 0]);
        $mettaUser = MettaUser::factory()->create(['idUser' => $user->idUser]);

        $request = identificationuserrequest::factory()->create([
            'idUser' => $user->idUser,
            'status' => StatusRequest::InProgressNational->value
        ]);

        $notifyCalled = false;
        $notifyCallback = function($userId, $eventType, $params) use (&$notifyCalled) {
            $notifyCalled = true;
            $this->assertEquals(TypeEventNotificationEnum::RequestDenied, $eventType);
            $this->assertArrayHasKey('msg', $params);
            $this->assertArrayHasKey('type', $params);
        };

        // Act
        $result = $this->identificationRequestService->rejectIdentity(
            $user->idUser,
            'Document not valid',
            $notifyCallback
        );

        // Assert
        $this->assertTrue($result);
        $request->refresh();
        $this->assertEquals('Document not valid', $request->note);

        Auth::logout();
    }

    /**
     * Test rejectIdentity returns false when no in-progress request
     */
    public function test_reject_identity_returns_false_when_no_request()
    {
        // Arrange
        $user = User::factory()->create();

        $notifyCallback = function() {};

        // Act
        $result = $this->identificationRequestService->rejectIdentity(
            $user->idUser,
            'Note',
            $notifyCallback
        );

        // Assert
        $this->assertFalse($result);
    }

    /**
     * Test validateIdentity validates identification request
     */
    public function test_validate_identity_works()
    {
        // Arrange
        $authenticatedUser = User::factory()->create();
        Auth::login($authenticatedUser);

        $user = User::factory()->create(['iden_notif' => 0]);
        $mettaUser = MettaUser::factory()->create(['idUser' => $user->idUser]);

        $request = identificationuserrequest::factory()->create([
            'idUser' => $user->idUser,
            'status' => StatusRequest::InProgressNational->value
        ]);

        $getNewStatusCallback = function($idUser) {
            return StatusRequest::ValidNational->value;
        };

        $notifyCalled = false;
        $notifyCallback = function($userId, $eventType, $params) use (&$notifyCalled) {
            $notifyCalled = true;
            $this->assertEquals(TypeEventNotificationEnum::RequestAccepted, $eventType);
        };

        // Act
        $result = $this->identificationRequestService->validateIdentity(
            $user->idUser,
            $getNewStatusCallback,
            $notifyCallback
        );

        // Assert
        $this->assertTrue($result);
        $request->refresh();
        $this->assertEquals(StatusRequest::ValidNational->value, $request->status);

        Auth::logout();
    }

    /**
     * Test validateIdentity returns false when no in-progress request
     */
    public function test_validate_identity_returns_false_when_no_request()
    {
        // Arrange
        $user = User::factory()->create();

        $getNewStatusCallback = function($idUser) {
            return StatusRequest::ValidNational->value;
        };

        $notifyCallback = function() {};

        // Act
        $result = $this->identificationRequestService->validateIdentity(
            $user->idUser,
            $getNewStatusCallback,
            $notifyCallback
        );

        // Assert
        $this->assertFalse($result);
    }

    /**
     * Test validateIdentity returns false when callback returns null
     */
    public function test_validate_identity_returns_false_when_callback_returns_null()
    {
        // Arrange
        $authenticatedUser = User::factory()->create();
        Auth::login($authenticatedUser);

        $user = User::factory()->create();
        $mettaUser = MettaUser::factory()->create(['idUser' => $user->idUser]);

        $request = identificationuserrequest::factory()->create([
            'idUser' => $user->idUser,
            'status' => StatusRequest::InProgressNational->value
        ]);

        $getNewStatusCallback = function($idUser) {
            return null; // Simulate failure to determine status
        };

        $notifyCallback = function() {};

        // Act
        $result = $this->identificationRequestService->validateIdentity(
            $user->idUser,
            $getNewStatusCallback,
            $notifyCallback
        );

        // Assert
        $this->assertFalse($result);

        Auth::logout();
    }
}
