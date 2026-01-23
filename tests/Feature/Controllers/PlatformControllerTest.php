<?php

/**
 * Test Suite for PlatformController
 *
 * @package Tests\Feature\Controllers
 * @see App\Http\Controllers\PlatformController
 * @author 2earn Development Team
 * @created 2026-01-22
 */

namespace Tests\Feature\Controllers;

use Tests\TestCase;
use App\Models\User;
use App\Models\Platform;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class PlatformControllerTest extends TestCase
{
    use DatabaseTransactions;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->actingAs($this->user);
    }

    /** @test */
    public function test_index_returns_datatables()
    {
        $this->markTestSkipped('Requires Platform model');
    }

    /** @test */
    public function test_datatables_includes_platform_type()
    {
        $this->markTestSkipped('Requires PlatformType enum');
    }

    /** @test */
    public function test_datatables_includes_business_sector()
    {
        $this->markTestSkipped('Requires BusinessSector model');
    }

    /** @test */
    public function test_datatables_includes_action_column()
    {
        $this->markTestSkipped('Requires view components');
    }

    /** @test */
    public function test_datatables_formats_dates()
    {
        $this->markTestSkipped('Requires date formatting test');
    }
}
