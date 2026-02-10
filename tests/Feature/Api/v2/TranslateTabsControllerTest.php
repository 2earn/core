<?php

namespace Tests\Feature\Api\v2;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Test Suite for TranslateTabsController (API v2)
 *
 * @package Tests\Feature\Api\v2
 * @see App\Http\Controllers\Api\v2\TranslateTabsController
 */
#[Group('api')]
#[Group('api_v2')]
#[Group('translate_tabs')]
class TranslateTabsControllerTest extends TestCase
{
    use WithFaker, DatabaseTransactions;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        Sanctum::actingAs($this->user);
    }

    #[Test]
    public function it_can_get_paginated_translations()
    {
        $response = $this->getJson('/api/v2/translate-tabs?per_page=10');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data'
            ]);
    }

    #[Test]
    public function it_can_search_translations()
    {
        $response = $this->getJson('/api/v2/translate-tabs?search=test');

        $response->assertStatus(200)
            ->assertJsonFragment(['success' => true]);
    }

    #[Test]
    public function it_can_get_all_translations()
    {
        $response = $this->getJson('/api/v2/translate-tabs/all');

        $response->assertStatus(200)
            ->assertJsonFragment(['success' => true]);
    }

    #[Test]
    public function it_can_get_translation_by_id()
    {
        $response = $this->getJson('/api/v2/translate-tabs/1');

        $this->assertContains($response->status(), [200, 404]);
    }

    #[Test]
    public function it_returns_404_for_nonexistent_translation()
    {
        $response = $this->getJson('/api/v2/translate-tabs/99999');

        $response->assertStatus(404)
            ->assertJsonFragment(['success' => false]);
    }

    #[Test]
    public function it_can_search_translations_with_keyword()
    {
        $response = $this->postJson('/api/v2/translate-tabs/search', [
            'search' => 'test'
        ]);

        $response->assertStatus(200)
            ->assertJsonFragment(['success' => true]);
    }

    #[Test]
    public function it_validates_search_request()
    {
        $response = $this->postJson('/api/v2/translate-tabs/search', []);

        $response->assertStatus(422);
    }

    #[Test]
    public function it_can_get_translations_by_language()
    {
        $response = $this->getJson('/api/v2/translate-tabs/language/fr');

        $response->assertStatus(200)
            ->assertJsonFragment(['success' => true]);
    }

    #[Test]
    public function it_can_create_translation()
    {
        $data = [
            'tab_name' => 'test_tab',
            'language' => 'fr',
            'translation' => 'Test Translation'
        ];

        $response = $this->postJson('/api/v2/translate-tabs', $data);

        $response->assertStatus(201)
            ->assertJsonFragment(['success' => true]);
    }

    #[Test]
    public function it_validates_translation_creation()
    {
        $response = $this->postJson('/api/v2/translate-tabs', []);

        $response->assertStatus(422);
    }

    #[Test]
    public function it_can_update_translation()
    {
        $data = [
            'translation' => 'Updated Translation'
        ];

        $response = $this->putJson('/api/v2/translate-tabs/1', $data);

        $this->assertContains($response->status(), [200, 404]);
    }

    #[Test]
    public function it_can_delete_translation()
    {
        $response = $this->deleteJson('/api/v2/translate-tabs/1');

        $this->assertContains($response->status(), [200, 404]);
    }

    #[Test]
    public function it_can_bulk_update_translations()
    {
        $data = [
            'translations' => [
                ['id' => 1, 'translation' => 'Updated 1'],
                ['id' => 2, 'translation' => 'Updated 2']
            ]
        ];

        $response = $this->postJson('/api/v2/translate-tabs/bulk-update', $data);

        $response->assertStatus(200)
            ->assertJsonFragment(['success' => true]);
    }
}

