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
    public function test_index_returns_datatables()
    {
        $this->markTestSkipped('Requires CountriesService setup');
    }

    /** @test */
    public function test_datatables_includes_action_column()
    {
        $this->markTestSkipped('Requires datatables implementation');
    }

    /** @test */
    public function test_returns_countries_with_correct_fields()
    {
        $this->markTestSkipped('Requires country data');
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
