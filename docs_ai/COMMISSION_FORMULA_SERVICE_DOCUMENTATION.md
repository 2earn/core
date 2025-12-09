# Commission Formula Service Documentation

## Overview
The `CommissionFormulaService` provides a centralized service layer for managing Plan label throughout the application. It encapsulates all business logic related to commission formula operations, ensuring consistent behavior and easier maintenance.

**Location:** `app/Services/Commission/CommissionFormulaService.php`

## Purpose
- Centralize commission formula business logic
- Provide reusable methods for controllers and other services
- Handle error logging and exception management
- Ensure data consistency across the application

## Available Methods

### 1. getCommissionFormulas(array $filters = []): EloquentCollection

Get all Plan label with optional filters.

**Parameters:**
- `$filters` (array): Optional filters to apply
  - `is_active` (boolean): Filter by active status
  - `search` (string): Search in name or description
  - `min_commission` (float): Minimum commission value
  - `max_commission` (float): Maximum commission value
  - `with` (array): Relationships to eager load
  - `order_by` (string): Column to order by (default: 'created_at')
  - `order_direction` (string): Order direction (default: 'desc')

**Returns:** `EloquentCollection` of CommissionFormula models

**Example:**
```php
$service = app(CommissionFormulaService::class);

$formulas = $service->getCommissionFormulas([
    'is_active' => true,
    'search' => 'Premium',
    'order_by' => 'initial_commission'
]);
```

---

### 2. getActiveFormulas(): EloquentCollection

Get only active Plan label, ordered by initial commission.

**Returns:** `EloquentCollection` of active CommissionFormula models

**Example:**
```php
$activeFormulas = $service->getActiveFormulas();
```

---

### 3. getCommissionFormulaById(int $id): ?CommissionFormula

Get a specific commission formula by its ID.

**Parameters:**
- `$id` (int): The commission formula ID

**Returns:** `CommissionFormula|null`

**Example:**
```php
$formula = $service->getCommissionFormulaById(1);
if ($formula) {
    echo $formula->name;
}
```

---

### 4. createCommissionFormula(array $data): ?CommissionFormula

Create a new commission formula.

**Parameters:**
- `$data` (array): Commission formula data
  - `name` (string, required): Formula name
  - `description` (string, optional): Formula description
  - `initial_commission` (decimal, required): Initial commission percentage
  - `final_commission` (decimal, required): Final commission percentage
  - `is_active` (boolean, default: true): Active status
  - `created_by` (int, optional): User ID who created it
  - `updated_by` (int, optional): User ID who updated it

**Returns:** `CommissionFormula|null`

**Example:**
```php
$formula = $service->createCommissionFormula([
    'name' => 'Standard Commission',
    'description' => 'Standard commission for regular deals',
    'initial_commission' => 5.00,
    'final_commission' => 10.00,
    'is_active' => true,
    'created_by' => auth()->id()
]);
```

---

### 5. updateCommissionFormula(int $id, array $data): ?CommissionFormula

Update an existing commission formula.

**Parameters:**
- `$id` (int): The commission formula ID
- `$data` (array): Data to update

**Returns:** `CommissionFormula|null` (refreshed model)

**Example:**
```php
$formula = $service->updateCommissionFormula(1, [
    'name' => 'Updated Commission Name',
    'final_commission' => 12.00,
    'updated_by' => auth()->id()
]);
```

---

### 6. deleteCommissionFormula(int $id): bool

Soft delete a commission formula.

**Parameters:**
- `$id` (int): The commission formula ID

**Returns:** `bool` (success status)

**Example:**
```php
$deleted = $service->deleteCommissionFormula(1);
if ($deleted) {
    echo "Formula deleted successfully";
}
```

---

### 7. toggleActive(int $id): bool

Toggle the active status of a commission formula.

**Parameters:**
- `$id` (int): The commission formula ID

**Returns:** `bool` (success status)

**Example:**
```php
$toggled = $service->toggleActive(1);
```

---

### 8. calculateCommission(int $formulaId, float $value, string $type = 'initial'): ?float

Calculate commission for a given value using a specific formula.

**Parameters:**
- `$formulaId` (int): The commission formula ID
- `$value` (float): The value to calculate commission on
- `$type` (string): Commission type ('initial' or 'final', default: 'initial')

**Returns:** `float|null` (calculated commission value)

**Example:**
```php
$commission = $service->calculateCommission(1, 1000.00, 'initial');
// If formula has 5% initial commission: returns 50.00

$finalCommission = $service->calculateCommission(1, 1000.00, 'final');
// If formula has 10% final commission: returns 100.00
```

---

### 9. getForSelect(): EloquentCollection

Get active formulas optimized for dropdown/select fields.

**Returns:** `EloquentCollection` with id, name, initial_commission, and final_commission

**Example:**
```php
$formulas = $service->getForSelect();

// In Blade:
@foreach($formulas as $formula)
    <option value="{{ $formula->id }}">
        {{ $formula->name }} ({{ $formula->initial_commission }}% - {{ $formula->final_commission }}%)
    </option>
@endforeach
```

---

### 10. getPaginatedFormulas(array $filters = [], ?int $page = null, int $perPage = 10): array

Get paginated Plan label for API responses with commission_range appended.

**Parameters:**
- `$filters` (array): Optional filters
  - `is_active` (boolean): Filter by active status
  - `search` (string): Search by name
  - `order_by` (string): Column to order by (default: 'created_at')
  - `order_direction` (string): Order direction (default: 'desc')
- `$page` (int|null): Page number (if null, returns all results without pagination)
- `$perPage` (int): Results per page (default: 10)

