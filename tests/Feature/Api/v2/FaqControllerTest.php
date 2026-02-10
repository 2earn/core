<?php

namespace Tests\Feature\Api\v2;

use App\Models\Faq;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Test Suite for FaqController (API v2)
 *
 * @package Tests\Feature\Api\v2
 * @see App\Http\Controllers\Api\v2\FaqController
 */
#[Group('api')]
#[Group('api_v2')]
#[Group('faqs')]
class FaqControllerTest extends TestCase
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
    public function it_can_get_all_faqs()
    {
        Faq::factory()->count(3)->create();

        $response = $this->getJson('/api/v2/faqs');

        $response->assertStatus(200)
            ->assertJsonFragment(['status' => true]);
    }

    #[Test]
    public function it_can_get_paginated_faqs()
    {
        Faq::factory()->count(15)->create();

        $response = $this->getJson('/api/v2/faqs/paginated?per_page=10');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'data',
                'pagination'
            ]);
    }

    #[Test]
    public function it_can_search_faqs()
    {
        Faq::factory()->create(['question' => 'How to test?']);

        $response = $this->getJson('/api/v2/faqs/paginated?search=test');

        $response->assertStatus(200)
            ->assertJsonFragment(['status' => true]);
    }

    #[Test]
    public function it_can_get_faq_by_id()
    {
        $faq = Faq::factory()->create();

        $response = $this->getJson("/api/v2/faqs/{$faq->id}");

        $response->assertStatus(200)
            ->assertJsonFragment(['status' => true]);
    }

    #[Test]
    public function it_returns_404_for_nonexistent_faq()
    {
        $response = $this->getJson('/api/v2/faqs/99999');

        $response->assertStatus(404)
            ->assertJsonFragment(['status' => false]);
    }

    #[Test]
    public function it_can_create_faq()
    {
        $data = [
            'question' => 'Test Question?',
            'answer' => 'Test Answer'
        ];

        $response = $this->postJson('/api/v2/faqs', $data);

        $response->assertStatus(201)
            ->assertJsonFragment(['status' => true]);
    }

    #[Test]
    public function it_validates_faq_creation()
    {
        $response = $this->postJson('/api/v2/faqs', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['question', 'answer']);
    }

    #[Test]
    public function it_can_update_faq()
    {
        $faq = Faq::factory()->create();

        $data = [
            'question' => 'Updated Question?',
            'answer' => 'Updated Answer'
        ];

        $response = $this->putJson("/api/v2/faqs/{$faq->id}", $data);

        $response->assertStatus(200)
            ->assertJsonFragment(['status' => true]);
    }

    #[Test]
    public function it_can_delete_faq()
    {
        $faq = Faq::factory()->create();

        $response = $this->deleteJson("/api/v2/faqs/{$faq->id}");

        $response->assertStatus(200)
            ->assertJsonFragment(['status' => true]);
    }

    #[Test]
    public function it_validates_per_page_parameter()
    {
        $response = $this->getJson('/api/v2/faqs/paginated?per_page=150');

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['per_page']);
    }
}

