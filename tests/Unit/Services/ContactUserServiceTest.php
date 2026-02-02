<?php

namespace Tests\Unit\Services;

use App\Models\ContactUser;
use App\Models\User;
use App\Services\ContactUserService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class ContactUserServiceTest extends TestCase
{
    use DatabaseTransactions;

    protected ContactUserService $contactUserService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->contactUserService = new ContactUserService();
    }

    /**
     * Test findByIdAndUserId returns contact user
     */
    public function test_find_by_id_and_user_id_returns_contact()
    {
        // Arrange
        $user = User::factory()->create();
        $contact = ContactUser::factory()->create(['idUser' => $user->idUser]);

        // Act
        $result = $this->contactUserService->findByIdAndUserId($contact->id, $user->idUser);

        // Assert
        $this->assertNotNull($result);
        $this->assertEquals($contact->id, $result->id);
        $this->assertEquals($user->idUser, $result->idUser);
    }

    /**
     * Test findByIdAndUserId returns null for non-existent
     */
    public function test_find_by_id_and_user_id_returns_null_for_nonexistent()
    {
        // Act
        $result = $this->contactUserService->findByIdAndUserId(99999, '99999');

        // Assert
        $this->assertNull($result);
    }

    /**
     * Test findByUserIdAndFullPhone returns contact user
     */
    public function test_find_by_user_id_and_full_phone_returns_contact()
    {
        // Arrange
        $user = User::factory()->create();
        $contact = ContactUser::factory()->create([
            'idUser' => $user->idUser,
            'fullphone_number' => '+1234567890'
        ]);

        // Act
        $result = $this->contactUserService->findByUserIdAndFullPhone($user->idUser, '+1234567890');

        // Assert
        $this->assertNotNull($result);
        $this->assertEquals($contact->id, $result->id);
        $this->assertEquals('+1234567890', $result->fullphone_number);
    }

    /**
     * Test findByUserIdAndFullPhone returns null for non-existent
     */
    public function test_find_by_user_id_and_full_phone_returns_null_for_nonexistent()
    {
        // Act
        $result = $this->contactUserService->findByUserIdAndFullPhone('99999', '+9999999999');

        // Assert
        $this->assertNull($result);
    }

    /**
     * Test findByUserIdMobileAndCode returns contact user
     */
    public function test_find_by_user_id_mobile_and_code_returns_contact()
    {
        // Arrange
        $user = User::factory()->create();
        $contact = ContactUser::factory()->create([
            'idUser' => $user->idUser,
            'mobile' => '1234567890',
            'phonecode' => '+1'
        ]);

        // Act
        $result = $this->contactUserService->findByUserIdMobileAndCode($user->idUser, '1234567890', '+1');

        // Assert
        $this->assertNotNull($result);
        $this->assertEquals($contact->id, $result->id);
        $this->assertEquals('1234567890', $result->mobile);
    }

    /**
     * Test findByUserIdMobileAndCode returns null for non-existent
     */
    public function test_find_by_user_id_mobile_and_code_returns_null_for_nonexistent()
    {
        // Act
        $result = $this->contactUserService->findByUserIdMobileAndCode('99999', '9999999999', '+99');

        // Assert
        $this->assertNull($result);
    }

    /**
     * Test getByUserId returns all user contacts
     */
    public function test_get_by_user_id_returns_all_contacts()
    {
        // Arrange
        $user = User::factory()->create();
        ContactUser::factory()->count(3)->create(['idUser' => $user->idUser]);
        ContactUser::factory()->count(2)->create(); // Other users

        // Act
        $result = $this->contactUserService->getByUserId($user->idUser);

        // Assert
        $this->assertGreaterThanOrEqual(3, $result->count());

        foreach ($result as $contact) {
            $this->assertEquals($user->idUser, $contact->idUser);
        }
    }

    /**
     * Test getByUserId returns empty collection for no contacts
     */
    public function test_get_by_user_id_returns_empty_for_no_contacts()
    {
        // Act
        $result = $this->contactUserService->getByUserId('99999');

        // Assert
        $this->assertCount(0, $result);
    }

    /**
     * Test create creates contact user
     */
    public function test_create_creates_contact_user()
    {
        // Arrange
        $user = User::factory()->create();
        $contactUser = User::factory()->create();
        $data = [
            'idUser' => $user->idUser,
            'idContact' => $contactUser->idUser,
            'name' => 'John',
            'lastName' => 'Doe',
            'mobile' => '1234567890',
            'phonecode' => '+1',
            'fullphone_number' => '+11234567890'
        ];

        // Act
        $result = $this->contactUserService->create($data);

        // Assert
        $this->assertInstanceOf(ContactUser::class, $result);
        $this->assertEquals($user->idUser, $result->idUser);
        $this->assertEquals('John', $result->name);
        $this->assertDatabaseHas('contact_users', [
            'idUser' => $user->idUser,
            'mobile' => '1234567890'
        ]);
    }

    /**
     * Test update updates contact user
     */
    public function test_update_updates_contact_user()
    {
        // Arrange
        $contact = ContactUser::factory()->create(['name' => 'Old Name']);
        $data = ['name' => 'New Name'];

        // Act
        $result = $this->contactUserService->update($contact->id, $data);

        // Assert
        $this->assertTrue($result);

        $contact->refresh();
        $this->assertEquals('New Name', $contact->name);
    }

    /**
     * Test update returns false for non-existent
     */
    public function test_update_returns_false_for_nonexistent()
    {
        // Act
        $result = $this->contactUserService->update(99999, ['name' => 'Test']);

        // Assert
        $this->assertFalse($result);
    }

    /**
     * Test delete deletes contact user
     */
    public function test_delete_deletes_contact_user()
    {
        // Arrange
        $contact = ContactUser::factory()->create();

        // Act
        $result = $this->contactUserService->delete($contact->id);

        // Assert
        $this->assertTrue($result);
        $this->assertDatabaseMissing('contact_users', ['id' => $contact->id]);
    }

    /**
     * Test delete returns false for non-existent
     */
    public function test_delete_returns_false_for_nonexistent()
    {
        // Act
        $result = $this->contactUserService->delete(99999);

        // Assert
        $this->assertFalse($result);
    }

    /**
     * Test findByIdAndUserId with wrong user
     */
    public function test_find_by_id_and_user_id_returns_null_for_wrong_user()
    {
        // Arrange
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $contact = ContactUser::factory()->create(['idUser' => $user1->idUser]);

        // Act
        $result = $this->contactUserService->findByIdAndUserId($contact->id, $user2->idUser);

        // Assert
        $this->assertNull($result);
    }

    /**
     * Test update with multiple fields
     */
    public function test_update_with_multiple_fields()
    {
        // Arrange
        $contact = ContactUser::factory()->create();
        $data = [
            'name' => 'Updated Name',
            'lastName' => 'Updated LastName',
            'mobile' => '9876543210'
        ];

        // Act
        $result = $this->contactUserService->update($contact->id, $data);

        // Assert
        $this->assertTrue($result);

        $contact->refresh();
        $this->assertEquals('Updated Name', $contact->name);
        $this->assertEquals('Updated LastName', $contact->lastName);
        $this->assertEquals('9876543210', $contact->mobile);
    }
}
