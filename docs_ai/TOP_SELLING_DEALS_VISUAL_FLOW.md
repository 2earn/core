# Top Selling Deals Chart - Visual Flow Diagram

```
┌─────────────────────────────────────────────────────────────────────────┐
│                    TOP SELLING DEALS CHART FLOW                         │
└─────────────────────────────────────────────────────────────────────────┘

                              ┌──────────┐
                              │  CLIENT  │
                              └─────┬────┘
                                    │
                         GET Request with Query Params
                         /api/partner/sales/dashboard/top-deals
                              ?start_date=2025-01-01
                              &end_date=2025-12-31
                              &platform_id=3
                              &limit=10
                                    │
                                    ▼
                    ┌───────────────────────────────┐
                    │         API ROUTE             │
                    │  routes/api.php               │
                    │                               │
                    │  Route::get('/sales/          │
                    │    dashboard/top-deals',      │
                    │    [Controller, 'method'])    │
                    └───────────────┬───────────────┘
                                    │
                                    ▼
           ┌────────────────────────────────────────────────┐
           │          CONTROLLER LAYER                      │
           │  SalesDashboardController.php                  │
           │                                                │
           │  getTopSellingDeals(Request $request)          │
           │                                                │
           │  ┌──────────────────────────────────────┐     │
           │  │  1. VALIDATE REQUEST                 │     │
           │  │     • start_date (nullable|date)     │     │
           │  │     • end_date (nullable|date)       │     │
           │  │     • platform_id (nullable|integer) │     │
           │  │     • limit (nullable|integer|1-100) │     │
           │  └──────────────────────────────────────┘     │
           │                    │                           │
           │         ┌──────────┴──────────┐               │
           │         │   VALIDATION PASS   │               │
           │         │        OR           │               │
           │         │   VALIDATION FAIL   │               │
           │         └──────────┬──────────┘               │
           │                    │                           │
           │         ┌──────────▼──────────┐               │
           │         │       FAIL          │               │
           │         │  Return 422         │               │
           │         │  with errors        │               │
           │         └─────────────────────┘               │
           │                    │                           │
           │         ┌──────────▼──────────┐               │
           │         │       PASS          │               │
           │         │  Build filters      │               │
           │         │  Call Service       │               │
           │         └──────────┬──────────┘               │
           └────────────────────┼───────────────────────────┘
                                │
                                ▼
      ┌─────────────────────────────────────────────────────────┐
      │               SERVICE LAYER                              │
      │  SalesDashboardService.php                               │
      │                                                           │
      │  getTopSellingDeals(array $filters)                      │
      │                                                           │
      │  ┌────────────────────────────────────────────────────┐  │
      │  │  2. CHECK AUTHORIZATION (if user_id & platform_id) │  │
      │  │     • platformService->userHasRoleInPlatform()     │  │
      │  └────────────────────────────────────────────────────┘  │
      │                          │                               │
      │  ┌───────────────────────▼──────────────────────────┐   │
      │  │  3. BUILD DATABASE QUERY                         │   │
      │  │                                                   │   │
      │  │  Order::query()                                   │   │
      │  │    ->select(                                      │   │
      │  │       'deals.id as deal_id',                      │   │
      │  │       'deals.name as deal_name',                  │   │
      │  │       SUM(order_details.qty) as total_sales,      │   │
      │  │       COUNT(DISTINCT orders.id) as sales_count    │   │
      │  │    )                                              │   │
      │  │    ->join('order_details', ...)                   │   │
      │  │    ->join('items', ...)                           │   │
      │  │    ->join('deals', ...)                           │   │
      │  │    ->where('orders.status', Dispatched)           │   │
      │  │    ->where('created_at', '>=', start_date)        │   │
      │  │    ->where('created_at', '<=', end_date)          │   │
      │  │    ->where('deals.platform_id', platform_id)      │   │
      │  │    ->groupBy('deals.id', 'deals.name')            │   │
      │  │    ->orderByDesc('total_sales')                   │   │
      │  │    ->limit($limit)                                │   │
      │  │    ->get()                                        │   │
      │  └───────────────────────────────────────────────────┘   │
      │                          │                               │
      │  ┌───────────────────────▼──────────────────────────┐   │
      │  │  4. FORMAT RESULTS                               │   │
      │  │     • Map to array structure                     │   │
      │  │     • Cast types (int)                           │   │
      │  │     • Return array of deals                      │   │
      │  └──────────────────────────────────────────────────┘   │
      └──────────────────────────┬───────────────────────────────┘
                                 │
                                 ▼
                 ┌───────────────────────────────┐
                 │       DATABASE LAYER          │
                 │                               │
                 │  ┌─────────────────────────┐  │
                 │  │  orders (status = 6)    │  │
                 │  └──────────┬──────────────┘  │
                 │             │ JOIN             │
                 │  ┌──────────▼──────────────┐  │
                 │  │  order_details (qty)    │  │
                 │  └──────────┬──────────────┘  │
                 │             │ JOIN             │
                 │  ┌──────────▼──────────────┐  │
                 │  │  items (deal_id)        │  │
                 │  └──────────┬──────────────┘  │
                 │             │ JOIN             │
                 │  ┌──────────▼──────────────┐  │
                 │  │  deals (id, name)       │  │
                 │  └─────────────────────────┘  │
                 └───────────────┬───────────────┘
                                 │
                      AGGREGATION & GROUPING
                                 │
                                 ▼
                    ┌────────────────────────┐
                    │  RESULT SET            │
                    │  [                     │
                    │    {                   │
                    │      deal_id: 1,       │
                    │      deal_name: "...", │
                    │      total_sales: 1250,│
                    │      sales_count: 45   │
                    │    }                   │
                    │  ]                     │
                    └────────────┬───────────┘
                                 │
                                 ▼
              ┌──────────────────────────────────────┐
              │     CONTROLLER (Return Response)     │
              │                                      │
              │  ┌────────────────────────────────┐  │
              │  │  5. LOG SUCCESS                │  │
              │  │     • Filters used             │  │
              │  │     • Count of results         │  │
              │  └────────────────────────────────┘  │
              │                                      │
              │  ┌────────────────────────────────┐  │
              │  │  6. RETURN JSON RESPONSE       │  │
              │  │     {                          │  │
              │  │       "status": true,          │  │
              │  │       "message": "Success",    │  │
              │  │       "data": {                │  │
              │  │         "top_deals": [...]     │  │
              │  │       }                        │  │
              │  │     }                          │  │
              │  └────────────────────────────────┘  │
              └──────────────────┬───────────────────┘
                                 │
                        HTTP 200 OK
                                 │
                                 ▼
                         ┌───────────────┐
                         │    CLIENT     │
                         │  Receives     │
                         │  Response     │
                         └───────────────┘


═══════════════════════════════════════════════════════════════════════

                          ERROR HANDLING FLOW

┌─────────────────────────────────────────────────────────────────────┐
│                                                                     │
│  VALIDATION ERROR → Return 422 with error details                  │
│  ├─ Invalid date format                                            │
│  ├─ end_date before start_date                                     │
│  ├─ Invalid platform_id                                            │
│  └─ Invalid limit value                                            │
│                                                                     │
│  AUTHORIZATION ERROR → Return 403 (if user lacks platform role)    │
│                                                                     │
│  DATABASE ERROR → Return 500 with generic error message            │
│  ├─ Log detailed error with stack trace                            │
│  └─ Return safe error message to client                            │
│                                                                     │
└─────────────────────────────────────────────────────────────────────┘


═══════════════════════════════════════════════════════════════════════

                           DATA CALCULATION

┌─────────────────────────────────────────────────────────────────────┐
│                                                                     │
│  TOTAL_SALES Calculation:                                          │
│  ┌──────────────────────────────────────────────────────────────┐  │
│  │  SUM(order_details.qty)                                      │  │
│  │  WHERE orders.status = 6 (Dispatched)                        │  │
│  │  GROUP BY deals.id                                           │  │
│  └──────────────────────────────────────────────────────────────┘  │
│                                                                     │
│  SALES_COUNT Calculation:                                          │
│  ┌──────────────────────────────────────────────────────────────┐  │
│  │  COUNT(DISTINCT orders.id)                                   │  │
│  │  WHERE orders.status = 6 (Dispatched)                        │  │
│  │  GROUP BY deals.id                                           │  │
│  └──────────────────────────────────────────────────────────────┘  │
│                                                                     │
│  Example:                                                          │
│  Deal "Summer Sale" has:                                           │
│    - Order #1: 5 items → qty = 5                                  │
│    - Order #2: 10 items → qty = 10                                │
│    - Order #3: 15 items → qty = 15                                │
│                                                                     │
│  Result:                                                           │
│    total_sales = 5 + 10 + 15 = 30                                 │
│    sales_count = 3 (distinct orders)                               │
│                                                                     │
└─────────────────────────────────────────────────────────────────────┘


═══════════════════════════════════════════════════════════════════════

                        FILTER APPLICATION

┌─────────────────────────────────────────────────────────────────────┐
│                                                                     │
│  1. START_DATE Filter:                                             │
│     WHERE orders.created_at >= '2025-01-01'                        │
│                                                                     │
│  2. END_DATE Filter:                                               │
│     WHERE orders.created_at <= '2025-12-31'                        │
│                                                                     │
│  3. PLATFORM_ID Filter:                                            │
│     WHERE deals.platform_id = 3                                    │
│                                                                     │
│  4. LIMIT Filter:                                                  │
│     LIMIT 10                                                       │
│                                                                     │
│  5. STATUS Filter (Always Applied):                                │
│     WHERE orders.status = 6 (Dispatched only)                      │
│                                                                     │
└─────────────────────────────────────────────────────────────────────┘
```

## Key Components

### 1. **Route** → Entry point for API requests
### 2. **Controller** → Validates input and coordinates
### 3. **Service** → Contains business logic
### 4. **Database** → Performs data aggregation
### 5. **Response** → Returns formatted JSON

## Order Status Reference
- New = 1
- Ready = 2
- Simulated = 3
- Paid = 4
- Failed = 5
- **Dispatched = 6** ✅ (Used for successful orders)

## Files Involved
1. `routes/api.php` - Route definition
2. `app/Http/Controllers/Api/partner/SalesDashboardController.php` - Controller
3. `app/Services/Dashboard/SalesDashboardService.php` - Service
4. Database tables: `orders`, `order_details`, `items`, `deals`

