# Commission Formula Index Component Documentation

## Overview
The `CommissionFormulaIndex` component is a comprehensive Livewire component that displays a list of Plan label with search, filtering, sorting, and CRUD operations.

## Files Created

### 1. Component
**File**: `app/Livewire/Commission/CommissionFormulaIndex.php`

### 2. View
**File**: `resources/views/livewire/commission/commission-formula-index.blade.php`

## Component Features

### 🎯 Core Functionality
- ✅ Display list of Plan label in a table
- ✅ Real-time search (name/description)
- ✅ Filter by active status
- ✅ Sortable columns (ID, name, status, created date)
- ✅ Statistics cards (total, active, avg commissions)
- ✅ Toggle active/inactive status
- ✅ Delete with confirmation modal
- ✅ Pagination support (via WithPagination trait)
- ✅ Loading indicators
- ✅ Success/Error flash messages

### 🔍 Search & Filters
- **Search**: Live search in name and description (300ms debounce)
- **Status Filter**: All, Active, Inactive
- **Sort By**: ID, Name, Status, Created Date
- **Sort Direction**: Ascending/Descending
- **Clear Filters**: Reset all filters to default

### 📊 Statistics Display
Four statistic cards showing:
1. **Total Formulas** - Total count of all Plan label
2. **Active Formulas** - Count of active formulas
3. **Avg Initial Commission** - Average of all initial_commission values
4. **Avg Final Commission** - Average of all final_commission values

### 🎨 Table Display
Columns shown:
- **ID** - Formula ID (sortable)
- **Name** - Formula name (sortable)
- **Commission Range** - Formatted as "5.00% - 10.00%"
- **Initial** - Initial commission percentage
- **Final** - Final commission percentage
- **Description** - Truncated to 50 characters
- **Status** - Active/Inactive badge (sortable)
- **Created** - Creation date (sortable)
- **Actions** - Toggle, Edit, Delete buttons (admin only)

## Component Properties

### Public Properties
```php
public $search = '';              // Search term
public $filterActive = '';        // Active status filter
public $sortBy = 'created_at';   // Sort column
public $sortDirection = 'desc';   // Sort direction
public $showDeleteModal = false;  // Delete modal state
public $deleteId = null;          // ID to delete
```

### Protected Properties
```php
protected $commissionFormulaService;  // Injected service
```

## Component Methods

### 1. `boot(CommissionFormulaService $service)`
Inject the CommissionFormulaService.

### 2. `mount()`
Initialize the component.

### 3. `updatingSearch()`
Reset pagination when search changes.

### 4. `updatingFilterActive()`
Reset pagination when filter changes.

### 5. `sortBy($field)`
Handle column sorting. Toggle direction if clicking same column.

**Parameters:**
- `$field` - Column name to sort by

### 6. `toggleActive($id)`
Toggle the active status of a formula.

**Parameters:**
- `$id` - Formula ID

**Flash Messages:**
- Success: "Commission formula status updated successfully."
- Error: "Failed to update commission formula status."

### 7. `confirmDelete($id)`
Show delete confirmation modal.

**Parameters:**
- `$id` - Formula ID to delete

### 8. `cancelDelete()`
Hide delete confirmation modal.

### 9. `deleteFormula()`
Delete the formula (soft delete).

**Flash Messages:**
- Success: "Commission formula deleted successfully."
- Error: "Failed to delete commission formula."

### 10. `clearFilters()`
Reset all filters and sorting to defaults.

### 11. `render()`
Render the component with data.

**Returns:**
- `formulas` - Collection of Plan label
- `statistics` - Statistics array

## View Features

### Statistics Cards Section
Four cards displaying key metrics with icons:
- Total Formulas (green check icon)
- Active Formulas (green circle check icon)
- Average Initial Commission (blue percent icon)
- Average Final Commission (purple percent icon)

### Search & Filter Bar
- **Search Box**: Live search with 300ms debounce
- **Status Dropdown**: Filter by All/Active/Inactive
- **Clear Filters Button**: Reset all filters

