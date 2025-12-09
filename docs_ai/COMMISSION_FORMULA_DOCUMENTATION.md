# Commission Formula Model Documentation

## Overview
The `CommissionFormula` model represents commission combinations with initial and final commission values. It's designed to manage different commission structures for partners, affiliates, or any business relationship that requires commission tracking.

## Files Created

### 1. Model
**File**: `app/Models/CommissionFormula.php`

### 2. Migration
**File**: `database/migrations/2025_11_19_083342_create_commission_formulas_table.php`

### 3. Service
**File**: `app/Services/Commission/CommissionFormulaService.php`

### 4. Factory
**File**: `database/factories/CommissionFormulaFactory.php`

### 5. Seeder
**File**: `database/seeders/CommissionFormulaSeeder.php`

## Database Schema

### Table: `commission_formulas`

| Column | Type | Description |
|--------|------|-------------|
| id | bigint | Primary key |
| initial_commission | decimal(10,2) | Initial commission percentage or amount |
| final_commission | decimal(10,2) | Final commission percentage or amount |
| name | varchar(255) | Optional name for the commission formula |
| description | text | Description of the commission formula |
| is_active | boolean | Whether this formula is active (default: true) |
| created_by | bigint | User ID who created the record |
| updated_by | bigint | User ID who last updated the record |
| created_at | timestamp | Creation timestamp |
| updated_at | timestamp | Last update timestamp |
| deleted_at | timestamp | Soft delete timestamp |

### Indexes
- `is_active` - For quick filtering of active formulas
- `initial_commission, final_commission` - For range queries

## Model Features

### Traits Used
- ✅ `HasFactory` - For factory support
- ✅ `HasAuditing` - For audit trail (created_by, updated_by)
- ✅ `SoftDeletes` - For soft deletion support

### Fillable Fields
```php
[
    'initial_commission',
    'final_commission',
    'name',
    'description',
    'is_active',
    'created_by',
    'updated_by',
]
```

### Casts
```php
[
    'initial_commission' => 'decimal:2',
    'final_commission' => 'decimal:2',
    'is_active' => 'boolean',
]
```

### Methods

#### `getCommissionRange(): string`
Get the commission range as a formatted string.

**Returns**: "5.00% - 10.00%"

**Example:**
```php
$formula = CommissionFormula::find(1);
echo $formula->getCommissionRange(); // "5.00% - 10.00%"
```

#### `calculateCommission(float $value, string $type = 'initial'): float`
Calculate commission based on a value.

**Parameters:**
- `$value`: The base amount to calculate commission from
- `$type`: 'initial' or 'final' commission rate

**Example:**
```php
$formula = CommissionFormula::find(1);
$commission = $formula->calculateCommission(1000, 'initial'); // Returns 50 if initial_commission is 5%
```

### Scopes

#### `active()`
Get only active Plan label.

**Example:**
```php
$activeFormulas = CommissionFormula::active()->get();
```

#### `withinRange(float $min, float $max)`
Get formulas within a specific commission range.

**Example:**
```php
$formulas = CommissionFormula::withinRange(5, 20)->get();
```

## Service: CommissionFormulaService

### Available Methods

#### 1. `getCommissionFormulas(array $filters = []): EloquentCollection`
Get all Plan label with optional filters.

**Filters:**
- `is_active`: Filter by active status
- `search`: Search in name/description
- `min_commission`: Minimum commission
- `max_commission`: Maximum commission
- `with`: Relationships to load
- `order_by`: Column to order by
- `order_direction`: Order direction

**Example:**
```php
$formulas = $commissionFormulaService->getCommissionFormulas([
    'is_active' => true,
    'search' => 'premium',
    'order_by' => 'initial_commission'
]);
```

#### 2. `getActiveFormulas(): EloquentCollection`
Get only active Plan label.

**Example:**
```php
$activeFormulas = $commissionFormulaService->getActiveFormulas();
```

#### 3. `getCommissionFormulaById(int $id): ?CommissionFormula`
Get commission formula by ID.

**Example:**
```php
$formula = $commissionFormulaService->getCommissionFormulaById(1);
```

#### 4. `createCommissionFormula(array $data): ?CommissionFormula`
Create a new commission formula.

**Example:**
```php
$formula = $commissionFormulaService->createCommissionFormula([
    'name' => 'Gold Plan',
    'initial_commission' => 10.00,
    'final_commission' => 18.00,
    'is_active' => true
]);
```

#### 5. `updateCommissionFormula(int $id, array $data): ?CommissionFormula`
Update commission formula.

**Example:**
```php
$formula = $commissionFormulaService->updateCommissionFormula($id, [
    'final_commission' => 20.00
]);
```

#### 6. `deleteCommissionFormula(int $id): bool`
Soft delete commission formula.

**Example:**
```php
$success = $commissionFormulaService->deleteCommissionFormula($id);
```

#### 7. `toggleActive(int $id): bool`
Toggle active status.

**Example:**
```php
$success = $commissionFormulaService->toggleActive($id);
```

#### 8. `calculateCommission(int $formulaId, float $value, string $type = 'initial'): ?float`
Calculate commission for a value using a specific formula.

