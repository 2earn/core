# Plan Label Quick Reference Guide

## Overview
The `PlanLabel` model (formerly `CommissionFormula`) represents commission plans with additional attributes like step, rate, and star ratings.

---

## Model Properties

### Required Fields
- `name` - Plan name (e.g., "Modest", "Standard", "Good")
- `initial_commission` - Starting commission percentage (0-100)
- `final_commission` - Ending commission percentage (0-100)

### Optional Fields
- `step` - Integer step value
- `rate` - Float rate value (with 2 decimals)
- `stars` - Rating from 1 to 5 stars
- `description` - Text description of the plan
- `is_active` - Boolean (default: true)

---

## Service Usage

### Get All Plan Labels
```php
use App\Services\Commission\PlanLabelService;

$service = app(PlanLabelService::class);

// Get all labels
$labels = $service->getPlanLabels();

// Get with filters
$labels = $service->getPlanLabels([
    'is_active' => true,
    'search' => 'standard',
    'stars' => 5,
    'step' => 10,
    'order_by' => 'stars',
    'order_direction' => 'desc'
]);
```

### Get Active Labels
```php
$activeLabels = $service->getActiveLabels();
```

### Get Labels by Stars
```php
// Get all 5-star labels
$fiveStarLabels = $service->getLabelsByStars(5);
```

### Get Label by ID
```php
$label = $service->getPlanLabelById(1);
```

### Create a Plan Label
```php
$label = $service->createPlanLabel([
    'name' => 'Premium Plan',
    'step' => 15,
    'rate' => 3.5,
    'stars' => 4,
    'initial_commission' => 20.00,
    'final_commission' => 30.00,
    'description' => 'Premium commission plan',
    'is_active' => true,
    'created_by' => auth()->id(),
]);
```

### Update a Plan Label
```php
$label = $service->updatePlanLabel(1, [
    'name' => 'Updated Plan Name',
    'rate' => 4.0,
    'stars' => 5,
    'updated_by' => auth()->id(),
]);
```

### Delete a Plan Label
```php
$success = $service->deletePlanLabel(1); // Soft delete
```

### Toggle Active Status
```php
$success = $service->toggleActive(1);
```

### Calculate Commission
```php
$commission = $service->calculateCommission(1, 1000.00, 'initial');
// Returns commission amount based on initial_commission percentage
```

### Get Labels for Dropdown
```php
$selectOptions = $service->getForSelect();
// Returns: id, name, initial_commission, final_commission, step, rate, stars
```

### Get Paginated Labels
```php
$result = $service->getPaginatedLabels(
    ['is_active' => true, 'stars' => 5],
    1, // page
    10 // per page
);

$labels = $result['labels'];
$total = $result['total'];
```

### Get Statistics
```php
$stats = $service->getStatistics();
// Returns:
// [
//     'total' => 10,
//     'active' => 8,
//     'inactive' => 2,
//     'avg_initial' => 25.5,
//     'avg_final' => 45.2,
//     'avg_rate' => 3.2
// ]
```

---

## Model Usage

### Query Examples
```php
use App\Models\PlanLabel;

// Get all active labels
$labels = PlanLabel::active()->get();

// Get labels by stars
$fiveStarLabels = PlanLabel::byStars(5)->get();

// Get labels within commission range
$labels = PlanLabel::withinRange(10.0, 50.0)->get();

// Combined scopes
$labels = PlanLabel::active()
    ->byStars(4)
    ->orderBy('step')
    ->get();
```

### Relationships
```php
// Get icon image
$iconUrl = $label->iconImage?->url;

// Get all deals using this plan
$deals = $label->deals;
```

### Calculate Commission
```php
// Calculate initial commission
$commission = $label->calculateCommission(1000.00, 'initial');

// Calculate final commission
$commission = $label->calculateCommission(1000.00, 'final');
```

### Get Commission Range
```php
$range = $label->getCommissionRange();
// Returns: "10% - 20%"
```

---

## API Usage

### Get Plan Labels (API Endpoint)
```http
GET /api/partner/commission-formulas?page=1&stars=5&step=10&active=1
```

**Response:**
```json
{
    "status": true,
    "data": [...],
    "total": 10
}
```

---

## Livewire Components

### Index Component
```php
use App\Livewire\PlanLabelIndex;

// Route: /commission/formula/index
// Features:
// - Search by name/description
// - Filter by active status
// - Filter by stars (1-5)
// - Sort by any column
// - Toggle active status
// - Delete with confirmation
```