### Table Features
- **Responsive Design**: Mobile-friendly table
- **Sortable Headers**: Click to sort, shows arrow indicators
- **Status Badges**: Color-coded (green=active, red=inactive)
- **Commission Display**: Formatted with 2 decimal places
- **Action Buttons**: Toggle, Edit, Delete (admin only)

### Empty State
When no formulas found:
- Large file icon
- "No Plan label found" message
- Helpful text about adjusting filters
- "Add First Formula" button (admin only)

### Delete Modal
- Centered modal with backdrop
- Warning icon
- Confirmation message
- Cancel and Delete buttons

## Usage

### Route Setup
Add to your routes file (`web.php`):

```php
Route::get('/commission-formulas', App\Livewire\Commission\CommissionFormulaIndex::class)
    ->name('plan_label_index');
```

### Access the Component
Navigate to: `/commission-formulas`

### Required Routes
The component expects these routes to exist:
- `commission_formula_create` - Create new formula
- `commission_formula_edit` - Edit formula (requires `id` parameter)

### Required Permissions
Admin-only features (controlled by `\App\Models\User::isSuperAdmin()`):
- Add new formula button
- Toggle active status
- Edit button
- Delete button
- Action column

## View Structure

```blade
<div>
    {{-- Statistics Cards Row --}}
    <div class="row mb-3">
        {{-- 4 statistic cards --}}
    </div>

    {{-- Main Card --}}
    <div class="card">
        {{-- Header with title and Add button --}}
        <div class="card-header">...</div>

        {{-- Search and Filters --}}
        <div class="card-body">...</div>

        {{-- Table --}}
        <div class="card-body">
            {{-- Loading indicator --}}
            {{-- Flash messages --}}
            {{-- Table or empty state --}}
        </div>
    </div>

    {{-- Delete Modal --}}
    @if($showDeleteModal)
        <div class="modal">...</div>
    @endif
</div>
```

## Query String Parameters

The component persists filters in the URL:
- `?search=premium` - Search term
- `?filterActive=1` - Active filter (0=inactive, 1=active, empty=all)
- `?sortBy=name` - Sort column
- `?sortDirection=asc` - Sort direction

Example URL: `/commission-formulas?search=premium&filterActive=1&sortBy=name&sortDirection=asc`

## Styling & Icons

### Icons Used (Remix Icon)
- `ri-list-check` - Total formulas
- `ri-checkbox-circle-line` - Active status
- `ri-percent-line` - Commission percentages
- `ri-add-line` - Add button
- `ri-search-line` - Search icon
- `ri-filter-off-line` - Clear filters
- `ri-arrow-up-s-line` / `ri-arrow-down-s-line` - Sort indicators
- `ri-edit-2-line` - Edit button
- `ri-delete-bin-line` - Delete button
- `ri-pause-circle-line` / `ri-play-circle-line` - Toggle status
- `ri-alert-line` - Warning icon in modal

### CSS Classes
- Cards use: `card`, `card-animate`, `card-body`
- Badges use: `badge`, `badge-soft-success`, `badge-soft-danger`, etc.
- Tables use: `table`, `table-nowrap`, `table-striped-columns`
- Buttons use: `btn`, `btn-sm`, `btn-soft-info`, etc.

## Integration with Service

The component uses `CommissionFormulaService` for all data operations:

```php
// Get formulas with filters
$formulas = $this->commissionFormulaService->getCommissionFormulas($filters);

// Get statistics
$statistics = $this->commissionFormulaService->getStatistics();

// Toggle active status
$this->commissionFormulaService->toggleActive($id);

// Delete formula
$this->commissionFormulaService->deleteCommissionFormula($id);
```

## Livewire Features Used

