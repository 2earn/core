<?php

namespace Tests\Unit\Services\FinancialRequest;

use App\Models\detail_financial_request;
use App\Models\FinancialRequest;
use App\Models\User;
use App\Services\FinancialRequest\FinancialRequestService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class FinancialRequestServiceTest extends TestCase
{
    use DatabaseTransactions;

    protected FinancialRequestService $financialRequestService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->financialRequestService = new FinancialRequestService();
    }

    /**
     * Test resetOutGoingNotification resets accepted notifications
     */
    public function test_reset_out_going_notification_resets_accepted()
    {
        // Arrange
        $user = User::factory()->create();
        FinancialRequest::factory()->accepted()->count(2)->create([
            'idSender' => $user->idUser,
            'vu' => 0
        ]);

        // Act
        $result = $this->financialRequestService->resetOutGoingNotification($user->idUser);

        // Assert
        $this->assertGreaterThan(0, $result);
    }

    /**
     * Test resetOutGoingNotification resets refused notifications
     */
    public function test_reset_out_going_notification_resets_refused()
    {
        // Arrange
        $user = User::factory()->create();
        FinancialRequest::factory()->refused()->count(2)->create([
            'idSender' => $user->idUser,
            'vu' => 0
        ]);

        // Act
        $result = $this->financialRequestService->resetOutGoingNotification($user->idUser);

        // Assert
        $this->assertGreaterThan(0, $result);
    }

    /**
     * Test resetInComingNotification resets incoming notifications
     */
    public function test_reset_in_coming_notification_resets_notifications()
    {
        // Arrange
        $user = User::factory()->create();
        $financialRequest = FinancialRequest::factory()->create();

        detail_financial_request::factory()->count(2)->create([
            'numeroRequest' => $financialRequest->numeroReq,
            'idUser' => $user->idUser,
            'vu' => 0
        ]);

        // Act
        $result = $this->financialRequestService->resetInComingNotification($user->idUser);

        // Assert
        $this->assertGreaterThan(0, $result);
    }

    /**
     * Test getByNumeroReq returns financial request
     */
    public function test_get_by_numero_req_returns_request()
    {
        // Arrange
        $financialRequest = FinancialRequest::factory()->create();

        // Act
        $result = $this->financialRequestService->getByNumeroReq($financialRequest->numeroReq);

        // Assert
        $this->assertNotNull($result);
        $this->assertEquals($financialRequest->numeroReq, $result->numeroReq);
    }

    /**
     * Test getByNumeroReq returns null for non-existent request
     */
    public function test_get_by_numero_req_returns_null_for_nonexistent()
    {
        // Act
        $result = $this->financialRequestService->getByNumeroReq('999999');

        // Assert
        $this->assertNull($result);
    }

    /**
     * Test getDetailRequest returns detail request
     */
    public function test_get_detail_request_returns_detail()
    {
        // Arrange
        $user = User::factory()->create();
        $financialRequest = FinancialRequest::factory()->create();
        $detail = detail_financial_request::factory()->create([
            'numeroRequest' => $financialRequest->numeroReq,
            'idUser' => $user->idUser
        ]);

        // Act
        $result = $this->financialRequestService->getDetailRequest($financialRequest->numeroReq, $user->idUser);

        // Assert
        $this->assertNotNull($result);
        $this->assertEquals($detail->id, $result->id);
    }

    /**
     * Test countRequestsInOpen counts open requests
     */
    public function test_count_requests_in_open_counts_correctly()
    {
        // Arrange
        $user = User::factory()->create();
        $financialRequest = FinancialRequest::factory()->pending()->create();

        detail_financial_request::factory()->count(3)->create([
            'numeroRequest' => $financialRequest->numeroReq,
            'idUser' => $user->idUser,
            'vu' => 0
        ]);

        // Act
        $result = $this->financialRequestService->countRequestsInOpen($user->idUser);

        // Assert
        $this->assertGreaterThanOrEqual(3, $result);
    }

    /**
     * Test countRequestsOutAccepted counts accepted requests
     */
    public function test_count_requests_out_accepted_counts_correctly()
    {
        // Arrange
        $user = User::factory()->create();
        FinancialRequest::factory()->accepted()->count(2)->create([
            'idSender' => $user->idUser,
            'vu' => 0
        ]);

        // Act
        $result = $this->financialRequestService->countRequestsOutAccepted($user->idUser);

        // Assert
        $this->assertGreaterThanOrEqual(2, $result);
    }

    /**
     * Test countRequestsOutRefused counts refused requests
     */
    public function test_count_requests_out_refused_counts_correctly()
    {
        // Arrange
        $user = User::factory()->create();
        FinancialRequest::factory()->refused()->count(2)->create([
            'idSender' => $user->idUser,
            'vu' => 0
        ]);

        // Act
        $result = $this->financialRequestService->countRequestsOutRefused($user->idUser);

        // Assert
        $this->assertGreaterThanOrEqual(2, $result);
    }

    /**
     * Test acceptFinancialRequest accepts a request
     */
    public function test_accept_financial_request_accepts_request()
    {
        // Arrange
        $user = User::factory()->create();
        $financialRequest = FinancialRequest::factory()->pending()->create();

        detail_financial_request::factory()->create([
            'numeroRequest' => $financialRequest->numeroReq,
            'idUser' => $user->idUser,
            'response' => null
        ]);

        // Act
        $result = $this->financialRequestService->acceptFinancialRequest(
            $financialRequest->numeroReq,
            $user->idUser
        );

        // Assert
        $this->assertTrue($result);

        // Verify the request was accepted
        $financialRequest->refresh();
        $this->assertEquals(1, $financialRequest->status);
        $this->assertEquals($user->idUser, $financialRequest->idUserAccepted);
    }

    /**
     * Test rejectFinancialRequest rejects a request
     */
    public function test_reject_financial_request_rejects_request()
    {
        // Arrange
        $user = User::factory()->create();
        $financialRequest = FinancialRequest::factory()->pending()->create();

        detail_financial_request::factory()->create([
            'numeroRequest' => $financialRequest->numeroReq,
            'idUser' => $user->idUser,
            'response' => null
        ]);

        // Act
        $result = $this->financialRequestService->rejectFinancialRequest(
            $financialRequest->numeroReq,
            $user->idUser
        );

        // Assert
        $this->assertTrue($result);

        // Verify the detail was rejected
        $detail = detail_financial_request::where('numeroRequest', $financialRequest->numeroReq)
            ->where('idUser', $user->idUser)
            ->first();
        $this->assertEquals(2, $detail->response);
    }

    /**
     * Test rejectFinancialRequest marks request as refused when all reject
     */
    public function test_reject_financial_request_marks_refused_when_all_reject()
    {
        // Arrange
        $user = User::factory()->create();
        $financialRequest = FinancialRequest::factory()->pending()->create();

        // Only one user in the request
        detail_financial_request::factory()->create([
            'numeroRequest' => $financialRequest->numeroReq,
            'idUser' => $user->idUser,
            'response' => null
        ]);

        // Act
        $result = $this->financialRequestService->rejectFinancialRequest(
            $financialRequest->numeroReq,
            $user->idUser
        );

        // Assert
        $this->assertTrue($result);

        $financialRequest->refresh();
        $this->assertEquals(5, $financialRequest->status); // Status 5 = refused
    }

    /**
     * Test cancelFinancialRequest cancels a request
     */
    public function test_cancel_financial_request_cancels_request()
    {
        // Arrange
        $user = User::factory()->create();
        $financialRequest = FinancialRequest::factory()->pending()->create([
            'idSender' => $user->idUser
        ]);

        // Act
        $result = $this->financialRequestService->cancelFinancialRequest(
            $financialRequest->numeroReq,
            $user->idUser
        );

        // Assert
        $this->assertTrue($result);

        $financialRequest->refresh();
        $this->assertEquals(3, $financialRequest->status); // Status 3 = canceled
    }

    /**
     * Test getRequestsFromUser returns user's requests
     */
    public function test_get_requests_from_user_returns_requests()
    {
        // Arrange
        $user = User::factory()->create();
        $initialCount = FinancialRequest::where('idSender', $user->idUser)->count();

        FinancialRequest::factory()->count(3)->create([
            'idSender' => $user->idUser
        ]);

        // Act
        $result = $this->financialRequestService->getRequestsFromUser($user->idUser);

        // Assert
        $this->assertGreaterThanOrEqual($initialCount + 3, $result->count());
    }

    /**
     * Test getRequestsFromUser excludes canceled when showCanceled is false
     */
    public function test_get_requests_from_user_excludes_canceled()
    {
        // Arrange
        $user = User::factory()->create();
        FinancialRequest::factory()->canceled()->create([
            'idSender' => $user->idUser
        ]);
        $activeRequest = FinancialRequest::factory()->pending()->create([
            'idSender' => $user->idUser
        ]);

        // Act
        $result = $this->financialRequestService->getRequestsFromUser($user->idUser, false);

        // Assert
        $this->assertGreaterThanOrEqual(1, $result->count());
        // Should not contain status 3 (canceled)
        foreach ($result as $request) {
            $this->assertNotEquals(3, $request->FStatus);
        }
    }

    /**
     * Test validateRequestForAcceptance validates open request
     */
    public function test_validate_request_for_acceptance_validates_open_request()
    {
        // Arrange
        $financialRequest = FinancialRequest::factory()->pending()->create();

        // Act
        $result = $this->financialRequestService->validateRequestForAcceptance($financialRequest->numeroReq);

        // Assert
        $this->assertTrue($result['success']);
        $this->assertArrayHasKey('request', $result);
    }

    /**
     * Test validateRequestForAcceptance fails for non-open request
     */
    public function test_validate_request_for_acceptance_fails_for_non_open()
    {
        // Arrange
        $financialRequest = FinancialRequest::factory()->accepted()->create();

        // Act
        $result = $this->financialRequestService->validateRequestForAcceptance($financialRequest->numeroReq);

        // Assert
        $this->assertFalse($result['success']);
    }

    /**
     * Test validateAndRejectRequest rejects valid request
     */
    public function test_validate_and_reject_request_rejects_valid_request()
    {
        // Arrange
        $user = User::factory()->create();
        $financialRequest = FinancialRequest::factory()->pending()->create();

        detail_financial_request::factory()->create([
            'numeroRequest' => $financialRequest->numeroReq,
            'idUser' => $user->idUser,
            'response' => null
        ]);

        // Act
        $result = $this->financialRequestService->validateAndRejectRequest(
            $financialRequest->numeroReq,
            $user->idUser
        );

        // Assert
        $this->assertTrue($result['success']);
    }

    /**
     * Test validateAndRejectRequest fails for invalid request
     */
    public function test_validate_and_reject_request_fails_for_invalid()
    {
        // Arrange
        $user = User::factory()->create();
        $financialRequest = FinancialRequest::factory()->accepted()->create();

        // Act
        $result = $this->financialRequestService->validateAndRejectRequest(
            $financialRequest->numeroReq,
            $user->idUser
        );

        // Assert
        $this->assertFalse($result['success']);
    }

    /**
     * Test createFinancialRequest creates request with details
     */
    public function test_create_financial_request_creates_request_with_details()
    {
        // Arrange
        $sender = User::factory()->create();
        $recipient1 = User::factory()->create();
        $recipient2 = User::factory()->create();
        $amount = 100.50;
        $securityCode = 'ABC123';
        $selectedUsers = [$recipient1->idUser, $recipient2->idUser];

        // Act
        $this->financialRequestService->createFinancialRequest(
            $sender->idUser,
            $amount,
            $selectedUsers,
            $securityCode
        );

        // Assert
        $this->assertDatabaseHas('financial_request', [
            'idSender' => $sender->idUser,
            'amount' => $amount,
            'securityCode' => $securityCode,
            'status' => '0'
        ]);
    }
}

