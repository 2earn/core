# Commission Formula Deal Integration

## Overview
This document describes the implementation of commission formula integration with deals, where users can select a commission formula when creating or editing a deal, and the initial and final commission values are automatically populated from the selected formula.

## Changes Made

### 1. Database Migration
**File:** `database/migrations/2025_11_19_100414_add_commission_formula_id_to_deals_table.php`

Added `commission_formula_id` column to the `deals` table:
- Type: `unsignedBigInteger`, nullable
- Foreign key reference to `commission_formulas.id`
- On delete: set null
- Position: After `final_commission` column

### 2. Deal Model Updates
**File:** `app/Models/Deal.php`

- Added `commission_formula_id` to the `$fillable` array
- Added `commissionFormula()` relationship method:
  ```php
  public function commissionFormula()
  {
      return $this->belongsTo(CommissionFormula::class, 'commission_formula_id');
  }
  ```

### 3. DealsCreateUpdate Livewire Component
**File:** `app/Livewire/DealsCreateUpdate.php`

**Properties Added:**
- `$commission_formula_id` - Stores the selected commission formula ID
- `$commissionFormulas` - Collection of active commission formulas

**Methods Modified:**

1. **mount()** - Loads active commission formulas:
   ```php
   $this->commissionFormulas = \App\Models\CommissionFormula::where('is_active', true)->get();
   ```

2. **updatedCommissionFormulaId()** - New method that automatically updates commission values when a formula is selected:
   ```php
   public function updatedCommissionFormulaId($value)
   {
       if ($value) {
           $formula = \App\Models\CommissionFormula::find($value);
           if ($formula) {
               $this->initial_commission = $formula->initial_commission;
               $this->final_commission = $formula->final_commission;
           }
       }
   }
   ```

3. **edit()** - Loads `commission_formula_id` from existing deal

4. **updateDeal()** - Includes `commission_formula_id` in update parameters

5. **store()** - Includes `commission_formula_id` in create parameters

### 4. Deals Create/Update View
**File:** `resources/views/livewire/deals-create-update.blade.php`

**Changes:**
- Added Commission Formula dropdown select field
- Modified Initial Commission and Final Commission fields to be read-only
- Fields are now auto-populated when a formula is selected

**HTML Structure:**
```blade
<div class="form-group col-3 mb-3">
    <label for="commission_formula_id">{{__('Commission Formula')}}</label>
    <select class="form-control" id="commission_formula_id" wire:model.live="commission_formula_id">
        <option value="">{{__('Select Commission Formula')}}</option>
        @foreach($commissionFormulas as $formula)
            <option value="{{ $formula->id }}">
                {{ $formula->name }} ({{ $formula->initial_commission }}% - {{ $formula->final_commission }}%)
            </option>
        @endforeach
    </select>
</div>

<div class="form-group col-3 mb-3">
    <label for="initial_commission">{{__('Initial commission')}}</label>
    <input type="number" class="form-control" id="initial_commission" 
           wire:model.live="initial_commission" readonly>
    <div class="form-text">{{__('Auto-filled from formula')}}</div>
</div>

<div class="form-group col-3 mb-3">
    <label for="final_commission">{{__('Final commission')}}</label>
    <input type="number" class="form-control" id="final_commission" 
           wire:model.live="final_commission" readonly>
    <div class="form-text">{{__('Auto-filled from formula')}}</div>
</div>
```

### 5. Deals Index View
**File:** `resources/views/livewire/deals-index.blade.php`

**Added Display Section:**
```blade
<div class="row g-2 mb-3">
    <div class="col-md-6 col-6">
        <div class="p-2 bg-light rounded">
            <p class="text-success fs-12 mb-1">
                <i class="fas fa-percent me-1"></i>{{__('Initial Commission')}}
            </p>
            <span class="badge bg-success-subtle text-success px-2 py-1">
                {{number_format($deal->initial_commission, 2)}}%
            </span>
            @if($deal->commissionFormula)
                <small class="d-block text-muted mt-1">
                    <i class="fas fa-calculator me-1"></i>{{$deal->commissionFormula->name}}
                </small>
            @endif
        </div>
    </div>
    <div class="col-md-6 col-6">
        <div class="p-2 bg-light rounded">
            <p class="text-warning fs-12 mb-1">
                <i class="fas fa-percent me-1"></i>{{__('Final Commission')}}
            </p>
            <span class="badge bg-warning-subtle text-warning px-2 py-1">
                {{number_format($deal->final_commission, 2)}}%
            </span>
            @if($deal->commissionFormula)
                <small class="d-block text-muted mt-1">
                    <i class="fas fa-chart-line me-1"></i>{{__('Formula Applied')}}
                </small>
            @endif
        </div>
    </div>
</div>
```

### 6. DealsIndex Livewire Component
**File:** `app/Livewire/DealsIndex.php`

**Added Eager Loading:**
- Modified `prepareQuery()` to eager load the `commissionFormula` relationship:
  ```php
  $query->with('commissionFormula');
  ```
- This prevents N+1 query issues when displaying commission formula names in the index view

## User Workflow

### Creating a New Deal
1. User navigates to create deal page
2. User selects a Commission Formula from the dropdown
3. Initial Commission and Final Commission fields are automatically populated with values from the selected formula
4. Fields are read-only to ensure consistency with the formula
5. User completes other deal information and saves
6. Deal is created with the commission values from the selected formula

### Editing an Existing Deal
1. User navigates to edit deal page
2. If deal has an associated commission formula, it is pre-selected in the dropdown
3. Initial and Final commission values are displayed as read-only
4. User can change the commission formula selection
5. When changed, commission values update automatically
6. User saves changes

### Viewing Deals (Index Page)
1. Each deal card displays:
   - Initial Commission percentage with success badge (green)
   - Final Commission percentage with warning badge (yellow)
   - Associated Commission Formula name (if applicable)
   - Visual indicators showing "Formula Applied"

## Benefits

1. **Consistency**: Commission values are always consistent with the selected formula
2. **Ease of Use**: No manual entry required for commission percentages
3. **Traceability**: Each deal can be traced back to its commission formula
4. **Flexibility**: Commission formulas can be updated centrally without affecting existing deals
5. **Validation**: Read-only fields prevent accidental modifications
6. **Performance**: Eager loading prevents N+1 query issues

## Database Relationships

```
deals
├── commission_formula_id (FK) → commission_formulas.id
├── initial_commission (decimal)
└── final_commission (decimal)

commission_formulas
├── id (PK)
├── name
├── initial_commission (decimal)
├── final_commission (decimal)
└── is_active (boolean)
```

## Notes

- The migration sets the foreign key with `onDelete('set null')` to preserve deal data if a commission formula is deleted
- Only active commission formulas (`is_active = true`) are shown in the dropdown
- Commission values are formatted to 2 decimal places in the display
- The implementation uses Livewire's reactive properties for real-time updates

