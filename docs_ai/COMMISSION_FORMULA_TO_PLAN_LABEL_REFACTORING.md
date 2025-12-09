# Commission Formula to Plan Label Refactoring Summary

## Date: December 9, 2025

## Overview
This document summarizes the complete refactoring of the `CommissionFormula` model to `PlanLabel`, including the addition of new fields and renaming of all related files and references.

---

## Database Changes

### Migration: `2025_12_09_140513_add_fields_and_rename_commission_formulas_to_plan_labels_table`

**New Fields Added:**
- `step` (integer, nullable) - Step value for the plan
- `rate` (float, nullable) - Rate value with 2 decimal places
- `stars` (unsignedTinyInteger, nullable) - Rating from 1 to 5 stars

**Table Renamed:**
- `commission_formulas` → `plan_labels`

---

## Model Changes

### Created: `app/Models/PlanLabel.php`
**New Model Features:**
- Renamed from `CommissionFormula` to `PlanLabel`
- Updated table name to `plan_labels`
- Added new fillable fields: `step`, `rate`, `stars`
- Added new casts for the new fields
- Updated default image path constant
- Added new scope: `scopeByStars($query, int $stars)`
- Maintained all existing relationships and methods

**Key Constants:**
- `IMAGE_TYPE_ICON` = 'icon'
- `DEFAULT_IMAGE_TYPE_ICON` = 'resources/images/plan-labels/icon/default-plan-label-icon.png'

---

## Service Changes

### Created: `app/Services/Commission/PlanLabelService.php`
**Service Methods:**
- `getPlanLabels(array $filters = [])` - Get all plan labels with filters
- `getActiveLabels()` - Get only active labels
- `getPlanLabelById(int $id)` - Get label by ID
- `createPlanLabel(array $data)` - Create new label
- `updatePlanLabel(int $id, array $data)` - Update existing label
- `deletePlanLabel(int $id)` - Delete label (soft delete)
- `toggleActive(int $id)` - Toggle active status
- `calculateCommission(int $labelId, float $value, string $type = 'initial')` - Calculate commission
- `getForSelect()` - Get labels for dropdown/select
- `getPaginatedLabels(array $filters = [], ?int $page = null, int $perPage = 10)` - Get paginated labels
- `getStatistics()` - Get label statistics including avg_rate
- `findByRange(float $initialCommission, float $finalCommission)` - Find by commission range
- `getLabelsByStars(int $stars)` - Get labels by star rating

**New Filters Supported:**
- `stars` - Filter by star rating (1-5)
- `step` - Filter by step value
- All existing filters maintained

---

## Controller Changes

### Created: `app/Http/Controllers/Api/partner/PlanLabelPartnerController.php`
**New Features:**
- Renamed from `CommissionFormulaPartnerController`
- Added validation for `stars` (min:1, max:5)
- Added validation for `step` (integer)
- Updated all references to use `PlanLabelService`

---

## Livewire Component Changes

### Created: `app/Livewire/PlanLabelIndex.php`
**New Features:**
- Renamed from `CommissionFormulaIndex`
- Added `$filterStars` property for filtering by stars
- Added `updatingFilterStars()` method
- Updated all references to use `PlanLabelService`
- Updated view reference to `livewire.plan-label-index`

### Created: `app/Livewire/PlanLabelCreateUpdate.php`
**New Features:**
- Renamed from `CommissionFormulaCreateUpdate`
- Added new properties: `$step`, `$rate`, `$stars`
- Added validation rules for new fields:
  - `step` - nullable|integer
  - `rate` - nullable|numeric|min:0
  - `stars` - nullable|integer|min:1|max:5
- Updated all references to use `PlanLabelService`
- Updated view reference to `livewire.plan-label-create-update`

---

## Route Changes

### File: `routes/web.php`
**Updated Routes:**
```php
// Commission Formula routes (keeping same route names for backward compatibility)
Route::prefix('/commission/formula')->name('commission_formula_')->group(function () {
    Route::get('/index', \App\Livewire\PlanLabelIndex::class)->name('index');
    Route::get('/create', \App\Livewire\PlanLabelCreateUpdate::class)->name('create');
    Route::get('/edit/{id}', \App\Livewire\PlanLabelCreateUpdate::class)->name('edit');
});
```

### File: `routes/api.php`
**Updated:**
- Import changed from `CommissionFormulaPartnerController` to `PlanLabelPartnerController`
- Route still uses old endpoint name for backward compatibility

---

## Model Relationship Updates

### File: `app/Models/Deal.php`
**Updated Methods:**
- `commissionPlan()` - Now returns `belongsTo(PlanLabel::class)`
- `determinePlan()` - Now uses `PlanLabel::where()` instead of `CommissionFormula::where()`

---

## View Updates

