# Laravel Auditing Solution - Created By & Updated By

This solution implements automatic tracking of `created_by` and `updated_by` fields for all models in your Laravel application.

## Components

### 1. HasAuditing Trait (`app/Traits/HasAuditing.php`)

A reusable trait that automatically populates `created_by` and `updated_by` fields based on the authenticated user.

**Features:**
- Automatically sets `created_by` and `updated_by` when a record is created
- Automatically updates `updated_by` when a record is modified
- Provides `creator()` and `updater()` relationships to access the User who created/updated the record
- Only sets values when a user is authenticated

### 2. Migration (`database/migrations/2025_11_10_085118_add_auditing_fields_to_all_tables.php`)

Adds `created_by` and `updated_by` columns to all your tables.

**Features:**
- Adds nullable foreign key columns
- Sets foreign key constraints to `users` table
- Uses `onDelete('set null')` to prevent cascading deletes
- Checks if tables and columns exist before adding
- Can be safely rolled back

## How to Use

### Step 1: Run the Migration

```bash
php artisan migrate
```

This will add `created_by` and `updated_by` columns to all tables listed in the migration.

### Step 2: Add the Trait to Your Models

For each model you want to track, add the `HasAuditing` trait:

```php
<?php

namespace App\Models;

use App\Traits\HasAuditing;
use Illuminate\Database\Eloquent\Model;

class YourModel extends Model
{
    use HasAuditing;
    
    // ... rest of your model code
}
```

### Step 3: Use the Auditing Fields

Once set up, the fields are automatically populated:

```php
// Creating a new record (when authenticated)
$deal = Deal::create([
    'name' => 'New Deal',
    'description' => 'Deal description'
]);
// $deal->created_by will be set to Auth::id()
// $deal->updated_by will be set to Auth::id()

// Updating a record
$deal->update(['name' => 'Updated Deal']);
// $deal->updated_by will be updated to current Auth::id()

// Accessing the creator and updater
$creator = $deal->creator; // Returns User model
$updater = $deal->updater; // Returns User model

echo $deal->creator->name; // "John Doe"
echo $deal->updater->email; // "jane@example.com"
```

## Models Already Updated

The following models have been updated with the `HasAuditing` trait:
- Deal
- Order
- News

## Adding to Remaining Models

You need to add the trait to all your other models. Here's a quick example for each:

```php
// app/Models/Item.php
use App\Traits\HasAuditing;
class Item extends Model {
    use HasFactory, HasAuditing;
}

// app/Models/Survey.php
use App\Traits\HasAuditing;
class Survey extends Model {
    use HasFactory, HasAuditing;
}

// app/Models/Event.php
use App\Traits\HasAuditing;
class Event extends Model {
    use HasFactory, HasAuditing;
}

// ... and so on for all models
```

## Tables Included in Migration

The migration adds auditing fields to the following tables:
- users
- deals
- items
- orders
- order_details
- order_deals
- shares
- item_deal_histories
- news
- likes
- comments
- surveys
- survey_questions
- survey_question_choices
- survey_responses
- survey_response_items
- targets
- groups
- conditions
- images
- faqs
- events
- hashtags
- coupons
- balance_injector_coupons
- business_sectors
- commission_break_downs
- carts
- cart_items
- pools
- trees
- cash_balances
- b_f_ss_balances
- discount_balances
- s_m_s_balances
- shares_balances
- tree_balances
- chance_balances
- current_balances
- activities
- sms
- user_current_balance_horisontals
- user_current_balance_verticals
- contact_users
- committed_investor_requests
- instructor_requests
- user_guides
- vips
- translale_models
- operation_categories

## Optional: Add to Fillable Arrays

If you want to manually set these fields, add them to your model's `$fillable` array:

```php
protected $fillable = [
    // ... existing fields
    'created_by',
    'updated_by',
];
```

**Note:** This is not required for automatic functionality, only if you need to manually set these values.

## Querying by Creator/Updater

You can query records by who created or updated them:

```php
// Get all deals created by user ID 1
$deals = Deal::where('created_by', 1)->get();

// Get all orders updated by current user
$orders = Order::where('updated_by', Auth::id())->get();

// Using relationships
$deals = Deal::whereHas('creator', function($query) {
    $query->where('email', 'like', '%@example.com');
})->get();
```

## Benefits

1. **Automatic Tracking**: No need to manually set created_by/updated_by in controllers
2. **Consistent**: Same behavior across all models using the trait
3. **Relationships**: Easy access to creator and updater user objects
4. **Safe**: Uses nullable fields and set null on delete
5. **Reusable**: Just add the trait to any model
6. **Clean Code**: Keeps controllers free from auditing logic

## Important Notes

- Fields are only set when a user is authenticated (`Auth::check()`)
- If no user is authenticated, the fields remain `null`
- The migration checks if columns already exist before adding them
- Foreign keys use `onDelete('set null')` to prevent issues when users are deleted
- The trait uses boot method, so it works automatically with Eloquent events

## Rollback

If you need to remove the auditing fields:

```bash
php artisan migrate:rollback
```

This will remove the `created_by` and `updated_by` columns from all tables.

