# Commission Formula Index Quick Reference

## Component Location
- **Component**: `app/Livewire/Commission/CommissionFormulaIndex.php`
- **View**: `resources/views/livewire/commission/commission-formula-index.blade.php`

## Route Setup

```php
// In routes/web.php
use App\Livewire\Commission\CommissionFormulaIndex;

Route::middleware(['auth'])->group(function () {
    Route::get('/commission-formulas', CommissionFormulaIndex::class)
        ->name('plan_label_index');
});
```

## Features at a Glance

### ✅ Display
- Table list with all Plan label
- 4 statistics cards (total, active, avg commissions)
- Responsive design

### ✅ Search & Filter
- Real-time search (name/description)
- Filter by active status
- Sortable columns
- Clear filters button

### ✅ Actions (Admin Only)
- Toggle active/inactive status
- Edit formula
- Delete formula (with confirmation)

### ✅ UI Elements
- Loading indicators
- Success/Error flash messages
- Empty state
- Delete confirmation modal

## URL Parameters

```
/commission-formulas?search=premium&filterActive=1&sortBy=name&sortDirection=asc
```

## Component Methods

### Public Methods
```php
sortBy($field)              // Sort by column
toggleActive($id)           // Toggle formula status
confirmDelete($id)          // Show delete modal
cancelDelete()              // Hide delete modal
deleteFormula()             // Delete formula
clearFilters()              // Reset all filters
```

## Wire Directives Used

```blade
{{-- Search with debounce --}}
wire:model.live.debounce.300ms="search"

{{-- Live filter --}}
wire:model.live="filterActive"

{{-- Click handlers --}}
wire:click="sortBy('name')"
wire:click="toggleActive({{ $id }})"
wire:click="confirmDelete({{ $id }})"

{{-- Loading states --}}
wire:loading
wire:loading.remove
```

## Table Columns

| Column | Sortable | Format |
|--------|----------|--------|
| ID | ✅ | Integer |
| Name | ✅ | String |
| Range | ❌ | "5.00% - 10.00%" |
| Initial | ❌ | "5.00%" badge |
| Final | ❌ | "10.00%" badge |
| Description | ❌ | Truncated 50 chars |
| Status | ✅ | Badge (Active/Inactive) |
| Created | ✅ | Y-m-d format |
| Actions | ❌ | Buttons (admin only) |

## Statistics Cards

1. **Total Formulas** - Count of all formulas
2. **Active Formulas** - Count of active only
3. **Avg Initial Commission** - Average of initial_commission
4. **Avg Final Commission** - Average of final_commission

## Action Buttons

### Toggle Status
```blade
<button wire:click="toggleActive({{ $id }})" 
        class="btn btn-sm btn-soft-{{ $active ? 'warning' : 'success' }}">
    <i class="ri-{{ $active ? 'pause' : 'play' }}-circle-line"></i>
</button>
```

### Edit
```blade
<a href="{{ route('commission_formula_edit', ['locale' => app()->getLocale(), 'id' => $id]) }}" 
   class="btn btn-sm btn-soft-info">
    <i class="ri-edit-2-line"></i>
</a>
```

### Delete
```blade
<button wire:click="confirmDelete({{ $id }})" 
        class="btn btn-sm btn-soft-danger">
    <i class="ri-delete-bin-line"></i>
</button>
```

## Flash Messages

### Success
```php
session()->flash('success', __('Commission formula status updated successfully.'));
```

### Error
```php
session()->flash('error', __('Failed to update commission formula status.'));
```

## Empty State

Shows when no formulas found:
```blade
<div class="text-center py-5">
    <i class="ri-file-list-3-line display-4 text-muted"></i>
    <h5 class="mt-2">{{ __('No Plan label found') }}</h5>
    <p class="text-muted">{{ __('Try adjusting your search...') }}</p>
    <a href="{{ route('commission_formula_create') }}" class="btn btn-success mt-3">
        <i class="ri-add-line"></i> {{ __('Add First Formula') }}
    </a>
</div>
```

## Delete Modal

```blade
@if($showDeleteModal)
    <div class="modal fade show d-block">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5>{{ __('Confirm Delete') }}</h5>
                    <button wire:click="cancelDelete"></button>
                </div>
                <div class="modal-body">
                    <i class="ri-alert-line display-5 text-warning"></i>
                    <h4>{{ __('Are you sure?') }}</h4>
                </div>
                <div class="modal-footer">
                    <button wire:click="cancelDelete">{{ __('Cancel') }}</button>
                    <button wire:click="deleteFormula">{{ __('Delete') }}</button>
                </div>
            </div>
        </div>
    </div>
@endif
```

## Service Integration

```php
// In component
protected $commissionFormulaService;

public function boot(CommissionFormulaService $commissionFormulaService)
{
    $this->commissionFormulaService = $commissionFormulaService;
}

public function render()
{
    $filters = [
        'search' => $this->search,
        'is_active' => $this->filterActive !== '' ? (bool) $this->filterActive : null,
        'order_by' => $this->sortBy,
        'order_direction' => $this->sortDirection,
    ];

    $formulas = $this->commissionFormulaService->getCommissionFormulas($filters);
    $statistics = $this->commissionFormulaService->getStatistics();

    return view('livewire.commission.commission-formula-index', [
        'formulas' => $formulas,
        'statistics' => $statistics,
    ]);
}
```

## Required Routes

The component expects these routes:
```php
Route::get('/commission-formulas', CommissionFormulaIndex::class)
    ->name('plan_label_index');

Route::get('/commission-formulas/create', CommissionFormulaCreate::class)
    ->name('commission_formula_create');

Route::get('/commission-formulas/{id}/edit', CommissionFormulaEdit::class)
    ->name('commission_formula_edit');
```

## Admin Check

All admin-only features use:
```php
@if(\App\Models\User::isSuperAdmin())
    {{-- Admin only content --}}
@endif
```

## Common Customizations

### Change Debounce Time
```blade
{{-- From 300ms to 500ms --}}
wire:model.live.debounce.500ms="search"
```

### Add New Filter
```php
// In component
public $minCommission = '';

// In render()
if ($this->minCommission) {
    $filters['min_commission'] = $this->minCommission;
}
```

### Change Sort Default
```php
public $sortBy = 'name';          // Sort by name instead
public $sortDirection = 'asc';    // Ascending instead
```

## Icons Used

- 📊 Statistics: `ri-list-check`, `ri-checkbox-circle-line`, `ri-percent-line`
- 🔍 Search: `ri-search-line`, `ri-filter-off-line`
- 🔄 Actions: `ri-play-circle-line`, `ri-pause-circle-line`
- ✏️ Edit: `ri-edit-2-line`
- 🗑️ Delete: `ri-delete-bin-line`
- ⚠️ Warning: `ri-alert-line`
- ➕ Add: `ri-add-line`
- ⬆️⬇️ Sort: `ri-arrow-up-s-line`, `ri-arrow-down-s-line`

## Key Features

✅ Real-time search (300ms debounce)
✅ Live filtering
✅ Sortable columns  
✅ Statistics dashboard
✅ Toggle active status
✅ Delete with confirmation
✅ Loading indicators
✅ Flash messages
✅ Empty state
✅ Responsive design
✅ Admin-only actions
✅ URL persistence

## Testing Commands

```bash
# Test the component
php artisan test --filter CommissionFormulaIndexTest

# Check Livewire components
php artisan livewire:list
```

## Quick Start

1. Add route to `web.php`
2. Navigate to `/commission-formulas`
3. Search, filter, and manage formulas
4. Toggle status or delete as needed

