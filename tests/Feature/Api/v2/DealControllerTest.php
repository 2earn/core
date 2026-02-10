<?php

namespace Tests\Feature\Api\v2;

use App\Models\Deal;
use App\Models\User;
use App\Models\Platform;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Test Suite for DealController (API v2)
 *
 * @package Tests\Feature\Api\v2
 * @see App\Http\Controllers\Api\v2\DealController
 */
#[Group('api')]
#[Group('api_v2')]
#[Group('deals')]
class DealControllerTest extends TestCase
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
    public function it_can_get_filtered_deals()
    {
        Deal::factory()->count(5)->create();

        $response = $this->getJson('/api/v2/deals?is_super_admin=1&per_page=10');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'data'
            ]);
    }

    #[Test]
    public function it_requires_is_super_admin_field()
    {
        $response = $this->getJson('/api/v2/deals');

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['is_super_admin']);
    }

    #[Test]
    public function it_can_get_all_deals()
    {
        Deal::factory()->count(3)->create();

        $response = $this->getJson('/api/v2/deals/all');

        $response->assertStatus(200)
            ->assertJsonFragment(['status' => true]);
    }

    #[Test]
    public function it_can_get_partner_deals()
    {
        $response = $this->getJson("/api/v2/deals/partner?user_id={$this->user->id}&per_page=5");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'data'
            ]);
    }

    #[Test]
    public function it_validates_partner_deals_request()
    {
        $response = $this->getJson('/api/v2/deals/partner');

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['user_id']);
    }

    #[Test]
    public function it_can_filter_deals_by_keyword()
    {
        $response = $this->getJson('/api/v2/deals?is_super_admin=1&keyword=test&per_page=10');

        $response->assertStatus(200)
            ->assertJsonFragment(['status' => true]);
    }

    #[Test]
    public function it_can_filter_deals_by_statuses()
    {
        $response = $this->getJson('/api/v2/deals?is_super_admin=1&statuses[]=active&statuses[]=pending&per_page=10');

        $response->assertStatus(200)
            ->assertJsonFragment(['status' => true]);
    }

    #[Test]
    public function it_can_filter_deals_by_types()
    {
        $response = $this->getJson('/api/v2/deals?is_super_admin=1&types[]=standard&types[]=premium&per_page=10');

        $response->assertStatus(200)
            ->assertJsonFragment(['status' => true]);
    }

    #[Test]
    public function it_can_filter_deals_by_platforms()
    {
        $platform = Platform::factory()->create();

        $response = $this->getJson("/api/v2/deals?is_super_admin=1&platforms[]={$platform->id}&per_page=10");

        $response->assertStatus(200)
            ->assertJsonFragment(['status' => true]);
    }

    #[Test]
    public function it_validates_per_page_maximum()
    {
        $response = $this->getJson('/api/v2/deals?is_super_admin=1&per_page=150');

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['per_page']);
    }
}

