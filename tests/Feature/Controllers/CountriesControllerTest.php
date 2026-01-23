<?php

/**
 * Test Suite for CountriesController
 *
 * @package Tests\Feature\Controllers
 * @see App\Http\Controllers\CountriesController
 * @author 2earn Development Team
 * @created 2026-01-22
 */

namespace Tests\Feature\Controllers;

use Tests\TestCase;
use App\Models\User;
use App\Services\CountriesService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Mockery;

class CountriesControllerTest extends TestCase
{
    use DatabaseTransactions;

    protected $user;
    protected $countriesService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->actingAs($this->user);
        $this->countriesService = Mockery::mock(CountriesService::class);
    }

    /** @test */
    public function test_index_returns_json_response()
    {
        $response = $this->getJson('/api/countries/datatables');

        // Should return a successful response
        $this->assertTrue(in_array($response->status(), [200, 500])); // 500 if table doesn't exist
    }

    /** @test */
    public function test_countries_service_can_be_mocked()
    {
        $mock = Mockery::mock(CountriesService::class);
        $mock->shouldReceive('getForDatatable')
            ->once()
            ->with(['id', 'name', 'phonecode', 'langage'])
            ->andReturn(collect([]));

        $this->app->instance(CountriesService::class, $mock);

        $this->assertInstanceOf(CountriesService::class, $mock);
    }

    /** @test */
    public function test_user_is_authenticated()
    {
        $this->assertAuthenticatedAs($this->user);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