**Example:**
```php
$commission = $commissionFormulaService->calculateCommission(1, 1000, 'initial');
```

#### 9. `getForSelect(): EloquentCollection`
Get formulas for dropdown/select options.

**Example:**
```php
$formulas = $commissionFormulaService->getForSelect();
```

#### 10. `getStatistics(): array`
Get commission formula statistics.

**Returns:**
```php
[
    'total' => int,
    'active' => int,
    'inactive' => int,
    'avg_initial' => float,
    'avg_final' => float,
]
```

**Example:**
```php
$stats = $commissionFormulaService->getStatistics();
```

#### 11. `findByRange(float $initialCommission, float $finalCommission): ?CommissionFormula`
Find formula by exact commission range.

**Example:**
```php
$formula = $commissionFormulaService->findByRange(5.00, 10.00);
```

## Usage Examples

### In Livewire Component

```php
use App\Services\Commission\CommissionFormulaService;

class CommissionManagement extends Component
{
    protected $commissionFormulaService;
    
    public function boot(CommissionFormulaService $commissionFormulaService)
    {
        $this->commissionFormulaService = $commissionFormulaService;
    }
    
    public function render()
    {
        $formulas = $this->commissionFormulaService->getActiveFormulas();
        $stats = $this->commissionFormulaService->getStatistics();
        
        return view('livewire.commission-management', compact('formulas', 'stats'));
    }
}
```

### In Controller

```php
use App\Services\Commission\CommissionFormulaService;

class CommissionController extends Controller
{
    protected $commissionFormulaService;
    
    public function __construct(CommissionFormulaService $commissionFormulaService)
    {
        $this->commissionFormulaService = $commissionFormulaService;
    }
    
    public function index()
    {
        $formulas = $this->commissionFormulaService->getCommissionFormulas([
            'is_active' => true
        ]);
        
        return view('commissions.index', compact('formulas'));
    }
    
    public function calculate(Request $request)
    {
        $commission = $this->commissionFormulaService->calculateCommission(
            $request->formula_id,
            $request->amount,
            $request->type
        );
        
        return response()->json(['commission' => $commission]);
    }
}
```

### Direct Model Usage

```php
// Get active formulas
$formulas = CommissionFormula::active()->get();

// Calculate commission
$formula = CommissionFormula::find(1);
$commission = $formula->calculateCommission(1000, 'initial');

// Get commission range
echo $formula->getCommissionRange(); // "5.00% - 10.00%"

// Within range
$formulas = CommissionFormula::withinRange(5, 20)->get();
```

## Factory Usage

```php
// Create a single formula
$formula = CommissionFormula::factory()->create();

// Create active formula
$formula = CommissionFormula::factory()->active()->create();

// Create with specific range
$formula = CommissionFormula::factory()->withRange(10, 20)->create();

// Create multiple formulas
CommissionFormula::factory()->count(5)->create();
```

## Seeder

Run the seeder to populate with sample data:

```bash
php artisan db:seed --class=CommissionFormulaSeeder
```

This will create 5 predefined commission plans:
- Starter: 5% - 10%
- Standard: 8% - 15%
- Premium: 12% - 20%
- Elite: 15% - 25%
- VIP: 20% - 30%

## Migration

Run the migration:

```bash
php artisan migrate
```

Rollback if needed:

```bash
php artisan migrate:rollback
```

## Testing Examples

```php
public function test_create_commission_formula()
{
    $formula = CommissionFormula::factory()->create([
        'initial_commission' => 10.00,
        'final_commission' => 20.00
    ]);
    
    $this->assertDatabaseHas('commission_formulas', [
        'id' => $formula->id,
        'initial_commission' => 10.00
    ]);
}

public function test_calculate_commission()
{
    $formula = CommissionFormula::factory()->create([
        'initial_commission' => 10.00
    ]);
    
    $commission = $formula->calculateCommission(1000, 'initial');
    
    $this->assertEquals(100.00, $commission);
}

public function test_get_active_formulas()
{
    CommissionFormula::factory()->active()->count(3)->create();
    CommissionFormula::factory()->inactive()->count(2)->create();
    
    $active = CommissionFormula::active()->count();
    
    $this->assertEquals(3, $active);
}
```

## Best Practices

1. **Always use the service** for business logic operations
2. **Use scopes** for common queries (active, withinRange)
3. **Leverage factory** for testing
4. **Use soft deletes** - don't permanently delete formulas
5. **Validate commission ranges** - ensure final > initial
6. **Cache frequently used formulas** for performance
7. **Audit trail** - created_by and updated_by are tracked automatically

## Example Validation Rules

```php
public function rules()
{
    return [
        'name' => 'required|string|max:255',
        'initial_commission' => 'required|numeric|min:0|max:100',
        'final_commission' => 'required|numeric|min:0|max:100|gt:initial_commission',
        'description' => 'nullable|string',
        'is_active' => 'boolean',
    ];
}
```

## Integration Points

The CommissionFormula can be used with:
- Platform commissions
- Affiliate programs
- Partner agreements
- Sales commission structures
- Referral programs
- Any commission-based system

---

**Status**: ✅ Complete and Ready for Use
**Date**: November 19, 2025

