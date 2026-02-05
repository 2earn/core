<?php

namespace Tests\Unit\Services;

use App\Services\RepresentativesService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class RepresentativesServiceTest extends TestCase
{
    use DatabaseTransactions;

    protected RepresentativesService $representativesService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->representativesService = new RepresentativesService();
    }

    /**
     * Test getAll method returns collection of representatives
     */
    public function test_get_all_works()
    {
        // Arrange
        DB::table('representatives')->insert([
            [
                'idUser' => '12345',
                'name' => 'John Doe',
                'fullphone_number' => '+1234567890',
                'id_country' => 1,
            ],
            [
                'idUser' => '67890',
                'name' => 'Jane Smith',
                'fullphone_number' => '+0987654321',
                'id_country' => 2,
            ],
        ]);

        // Act
        $result = $this->representativesService->getAll();

        // Assert
        $this->assertInstanceOf(\Illuminate\Support\Collection::class, $result);
        $this->assertGreaterThanOrEqual(2, $result->count());
        $this->assertTrue($result->contains('name', 'John Doe'));
        $this->assertTrue($result->contains('name', 'Jane Smith'));
    }

    /**
     * Test getAll method returns empty collection when no representatives exist
     */
    public function test_get_all_returns_empty_collection_when_no_representatives()
    {
        // Arrange - ensure table is empty
        DB::table('representatives')->truncate();

        // Act
        $result = $this->representativesService->getAll();

        // Assert
        $this->assertInstanceOf(\Illuminate\Support\Collection::class, $result);
        $this->assertCount(0, $result);
        $this->assertTrue($result->isEmpty());
    }

    /**
     * Test getAll method returns collection with correct structure
     */
    public function test_get_all_returns_correct_structure()
    {
        // Arrange
        DB::table('representatives')->insert([
            'idUser' => '99999',
            'name' => 'Test Representative',
            'fullphone_number' => '+1111111111',
            'id_country' => 3,
        ]);

        // Act
        $result = $this->representativesService->getAll();

        // Assert
        $this->assertNotEmpty($result);
        $representative = $result->first();
        $this->assertObjectHasProperty('idUser', $representative);
        $this->assertObjectHasProperty('name', $representative);
        $this->assertObjectHasProperty('fullphone_number', $representative);
        $this->assertObjectHasProperty('id_country', $representative);
    }
}