### File: `resources/views/livewire/deals-index.blade.php`
**Updated References:**
- Changed `\App\Models\CommissionFormula::DEFAULT_IMAGE_TYPE_ICON` to `\App\Models\PlanLabel::DEFAULT_IMAGE_TYPE_ICON`

---

## Seeder Updates

### File: `database/seeders/PlanLabelSeeder.php`
**Updated:**
- Import changed from `CommissionFormula` to `PlanLabel`
- All `CommissionFormula::create()` calls changed to `PlanLabel::create()`
- **Note:** Seeder class name kept as `PlanLabelSeeder` for backward compatibility

**Seeder Data Now Includes:**
- All original fields
- New `step` values (6, 7, 9, 19, 19, 14, 19)
- New `rate` values (0.50, 1.00, 2.00, 3.00, 4.00, 4.50, 5.00)
- New `stars` values (0, 1, 2, 3, 4, 4, 5)

### File: `database/seeders/AssignCommissionPlanToDealsSeeder.php`
**Updated:**
- Import changed from `CommissionFormula` to `PlanLabel`
- All references updated to use `PlanLabel`
- Error messages updated to reference "plan label"

---

## Files Created

1. `app/Models/PlanLabel.php` - New model
2. `app/Services/Commission/PlanLabelService.php` - New service
3. `app/Http/Controllers/Api/partner/PlanLabelPartnerController.php` - New controller
4. `app/Livewire/PlanLabelIndex.php` - New Livewire component
5. `app/Livewire/PlanLabelCreateUpdate.php` - New Livewire component
6. `database/migrations/2025_12_09_140513_add_fields_and_rename_commission_formulas_to_plan_labels_table.php` - Migration

---

## Files to Update (Manual Action Required)

### Views that need to be created/renamed:
1. `resources/views/livewire/plan-label-index.blade.php` (copy from commission-formula-index.blade.php)
2. `resources/views/livewire/plan-label-create-update.blade.php` (copy from commission-formula-create-update.blade.php)

**Updates needed in these views:**
- Change all `CommissionFormula` references to `PlanLabel`
- Update form to include fields for `step`, `rate`, and `stars`
- Update table columns to display new fields

---

## Old Files (Can be kept for reference or deleted)

1. `app/Models/CommissionFormula.php`
2. `app/Services/Commission/CommissionFormulaService.php`
3. `app/Http/Controllers/Api/partner/CommissionFormulaPartnerController.php`
4. `app/Livewire/CommissionFormulaIndex.php`
5. `app/Livewire/CommissionFormulaCreateUpdate.php`
6. `app/Livewire/Commission/CommissionFormulaIndex.php` (if exists)
7. `resources/views/livewire/commission-formula-index.blade.php`
8. `resources/views/livewire/commission-formula-create-update.blade.php`

---

## Testing Checklist

- [ ] Verify migration ran successfully
- [ ] Test creating new plan labels with new fields
- [ ] Test updating existing plan labels
- [ ] Test filtering by stars rating
- [ ] Test filtering by step
- [ ] Verify deals relationship still works
- [ ] Test API endpoints
- [ ] Test Livewire components
- [ ] Verify seeders work correctly
- [ ] Test image upload functionality
- [ ] Verify all references updated correctly

---

## Database Schema

### Table: `plan_labels`

| Column | Type | Attributes |
|--------|------|------------|
| id | bigint | Primary Key |
| step | integer | Nullable |
| rate | float(8,2) | Nullable |
| stars | tinyint unsigned | Nullable, 1-5 |
| initial_commission | decimal(10,2) | Required |
| final_commission | decimal(10,2) | Required |
| name | varchar(255) | Nullable |
| description | text | Nullable |
| is_active | boolean | Default: true |
| created_by | bigint unsigned | Nullable |
| updated_by | bigint unsigned | Nullable |
| created_at | timestamp | |
| updated_at | timestamp | |
| deleted_at | timestamp | Nullable (soft delete) |

**Indexes:**
- Primary: id
- Index: is_active
- Index: (initial_commission, final_commission)

---

## Backward Compatibility Notes

1. **Route Names:** All route names kept the same (`commission_formula_*`) to maintain backward compatibility
2. **API Endpoints:** API endpoint paths remain unchanged
3. **Database:** Table renamed, but all data preserved during migration
4. **Relationships:** All relationships maintained with updated model references

---

## Next Steps

1. ✅ Migration completed
2. ✅ Models created
3. ✅ Services created
4. ✅ Controllers created
5. ✅ Livewire components created
6. ✅ Routes updated
7. ⏳ Create/update Blade views (manual)
8. ⏳ Test all functionality (manual)
9. ⏳ Update any API documentation (manual)
10. ⏳ Delete old files after verification (manual)

---

## Notes

- The original `CommissionFormula` model and related files are still present in the codebase
- Consider deleting them after thorough testing
- All new fields are nullable to allow gradual migration
- Star rating validation ensures values between 1-5
- Image paths updated to use `plan-labels` directory instead of `commission-formulas`

