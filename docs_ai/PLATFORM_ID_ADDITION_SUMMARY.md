# Platform ID Addition Summary

## Overview
Added `platform_id` foreign key column to both `commission_break_downs` and `orders` tables to establish relationships with the `platforms` table.

## Changes Made

### 1. Database Migrations

#### Migration: `2025_12_18_085043_add_platform_id_to_commission_break_downs_table.php`
- **Location**: `database/migrations/`
- **Changes**:
  - Added `platform_id` column (unsignedBigInteger, nullable) after `order_id`
  - Added foreign key constraint referencing `platforms.id` with cascade on delete
- **Status**: ✅ Successfully run

#### Migration: `2025_12_18_085048_add_platform_id_to_orders_table.php`
- **Location**: `database/migrations/`
- **Changes**:
  - Added `platform_id` column (unsignedBigInteger, nullable) after `user_id`
  - Added foreign key constraint referencing `platforms.id` with cascade on delete
- **Status**: ✅ Successfully run

### 2. Model Updates

#### CommissionBreakDown Model
**File**: `app/Models/CommissionBreakDown.php`

**Changes**:
1. Added `platform_id` to the `$fillable` array
2. Added relationship method:
```php
public function platform()
{
    return $this->belongsTo(\Core\Models\Platform::class, 'platform_id', 'id');
}
```

#### Order Model
**File**: `app/Models/Order.php`

**Changes**:
1. Added `platform_id` to the `$fillable` array
2. Added relationship method:
```php
public function platform()
{
    return $this->belongsTo(\Core\Models\Platform::class, 'platform_id', 'id');
}
```

#### Platform Model
**File**: `Core/Models/Platform.php`

**Changes**:
Added inverse relationship methods:
```php
public function orders()
{
    return $this->hasMany(\App\Models\Order::class);
}

public function commissionBreakdowns()
{
    return $this->hasMany(\App\Models\CommissionBreakDown::class);
}
```

## Usage Examples

### Accessing Platform from Order
```php
$order = Order::find(1);
$platform = $order->platform; // Get the associated platform
```

### Accessing Platform from CommissionBreakDown
```php
$commission = CommissionBreakDown::find(1);
$platform = $commission->platform; // Get the associated platform
```

### Accessing Orders from Platform
```php
$platform = Platform::find(1);
$orders = $platform->orders; // Get all orders for this platform
```

### Accessing Commission Breakdowns from Platform
```php
$platform = Platform::find(1);
$breakdowns = $platform->commissionBreakdowns; // Get all commission breakdowns for this platform
```

### Creating Order with Platform
```php
$order = Order::create([
    'user_id' => 1,
    'platform_id' => 5,
    'status' => OrderEnum::New,
    'note' => 'Test order',
    // ... other fields
]);
```

### Creating Commission Breakdown with Platform
```php
$breakdown = CommissionBreakDown::create([
    'order_id' => 10,
    'deal_id' => 20,
    'platform_id' => 5,
    'type' => CommissionTypeEnum::IN,
    'commission_value' => 100.50,
    // ... other fields
]);
```

## Database Schema

### commission_break_downs Table
```sql
platform_id BIGINT UNSIGNED NULL
FOREIGN KEY (platform_id) REFERENCES platforms(id) ON DELETE CASCADE
```

### orders Table
```sql
platform_id BIGINT UNSIGNED NULL
FOREIGN KEY (platform_id) REFERENCES platforms(id) ON DELETE CASCADE
```

## Notes
- Both `platform_id` columns are nullable to allow for existing records without platform associations
- Foreign key constraints use `CASCADE` on delete, meaning if a platform is deleted, all associated orders and commission breakdowns will also be deleted
- The relationships are bidirectional: you can access platform from order/commission breakdown, and vice versa
- Make sure to clear Laravel caches after deployment: `php artisan optimize:clear`

## Deployment Checklist
- [x] Create migrations
- [x] Run migrations
- [x] Update CommissionBreakDown model
- [x] Update Order model
- [x] Update Platform model with inverse relationships
- [ ] Clear caches on production: `php artisan optimize:clear`
- [ ] Test relationships in production environment
- [ ] Update any existing code that creates orders/commission breakdowns to include platform_id

## Date
Created: December 18, 2025

