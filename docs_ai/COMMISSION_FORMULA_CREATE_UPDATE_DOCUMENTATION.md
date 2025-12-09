# Commission Formula Create/Update Component Documentation

## Overview
The `CommissionFormulaCreateUpdate` component is a comprehensive Livewire component that handles both creating new Plan label and editing existing ones in a single, unified interface.

## Files Created

### 1. Component
**File**: `app/Livewire/CommissionFormulaCreateUpdate.php`

### 2. View
**File**: `resources/views/livewire/commission-formula-create-update.blade.php`

### 3. Routes Updated
**File**: `routes/web.php`

## Component Features

### 🎯 Core Functionality
- ✅ Single component for both Create and Edit operations
- ✅ Real-time validation with error messages
- ✅ Commission range preview
- ✅ Active/inactive status toggle
- ✅ Service layer integration
- ✅ Success/Error flash messages
- ✅ Cancel functionality
- ✅ Loading states
- ✅ Help & Guidelines section

### 📝 Form Fields
1. **Name** (Required)
   - Text input, max 255 characters
   - Descriptive name for the formula

2. **Initial Commission** (Required)
   - Numeric input, 0-100%
   - 2 decimal places
   - Must be less than final commission

3. **Final Commission** (Required)
   - Numeric input, 0-100%
   - 2 decimal places
   - Must be greater than initial commission

4. **Description** (Optional)
   - Textarea, unlimited length
   - Detailed description

5. **Is Active** (Boolean)
   - Toggle switch
   - Default: true (active)

## Validation Rules

### Built-in Validation
```php
[
    'name' => 'required|string|max:255',
    'initial_commission' => 'required|numeric|min:0|max:100',
    'final_commission' => 'required|numeric|min:0|max:100|gt:initial_commission',
    'description' => 'nullable|string',
    'is_active' => 'boolean',
]
```

### Key Validation Points
- ✅ Name is required and limited to 255 characters
- ✅ Both commissions are required numeric values
- ✅ Commissions must be between 0 and 100
- ✅ Final commission must be greater than initial commission
- ✅ Description is optional
- ✅ Active status defaults to true

### Real-time Validation
- Validates on blur for each field
- Shows inline error messages
- Prevents form submission if validation fails

## Component Properties

### Public Properties
```php
public $formulaId = null;          // Formula ID for edit mode
public $name = '';                 // Formula name
public $initial_commission = '';   // Initial commission %
public $final_commission = '';     // Final commission %
public $description = '';          // Description text
public $is_active = true;          // Active status
public $isEditMode = false;        // Edit mode flag
```

### Protected Properties
```php
protected $commissionFormulaService;  // Injected service
```

## Component Methods

### 1. `boot(CommissionFormulaService $service)`
Inject the CommissionFormulaService dependency.

### 2. `mount($id = null)`
Initialize the component. If `$id` is provided, load the formula for editing.

**Parameters:**
- `$id` (optional) - Formula ID to edit

### 3. `loadFormula($id)`
Load an existing formula into the form fields.

**Parameters:**
- `$id` - Formula ID

**Actions:**
- Fetches formula from service
- Populates all form fields
- Sets edit mode
- Redirects if formula not found

### 4. `updated($propertyName)`
Real-time validation hook. Validates individual field on change.

**Parameters:**
- `$propertyName` - Name of the updated property

### 5. `save()`
Save the formula (create or update based on mode).

**Actions:**
- Validates all fields
- Prepares data array
- Calls service create/update method
- Shows success/error flash message
- Redirects to index page on success

### 6. `cancel()`
Cancel the form and return to index page.

**Returns:** Redirect to plan_label_index

## View Features

### Form Layout
- **Centered Layout**: 8-column width, offset 2 columns (responsive)
- **Card Design**: Clean card interface with header
- **Help Section**: Expandable help card with guidelines

### UI Components

#### 1. Header
- Dynamic title (Create/Edit)
- Breadcrumb navigation
- Percent icon

#### 2. Form Fields
- **Text Input**: Name field with placeholder
- **Number Inputs**: Commission fields with % suffix
- **Textarea**: Description field (4 rows)
- **Toggle Switch**: Active status with visual feedback

