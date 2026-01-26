<?php

namespace Tests\Unit\Services;

use App\Services\UserContactNumberService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserContactNumberServiceTest extends TestCase
{
    use RefreshDatabase;

    protected UserContactNumberService $userContactNumberService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->userContactNumberService = new UserContactNumberService();
    }

    /**
     * Test findByMobileAndIsoForUser finds contact number
     */
    public function test_find_by_mobile_and_iso_for_user_finds_contact()
    {
        // Arrange
        $user = \App\Models\User::factory()->create();
        $contact = \App\Models\UserContactNumber::factory()->create([
            'idUser' => $user->id,
            'mobile' => '1234567890',
            'isoP' => 'US',
        ]);

        // Act
        $result = $this->userContactNumberService->findByMobileAndIsoForUser('1234567890', 'US', $user->id);

        // Assert
        $this->assertNotNull($result);
        $this->assertEquals($contact->id, $result->id);
    }

    /**
     * Test findByMobileAndIsoForUser returns null when not found
     */
    public function test_find_by_mobile_and_iso_for_user_returns_null_when_not_found()
    {
        // Arrange
        $user = \App\Models\User::factory()->create();

        // Act
        $result = $this->userContactNumberService->findByMobileAndIsoForUser('9999999999', 'US', $user->id);

        // Assert
        $this->assertNull($result);
    }

    /**
     * Test deactivateAllForUser deactivates all contacts
     */
    public function test_deactivate_all_for_user_deactivates_contacts()
    {
        // Arrange
        $user = \App\Models\User::factory()->create();
        \App\Models\UserContactNumber::factory()->active()->count(3)->create(['idUser' => $user->id]);

        // Act
        $result = $this->userContactNumberService->deactivateAllForUser($user->id);

        // Assert
        $this->assertGreaterThan(0, $result);
        $activeContacts = \App\Models\UserContactNumber::where('idUser', $user->id)
            ->where('active', 1)
            ->count();
        $this->assertEquals(0, $activeContacts);
    }

    /**
     * Test setAsActiveAndPrimary sets contact as active
     */
    public function test_set_as_active_and_primary_sets_contact_active()
    {
        // Arrange
        $contact = \App\Models\UserContactNumber::factory()->inactive()->create();

        // Act
        $result = $this->userContactNumberService->setAsActiveAndPrimary($contact->id);

        // Assert
        $this->assertGreaterThan(0, $result);
        $contact->refresh();
        $this->assertEquals(1, $contact->active);
        $this->assertEquals(1, $contact->isID);
    }

    /**
     * Test updateAndActivate updates and activates contact
     */
    public function test_update_and_activate_updates_contact()
    {
        // Arrange
        $user = \App\Models\User::factory()->create();
        $contact1 = \App\Models\UserContactNumber::factory()->active()->create(['idUser' => $user->id]);
        $contact2 = \App\Models\UserContactNumber::factory()->inactive()->create(['idUser' => $user->id]);

        // Act
        $result = $this->userContactNumberService->updateAndActivate($contact2->id, $user->id);

        // Assert
        $this->assertTrue($result);
        $contact1->refresh();
        $contact2->refresh();
        $this->assertEquals(0, $contact1->active);
        $this->assertEquals(1, $contact2->active);
        $this->assertEquals(1, $contact2->isID);
    }

    /**
     * Test createAndActivate creates and activates contact
     */
    public function test_create_and_activate_activates_new_contact()
    {
        // Arrange
        $user = \App\Models\User::factory()->create();
        $existingContact = \App\Models\UserContactNumber::factory()->active()->create(['idUser' => $user->id]);
        $newContact = \App\Models\UserContactNumber::factory()->inactive()->create(['idUser' => $user->id]);

        // Act
        $result = $this->userContactNumberService->createAndActivate($newContact->id, $user->id);

        // Assert
        $this->assertTrue($result);
        $existingContact->refresh();
        $newContact->refresh();
        $this->assertEquals(0, $existingContact->active);
        $this->assertEquals(1, $newContact->active);
        $this->assertEquals(1, $newContact->isID);
    }

    /**
     * Test createUserContactNumber creates contact
     */
    public function test_create_user_contact_number_creates_contact()
    {
        // Arrange
        $user = \App\Models\User::factory()->create();

        // Act
        $result = $this->userContactNumberService->createUserContactNumber(
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
        $this->assertEquals(1, $result->active);
        $this->assertEquals(1, $result->isID);
        $this->assertDatabaseHas('usercontactnumber', [
            'idUser' => $user->id,
            'mobile' => '1234567890',
        ]);
    }

    /**
     * Test updateUserContactNumber updates existing contacts
     */
    public function test_update_user_contact_number_updates_contacts()
    {
        // Arrange
        $user = \App\Models\User::factory()->create();
        \App\Models\UserContactNumber::factory()->create([
            'idUser' => $user->id,
            'mobile' => '0000000000',
        ]);

        // Act
        $result = $this->userContactNumberService->updateUserContactNumber(
            $user->id,
            '1234567890',
            1,
            'US',
            '+11234567890'
        );

        // Assert
        $this->assertTrue($result);
        $this->assertDatabaseHas('usercontactnumber', [
            'idUser' => $user->id,
            'mobile' => '1234567890',
        ]);
    }

    /**
     * Test updateUserContactNumber returns false when no contacts exist
     */
    public function test_update_user_contact_number_returns_false_when_no_contacts()
    {
        // Arrange
        $user = \App\Models\User::factory()->create();

        // Act
        $result = $this->userContactNumberService->updateUserContactNumber(
            $user->id,
            '1234567890',
            1,
            'US',
            '+11234567890'
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
        $user = \App\Models\User::factory()->create();

        // Act
        $result = $this->userContactNumberService->createUserContactNumberByProp(
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
    }
}
