<?php
namespace Tests\Unit\Services\Deals;
use App\Enums\DealStatus;
use App\Models\Deal;
use App\Models\DealChangeRequest;
use App\Models\DealValidationRequest;
use App\Models\EntityRole;
use App\Models\Item;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Platform;
use App\Models\User;
use App\Services\Deals\DealService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;
class DealServiceTest extends TestCase
{
    use DatabaseTransactions;
    protected DealService $dealService;
    protected function setUp(): void
    {
        parent::setUp();
        $this->dealService = new DealService();
    }
    public function test_get_partner_deals_works()
    {
        $user = User::factory()->create();
        $platform = Platform::factory()->create();
        EntityRole::create(['user_id' => $user->id, 'entity_id' => $platform->id, 'entity_type' => Platform::class, 'role' => 'partner']);
        $deal = Deal::factory()->create(['platform_id' => $platform->id]);
        $result = $this->dealService->getPartnerDeals($user->id);
        $this->assertInstanceOf(Collection::class, $result);
        $this->assertGreaterThan(0, $result->count());
    }
    public function test_get_partner_deals_count_works()
    {
        $user = User::factory()->create();
        $platform = Platform::factory()->create();
        EntityRole::create(['user_id' => $user->id, 'entity_id' => $platform->id, 'entity_type' => Platform::class, 'role' => 'partner']);
        Deal::factory()->count(5)->create(['platform_id' => $platform->id]);
        $result = $this->dealService->getPartnerDealsCount($user->id);
        $this->assertEquals(5, $result);
    }
    public function test_get_partner_deal_by_id_works()
    {
        $user = User::factory()->create();
        $platform = Platform::factory()->create();
        EntityRole::create(['user_id' => $user->id, 'entity_id' => $platform->id, 'entity_type' => Platform::class, 'role' => 'partner']);
        $deal = Deal::factory()->create(['platform_id' => $platform->id]);
        $result = $this->dealService->getPartnerDealById($deal->id, $user->id);
        $this->assertNotNull($result);
        $this->assertEquals($deal->id, $result->id);
    }
    public function test_enrich_deals_with_requests_works()
    {
        $deal = Deal::factory()->create();
        DealChangeRequest::factory()->count(3)->create(['deal_id' => $deal->id]);
        DealValidationRequest::factory()->count(2)->create(['deal_id' => $deal->id]);
        $deals = collect([$deal]);
        $this->dealService->enrichDealsWithRequests($deals);
        $this->assertEquals(3, $deal->change_requests_count);
        $this->assertEquals(2, $deal->validation_requests_count);
    }
    public function test_get_deal_change_requests_count_works()
    {
        $deal = Deal::factory()->create();
        DealChangeRequest::factory()->count(4)->create(['deal_id' => $deal->id]);
        $result = $this->dealService->getDealChangeRequestsCount($deal->id);
        $this->assertEquals(4, $result);
    }
    public function test_get_deal_change_requests_limited_works()
    {
        $deal = Deal::factory()->create();
        DealChangeRequest::factory()->count(10)->create(['deal_id' => $deal->id]);
        $result = $this->dealService->getDealChangeRequestsLimited($deal->id, 3);
        $this->assertCount(3, $result);
    }
    public function test_get_deal_validation_requests_count_works()
    {
        $deal = Deal::factory()->create();
        DealValidationRequest::factory()->count(6)->create(['deal_id' => $deal->id]);
        $result = $this->dealService->getDealValidationRequestsCount($deal->id);
        $this->assertEquals(6, $result);
    }
    public function test_get_deal_validation_requests_limited_works()
    {
        $deal = Deal::factory()->create();
        DealValidationRequest::factory()->count(8)->create(['deal_id' => $deal->id]);
        $result = $this->dealService->getDealValidationRequestsLimited($deal->id, 5);
        $this->assertCount(5, $result);
    }
    public function test_get_deal_change_requests_works()
    {
        $deal = Deal::factory()->create();
        DealChangeRequest::factory()->count(4)->create(['deal_id' => $deal->id]);
        $result = $this->dealService->getDealChangeRequests($deal->id);
        $this->assertCount(4, $result);
    }
    public function test_get_deal_validation_requests_works()
    {
        $deal = Deal::factory()->create();
        DealValidationRequest::factory()->count(3)->create(['deal_id' => $deal->id]);
        $result = $this->dealService->getDealValidationRequests($deal->id);
        $this->assertCount(3, $result);
    }
    public function test_user_has_permission_works()
    {
        $user = User::factory()->create();
        $platform = Platform::factory()->create();
        EntityRole::create(['user_id' => $user->id, 'entity_id' => $platform->id, 'entity_type' => Platform::class, 'role' => 'partner']);
        $deal = Deal::factory()->create(['platform_id' => $platform->id]);
        $result = $this->dealService->userHasPermission($deal, $user->id);
        $this->assertTrue($result);
    }
    public function test_create_validation_request_works()
    {
        $deal = Deal::factory()->create();
        $user = User::factory()->create();
        $result = $this->dealService->createValidationRequest($deal->id, $user->id, 'Test notes');
        $this->assertInstanceOf(DealValidationRequest::class, $result);
        $this->assertEquals($deal->id, $result->deal_id);
    }
    public function test_create_change_request_works()
    {
        $deal = Deal::factory()->create();
        $user = User::factory()->create();
        $changes = ['name' => 'New Name'];
        $result = $this->dealService->createChangeRequest($deal->id, $changes, $user->id);
        $this->assertInstanceOf(DealChangeRequest::class, $result);
        $this->assertEquals($deal->id, $result->deal_id);
    }
    public function test_get_filtered_deals_works()
    {
        Deal::factory()->count(5)->create(['status' => DealStatus::Opened->value]);
        Deal::factory()->count(2)->create(['status' => DealStatus::Archived->value]);
        $result = $this->dealService->getFilteredDeals(true);
        $this->assertInstanceOf(Collection::class, $result);
        $this->assertEquals(5, $result->count());
    }
    public function test_create_works()
    {
        $platform = Platform::factory()->create();
        $data = ['name' => 'Test Deal', 'platform_id' => $platform->id, 'start_date' => now(), 'end_date' => now()->addDays(30), 'target_turnover' => 10000, 'status' => DealStatus::New->value];
        $result = $this->dealService->create($data);
        $this->assertInstanceOf(Deal::class, $result);
        $this->assertEquals('Test Deal', $result->name);
    }
    public function test_find_works()
    {
        $deal = Deal::factory()->create();
        $result = $this->dealService->find($deal->id);
        $this->assertNotNull($result);
        $this->assertEquals($deal->id, $result->id);
    }
    public function test_update_works()
    {
        $deal = Deal::factory()->create(['name' => 'Old Name']);
        $result = $this->dealService->update($deal->id, ['name' => 'New Name']);
        $this->assertTrue($result);
        $this->assertDatabaseHas('deals', ['id' => $deal->id, 'name' => 'New Name']);
    }
    public function test_get_deal_parameter_works()
    {
        DB::table('settings')->insert(['ParameterName' => 'test_param', 'DecimalValue' => 15.5]);
        $result = $this->dealService->getDealParameter('test_param');
        $this->assertEquals(15.5, $result);
    }
    public function test_get_archived_deals_works()
    {
        Deal::factory()->count(3)->create(['status' => DealStatus::Archived->value]);
        Deal::factory()->count(2)->create(['status' => DealStatus::Opened->value]);
        $result = $this->dealService->getArchivedDeals();
        $this->assertEquals(3, $result->count());
    }
    public function test_get_dashboard_indicators_works()
    {
        $user = User::factory()->create();
        $platform = Platform::factory()->create();
        EntityRole::create(['user_id' => $user->id, 'entity_id' => $platform->id, 'entity_type' => Platform::class, 'role' => 'partner']);
        Deal::factory()->count(5)->create(['platform_id' => $platform->id]);
        $result = $this->dealService->getDashboardIndicators($user->id);
        $this->assertIsArray($result);
        $this->assertArrayHasKey('total_deals', $result);
        $this->assertEquals(5, $result['total_deals']);
    }
    public function test_get_deal_performance_chart_works()
    {
        $user = User::factory()->create();
        $platform = Platform::factory()->create();
        EntityRole::create(['user_id' => $user->id, 'entity_id' => $platform->id, 'entity_type' => Platform::class, 'role' => 'partner']);
        $deal = Deal::factory()->create(['platform_id' => $platform->id, 'start_date' => now()->subDays(10), 'end_date' => now()->addDays(20), 'target_turnover' => 10000]);
        $result = $this->dealService->getDealPerformanceChart($user->id, $deal->id);
        $this->assertIsArray($result);
        $this->assertArrayHasKey('deal_id', $result);
        $this->assertEquals($deal->id, $result['deal_id']);
    }
    public function test_get_all_deals_works()
    {
        Deal::factory()->count(8)->create();
        $result = $this->dealService->getAllDeals();
        $this->assertEquals(8, $result->count());
    }
    public function test_get_available_deals_works()
    {
        $user = User::factory()->create();
        $platform = Platform::factory()->create();
        EntityRole::create(['user_id' => $user->id, 'entity_id' => $platform->id, 'entity_type' => Platform::class, 'role' => 'partner']);
        Deal::factory()->count(3)->create(['platform_id' => $platform->id, 'status' => DealStatus::Opened->value]);
        $result = $this->dealService->getAvailableDeals($user->id);
        $this->assertEquals(3, $result->count());
    }
    public function test_find_by_id_works()
    {
        $deal = Deal::factory()->create();
        $result = $this->dealService->findById($deal->id);
        $this->assertNotNull($result);
        $this->assertEquals($deal->id, $result->id);
    }
    public function test_delete_works()
    {
        $deal = Deal::factory()->create();
        $result = $this->dealService->delete($deal->id);
        $this->assertTrue($result);
        $this->assertDatabaseMissing('deals', ['id' => $deal->id]);
    }
    public function test_get_deals_with_user_purchases_works()
    {
        $user = User::factory()->create();
        $deal = Deal::factory()->create();
        $item = Item::factory()->create(['deal_id' => $deal->id]);
        $order = Order::factory()->create(['user_id' => $user->id]);
        OrderDetail::factory()->create(['order_id' => $order->id, 'item_id' => $item->id]);
        $result = $this->dealService->getDealsWithUserPurchases($user->id);
        $this->assertInstanceOf(Collection::class, $result);
    }
}