#### 3. Commission Range Preview
Shows real-time preview when both commissions are valid:
```
Commission Range: 5.00% - 10.00%
```

#### 4. Active Status Badge
Real-time visual feedback:
- ✅ Green badge when active
- ❌ Red badge when inactive

#### 5. Action Buttons
- **Cancel**: Returns to index (light gray)
- **Save**: Creates/Updates formula (green)
  - Shows loading spinner during save
  - Disabled while loading

#### 6. Help & Guidelines Card
Two columns:
- **Commission Range**: Rules and constraints
- **Best Practices**: Usage recommendations

### Validation Display
- **Inline Errors**: Red text below each field
- **Field Highlighting**: Red border on invalid fields
- **Form Text**: Gray helper text below fields

### Loading States
- ✅ Spinner in save button during submission
- ✅ Button disabled during save
- ✅ "Saving..." text replaces button label

## Routes

### Route Configuration
```php
Route::prefix('/commission/formula')->name('commission_formula_')->group(function () {
    Route::get('/index', CommissionFormulaIndex::class)->name('index');
    Route::get('/create', CommissionFormulaCreateUpdate::class)->name('create');
    Route::get('/edit/{id}', CommissionFormulaCreateUpdate::class)->name('edit');
});
```

### Route Names
1. **plan_label_index** - List page
2. **plan_label_create** - Create page
3. **plan_label_edit** - Edit page (requires id parameter)

### URLs
- **List**: `/commission/formula/index`
- **Create**: `/commission/formula/create`
- **Edit**: `/commission/formula/edit/{id}`

## Usage Examples

### Access Create Form
```blade
<a href="{{ route('plan_label_create', ['locale' => app()->getLocale()]) }}">
    Add Formula
</a>
```

### Access Edit Form
```blade
<a href="{{ route('plan_label_edit', ['locale' => app()->getLocale(), 'id' => $formula->id]) }}">
    Edit
</a>
```

### Direct Navigation
```php
// Create
return redirect()->route('plan_label_create', ['locale' => app()->getLocale()]);

// Edit
return redirect()->route('plan_label_edit', [
    'locale' => app()->getLocale(),
    'id' => $formulaId
]);
```

## Service Integration

The component uses `CommissionFormulaService` for all operations:

```php
// Get formula by ID (for editing)
$formula = $this->commissionFormulaService->getCommissionFormulaById($id);

// Create new formula
$formula = $this->commissionFormulaService->createCommissionFormula($data);

// Update existing formula
$formula = $this->commissionFormulaService->updateCommissionFormula($id, $data);
```

## Flash Messages

### Success Messages
- **Create**: "Commission formula created successfully."
- **Update**: "Commission formula updated successfully."

### Error Messages
- **Not Found**: "Commission formula not found."
- **Create Failed**: "Failed to create commission formula."
- **Update Failed**: "Failed to update commission formula."

## Workflow

### Create Flow
1. User clicks "Add Formula" from index page
2. Component loads in create mode
3. User fills form fields
4. Real-time validation occurs on blur
5. User clicks "Create Formula"
6. Validation runs
7. If valid: Service creates formula, redirect to index with success message
8. If invalid: Show errors, stay on page

### Edit Flow
1. User clicks "Edit" button from index page
2. Component loads with formula ID
3. Formula data loaded into form
4. User modifies fields
5. Real-time validation occurs
6. User clicks "Update Formula"
7. Validation runs
8. If valid: Service updates formula, redirect to index with success message
9. If invalid: Show errors, stay on page

### Cancel Flow
1. User clicks "Cancel" button
2. Immediately redirect to index page
3. No data saved

## Validation Examples

### Valid Data
```php
[
    'name' => 'Premium Partner Plan',
    'initial_commission' => 10.00,
    'final_commission' => 20.00,
    'description' => 'High-tier commission structure',
    'is_active' => true
]
```

### Invalid Data Examples

#### Final <= Initial
```php
'initial_commission' => 15.00,
'final_commission' => 10.00,  // Error: Must be > initial
```

#### Out of Range
```php
'initial_commission' => 150.00,  // Error: Must be <= 100
'final_commission' => -5.00,     // Error: Must be >= 0
```

#### Missing Required
```php
'name' => '',  // Error: Required field
```

