# BFS Balance Percentage Updater Seeder

## Overview
This seeder updates all BFS balance records that have `balance_operation_id = 50` (SPONSORSHIP_COMMISSION_BFS) to have `percentage = 100.00`.

## Purpose
Ensures that all sponsorship commission BFS balance records have the correct percentage value of 100%, which corresponds to `BFSsBalances::BFS_100`.

## Usage

### Run the seeder
```bash
php artisan db:seed --class=BFSBalancePercentageUpdater
```

### Run with other seeders
If you want to include this in your main database seeder, add it to `DatabaseSeeder.php`:

```php
public function run()
{
    $this->call([
        // ... other seeders
        BFSBalancePercentageUpdater::class,
    ]);
}
```

## What it does

1. **Finds records**: Locates all records in the `bfss_balances` table where `balance_operation_id = 50`
2. **Updates percentage**: Sets the `percentage` field to `100.00` for all matching records
3. **Logs results**: Provides console output and logs the number of records updated
4. **Transaction safety**: Wraps the update in a database transaction for data integrity

## Technical Details

- **Table**: `bfss_balances`
- **Condition**: `balance_operation_id = 50` (SPONSORSHIP_COMMISSION_BFS)
- **Update**: `percentage = 100.00` (BFSsBalances::BFS_100)
- **File Location**: `database/seeders/BFSBalancePercentageUpdater.php`

## Expected Output

```
Starting BFS Balance Percentage Update...
Found X records to update.
Successfully updated X BFS balance records.
All records with balance_operation_id = 50 now have percentage = 100.00
```

## Error Handling

- If no records are found, it will display a warning message
- If an error occurs during the update, the transaction will be rolled back
- All errors are logged to the Laravel log file

## Related Files

- `app/Models/BFSsBalances.php` - The BFS Balance model
- `Core/Enum/BalanceOperationsEnum.php` - Contains SPONSORSHIP_COMMISSION_BFS enum (value 50)
- `app/Services/Sponsorship/Sponsorship.php` - Uses this balance operation type
