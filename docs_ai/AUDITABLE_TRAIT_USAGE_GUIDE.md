# Using the AuditableTrait in Models

## Overview

The `AuditableTrait` has been created to automatically populate the `created_by` and `updated_by` fields in your models. This trait should be added to all models that have auditing fields.

## How to Use

### Step 1: Add the Trait to Your Model

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\AuditableTrait;

class YourModel extends Model
{
    use AuditableTrait;
    
    protected $fillable = [
        // your other fields...
        'created_by',
        'updated_by',
    ];
}
```

### Step 2: The Trait Will Automatically:

1. **On Create**: Set both `created_by` and `updated_by` to the current authenticated user's ID
2. **On Update**: Set `updated_by` to the current authenticated user's ID

### Step 3: Access Creator and Updater Information

The trait provides two relationships:

```php
// Get the user who created the record
$model->creator; // Returns User model or default "System"

// Get the user who last updated the record
$model->updater; // Returns User model or default "System"

// In Blade views
{{ $model->creator->name }}
{{ $model->updater->name }}
```

## Models That Need This Trait

Based on the migration, the following models should have the `AuditableTrait` added:

### Core Tables
- [ ] User (already has auditing)
- [ ] Deal
- [ ] Item
- [ ] Order
- [ ] OrderDetail
- [ ] OrderDeal
- [ ] Share
- [ ] ItemDealHistory

### Survey System
- [ ] Survey
- [ ] SurveyQuestion
- [ ] SurveyQuestionChoice
- [ ] SurveyResponse
- [ ] SurveyResponseItem
- [ ] Target
- [ ] Group
- [ ] Condition

### Content
- [ ] News
- [ ] Like
- [ ] Comment
- [ ] Image
- [ ] Faq
- [ ] Event
- [ ] Hashtag

### Commerce
- [ ] Coupon
- [ ] BalanceInjectorCoupon
- [ ] BusinessSector
- [ ] CommissionBreakDown
- [ ] Cart
- [ ] CartItem

### Balance System
- [ ] Pool
- [ ] Tree
- [ ] CashBalance
- [ ] BFSSBalance
- [ ] DiscountBalance
- [ ] SMSBalance
- [ ] SharesBalance
- [ ] TreeBalance
- [ ] ChanceBalance
- [ ] CurrentBalance
- [ ] Activity
- [ ] SMS
- [ ] UserCurrentBalanceHorisontal
- [ ] UserCurrentBalanceVertical
- [ ] BalanceOperation
- [ ] OperationCategory

### User Related
- [ ] ContactUser
- [ ] CommittedInvestorRequest
- [ ] InstructorRequest
- [ ] UserGuide
- [ ] UserContact
- [ ] Vip
- [ ] UserEarn
- [ ] UserBalance
- [ ] UserContactNumber
- [ ] MettaUser
- [ ] Representative

### System Tables
- [ ] TranslaleModel
- [ ] Translatetab
- [ ] Transaction
- [ ] Targetable
- [ ] State
- [ ] Setting
- [ ] Role
- [ ] RoleHasPermission (pivot table - may not need a model)
- [ ] Platform
- [ ] FinancialRequest
- [ ] DetailFinancialRequest
- [ ] Country

## Example Implementation

Here's a complete example for the `Vip` model:

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\AuditableTrait;

class Vip extends Model
{
    use AuditableTrait;
    
    protected $table = 'vip';
    
    protected $fillable = [
        'idUser',
        'flashCoefficient',
        'flashDeadline',
        'flashNote',
        'flashMinAmount',
        'dateFNS',
        'maxShares',
        'solde',
        'declenched',
        'declenchedDate',
        'closed',
        'closedDate',
        'created_by',
        'updated_by',
    ];
    
    protected $casts = [
        'flashCoefficient' => 'integer',
        'flashDeadline' => 'integer',
        'flashMinAmount' => 'float',
        'maxShares' => 'float',
        'solde' => 'float',
        'declenched' => 'boolean',
        'closed' => 'boolean',
        'dateFNS' => 'datetime',
        'declenchedDate' => 'datetime',
        'closedDate' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'created_by' => 'integer',
        'updated_by' => 'integer',
    ];
    
    // Relationship to the user this VIP record belongs to
    public function user()
    {
        return $this->belongsTo(User::class, 'idUser', 'id');
    }
}
```

## Important Notes

1. **Authentication Required**: The trait only sets auditing fields when a user is authenticated. For seeding or console commands, you may need to manually set these fields.

2. **Manual Override**: You can still manually set `created_by` and `updated_by` when needed:
   ```php
   Model::create([
       'field' => 'value',
       'created_by' => $specificUserId,
   ]);
   ```

3. **Pivot Tables**: For pivot tables like `role_has_permissions`, you may not need a full model, but if you create one, add the trait.

4. **Testing**: In tests, make sure to authenticate a user before creating records:
   ```php
   $user = User::factory()->create();
   $this->actingAs($user);
   
   $model = YourModel::create([...]);
   $this->assertEquals($user->id, $model->created_by);
   ```

## Next Steps

1. Go through each model file and add `use AuditableTrait;`
2. Add `'created_by'` and `'updated_by'` to the `$fillable` array
3. Add appropriate casts for these fields
4. Test the implementation with your application