- ✅ **WithPagination** - Pagination trait
- ✅ **wire:model.live** - Real-time binding with debounce
- ✅ **wire:click** - Click event handling
- ✅ **wire:loading** - Loading states
- ✅ **Query String** - URL parameter persistence
- ✅ **Flash Messages** - Session-based messages
- ✅ **Listeners** - Event listening (`refreshList`)

## Customization

### Change Items Per Page
The component uses the service's default collection. To add pagination:

```php
// In CommissionFormulaService.php
public function getCommissionFormulas(array $filters = [], int $perPage = 15): LengthAwarePaginator
{
    // ... existing code ...
    return $query->paginate($perPage);
}
```

### Add More Filters
Add new filter properties and update the render method:

```php
public $minCommission = '';
public $maxCommission = '';

// In render():
if ($this->minCommission && $this->maxCommission) {
    $filters['min_commission'] = $this->minCommission;
    $filters['max_commission'] = $this->maxCommission;
}
```

### Customize Statistics
Modify the statistics cards in the view or add new cards:

```blade
<div class="col-xl-3 col-md-6">
    <div class="card card-animate">
        {{-- Your custom statistic --}}
    </div>
</div>
```

## Accessibility

- ✅ Semantic HTML structure
- ✅ ARIA labels on buttons
- ✅ Screen reader support for loading states
- ✅ Keyboard navigation support
- ✅ Color contrast for badges and buttons

## Performance Optimization

- ✅ **Debounced Search** - 300ms debounce on search input
- ✅ **Loading States** - Show spinner during operations
- ✅ **Query String** - Shareable/bookmarkable URLs
- ✅ **Service Layer** - Optimized queries with eager loading
- ✅ **Conditional Rendering** - Only render modal when needed

## Testing

### Manual Testing Checklist
- [ ] Search functionality works
- [ ] Filter by active/inactive works
- [ ] Sorting works on all sortable columns
- [ ] Toggle active status works
- [ ] Delete confirmation modal appears
- [ ] Delete operation works
- [ ] Clear filters resets everything
- [ ] Empty state shows when no results
- [ ] Statistics display correctly
- [ ] Admin-only features hidden for non-admins
- [ ] Flash messages appear after operations

### Example Test
```php
public function test_can_view_plan_label_index()
{
    $user = User::factory()->create(['role' => 'admin']);
    
    $this->actingAs($user)
        ->get(route('plan_label_index'))
        ->assertStatus(200)
        ->assertSee('Plan label');
}

public function test_can_search_formulas()
{
    CommissionFormula::factory()->create(['name' => 'Premium Plan']);
    
    Livewire::test(CommissionFormulaIndex::class)
        ->set('search', 'Premium')
        ->assertSee('Premium Plan');
}
```

## Troubleshooting

### Issue: Component not found
**Solution**: Make sure to import with full namespace:
```php
use App\Livewire\Commission\CommissionFormulaIndex;
```

### Issue: View not found
**Solution**: Check that view file exists at:
`resources/views/livewire/commission/commission-formula-index.blade.php`

### Issue: Service not injecting
**Solution**: Make sure service is in `app/Services/Commission/CommissionFormulaService.php`

### Issue: Routes not working
**Solution**: Add required routes for create and edit:
```php
Route::get('/commission-formulas/create', ...)->name('commission_formula_create');
Route::get('/commission-formulas/{id}/edit', ...)->name('commission_formula_edit');
```

## Future Enhancements

Consider adding:
1. **Export** - Export to CSV/Excel
2. **Bulk Actions** - Select multiple and perform actions
3. **Inline Editing** - Edit without going to separate page
4. **Advanced Filters** - Commission range sliders
5. **View Details** - Modal or slide-out panel with full details
6. **History** - Track changes to formulas
7. **Clone** - Duplicate existing formulas
8. **Import** - Import formulas from CSV

---

**Status**: ✅ Complete and Ready for Use
**Date**: November 19, 2025
**Component Type**: Livewire Full-Page Component
**Dependencies**: CommissionFormulaService, Bootstrap 5, Remix Icon

