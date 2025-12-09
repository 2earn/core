# Order Simulation Payment Seeder Documentation

## Overview
The `OrderSimulationPaymentSeeder` updates existing orders (ID 1 to 5) with simulation and payment details based on their current status.

## Created: December 8, 2025

## Purpose
Update orders with IDs 1-5 to set appropriate simulation and payment fields based on their status:
- **Status 5 (Failed)**: Set simulation failure details
- **Status 6 (Dispatched)**: Set payment success details

## File Location
`database/seeders/OrderSimulationPaymentSeeder.php`

## Features

### Status 5 (Failed Orders) Updates
For orders with status = 5 (Failed), the seeder sets:
- `simulation_result` = 0 (false)
- `simulation_details` = 'Simulation failed due to insufficient funds in the account balances'
- `simulation_datetime` = order's `updated_at` timestamp

### Status 6 (Dispatched Orders) Updates
For orders with status = 6 (Dispatched), the seeder sets:
- `payment_result` = 1 (true)
- `payment_details` = 'Payment successful'
- `payment_datetime` = order's `updated_at` timestamp

## Usage

### Run the seeder directly:
```bash
php artisan db:seed --class=OrderSimulationPaymentSeeder
```

### Add to DatabaseSeeder:
```php
// In database/seeders/DatabaseSeeder.php
$this->call([
    OrderSimulationPaymentSeeder::class,
]);
```

### Run with fresh migration:
```bash
php artisan migrate:fresh --seed
```

## Requirements
- Orders with IDs 1-5 must exist in the database
- Orders should have status 5 or 6 for updates to apply
- The seeder will skip orders that don't have these statuses

## How It Works

1. **Fetches Orders**: Retrieves all orders where `id` is between 1 and 5
2. **Checks Status**: For each order, checks if status is 5 or 6
3. **Updates Fields**: Updates appropriate fields based on status
4. **Logs Actions**: Logs each update operation

### Logic Flow
```php
// Pseudocode
foreach (orders 1-5) {
    if (status == 5) {
        // Set simulation failure
        simulation_result = 0
        simulation_details = "Simulation failed..."
        simulation_datetime = updated_at
    }
    
    if (status == 6) {
        // Set payment success
        payment_result = 1
        payment_details = "Payment successful"
        payment_datetime = updated_at
    }
}
```

## Database Fields Updated

### For Status 5 Orders:
| Field | Value |
|-------|-------|
| `simulation_result` | 0 |
| `simulation_details` | 'Simulation failed due to insufficient funds in the account balances' |
| `simulation_datetime` | Order's `updated_at` timestamp |

### For Status 6 Orders:
| Field | Value |
|-------|-------|
| `payment_result` | 1 |
| `payment_details` | 'Payment successful' |
| `payment_datetime` | Order's `updated_at` timestamp |

## Logging
The seeder provides detailed logging:
- Start message: "Starting OrderSimulationPaymentSeeder"
- Warning if no orders found: "No orders found with IDs between 1 and 5."
- Success per order: "Updated Order ID: X - Status Y - Details"
- Completion message: "OrderSimulationPaymentSeeder completed successfully"

## Example Output
```
[2025-12-08 10:00:00] local.NOTICE: Starting OrderSimulationPaymentSeeder
[2025-12-08 10:00:00] local.INFO: Updated Order ID: 1 - Status 5 (Failed) - Simulation failed
[2025-12-08 10:00:00] local.INFO: Updated Order ID: 3 - Status 6 (Dispatched) - Payment successful
[2025-12-08 10:00:00] local.NOTICE: OrderSimulationPaymentSeeder completed successfully
```

## Testing

### Before Running:
```bash
# Check existing orders
php artisan tinker
>>> Order::whereBetween('id', [1, 5])->get(['id', 'status']);
```

### After Running:
```bash
# Verify updates for status 5
php artisan tinker
>>> Order::where('status', 5)->whereBetween('id', [1, 5])->get(['id', 'simulation_result', 'simulation_details']);

# Verify updates for status 6
>>> Order::where('status', 6)->whereBetween('id', [1, 5])->get(['id', 'payment_result', 'payment_details']);
```

## Related Files
- Model: `app/Models/Order.php`
- Enum: `Core/Enum/OrderEnum.php`
- Migration: `database/migrations/2024_11_01_112138_create_orders_table.php`
- Simulation Fields Migration: `database/migrations/2025_11_05_000000_add_simulation_fields_to_orders_table.php`
- Payment Fields Migration: `database/migrations/2025_11_05_000001_add_payment_result_fields_to_orders_table.php`

## Customization

### Change Order ID Range:
```php
// Update this line in the run() method
$orders = Order::whereBetween('id', [1, 5])->get();

// Example: Update orders 1-10
$orders = Order::whereBetween('id', [1, 10])->get();
```

### Change Simulation Details Message:
```php
'simulation_details' => 'Your custom message here',
```

### Change Payment Details Message:
```php
'payment_details' => 'Your custom message here',
```

### Use Different Timestamp:
```php
// Instead of $order->updated_at, use:
'simulation_datetime' => now(),
// or
'payment_datetime' => Carbon::parse('2025-12-08 10:00:00'),
```

## Important Notes

1. **Non-Destructive**: Only updates specified fields, preserves all other order data
2. **Idempotent**: Can be run multiple times safely (will just update the same fields)
3. **Status-Specific**: Only updates orders with status 5 or 6
4. **Graceful Handling**: If orders 1-5 don't exist, logs warning and exits cleanly
5. **Uses updated_at**: Simulation and payment datetimes are set to the order's current `updated_at` value

## Use Cases

1. **Development**: Set up test data for simulation/payment testing
2. **Data Migration**: Populate simulation/payment fields for existing orders
3. **Testing**: Create consistent test scenarios for failed/successful orders
4. **Demos**: Prepare demonstration data with proper simulation/payment states

## Workflow Integration

This seeder is typically used:
1. After creating orders (via OrderSeeder or manual creation)
2. Before testing simulation/payment flows
3. As part of a complete database seeding process

### Example Workflow:
```bash
# 1. Create fresh database
php artisan migrate:fresh

# 2. Seed users and basic data
php artisan db:seed --class=UserSeeder

# 3. Create orders
php artisan db:seed --class=OrderSeeder

# 4. Update orders with simulation/payment data
php artisan db:seed --class=OrderSimulationPaymentSeeder
```

## Safety Considerations

- ✅ Uses Laravel's `update()` method (mass assignment protected)
- ✅ Validates order IDs exist before updating
- ✅ Checks order status before applying updates
- ✅ Logs all operations for audit trail
- ✅ No data deletion, only updates
- ⚠️ Always backup database before running in production
- ⚠️ Review order statuses before running

## Quick Reference

| Status | Field Updated | Value |
|--------|---------------|-------|
| 5 (Failed) | `simulation_result` | 0 |
| 5 (Failed) | `simulation_details` | "Simulation failed..." |
| 5 (Failed) | `simulation_datetime` | `updated_at` |
| 6 (Dispatched) | `payment_result` | 1 |
| 6 (Dispatched) | `payment_details` | "Payment successful" |
| 6 (Dispatched) | `payment_datetime` | `updated_at` |