### Create/Update Component
```php
use App\Livewire\PlanLabelCreateUpdate;

// Routes:
// - Create: /commission/formula/create
// - Edit: /commission/formula/edit/{id}

// Features:
// - Create new plan label
// - Edit existing plan label
// - Upload icon image
// - Validate all fields
// - Real-time validation
```

---

## Validation Rules

### Plan Label Fields
```php
[
    'name' => 'required|string|max:255',
    'step' => 'nullable|integer',
    'rate' => 'nullable|numeric|min:0',
    'stars' => 'nullable|integer|min:1|max:5',
    'initial_commission' => 'required|numeric|min:0|max:100',
    'final_commission' => 'required|numeric|min:0|max:100|gt:initial_commission',
    'description' => 'nullable|string',
    'is_active' => 'boolean',
    'iconImage' => 'nullable|image|mimes:jpeg,png,jpg,svg,webp|max:2048',
]
```

---

## Database Queries

### Find by Commission Range
```php
$label = PlanLabel::where('initial_commission', 20.0)
    ->where('final_commission', 30.0)
    ->first();
```

### Get Nearest Plan
```php
$label = PlanLabel::where('is_active', true)
    ->selectRaw('*, ABS(final_commission - ?) as commission_diff', [25.0])
    ->orderBy('commission_diff', 'asc')
    ->first();
```

### Filter by Multiple Criteria
```php
$labels = PlanLabel::where('is_active', true)
    ->where('stars', '>=', 3)
    ->whereBetween('rate', [2.0, 4.0])
    ->orderBy('step')
    ->get();
```

---

## Common Use Cases

### 1. Get Best Plans (5 stars)
```php
$bestPlans = PlanLabel::active()
    ->byStars(5)
    ->orderBy('rate', 'desc')
    ->get();
```

### 2. Get Plans for Dropdown
```php
$planOptions = PlanLabel::active()
    ->select('id', 'name', 'stars', 'rate')
    ->orderBy('stars', 'desc')
    ->orderBy('rate', 'desc')
    ->get()
    ->map(fn($plan) => [
        'value' => $plan->id,
        'label' => "{$plan->name} (" . str_repeat('⭐', $plan->stars) . ")"
    ]);
```

### 3. Assign Plan to Deal
```php
use App\Models\Deal;

$deal = Deal::find(1);
$plan = PlanLabel::active()
    ->where('initial_commission', '<=', $deal->initial_commission)
    ->where('final_commission', '>=', $deal->final_commission)
    ->first();

if ($plan) {
    $deal->plan = $plan->id;
    $deal->save();
}
```

### 4. Get Plan Statistics by Stars
```php
$statsByStars = PlanLabel::active()
    ->selectRaw('stars, COUNT(*) as count, AVG(rate) as avg_rate')
    ->groupBy('stars')
    ->orderBy('stars')
    ->get();
```

---

## Migration Notes

### From CommissionFormula to PlanLabel

1. **Table renamed:** `commission_formulas` → `plan_labels`
2. **New fields added:** `step`, `rate`, `stars`
3. **All data preserved:** Existing records remain intact
4. **New fields nullable:** Allows gradual data population

### Rollback
```bash
php artisan migrate:rollback
```

---

## Tips & Best Practices

1. **Always use the service layer** for business logic
2. **Filter by active status** when showing plans to users
3. **Use star ratings** to highlight premium plans
4. **Validate stars** to ensure 1-5 range
5. **Use scopes** for common queries (active, byStars)
6. **Soft delete** is enabled - use restore() if needed
7. **Eager load relationships** to avoid N+1 queries
8. **Cache active labels** if frequently accessed

---

## Example: Complete CRUD Flow

```php
use App\Services\Commission\PlanLabelService;

$service = app(PlanLabelService::class);

// Create
$label = $service->createPlanLabel([
    'name' => 'Ultimate Plan',
    'step' => 20,
    'rate' => 5.0,
    'stars' => 5,
    'initial_commission' => 50.0,
    'final_commission' => 80.0,
    'description' => 'Our best plan',
    'is_active' => true,
]);

// Read
$label = $service->getPlanLabelById($label->id);

// Update
$label = $service->updatePlanLabel($label->id, [
    'rate' => 5.5,
]);

// Delete
$service->deletePlanLabel($label->id);
```

---

## Related Documentation

- [Full Refactoring Summary](./COMMISSION_FORMULA_TO_PLAN_LABEL_REFACTORING.md)
- Model: `app/Models/PlanLabel.php`
- Service: `app/Services/Commission/PlanLabelService.php`
- Migration: `database/migrations/2025_12_09_140513_add_fields_and_rename_commission_formulas_to_plan_labels_table.php`

