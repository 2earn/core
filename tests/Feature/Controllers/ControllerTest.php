<?php

/**
 * Test Suite for Base Controller
 *
 * @package Tests\Feature\Controllers
 * @see App\Http\Controllers\Controller
 * @author 2earn Development Team
 * @created 2026-01-22
 */

namespace Tests\Feature\Controllers;

use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ControllerTest extends TestCase
{
    use DatabaseTransactions;

    #[Test]
    public function test_base_controller_exists()
    {
        $this->assertTrue(class_exists(Controller::class));
    }

    #[Test]
    public function test_base_controller_extends_laravel_controller()
    {
        $controller = new Controller();
        $this->assertInstanceOf(\Illuminate\Routing\Controller::class, $controller);
    }
}
