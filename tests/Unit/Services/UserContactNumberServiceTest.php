<?php

namespace Tests\Unit\Services;

use App\Models\User;
use App\Models\UserContactNumber;
use App\Services\UserContactNumberService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class UserContactNumberServiceTest extends TestCase
{
    use DatabaseTransactions;

    protected UserContactNumberService $userContactNumberService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->userContactNumberService = new UserContactNumberService();
    }

    /**
     * Test findByMobileAndIsoForUser returns contact when found
     */
    public function test_find_by_mobile_and_iso_for_user_returns_contact()
    {
        // Arrange
        $user = User::factory()->create();
        $contact = UserContactNumber::factory()->create([
            'idUser' => $user->id,
            'mobile' => '1234567890',
            'isoP' => 'US',
        ]);

        // Act
        $result = $this->userContactNumberService->findByMobileAndIsoForUser('1234567890', 'US', $user->id);

        // Assert
        $this->assertNotNull($result);
        $this->assertEquals($contact->id, $result->id);
        $this->assertEquals('1234567890', $result->mobile);
        $this->assertEquals('US', $result->isoP);
    }

    /**
     * Test findByMobileAndIsoForUser returns null when not found
     */
    public function test_find_by_mobile_and_iso_for_user_returns_null_when_not_found()
    {
        // Arrange
        $user = User::factory()->create();

        // Act
        $result = $this->userContactNumberService->findByMobileAndIsoForUser('9999999999', 'XX', $user->id);

        // Assert
        $this->assertNull($result);
    }

    /**
     * Test deactivateAllForUser deactivates all contacts
     */
    public function test_deactivate_all_for_user_deactivates_contacts()
    {
        // Arrange
        $user = User::factory()->create();
        UserContactNumber::factory()->active()->count(3)->create(['idUser' => $user->id]);

        // Act
        $result = $this->userContactNumberService->deactivateAllForUser($user->id);

        // Assert
        $this->assertGreaterThan(0, $result);

        // Verify all contacts are deactivated
        $contacts = UserContactNumber::where('idUser', $user->id)->get();
        foreach ($contacts as $contact) {
            $this->assertEquals(0, $contact->active);
            $this->assertEquals(0, $contact->isID);
        }
    }

    /**
     * Test setAsActiveAndPrimary sets contact as active
     */
    public function test_set_as_active_and_primary_activates_contact()
    {
        // Arrange
        $user = User::factory()->create();
        $contact = UserContactNumber::factory()->inactive()->create(['idUser' => $user->id]);

        // Act
        $result = $this->userContactNumberService->setAsActiveAndPrimary($contact->id);

        // Assert
        $this->assertGreaterThan(0, $result);

        // Verify contact is active and primary
        $contact->refresh();
        $this->assertEquals(1, $contact->active);
        $this->assertEquals(1, $contact->isID);
    }

    /**
     * Test updateAndActivate successfully updates and activates
     */
    public function test_update_and_activate_works()
    {
        // Arrange
        $user = User::factory()->create();
        $contact1 = UserContactNumber::factory()->active()->create(['idUser' => $user->id]);
        $contact2 = UserContactNumber::factory()->inactive()->create(['idUser' => $user->id]);

        // Act
        $result = $this->userContactNumberService->updateAndActivate($contact2->id, $user->id);

        // Assert
        $this->assertTrue($result);

        // Verify contact1 is deactivated
        $contact1->refresh();
        $this->assertEquals(0, $contact1->active);

        // Verify contact2 is activated
        $contact2->refresh();
        $this->assertEquals(1, $contact2->active);
        $this->assertEquals(1, $contact2->isID);
    }

    /**
     * Test createAndActivate successfully creates and activates
     */
    public function test_create_and_activate_works()
    {
        // Arrange
        $user = User::factory()->create();
        $contact1 = UserContactNumber::factory()->active()->create(['idUser' => $user->id]);
        $contact2 = UserContactNumber::factory()->create(['idUser' => $user->id]);

        // Act
        $result = $this->userContactNumberService->createAndActivate($contact2->id, $user->id);

        // Assert
        $this->assertTrue($result);

        // Verify old contacts are deactivated
        $contact1->refresh();
        $this->assertEquals(0, $contact1->active);

        // Verify new contact is activated
        $contact2->refresh();
        $this->assertEquals(1, $contact2->active);
        $this->assertEquals(1, $contact2->isID);
    }

    /**
     * Test createUserContactNumber creates new contact
     */
    public function test_create_user_contact_number_creates_contact()
    {
        // Arrange
        $user = User::factory()->create();

        // Act
        $result = $this->userContactNumberService->createUserContactNumber(
            $user->id,
            '1234567890',
            1,
            'us',
            '+11234567890'
        );

        // Assert
        $this->assertInstanceOf(UserContactNumber::class, $result);
        $this->assertEquals($user->id, $result->idUser);
        $this->assertEquals('1234567890', $result->mobile);
        $this->assertEquals(1, $result->codeP);
        $this->assertEquals('us', $result->isoP);
        $this->assertEquals('+11234567890', $result->fullNumber);
        $this->assertEquals(1, $result->active);
        $this->assertEquals(true, $result->isID);
    }

    /**
     * Test updateUserContactNumber updates existing contacts
     */
    public function test_update_user_contact_number_updates_contacts()
    {
        // Arrange
        $user = User::factory()->create();
        UserContactNumber::factory()->count(2)->create(['idUser' => $user->id]);

        // Act
        $result = $this->userContactNumberService->updateUserContactNumber(
            $user->id,
            '9876543210',
            44,
            'GB',
            '+449876543210'
        );

        // Assert
        $this->assertTrue($result);

        // Verify all contacts were updated
        $contacts = UserContactNumber::where('idUser', $user->id)->get();
        foreach ($contacts as $contact) {
            $this->assertEquals('9876543210', $contact->mobile);
            $this->assertEquals(44, $contact->codeP);
            $this->assertEquals('GB', $contact->isoP);
            $this->assertEquals('+449876543210', $contact->fullNumber);
        }
    }

    /**
     * Test updateUserContactNumber returns false when no contacts exist
     */
    public function test_update_user_contact_number_returns_false_when_no_contacts()
    {
        // Arrange
        $user = User::factory()->create();

        // Act
        $result = $this->userContactNumberService->updateUserContactNumber(
            $user->id,
            '9876543210',
            44,
            'GB',
            '+449876543210'
        );

        // Assert
        $this->assertFalse($result);
    }

    /**
     * Test createUserContactNumberByProp creates contact with properties
     */
    public function test_create_user_contact_number_by_prop_creates_contact()
    {
        // Arrange
        $user = User::factory()->create();

        // Act
        $result = $this->userContactNumberService->createUserContactNumberByProp(
            $user->id,
            '5555555555',
            33,
            'FR',
            '+335555555555'
        );

        // Assert
        $this->assertInstanceOf(UserContactNumber::class, $result);
        $this->assertEquals($user->id, $result->idUser);
        $this->assertEquals('5555555555', $result->mobile);
        $this->assertEquals(33, $result->codeP);
        $this->assertEquals('FR', $result->isoP);
        $this->assertEquals('+335555555555', $result->fullNumber);
        $this->assertEquals(0, $result->active);
        $this->assertEquals(false, $result->isID);
    }

    /**
     * Test deactivateAllForUser returns zero when user has no contacts
     */
    public function test_deactivate_all_for_user_returns_zero_when_no_contacts()
    {
        // Arrange
        $user = User::factory()->create();

        // Act
        $result = $this->userContactNumberService->deactivateAllForUser($user->id);

        // Assert
        $this->assertEquals(0, $result);
    }

    /**
     * Test findByMobileAndIsoForUser is case-insensitive for ISO
     */
    public function test_find_by_mobile_and_iso_for_user_case_sensitive()
    {
        // Arrange
        $user = User::factory()->create();
        $contact = UserContactNumber::factory()->create([
            'idUser' => $user->id,
            'mobile' => '1234567890',
            'isoP' => 'us',
        ]);

        // Act
        $result = $this->userContactNumberService->findByMobileAndIsoForUser('1234567890', 'us', $user->id);

        // Assert
        $this->assertNotNull($result);
        $this->assertEquals($contact->id, $result->id);
    }
}
