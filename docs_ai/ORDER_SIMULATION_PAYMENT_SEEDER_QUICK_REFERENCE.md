# Order Simulation Payment Seeder - Quick Reference

## What It Does
Updates orders with IDs 1-5 to set simulation/payment fields based on their status.

## Run Command
```bash
php artisan db:seed --class=OrderSimulationPaymentSeeder
```

## Updates Applied

### Status 5 (Failed Orders)
```php
simulation_result = 0
simulation_details = 'Simulation failed due to insufficient funds in the account balances'
simulation_datetime = updated_at
```

### Status 6 (Dispatched Orders)
```php
payment_result = 1
payment_details = 'Payment successful'
payment_datetime = updated_at
```

## Key Points
- ✅ Updates orders ID 1-5 only
- ✅ Only updates orders with status 5 or 6
- ✅ Safe to run multiple times
- ✅ Logs all operations
- ✅ Preserves other order data

## Check Results
```bash
# In tinker
php artisan tinker

# Check status 5 orders
>>> Order::where('status', 5)->whereBetween('id', [1, 5])->get(['id', 'simulation_result', 'simulation_details', 'simulation_datetime']);

# Check status 6 orders
>>> Order::where('status', 6)->whereBetween('id', [1, 5])->get(['id', 'payment_result', 'payment_details', 'payment_datetime']);
```

## Files Created
1. `database/seeders/OrderSimulationPaymentSeeder.php` - The seeder
2. `docs_ai/ORDER_SIMULATION_PAYMENT_SEEDER_DOCUMENTATION.md` - Full documentation
3. `docs_ai/ORDER_SIMULATION_PAYMENT_SEEDER_QUICK_REFERENCE.md` - This file

## Related Files
- Also created: `database/seeders/OrderSeeder.php` (creates new orders)
- Also created: `docs_ai/ORDER_SEEDER_DOCUMENTATION.md`

