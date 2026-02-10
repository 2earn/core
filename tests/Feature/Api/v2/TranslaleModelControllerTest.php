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
 * Test Suite for TranslaleModelController (API v2)
 *
 * @package Tests\Feature\Api\v2
 * @see App\Http\Controllers\Api\v2\TranslaleModelController
 */
#[Group('api')]
#[Group('api_v2')]
#[Group('translations')]
class TranslaleModelControllerTest extends TestCase
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
        $response = $this->getJson('/api/v2/translale-models?per_page=10');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data'
            ]);
    }

    #[Test]
    public function it_can_search_translations()
    {
        $response = $this->getJson('/api/v2/translale-models?search=test');

        $response->assertStatus(200)
            ->assertJsonFragment(['success' => true]);
    }

    #[Test]
    public function it_can_get_all_translations()
    {
        $response = $this->getJson('/api/v2/translale-models/all');

        $response->assertStatus(200)
            ->assertJsonFragment(['success' => true]);
    }

    #[Test]
    public function it_can_get_translation_by_id()
    {
        $response = $this->getJson('/api/v2/translale-models/1');

        $this->assertContains($response->status(), [200, 404]);
    }

    #[Test]
    public function it_returns_404_for_nonexistent_translation()
    {
        $response = $this->getJson('/api/v2/translale-models/99999');

        $response->assertStatus(404)
            ->assertJsonFragment(['success' => false]);
    }

    #[Test]
    public function it_can_search_translations_with_keyword()
    {
        $response = $this->postJson('/api/v2/translale-models/search', [
            'search' => 'test'
        ]);

        $response->assertStatus(200)
            ->assertJsonFragment(['success' => true]);
    }

    #[Test]
    public function it_validates_search_request()
    {
        $response = $this->postJson('/api/v2/translale-models/search', []);

        $response->assertStatus(422);
    }

    #[Test]
    public function it_can_create_translation()
    {
        $data = [
            'model_type' => 'Platform',
            'model_id' => 1,
            'language' => 'fr',
            'field' => 'name',
            'value' => 'Test Translation'
        ];

        $response = $this->postJson('/api/v2/translale-models', $data);

        $response->assertStatus(201)
            ->assertJsonFragment(['success' => true]);
    }

    #[Test]
    public function it_validates_translation_creation()
    {
        $response = $this->postJson('/api/v2/translale-models', []);

        $response->assertStatus(422);
    }

    #[Test]
    public function it_can_update_translation()
    {
        $data = [
            'value' => 'Updated Translation'
        ];

        $response = $this->putJson('/api/v2/translale-models/1', $data);

        $this->assertContains($response->status(), [200, 404]);
    }

    #[Test]
    public function it_can_delete_translation()
    {
        $response = $this->deleteJson('/api/v2/translale-models/1');

        $this->assertContains($response->status(), [200, 404]);
    }

    #[Test]
    public function it_can_get_translation_count()
    {
        $response = $this->getJson('/api/v2/translale-models/count');

        $response->assertStatus(200)
            ->assertJsonFragment(['success' => true]);
    }

    #[Test]
    public function it_can_get_key_value_arrays()
    {
        $response = $this->getJson('/api/v2/translale-models/key-value-arrays');

        $response->assertStatus(200)
            ->assertJsonFragment(['success' => true]);
    }

    #[Test]
    public function it_can_check_translation_exists()
    {
        $response = $this->getJson('/api/v2/translale-models/exists?name=test.key');

        $response->assertStatus(200)
            ->assertJsonFragment(['success' => true]);
    }

    #[Test]
    public function it_validates_exists_request()
    {
        $response = $this->getJson('/api/v2/translale-models/exists');

        $response->assertStatus(422);
    }

    #[Test]
    public function it_can_get_translations_by_pattern()
    {
        $response = $this->getJson('/api/v2/translale-models/by-pattern?pattern=test%');

        $response->assertStatus(200)
            ->assertJsonFragment(['success' => true]);
    }

    #[Test]
    public function it_validates_by_pattern_request()
    {
        $response = $this->getJson('/api/v2/translale-models/by-pattern');

        $response->assertStatus(422);
    }
}