**Returns:** 
```php
[
    'formulas' => Collection|LengthAwarePaginator,
    'total' => int
]
```

**Example:**
```php
$result = $service->getPaginatedFormulas([
    'is_active' => true,
    'search' => 'premium'
], 1, 15);

$formulas = $result['formulas'];
$total = $result['total'];

// Each formula has commission_range appended:
// $formulas[0]->commission_range => "5% - 10%"
```

---

## Usage in Controllers

### Example: CommissionFormulaPartnerController

```php
<?php

namespace App\Http\Controllers\Api\partner;

use App\Http\Controllers\Controller;
use App\Services\Commission\CommissionFormulaService;
use Illuminate\Http\Request;

class CommissionFormulaPartnerController extends Controller
{
    protected CommissionFormulaService $commissionFormulaService;

    public function __construct(CommissionFormulaService $commissionFormulaService)
    {
        $this->commissionFormulaService = $commissionFormulaService;
    }

    public function index(Request $request)
    {
        $filters = [
            'is_active' => $request->boolean('active', null),
            'search' => $request->input('search')
        ];

        $result = $this->commissionFormulaService->getPaginatedFormulas(
            $filters,
            $request->input('page'),
            10
        );

        return response()->json([
            'status' => true,
            'data' => $result['formulas'],
            'total' => $result['total']
        ]);
    }
}
```

---

## Usage in Livewire Components

### Example: DealsCreateUpdate

```php
<?php

namespace App\Livewire;

use App\Services\Commission\CommissionFormulaService;
use Livewire\Component;

class DealsCreateUpdate extends Component
{
    public $commissionFormulas;
    protected CommissionFormulaService $commissionFormulaService;

    public function boot(CommissionFormulaService $commissionFormulaService)
    {
        $this->commissionFormulaService = $commissionFormulaService;
    }

    public function mount()
    {
        // Get active formulas for dropdown
        $this->commissionFormulas = $this->commissionFormulaService->getForSelect();
    }
}
```

---

## Error Handling

All methods in the service include try-catch blocks and log errors using Laravel's Log facade. When an error occurs:

1. The error is logged with context: `Log::error('Error message: ' . $e->getMessage())`
2. A safe default value is returned:
   - Collections return empty `EloquentCollection()`
   - Single objects return `null`
   - Booleans return `false`
   - Arrays return empty arrays with appropriate structure

**Example:**
```php
// If database error occurs
$formulas = $service->getActiveFormulas();
// Returns: new EloquentCollection() (empty collection)

$formula = $service->getCommissionFormulaById(999);
// Returns: null

$deleted = $service->deleteCommissionFormula(999);
// Returns: false
```

---

## Integration Points

### 1. Deal Creation/Update
The service is used in the Deals workflow to populate commission formula dropdowns and calculate commission values.

### 2. Partner API
The `getPaginatedFormulas()` method is exposed via the Partner API endpoint:
- **Endpoint:** `GET /api/partner/commission-formulas`
- **Route Name:** `api_partner_commission_formulas_index`

### 3. Commission Calculations
Use the `calculateCommission()` method anywhere commission calculations are needed based on formula rates.

---

## Best Practices

1. **Always use the service layer** instead of directly querying CommissionFormula model in controllers
2. **Dependency injection** - Inject the service in constructors or use method injection
3. **Error handling** - Always check for null returns or empty collections
4. **Logging** - Service automatically logs errors; check logs when debugging
5. **Caching** - Consider adding caching for frequently accessed formulas (e.g., getForSelect)

---

## Testing Examples

```php
use App\Services\Commission\CommissionFormulaService;
use App\Models\CommissionFormula;

class CommissionFormulaServiceTest extends TestCase
{
    protected $service;

    public function setUp(): void
    {
        parent::setUp();
        $this->service = app(CommissionFormulaService::class);
    }

    public function test_get_active_formulas()
    {
        CommissionFormula::factory()->create(['is_active' => true]);
        CommissionFormula::factory()->create(['is_active' => false]);

        $formulas = $this->service->getActiveFormulas();

        $this->assertCount(1, $formulas);
        $this->assertTrue($formulas->first()->is_active);
    }

    public function test_calculate_commission()
    {
        $formula = CommissionFormula::factory()->create([
            'initial_commission' => 5.00,
            'final_commission' => 10.00
        ]);

        $initialCommission = $this->service->calculateCommission($formula->id, 1000, 'initial');
        $finalCommission = $this->service->calculateCommission($formula->id, 1000, 'final');

        $this->assertEquals(50.00, $initialCommission);
        $this->assertEquals(100.00, $finalCommission);
    }
}
```

---

## Related Files

- **Model:** `app/Models/CommissionFormula.php`
- **Controller:** `app/Http/Controllers/Api/partner/CommissionFormulaPartnerController.php`
- **Migration:** `database/migrations/*_create_commission_formulas_table.php`
- **Service:** `app/Services/Commission/CommissionFormulaService.php`
- **Routes:** `routes/api.php` (Partner API routes)

---

## Future Enhancements

1. **Caching Layer** - Add Redis caching for frequently accessed formulas
2. **Event Dispatching** - Dispatch events on create/update/delete operations
3. **Validation Service** - Add validation methods for commission ranges
4. **Bulk Operations** - Add methods for bulk create/update/delete
5. **Formula Versioning** - Track formula changes over time
6. **Advanced Calculations** - Support for tiered commission structures

---

## Support

For questions or issues related to the CommissionFormulaService:
1. Check the error logs: `storage/logs/laravel.log`
2. Review the test suite for usage examples
3. Refer to the CommissionFormula model documentation
4. Check the API documentation for Partner endpoints

