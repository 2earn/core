<?php

namespace Tests\Unit\Services\sms;
use App\Models\Sms;
use App\Models\User;
use App\Services\sms\SmsService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Pagination\LengthAwarePaginator;
use Tests\TestCase;

class SmsServiceTest extends TestCase
{
    use DatabaseTransactions;

    protected SmsService $smsService;
    protected function setUp(): void
    {
        parent::setUp();
        $this->smsService = new SmsService();
    }
    /**
     * Test getStatistics method returns correct counts
     */
    public function test_get_statistics_returns_correct_counts()
    {
        // Arrange - Create SMS with specific non-overlapping dates
        // 3 SMS today
        Sms::factory()->count(3)->create(['created_at' => now()]);

        // 5 SMS earlier this week (but not today)
        // Use start of week + 1 day to ensure it's in this week but not today
        $thisWeekDate = now()->startOfWeek()->addDay();
        if ($thisWeekDate->isToday()) {
            $thisWeekDate = now()->startOfWeek()->addDays(2);
        }
        Sms::factory()->count(5)->create(['created_at' => $thisWeekDate]);

        // 7 SMS earlier this month (but before this week started)
        // Use start of month to ensure it's in this month but before this week
        $thisMonthDate = now()->startOfMonth()->addDay();
        Sms::factory()->count(7)->create(['created_at' => $thisMonthDate]);

        // 2 SMS from 2 months ago (outside current month)
        Sms::factory()->count(2)->create(['created_at' => now()->subMonths(2)]);

        // Act
        $result = $this->smsService->getStatistics();

        // Assert
        $this->assertIsArray($result);
        $this->assertArrayHasKey('today', $result);
        $this->assertArrayHasKey('week', $result);
        $this->assertArrayHasKey('month', $result);
        $this->assertArrayHasKey('total', $result);

        // Today should have exactly 3
        $this->assertEquals(3, $result['today']);

        // Week should have today (3) + this week (5) = 8
        $this->assertEquals(8, $result['week']);

        // Month should have all from this month: today (3) + this week (5) + this month (7) = 15
        $this->assertEquals(15, $result['month']);

        // Total should be all records: 3 + 5 + 7 + 2 = 17
        $this->assertEquals(17, $result['total']);
    }
    /**
     * Test getStatistics returns zeros when no SMS exist
     */
    public function test_get_statistics_returns_zeros_when_empty()
    {
        // Act
        $result = $this->smsService->getStatistics();
        // Assert
        $this->assertIsArray($result);
        $this->assertEquals(0, $result['today']);
        $this->assertEquals(0, $result['week']);
        $this->assertEquals(0, $result['month']);
        $this->assertEquals(0, $result['total']);
    }
    /**
     * Test getSmsData returns paginated results
     */
    public function test_get_sms_data_returns_paginated_results()
    {
        // Arrange
        Sms::factory()->count(30)->create();
        // Act
        $result = $this->smsService->getSmsData([], 10);
        // Assert
        $this->assertInstanceOf(LengthAwarePaginator::class, $result);
        $this->assertEquals(10, $result->perPage());
        $this->assertEquals(30, $result->total());
        $this->assertCount(10, $result->items());
    }
    /**
     * Test getSmsData filters by destination number
     */
    public function test_get_sms_data_filters_by_destination_number()
    {
        // Arrange
        Sms::factory()->count(3)->withDestination('+1234567890')->create();
        Sms::factory()->count(5)->create();
        // Act
        $result = $this->smsService->getSmsData(['destination_number' => '+1234567890']);
        // Assert
        $this->assertEquals(3, $result->total());
    }
    /**
     * Test getSmsData filters by date range
     */
    public function test_get_sms_data_filters_by_date_range()
    {
        // Arrange
        $today = now()->toDateString();
        $yesterday = now()->subDay()->toDateString();
        Sms::factory()->count(3)->create(['created_at' => $today]);
        Sms::factory()->count(2)->create(['created_at' => $yesterday]);
        // Act
        $result = $this->smsService->getSmsData([
            'date_from' => $today,
            'date_to' => $today
        ]);
        // Assert
        $this->assertEquals(3, $result->total());
    }
    /**
     * Test getSmsData filters by message content
     */
    public function test_get_sms_data_filters_by_message()
    {
        // Arrange
        Sms::factory()->count(2)->create(['message' => 'Test message with keyword']);
        Sms::factory()->count(3)->create(['message' => 'Different message']);
        // Act
        $result = $this->smsService->getSmsData(['message' => 'keyword']);
        // Assert
        $this->assertEquals(2, $result->total());
    }
    /**
     * Test getSmsData filters by user ID
     */
    public function test_get_sms_data_filters_by_user_id()
    {
        // Arrange
        $user = User::factory()->create();
        Sms::factory()->count(4)->create(['created_by' => $user->id]);
        Sms::factory()->count(2)->create();
        // Act
        $result = $this->smsService->getSmsData(['user_id' => $user->id]);
        // Assert
        $this->assertEquals(4, $result->total());
    }
    /**
     * Test getSmsDataQuery returns query builder
     */
    public function test_get_sms_data_query_returns_query_builder()
    {
        // Act
        $result = $this->smsService->getSmsDataQuery();
        // Assert
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Builder::class, $result);
    }
    /**
     * Test getSmsDataQuery applies filters correctly
     */
    public function test_get_sms_data_query_applies_filters()
    {
        // Arrange
        $user = User::factory()->create();
        Sms::factory()->count(3)->create([
            'created_by' => $user->id,
            'destination_number' => '+9876543210'
        ]);
        Sms::factory()->count(5)->create();
        // Act
        $query = $this->smsService->getSmsDataQuery([
            'user_id' => $user->id,
            'destination_number' => '+9876543210'
        ]);
        $result = $query->get();
        // Assert
        $this->assertCount(3, $result);
    }
    /**
     * Test findById returns SMS when exists
     */
    public function test_find_by_id_returns_sms_when_exists()
    {
        // Arrange
        $sms = Sms::factory()->create();
        // Act
        $result = $this->smsService->findById($sms->id);
        // Assert
        $this->assertNotNull($result);
        $this->assertInstanceOf(Sms::class, $result);
        $this->assertEquals($sms->id, $result->id);
    }
    /**
     * Test findById returns null when SMS not found
     */
    public function test_find_by_id_returns_null_when_not_found()
    {
        // Act
        $result = $this->smsService->findById(99999);
        // Assert
        $this->assertNull($result);
    }
    /**
     * Test getSmsData returns empty paginator when no SMS
     */
    public function test_get_sms_data_returns_empty_when_no_sms()
    {
        // Act
        $result = $this->smsService->getSmsData();
        // Assert
        $this->assertInstanceOf(LengthAwarePaginator::class, $result);
        $this->assertEquals(0, $result->total());
        $this->assertCount(0, $result->items());
    }
    /**
     * Test getSmsData orders by created_at descending
     */
    public function test_get_sms_data_orders_by_created_at_desc()
    {
        // Arrange
        $older = Sms::factory()->create(['created_at' => now()->subHours(2)]);
        $newer = Sms::factory()->create(['created_at' => now()]);
        // Act
        $result = $this->smsService->getSmsData();
        // Assert
        $this->assertEquals($newer->id, $result->items()[0]->id);
        $this->assertEquals($older->id, $result->items()[1]->id);
    }
}
