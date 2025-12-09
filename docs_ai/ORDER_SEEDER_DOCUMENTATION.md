# Order Seeder Documentation

## Overview
The `OrderSeeder` creates test orders with specific statuses for development and testing purposes.

## Created: December 8, 2025

## Purpose
Generate sample orders with two specific statuses:
- **Status 5 (Failed)**: Orders that failed during simulation
- **Status 6 (Dispatched)**: Orders that were successfully paid and dispatched

## File Location
`database/seeders/OrderSeeder.php`

## Features

### Status 5 (Failed Orders)
Orders that failed during the simulation phase due to insufficient funds:
- `simulation_result` = 0 (false)
- `simulation_details` = 'Simulation failed due to insufficient funds in the account balances'
- `simulation_datetime` = current timestamp (updated_at)
- `payment_result` = null (payment was never attempted)
- `payment_details` = null
- `payment_datetime` = null

### Status 6 (Dispatched Orders)
Orders that were successfully simulated, paid, and dispatched:
- `simulation_result` = 1 (true)
- `simulation_details` = 'Simulation successful'
- `simulation_datetime` = 10 minutes ago
- `payment_result` = 1 (true)
- `payment_details` = 'Payment successful'
- `payment_datetime` = current timestamp (updated_at)

## Usage

### Run the seeder directly:
```bash
php artisan db:seed --class=OrderSeeder
```

### Run from DatabaseSeeder:
Add to `database/seeders/DatabaseSeeder.php`:
```php
$this->call([
    OrderSeeder::class,
]);
```

### Run with other seeders:
```bash
php artisan db:seed
```

## Requirements
- Users must exist in the database before running this seeder
- The seeder will randomly select up to 5 users for each order type
- If no users exist, a warning will be logged and the seeder will exit gracefully

## Generated Data

### Common Fields (Both Status Types)
- `user_id`: Random user from database
- `note`: Random sentence (Faker)
- `out_of_deal_amount`: Random float 0-100
- `deal_amount_before_discount`: Random float 100-1000
- `total_order`: Random float 100-1000
- `total_order_quantity`: Random integer 1-10
- `deal_amount_after_discounts`: Random float 80-900
- `amount_after_discount`: Random float 80-900
- `commission_2_earn`: Random float 10-50
- `deal_amount_for_partner`: Random float 70-850
- `commission_for_camembert`: Random float 5-30
- `total_final_discount`: Random float 10-100
- `total_final_discount_percentage`: Random float 5-15
- `total_lost_discount`: Random float 0-50
- `total_lost_discount_percentage`: Random float 0-5

### Status-Specific Fields
**Failed (Status 5):**
- `paid_cash`: 0 (no payment was made)

**Dispatched (Status 6):**
- `paid_cash`: Random float 80-900 (payment amount)

## Logging
The seeder logs the following:
- Start and completion messages
- Each order creation with Order ID and User ID
- Warning if no users are found

## Database Model
Uses: `App\Models\Order`

## Related Files
- Model: `app/Models/Order.php`
- Enum: `Core/Enum/OrderEnum.php`
- Migration: `database/migrations/2024_11_01_112138_create_orders_table.php`

## Notes
- The seeder uses Faker for realistic test data
- Each run creates up to 10 orders (5 failed + 5 dispatched)
- Users are selected randomly from available users
- Timestamps are automatically handled by Laravel
- `simulation_datetime` and `payment_datetime` are set explicitly based on requirements

## Customization
To modify the number of orders created, change this line in each method:
```php
foreach ($users->random(min(5, $users->count())) as $user) {
```

Change `5` to your desired number.

## Testing
After running the seeder, verify:
```php
// Check failed orders
Order::where('status', 5)->get();

// Check dispatched orders
Order::where('status', 6)->get();
```

## Future Enhancements
- Add factory support for more flexible order generation
- Add command-line options to specify number of orders
- Support for other order statuses (New, Ready, Simulated, Paid)
- Relationship seeding (OrderDetails, OrderDeals)

