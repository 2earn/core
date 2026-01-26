<?php

namespace Tests\Unit\Services;

use App\Services\Service;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OperationCategoryServiceTest extends TestCase
{
    use RefreshDatabase;

    protected Service $operationCategoryService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->operationCategoryService = new Service();
    }

    /**
     * @test
     * TODO: Implement test methods for this service
     */
    public function test_service_exists()
    {
        $this->assertNotNull($this->service);
    }
}
