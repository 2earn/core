# Deal Service Quick Reference

## Service Location
`app/Services/Deals/DealService.php`

## Usage in Controller

### Dependency Injection
```php
private DealService $dealService;

public function __construct(DealService $dealService)
{
    $this->dealService = $dealService;
}
```

## Available Methods

### 1. Get Partner Deals (with pagination/filters)
```php
$deals = $this->dealService->getPartnerDeals(
    $userId,           // Required: User ID
    $platformId,       // Optional: Filter by platform
    $search,           // Optional: Search term
    $page,             // Optional: Page number (null = all results)
    $perPage           // Optional: Items per page (default: 5)
);
```
**Returns**: `Collection|LengthAwarePaginator`

### 2. Get Partner Deals Count
```php
$count = $this->dealService->getPartnerDealsCount(
    $userId,           // Required: User ID
    $platformId,       // Optional: Filter by platform
    $search            // Optional: Search term
);
```
**Returns**: `int`

### 3. Get Partner Deal by ID
```php
$deal = $this->dealService->getPartnerDealById(
    $dealId,           // Required: Deal ID
    $userId            // Required: User ID (for permission check)
);
```
**Returns**: `Deal|null`

### 4. Enrich Deals with Requests
```php
$this->dealService->enrichDealsWithRequests($deals);
```
Adds to each deal:
- `change_requests_count`
- `changeRequests` (last 3)
- `validation_requests_count`
- `validationRequests` (last 3)

### 5. Get Deal Change Requests
```php
$changeRequests = $this->dealService->getDealChangeRequests($dealId);
```
**Returns**: `Collection`

### 6. Check User Permission
```php
$hasPermission = $this->dealService->userHasPermission($deal, $userId);
```
**Returns**: `bool`

## Permission Logic

User has permission if they are:
- Marketing Manager (`marketing_manager_id`)
- Financial Manager (`financial_manager_id`)
- Owner (`owner_id`)

## Search Functionality

Searches in:
- Deal name
- Platform name

## Example: Full Index Method
```php
public function index(Request $request)
{
    $userId = $request->input('user_id');
    $platformId = $request->input('platform_id');
    $page = $request->input('page');
    $search = $request->input('search');

    // Get deals
    $deals = $this->dealService->getPartnerDeals(
        $userId,
        $platformId,
        $search,
        $page,
        self::PAGINATION_LIMIT
    );

    // Get count
    $totalCount = $this->dealService->getPartnerDealsCount(
        $userId,
        $platformId,
        $search
    );

    // Enrich with related data
    $this->dealService->enrichDealsWithRequests($deals);

    return response()->json([
        'status' => true,
        'data' => $deals,
        'total' => $totalCount
    ]);
}
```

## Example: Show Method
```php
public function show(Request $request, $dealId)
{
    $userId = $request->input('user_id');

    // Get deal with permission check
    $deal = $this->dealService->getPartnerDealById($dealId, $userId);

    if (!$deal) {
        return response()->json([
            'status' => 'Failed',
            'message' => 'Deal not found'
        ], Response::HTTP_NOT_FOUND);
    }

    // Get change requests
    $changeRequests = $this->dealService->getDealChangeRequests($dealId);

    return response()->json([
        'status' => true,
        'data' => [
            'deal' => $deal,
            'change_requests' => $changeRequests
        ]
    ]);
}
```

## Example: Permission Check
```php
public function changeStatus(Request $request, Deal $deal)
{
    $userId = $request->input('user_id');

    // Check permission
    if (!$this->dealService->userHasPermission($deal, $userId)) {
        return response()->json([
            'status' => 'Failed',
            'message' => 'You do not have permission'
        ], Response::HTTP_FORBIDDEN);
    }

    // Proceed with status change...
}
```

## Relationships Loaded

All methods automatically eager load:
- `platform` - The platform relationship

## Benefits
✅ Clean controller code
✅ Reusable query logic
✅ Testable in isolation
✅ Consistent permission checks
✅ Centralized search logic
✅ Easy to modify queries

## Testing
Mock the service in controller tests:
```php
$mockService = Mockery::mock(DealService::class);
$mockService->shouldReceive('getPartnerDeals')
    ->once()
    ->andReturn($expectedDeals);

$this->app->instance(DealService::class, $mockService);
```