## UI Screenshots (Conceptual)

### Create Form
```
┌─────────────────────────────────────────────┐
│ Create Commission Formula                    │
├─────────────────────────────────────────────┤
│ Formula Name *                               │
│ [Premium Partner Plan             ]          │
│                                              │
│ Initial Commission (%) *  Final Commission * │
│ [10.00] %                [20.00] %           │
│                                              │
│ ℹ️ Commission Range: 10.00% - 20.00%        │
│                                              │
│ Description                                  │
│ [High-tier partners get...        ]          │
│                                              │
│ [✓] Active Status                           │
│                                              │
│           [Cancel] [Create Formula]          │
└─────────────────────────────────────────────┘
```

### Help Section
```
┌─────────────────────────────────────────────┐
│ Help & Guidelines                            │
├─────────────────────────────────────────────┤
│ Commission Range      │ Best Practices       │
│ → 0% to 100%          │ → Use clear names    │
│ → Final > Initial     │ → Add descriptions   │
│ → 2 decimal places    │ → Set status         │
└─────────────────────────────────────────────┘
```

## Best Practices

### For Users
1. ✅ Use descriptive names (e.g., "Premium Partner Plan")
2. ✅ Add detailed descriptions explaining when to use the formula
3. ✅ Ensure final commission is meaningfully higher than initial
4. ✅ Set to inactive when not in current use
5. ✅ Use round percentages when possible (5%, 10%, 15%)

### For Developers
1. ✅ Always validate server-side (done via service)
2. ✅ Use service layer for all database operations
3. ✅ Provide clear error messages
4. ✅ Test both create and edit modes
5. ✅ Handle edge cases (0%, 100%, equal values)

## Testing Checklist

- [ ] Create form loads correctly
- [ ] Edit form loads with correct data
- [ ] Name validation works (required, max 255)
- [ ] Initial commission validation works (required, 0-100)
- [ ] Final commission validation works (required, 0-100, > initial)
- [ ] Description field accepts long text
- [ ] Active toggle works
- [ ] Commission range preview displays
- [ ] Real-time validation shows errors
- [ ] Create operation saves correctly
- [ ] Update operation saves correctly
- [ ] Cancel button redirects
- [ ] Success messages appear
- [ ] Error messages appear on failure
- [ ] Loading state shows during save
- [ ] Form fields reset after create
- [ ] Redirects work properly

## Troubleshooting

### Issue: Formula not found on edit
**Solution**: Check that the ID parameter is valid
```php
$formula = CommissionFormula::find($id);
if (!$formula) {
    // Handle not found
}
```

### Issue: Validation not working
**Solution**: Check validation rules and messages arrays

### Issue: Service not injecting
**Solution**: Ensure service exists at `app/Services/Commission/CommissionFormulaService.php`

### Issue: Routes not working
**Solution**: Clear route cache
```bash
php artisan route:clear
php artisan route:cache
```

## Customization

### Add New Field
1. Add property to component:
```php
public $new_field = '';
```

2. Add validation rule:
```php
'new_field' => 'required|string',
```

3. Add to view:
```blade
<input wire:model.blur="new_field" class="form-control">
```

4. Add to save data array:
```php
$data['new_field'] = $this->new_field;
```

### Change Validation
Update the `$rules` array in the component:
```php
protected $rules = [
    'initial_commission' => 'required|numeric|min:5|max:50',  // Changed range
];
```

### Customize Messages
Update the `$messages` array:
```php
protected $messages = [
    'name.required' => 'Please provide a formula name',
];
```

## Future Enhancements

Consider adding:
1. **Formula Templates** - Pre-defined common formulas
2. **Calculation Preview** - Show example calculations
3. **Duplicate Detection** - Warn if similar formula exists
4. **Formula History** - Track changes over time
5. **Bulk Import** - Import multiple formulas from CSV
6. **Formula Comparison** - Compare with other formulas
7. **Usage Statistics** - Show where formula is used

---

**Status**: ✅ Complete and Ready to Use
**Date**: November 19, 2025
**Component Type**: Livewire Full-Page Component
**Mode**: Unified Create/Edit
**Dependencies**: CommissionFormulaService, Bootstrap 5, Remix Icon

