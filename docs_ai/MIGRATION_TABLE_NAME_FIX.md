# Migration Table Name Fix

## Issue
```
SQLSTATE[42S02]: Base table or view not found: 1146 Table '2earn.user' doesn't exist
```

## Root Cause
The migrations were referencing the wrong table name. The project uses:
- Table name: `users` (plural)
- NOT: `user` (singular)

## What Was Fixed

### File 1: 2025_12_23_000001_create_partner_requests_table.php
Changed foreign key references:
```php
// OLD (WRONG)
$table->unsignedBigInteger('user_id')->references('id')->on('user')->onDelete('cascade');
$table->unsignedBigInteger('examiner_id')->nullable()->references('id')->on('user')->onDelete('cascade');

// NEW (CORRECT)
$table->unsignedBigInteger('user_id')->references('id')->on('users')->onDelete('cascade');
$table->unsignedBigInteger('examiner_id')->nullable()->references('id')->on('users')->onDelete('cascade');
```

### File 2: 2025_12_23_000002_add_partner_field_to_users_table.php
Changed schema table references:
```php
// OLD (WRONG)
Schema::table('user', function (Blueprint $table) {
    if (!Schema::hasColumn('user', 'partner')) {
        $table->integer('partner')->nullable()->after('instructor')->default(0);
    }
});

// NEW (CORRECT)
Schema::table('users', function (Blueprint $table) {
    if (!Schema::hasColumn('users', 'partner')) {
        $table->integer('partner')->nullable()->after('instructor')->default(0);
    }
});
```

## Why This Matters
- All existing migration files in the project use `users` (plural) for updating the users table
- The original CREATE migration (in generated folder) uses `users`
- Only the older migrations like committed_investor_requests use `user` (which may also be incorrect)
- Consistency: All new code should follow the current project standard

## Example from Project
From `2024_04_22_134624_update_users_table.php`:
```php
Schema::table('users', function (Blueprint $table) {
    // ... columns ...
});
```

From `2024_09_24_074129_update_users_table.php`:
```php
Schema::table('users', function (Blueprint $table) {
    // ... columns ...
});
```

## Status
✅ Fixed - Ready to run migrations

## Next Steps
1. If migrations have already been run, you may need to:
   ```bash
   php artisan migrate:rollback
   php artisan migrate
   ```

2. Or manually check if the migrations ran and drop the partner_requests table if it was created incorrectly

3. Then run:
   ```bash
   php artisan migrate
   ```

