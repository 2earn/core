# Commission Formula Quick Reference

## Model
```php
use App\Models\CommissionFormula;
```

## Service
```php
use App\Services\Commission\CommissionFormulaService;
```

## Inject in Livewire
```php
protected $commissionFormulaService;

public function boot(CommissionFormulaService $commissionFormulaService)
{
    $this->commissionFormulaService = $commissionFormulaService;
}
```

## Common Operations

### Get Active Formulas
```php
$formulas = $commissionFormulaService->getActiveFormulas();
```

### Get All with Filters
```php
$formulas = $commissionFormulaService->getCommissionFormulas([
    'is_active' => true,
    'search' => 'premium',
    'order_by' => 'initial_commission'
]);
```

### Get by ID
```php
$formula = $commissionFormulaService->getCommissionFormulaById($id);
```

### Create Formula
```php
$formula = $commissionFormulaService->createCommissionFormula([
    'name' => 'Gold Plan',
    'initial_commission' => 10.00,
    'final_commission' => 18.00,
    'description' => 'Gold tier commission',
    'is_active' => true
]);
```

### Update Formula
```php
$formula = $commissionFormulaService->updateCommissionFormula($id, [
    'final_commission' => 20.00
]);
```

### Delete Formula (Soft Delete)
```php
$success = $commissionFormulaService->deleteCommissionFormula($id);
```

### Toggle Active Status
```php
$success = $commissionFormulaService->toggleActive($id);
```

### Calculate Commission
```php
// Using service
$commission = $commissionFormulaService->calculateCommission($formulaId, 1000, 'initial');

// Using model
$formula = CommissionFormula::find($id);
$commission = $formula->calculateCommission(1000, 'final');
```

### Get for Select/Dropdown
```php
$formulas = $commissionFormulaService->getForSelect();
```

### Get Statistics
```php
$stats = $commissionFormulaService->getStatistics();
// Returns: ['total', 'active', 'inactive', 'avg_initial', 'avg_final']
```

### Find by Range
```php
$formula = $commissionFormulaService->findByRange(5.00, 10.00);
```

## Model Methods

### Get Commission Range
```php
$formula = CommissionFormula::find($id);
echo $formula->getCommissionRange(); // "5.00% - 10.00%"
```

### Calculate Commission
```php
$commission = $formula->calculateCommission(1000, 'initial');
```

## Scopes

### Active Formulas
```php
CommissionFormula::active()->get();
```

### Within Range
```php
CommissionFormula::withinRange(5, 20)->get();
```

## Factory

### Create Single
```php
$formula = CommissionFormula::factory()->create();
```

### Create Active
```php
$formula = CommissionFormula::factory()->active()->create();
```

### Create with Specific Range
```php
$formula = CommissionFormula::factory()->withRange(10, 20)->create();
```

### Create Multiple
```php
CommissionFormula::factory()->count(5)->create();
```

## Database Schema

```sql
CREATE TABLE commission_formulas (
    id BIGINT PRIMARY KEY,
    initial_commission DECIMAL(10,2),
    final_commission DECIMAL(10,2),
    name VARCHAR(255) NULLABLE,
    description TEXT NULLABLE,
    is_active BOOLEAN DEFAULT TRUE,
    created_by BIGINT NULLABLE,
    updated_by BIGINT NULLABLE,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    deleted_at TIMESTAMP NULLABLE
);
```

## Migration Commands

```bash
# Run migration
php artisan migrate

# Rollback
php artisan migrate:rollback

# Seed sample data
php artisan db:seed --class=CommissionFormulaSeeder
```

## Sample Seeded Data

The seeder creates 5 plans:
- **Starter**: 5% - 10%
- **Standard**: 8% - 15%
- **Premium**: 12% - 20%
- **Elite**: 15% - 25%
- **VIP**: 20% - 30%

## Validation Example

```php
[
    'name' => 'required|string|max:255',
    'initial_commission' => 'required|numeric|min:0|max:100',
    'final_commission' => 'required|numeric|min:0|max:100|gt:initial_commission',
    'description' => 'nullable|string',
    'is_active' => 'boolean',
]
```

## Return Types
- **EloquentCollection methods**: Return `Illuminate\Database\Eloquent\Collection`
- **Single entity methods**: Return `CommissionFormula|null`
- **Boolean operations**: Return `bool`
- **Statistics**: Return `array`
- **Calculate**: Return `float|null`

## Key Features
✅ Initial and final commission combinations
✅ Soft delete support
✅ Audit trail (created_by, updated_by)
✅ Active/inactive status
✅ Commission calculation helpers
✅ Range queries and filtering
✅ Factory for testing
✅ Seeder with sample data
✅ Comprehensive service layer
✅ Error handling with logging

