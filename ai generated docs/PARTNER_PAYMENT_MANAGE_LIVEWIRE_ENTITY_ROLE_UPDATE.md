# Partner Payment Manage Livewire Component Entity Role Update

## Summary
Updated the `PartnerPaymentManage` Livewire component and the `Platform` model to use the `entity_roles` table instead of directly checking `owner_id`, `marketing_manager_id`, and `financial_manager_id` columns.

## Changes Made

### 1. PartnerPaymentManage Component - `rules()` Method

**File:** `app/Livewire/PartnerPaymentManage.php`

#### Validation Rule Update

**Before:**
```php
'partner_id' => [
    'required',
    'exists:users,id',
    function ($attribute, $value, $fail) {
        $isPlatformPartner = \DB::table('platforms')
            ->where(function ($query) use ($value) {
                $query->where('financial_manager_id', $value)
                    ->orWhere('marketing_manager_id', $value)
                    ->orWhere('owner_id', $value);
            })
            ->exists();

        if (!$isPlatformPartner) {
            $fail('The selected partner must be a platform manager or owner.');
        }
    },
],
```

**After:**
```php
'partner_id' => [
    'required',
    'exists:users,id',
    function ($attribute, $value, $fail) {
        $isPlatformPartner = \DB::table('entity_roles')
            ->where('user_id', $value)
            ->where('roleable_type', 'App\\Models\\Platform')
            ->exists();

        if (!$isPlatformPartner) {
            $fail('The selected partner must be a platform manager or owner.');
        }
    },
],
```

### 2. PartnerPaymentManage Component - `searchPartners()` Method

**Before:**
```php
public function searchPartners()
{
    if (empty($this->searchPartner)) {
        return [];
    }

    $partnerIds = \DB::table('platforms')
        ->select('financial_manager_id', 'marketing_manager_id', 'owner_id')
        ->get()
        ->flatMap(function ($platform) {
            return [
                $platform->financial_manager_id,
                $platform->marketing_manager_id,
                $platform->owner_id
            ];
        })
        ->filter()
        ->unique()
        ->values();

    return User::whereIn('id', $partnerIds)
        ->where(function ($query) {
            $query->where('name', 'like', '%' . $this->searchPartner . '%')
                ->orWhere('email', 'like', '%' . $this->searchPartner . '%')
                ->orWhere('id', 'like', '%' . $this->searchPartner . '%');
        })
        ->limit(10)
        ->get();
}
```

**After:**
```php
public function searchPartners()
{
    if (empty($this->searchPartner)) {
        return [];
    }

    $partnerIds = \DB::table('entity_roles')
        ->where('roleable_type', 'App\\Models\\Platform')
        ->distinct()
        ->pluck('user_id');

    return User::whereIn('id', $partnerIds)
        ->where(function ($query) {
            $query->where('name', 'like', '%' . $this->searchPartner . '%')
                ->orWhere('email', 'like', '%' . $this->searchPartner . '%')
                ->orWhere('id', 'like', '%' . $this->searchPartner . '%');
        })
        ->limit(10)
        ->get();
}
```

### 3. Platform Model - `havePartnerSpecialRole()` Static Method

**File:** `app/Models/Platform.php`

**Before:**
```php
public static function havePartnerSpecialRole($id)
{
    if (User::isSuperAdmin()) {
        return true;
    }
    return Platform::where(function ($query) use ($id) {
        $query
            ->where('financial_manager_id', '=', $id)
            ->orWhere('owner_id', '=', $id)
            ->orWhere('marketing_manager_id', '=', $id);
    })
        ->exists();
}
```

**After:**
```php
public static function havePartnerSpecialRole($id)
{
    if (User::isSuperAdmin()) {
        return true;
    }
    return DB::table('entity_roles')
        ->where('user_id', $id)
        ->where('roleable_type', 'App\\Models\\Platform')
        ->exists();
}
```

## Benefits

### 1. **Simplified Query Logic**
**Before:** 
- Multiple column checks with `orWhere` clauses
- Complex nested queries
- Had to fetch all platforms and extract role IDs

**After:**
- Single table query
- Simple condition checks
- Direct user_id lookup in entity_roles

### 2. **Performance Improvements**

#### Validation Rule
**Before:**
```sql
SELECT * FROM platforms 
WHERE (financial_manager_id = ? OR marketing_manager_id = ? OR owner_id = ?)
```

