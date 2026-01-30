<?php

namespace Tests\Unit\Services;

use App\Services\Balances\OperationCategoryService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class OperationCategoryServiceTest extends TestCase
{
    use DatabaseTransactions;

    protected OperationCategoryService $operationCategoryService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->operationCategoryService = new OperationCategoryService();
    }

    /**
     * Test service exists and can be instantiated
     */
    public function test_service_exists()
    {
        $this->assertNotNull($this->operationCategoryService);
        $this->assertInstanceOf(OperationCategoryService::class, $this->operationCategoryService);
    }
}
