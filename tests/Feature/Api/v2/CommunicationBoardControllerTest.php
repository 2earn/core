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
 * Test Suite for CommunicationBoardController (API v2)
 *
 * @package Tests\Feature\Api\v2
 * @see App\Http\Controllers\Api\v2\CommunicationBoardController
 */
#[Group('api')]
#[Group('api_v2')]
#[Group('communication_board')]
class CommunicationBoardControllerTest extends TestCase
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
    public function it_can_get_all_communication_board_items()
    {
        $response = $this->getJson('/api/v2/communication-board');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'data'
            ]);
    }

    #[Test]
    public function it_can_get_communication_board_items_via_all_endpoint()
    {
        $response = $this->getJson('/api/v2/communication-board/all');

        $response->assertStatus(200)
            ->assertJsonFragment(['status' => true]);
    }
}