**After:**
```sql
SELECT EXISTS(
    SELECT 1 FROM entity_roles 
    WHERE user_id = ? AND roleable_type = 'App\Models\Platform'
)
```

#### Search Partners
**Before:**
```sql
SELECT financial_manager_id, marketing_manager_id, owner_id FROM platforms
-- Then manually filter, flatten, and deduplicate in PHP
```

**After:**
```sql
SELECT DISTINCT user_id FROM entity_roles 
WHERE roleable_type = 'App\Models\Platform'
```

### 3. **Maintainability**
- Consistent with the rest of the application
- Centralized role management
- Easy to add new role types without code changes

### 4. **Flexibility**
- Can support unlimited roles per platform
- Role names are data-driven, not hardcoded
- Users can have multiple roles across different platforms

## Usage in Component

### Validation
The validation rule ensures that only users with platform roles can be selected as partners when creating or editing partner payments.

### Partner Search
The search functionality allows admin users to find and select partners from all users who have any role in any platform.

### Permission Check
The `mount()` method uses `Platform::havePartnerSpecialRole()` to verify the current user has permission to access the payment management page.

## Testing Recommendations

### 1. Validation Testing
```php
// Test valid partner (has platform role)
$userWithRole = User::factory()->create();
EntityRole::create([
    'user_id' => $userWithRole->id,
    'roleable_type' => Platform::class,
    'roleable_id' => $platform->id,
    'name' => 'owner'
]);

// Test invalid partner (no platform role)
$userWithoutRole = User::factory()->create();

// Validation should pass for userWithRole
// Validation should fail for userWithoutRole
```

### 2. Search Testing
```php
// Create users with and without roles
$partner1 = User::factory()->create(['name' => 'John Partner']);
$partner2 = User::factory()->create(['name' => 'Jane Partner']);
$regularUser = User::factory()->create(['name' => 'Regular User']);

EntityRole::create([
    'user_id' => $partner1->id,
    'roleable_type' => Platform::class,
    'roleable_id' => $platform->id,
    'name' => 'owner'
]);

EntityRole::create([
    'user_id' => $partner2->id,
    'roleable_type' => Platform::class,
    'roleable_id' => $platform2->id,
    'name' => 'marketing_manager'
]);

// Search should return partner1 and partner2
// Search should NOT return regularUser
```

### 3. Permission Testing
```php
// Test with platform partner
$this->actingAs($partner1)
    ->get(route('partner_payment_manage'))
    ->assertSuccessful();

// Test with non-partner
$this->actingAs($regularUser)
    ->get(route('partner_payment_manage'))
    ->assertRedirect()
    ->assertSessionHas('error');
```

## Edge Cases to Consider

1. **User with no roles** - Should fail validation and not appear in search
2. **User with multiple platform roles** - Should appear once in search results
3. **User with partner role but no platform role** - Should not pass validation
4. **Deleted entity_roles records** - Handled by database constraints
5. **Super admin users** - Always pass `havePartnerSpecialRole()` check

## Migration Considerations

### Data Integrity
Ensure all users who previously had roles via the old columns now have corresponding entries in the `entity_roles` table:

```sql
-- Check for users in old columns not in entity_roles
SELECT DISTINCT u.id, u.name, u.email
FROM users u
WHERE u.id IN (
    SELECT p.owner_id FROM platforms p WHERE p.owner_id IS NOT NULL
    UNION
    SELECT p.marketing_manager_id FROM platforms p WHERE p.marketing_manager_id IS NOT NULL
    UNION
    SELECT p.financial_manager_id FROM platforms p WHERE p.financial_manager_id IS NOT NULL
)
AND u.id NOT IN (
    SELECT DISTINCT er.user_id 
    FROM entity_roles er 
    WHERE er.roleable_type = 'App\\Models\\Platform'
);
```

## Files Modified

1. **app/Livewire/PartnerPaymentManage.php**
   - Updated `rules()` validation
   - Updated `searchPartners()` method
   - More efficient partner lookup

2. **app/Models/Platform.php**
   - Updated `havePartnerSpecialRole()` static method
   - Uses entity_roles table for permission checks

## Related Updates

This update is part of a larger refactoring to use the `entity_roles` table throughout the application. Related files that have been updated:

- `app/Services/Deals/DealService.php`
- `app/Http/Controllers/Api/partner/PartnerPaymentController.php`
- `app/Http/Controllers/Api/partner/UserPartnerController.php`
- `app/Services/EntityRole/EntityRoleService.php`

## Date
January 16, 2026
