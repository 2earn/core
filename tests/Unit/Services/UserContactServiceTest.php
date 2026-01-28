<?php

namespace Tests\Unit\Services;

use App\Models\User;
use App\Models\UserContactNumber;
use App\Services\UserContactService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class UserContactServiceTest extends TestCase
{
    use DatabaseTransactions;

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
        $user = User::factory()->create();
        UserContactNumber::factory()->count(3)->create(['idUser' => $user->idUser]);

        // Act
        $result = $this->userContactService->getByUserIdWithSearch($user->idUser);

        // Assert
        $this->assertGreaterThanOrEqual(3, $result->count());
    }

    /**
     * Test getByUserIdWithSearch filters by mobile number
     */
    public function test_get_by_user_id_with_search_filters_by_mobile()
    {
        // Arrange
        $user = User::factory()->create();
        UserContactNumber::factory()->create([
            'idUser' => $user->idUser,
            'mobile' => '1234567890'
        ]);
        UserContactNumber::factory()->create([
            'idUser' => $user->idUser,
            'mobile' => '9876543210'
        ]);

        // Act
        $result = $this->userContactService->getByUserIdWithSearch($user->idUser, '1234');

        // Assert
        $this->assertGreaterThanOrEqual(1, $result->count());
        $this->assertTrue($result->contains(function ($contact) {
            return str_contains($contact->mobile, '1234');
        }));
    }

    /**
     * Test setActiveNumber activates a contact
     */
    public function test_set_active_number_activates_contact()
    {
        // Arrange
        $user = User::factory()->create();
        $contact1 = UserContactNumber::factory()->active()->create(['idUser' => $user->idUser]);
        $contact2 = UserContactNumber::factory()->inactive()->create(['idUser' => $user->idUser]);

        // Act
        $result = $this->userContactService->setActiveNumber($user->idUser, $contact2->id);

        // Assert
        $this->assertTrue($result);

        $contact1->refresh();
        $contact2->refresh();
        $this->assertEquals(0, $contact1->active);
        $this->assertEquals(1, $contact2->active);
    }

    /**
     * Test setActiveNumber deactivates all other contacts
     */
    public function test_set_active_number_deactivates_others()
    {
        // Arrange
        $user = User::factory()->create();
        $contact1 = UserContactNumber::factory()->active()->create(['idUser' => $user->idUser]);
        $contact2 = UserContactNumber::factory()->active()->create(['idUser' => $user->idUser]);
        $contact3 = UserContactNumber::factory()->inactive()->create(['idUser' => $user->idUser]);

        // Act
        $result = $this->userContactService->setActiveNumber($user->idUser, $contact3->id);

        // Assert
        $this->assertTrue($result);

        $contact1->refresh();
        $contact2->refresh();
        $contact3->refresh();
        $this->assertEquals(0, $contact1->active);
        $this->assertEquals(0, $contact2->active);
        $this->assertEquals(1, $contact3->active);
    }

    /**
     * Test deleteContact deletes inactive contact
     */
    public function test_delete_contact_deletes_inactive_contact()
    {
        // Arrange
        $user = User::factory()->create();
        $contact = UserContactNumber::factory()->inactive()->create([
            'idUser' => $user->idUser,
            'isID' => 0
        ]);

        // Act
        $result = $this->userContactService->deleteContact($contact->id);

        // Assert
        $this->assertTrue($result);
        $this->assertDatabaseMissing('usercontactnumber', ['id' => $contact->id]);
    }

    /**
     * Test deleteContact throws exception for active contact
     */
    public function test_delete_contact_throws_exception_for_active()
    {
        // Arrange
        $user = User::factory()->create();
        $contact = UserContactNumber::factory()->active()->create(['idUser' => $user->idUser]);

        // Assert
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Failed to delete active number');

        // Act
        $this->userContactService->deleteContact($contact->id);
    }

    /**
     * Test deleteContact throws exception for ID contact
     */
    public function test_delete_contact_throws_exception_for_id_contact()
    {
        // Arrange
        $user = User::factory()->create();
        $contact = UserContactNumber::factory()->inactive()->create([
            'idUser' => $user->idUser,
            'isID' => 1
        ]);

        // Assert
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Contact number deleting failed');

        // Act
        $this->userContactService->deleteContact($contact->id);
    }

    /**
     * Test deleteContact throws exception for non-existent contact
     */
    public function test_delete_contact_throws_exception_for_nonexistent()
    {
        // Assert
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Contact number not found');

        // Act
        $this->userContactService->deleteContact(99999);
    }

    /**
     * Test contactNumberExists returns true when exists
     */
    public function test_contact_number_exists_returns_true()
    {
        // Arrange
        $user = User::factory()->create();
        $contact = UserContactNumber::factory()->create([
            'idUser' => $user->idUser,
            'fullNumber' => '+1234567890'
        ]);

        // Act
        $result = $this->userContactService->contactNumberExists($user->idUser, '+1234567890');

        // Assert
        $this->assertTrue($result);
    }

    /**
     * Test contactNumberExists returns false when not exists
     */
    public function test_contact_number_exists_returns_false()
    {
        // Arrange
        $user = User::factory()->create();

        // Act
        $result = $this->userContactService->contactNumberExists($user->idUser, '+9999999999');

        // Assert
        $this->assertFalse($result);
    }

    /**
     * Test contactNumberExistsByMobile returns true when exists
     */
    public function test_contact_number_exists_by_mobile_returns_true()
    {
        // Arrange
        $user = User::factory()->create();
        $contact = UserContactNumber::factory()->create([
            'idUser' => $user->idUser,
            'mobile' => '1234567890',
            'isoP' => 'US'
        ]);

        // Act
        $result = $this->userContactService->contactNumberExistsByMobile($user->idUser, '1234567890', 'US');

        // Assert
        $this->assertTrue($result);
    }

    /**
     * Test contactNumberExistsByMobile returns false when not exists
     */
    public function test_contact_number_exists_by_mobile_returns_false()
    {
        // Arrange
        $user = User::factory()->create();

        // Act
        $result = $this->userContactService->contactNumberExistsByMobile($user->idUser, '9999999999', 'XX');

        // Assert
        $this->assertFalse($result);
    }

    /**
     * Test createContactNumber creates new contact
     */
    public function test_create_contact_number_creates_contact()
    {
        // Arrange
        $user = User::factory()->create();

        // Act
        $result = $this->userContactService->createContactNumber(
            $user->idUser,
            '1234567890',
            1,
            'US',
            '+11234567890'
        );

        // Assert
        $this->assertInstanceOf(UserContactNumber::class, $result);
        $this->assertEquals($user->idUser, $result->idUser);
        $this->assertEquals('1234567890', $result->mobile);
        $this->assertEquals(1, $result->codeP);
        $this->assertEquals('US', $result->isoP);
        $this->assertEquals('+11234567890', $result->fullNumber);
        $this->assertEquals(0, $result->active);
        $this->assertEquals(0, $result->isID);
    }

    /**
     * Test prepareContactNumberVerification returns success for new number
     */
    public function test_prepare_contact_number_verification_success()
    {
        // Arrange
        $user = User::factory()->create();

        // Act
        $result = $this->userContactService->prepareContactNumberVerification(
            $user->idUser,
            $user->id,
            '+1234567890',
            'US',
            '1234567890',
            'user@example.com',
            '+0987654321'
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
     * Test prepareContactNumberVerification fails for existing number
     */
    public function test_prepare_contact_number_verification_fails_for_existing()
    {
        // Arrange
        $user = User::factory()->create();
        UserContactNumber::factory()->create([
            'idUser' => $user->idUser,
            'fullNumber' => '+1234567890'
        ]);

        // Act
        $result = $this->userContactService->prepareContactNumberVerification(
            $user->idUser,
            $user->id,
            '+1234567890',
            'US',
            '1234567890',
            'user@example.com',
            '+0987654321'
        );

        // Assert
        $this->assertFalse($result['success']);
        $this->assertStringContainsString('already exists', $result['message']);
    }

    /**
     * Test prepareContactNumberVerification without email
     */
    public function test_prepare_contact_number_verification_without_email()
    {
        // Arrange
        $user = User::factory()->create();

        // Act
        $result = $this->userContactService->prepareContactNumberVerification(
            $user->idUser,
            $user->id,
            '+1234567890',
            'US',
            '1234567890',
            null,
            '+0987654321'
        );

        // Assert
        $this->assertTrue($result['success']);
        $this->assertFalse($result['shouldNotifyByEmail']);
        $this->assertTrue($result['shouldNotifyBySms']);
    }
}
