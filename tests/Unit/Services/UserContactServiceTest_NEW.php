<?php

namespace Tests\Unit\Services;

use App\Services\UserContactService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserContactServiceTest extends TestCase
{
    use RefreshDatabase;

    protected UserContactService $userContactService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->userContactService = new UserContactService();
    }

    /**
     * Test getByUserIdWithSearch returns all user contacts without search
     */
    public function test_get_by_user_id_with_search_returns_all_contacts()
    {
        // Arrange
        $user = \App\Models\User::factory()->create();
        \App\Models\UserContactNumber::factory()->count(3)->create(['idUser' => $user->id]);

        // Act
        $result = $this->userContactService->getByUserIdWithSearch($user->id);

        // Assert
        $this->assertCount(3, $result);
    }

    /**
     * Test getByUserIdWithSearch filters by search term
     */
    public function test_get_by_user_id_with_search_filters_by_search()
    {
        // Arrange
        $user = \App\Models\User::factory()->create();
        \App\Models\UserContactNumber::factory()->create([
            'idUser' => $user->id,
            'mobile' => '1234567890',
        ]);
        \App\Models\UserContactNumber::factory()->create([
            'idUser' => $user->id,
            'mobile' => '9876543210',
        ]);

        // Act
        $result = $this->userContactService->getByUserIdWithSearch($user->id, '1234');

        // Assert
        $this->assertCount(1, $result);
        $this->assertEquals('1234567890', $result->first()->mobile);
    }

    /**
     * Test setActiveNumber sets contact as active
     */
    public function test_set_active_number_sets_contact_as_active()
    {
        // Arrange
        $user = \App\Models\User::factory()->create();
        $contact1 = \App\Models\UserContactNumber::factory()->active()->create(['idUser' => $user->id]);
        $contact2 = \App\Models\UserContactNumber::factory()->inactive()->create(['idUser' => $user->id]);

        // Act
        $result = $this->userContactService->setActiveNumber($user->id, $contact2->id);

        // Assert
        $this->assertTrue($result);
        $contact1->refresh();
        $contact2->refresh();
        $this->assertEquals(0, $contact1->active);
        $this->assertEquals(1, $contact2->active);
    }

    /**
     * Test setActiveNumber returns false when contact not found
     */
    public function test_set_active_number_returns_false_when_not_found()
    {
        // Arrange
        $user = \App\Models\User::factory()->create();

        // Act
        $result = $this->userContactService->setActiveNumber($user->id, 9999);

        // Assert
        $this->assertFalse($result);
    }

    /**
     * Test deleteContact deletes inactive contact
     */
    public function test_delete_contact_deletes_inactive_contact()
    {
        // Arrange
        $contact = \App\Models\UserContactNumber::factory()->inactive()->create(['isID' => 0]);

        // Act
        $result = $this->userContactService->deleteContact($contact->id);

        // Assert
        $this->assertTrue($result);
        $this->assertDatabaseMissing('usercontactnumber', ['id' => $contact->id]);
    }

    /**
     * Test deleteContact throws exception when contact not found
     */
    public function test_delete_contact_throws_exception_when_not_found()
    {
        // Assert
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Contact number not found');

        // Act
        $this->userContactService->deleteContact(9999);
    }

    /**
     * Test deleteContact throws exception when contact is active
     */
    public function test_delete_contact_throws_exception_when_active()
    {
        // Arrange
        $contact = \App\Models\UserContactNumber::factory()->active()->create();

        // Assert
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Failed to delete active number');

        // Act
        $this->userContactService->deleteContact($contact->id);
    }

    /**
     * Test deleteContact throws exception when contact is ID
     */
    public function test_delete_contact_throws_exception_when_is_id()
    {
        // Arrange
        $contact = \App\Models\UserContactNumber::factory()->create([
            'active' => 0,
            'isID' => 1,
        ]);

        // Assert
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Contact number deleting failed');

        // Act
        $this->userContactService->deleteContact($contact->id);
    }

    /**
     * Test contactNumberExists returns true when exists
     */
    public function test_contact_number_exists_returns_true_when_exists()
    {
        // Arrange
        $user = \App\Models\User::factory()->create();
        $fullNumber = '+1234567890';
        \App\Models\UserContactNumber::factory()->create([
            'idUser' => $user->id,
            'fullNumber' => $fullNumber,
        ]);

        // Act
        $result = $this->userContactService->contactNumberExists($user->id, $fullNumber);

        // Assert
        $this->assertTrue($result);
    }

    /**
     * Test contactNumberExists returns false when not exists
     */
    public function test_contact_number_exists_returns_false_when_not_exists()
    {
        // Arrange
        $user = \App\Models\User::factory()->create();

        // Act
        $result = $this->userContactService->contactNumberExists($user->id, '+9999999999');

        // Assert
        $this->assertFalse($result);
    }

    /**
     * Test contactNumberExistsByMobile returns true when exists
     */
    public function test_contact_number_exists_by_mobile_returns_true()
    {
        // Arrange
        $user = \App\Models\User::factory()->create();
        \App\Models\UserContactNumber::factory()->create([
            'idUser' => $user->id,
            'mobile' => '1234567890',
            'isoP' => 'US',
        ]);

        // Act
        $result = $this->userContactService->contactNumberExistsByMobile($user->id, '1234567890', 'US');

        // Assert
        $this->assertTrue($result);
    }

    /**
     * Test contactNumberExistsByMobile returns false when not exists
     */
    public function test_contact_number_exists_by_mobile_returns_false()
    {
        // Arrange
        $user = \App\Models\User::factory()->create();

        // Act
        $result = $this->userContactService->contactNumberExistsByMobile($user->id, '9999999999', 'US');

        // Assert
        $this->assertFalse($result);
    }

    /**
     * Test createContactNumber creates new contact
     */
    public function test_create_contact_number_creates_new_contact()
    {
        // Arrange
        $user = \App\Models\User::factory()->create();

        // Act
        $result = $this->userContactService->createContactNumber(
            $user->id,
            '1234567890',
            1,
            'US',
            '+11234567890'
        );

        // Assert
        $this->assertInstanceOf(\App\Models\UserContactNumber::class, $result);
        $this->assertEquals($user->id, $result->idUser);
        $this->assertEquals('1234567890', $result->mobile);
        $this->assertEquals(0, $result->active);
        $this->assertEquals(0, $result->isID);
        $this->assertDatabaseHas('usercontactnumber', [
            'idUser' => $user->id,
            'mobile' => '1234567890',
            'fullNumber' => '+11234567890',
        ]);
    }

    /**
     * Test prepareContactNumberVerification returns error when number exists
     */
    public function test_prepare_contact_number_verification_returns_error_when_exists()
    {
        // Arrange
        $user = \App\Models\User::factory()->create();
        $fullNumber = '+1234567890';
        \App\Models\UserContactNumber::factory()->create([
            'idUser' => $user->id,
            'fullNumber' => $fullNumber,
        ]);

        // Act
        $result = $this->userContactService->prepareContactNumberVerification(
            $user->id,
            $user->id,
            $fullNumber,
            'US',
            '1234567890',
            'test@example.com',
            '+1000000000'
        );

        // Assert
        $this->assertFalse($result['success']);
        $this->assertEquals('This contact number already exists', $result['message']);
    }

    /**
     * Test prepareContactNumberVerification generates OTP successfully
     */
    public function test_prepare_contact_number_verification_generates_otp()
    {
        // Arrange
        $user = \App\Models\User::factory()->create();

        // Act
        $result = $this->userContactService->prepareContactNumberVerification(
            $user->id,
            $user->id,
            '+1234567890',
            'US',
            '1234567890',
            'test@example.com',
            '+1000000000'
        );

        // Assert
        $this->assertTrue($result['success']);
        $this->assertArrayHasKey('otpCode', $result);
        $this->assertArrayHasKey('verificationParams', $result);
        $this->assertTrue($result['shouldNotifyBySms']);
        $this->assertTrue($result['shouldNotifyByEmail']);
        $this->assertEquals(6, strlen($result['otpCode']));
    }

    /**
     * Test verifyAndSaveContactNumber returns error with invalid OTP
     */
    public function test_verify_and_save_contact_number_returns_error_with_invalid_otp()
    {
        // Arrange
        $user = \App\Models\User::factory()->create();

        // Act
        $result = $this->userContactService->verifyAndSaveContactNumber(
            $user->id,
            $user->id,
            '123456',
            '654321', // Different stored OTP
            '1234567890',
            1,
            'US',
            '+11234567890'
        );

        // Assert
        $this->assertFalse($result['success']);
        $this->assertEquals('Invalid OPT code', $result['message']);
    }

    /**
     * Test verifyAndSaveContactNumber saves contact with valid OTP
     */
    public function test_verify_and_save_contact_number_saves_with_valid_otp()
    {
        // Arrange
        $user = \App\Models\User::factory()->create();
        $otpCode = '123456';

        // Act
        $result = $this->userContactService->verifyAndSaveContactNumber(
            $user->id,
            $user->id,
            $otpCode,
            $otpCode, // Matching OTP
            '1234567890',
            1,
            'US',
            '+11234567890'
        );

        // Assert
        $this->assertTrue($result['success']);
        $this->assertEquals('Adding contact number completed successfully', $result['message']);
        $this->assertDatabaseHas('usercontactnumber', [
            'idUser' => $user->id,
            'mobile' => '1234567890',
            'fullNumber' => '+11234567890',
        ]);
    }
}
