# 🎯 Auditing Quick Reference Card

## Commands Cheat Sheet

```bash
# Check all tables have auditing fields
php artisan auditing:check-tables

# Check specific tables
php artisan auditing:check-tables vip pool platforms

# Show all columns in verbose mode
php artisan auditing:check-tables -v

# Scan all models for HasAuditing trait
php artisan auditing:find-models

# Show only models missing the trait
php artisan auditing:find-models --missing

# Full system verification
php artisan auditing:verify

# List all auditing commands
php artisan list auditing
```

## Model Setup Template

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasAuditing;

class YourModel extends Model
{
    use HasAuditing;
    
    protected $fillable = [
        // your fields...
        'created_by',
        'updated_by',
    ];
}
```

## Migration Template

```php
Schema::create('your_table', function (Blueprint $table) {
    $table->id();
    // your fields...
    
    // Auditing fields
    $table->timestamps();
    $table->unsignedBigInteger('created_by')->nullable();
    $table->unsignedBigInteger('updated_by')->nullable();
    
    $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
    $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');
});
```

## Usage Examples

```php
// Automatic auditing (just create/update normally)
Auth::loginUsingId(1);
$model = Model::create(['field' => 'value']);
// $model->created_by is now 1

// Access audit info
$model->creator->name      // User who created
$model->updater->name      // User who updated
$model->created_at         // When created
$model->updated_at         // When updated
```

## In Blade Templates

```blade
<p>Created by: {{ $model->creator->name }}</p>
<p>Updated by: {{ $model->updater->name }}</p>
<p>Created: {{ $model->created_at->diffForHumans() }}</p>
<p>Updated: {{ $model->updated_at->diffForHumans() }}</p>
```

## Troubleshooting

```bash
# Model not saving auditing fields?
php artisan auditing:find-models | grep YourModel

# Table missing columns?
php artisan auditing:check-tables your_table -v

# Full diagnostic
php artisan auditing:verify

# Test manually
php artisan tinker
> Auth::loginUsingId(1)
> $m = YourModel::create([...])
> $m->created_by  // Should show 1
```

## What Gets Tracked

✅ `created_at` - When record was created  
✅ `updated_at` - When record was last updated  
✅ `created_by` - User ID who created record  
✅ `updated_by` - User ID who last updated record  

## 21 Tables with Full Auditing

user_contacts, vip, user_earns, user_balances, usercontactnumber,  
translatetab, transactions, targetables, states, sms_balances,  
settings, role_has_permissions, roles, representatives, pool,  
platforms, metta_users, financial_request, detail_financial_request,  
countries, balance_operations

## Documentation Files

📄 README_AUDITING_COMPLETE.md - Complete overview  
📄 AUDITING_COMMANDS_DOCUMENTATION.md - Command details  
📄 AUDITING_IMPLEMENTATION_COMPLETE.md - Technical details  
📄 AUDITABLE_TRAIT_USAGE_GUIDE.md - Model guide  
📄 AUDITING_CHECKLIST.md - Verification checklist  

---
*Keep this file handy for quick reference!*

