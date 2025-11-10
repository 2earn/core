# Auditing Commands Documentation

This document describes the three Artisan commands available for managing and verifying the auditing system.

## Available Commands

```bash
php artisan list auditing
```

### 1. auditing:check-tables
Check if database tables have all required auditing fields.

### 2. auditing:find-models
Find all models and check which ones have the HasAuditing trait.

### 3. auditing:verify
Verify that the auditing system is properly configured (comprehensive check).

---

## Command Details

### `auditing:check-tables`

**Purpose**: Verify that database tables have all required auditing columns.

**Signature**: 
```bash
php artisan auditing:check-tables {tables?*}
```

**Options**:
- `tables` (optional): Specify one or more tables to check
- `-v, --verbose`: Show detailed column listings

**Default Behavior**: 
If no tables are specified, checks these 21 tables:
- user_contacts, vip, user_earns, user_balances, usercontactnumber
- translatetab, transactions, targetables, states, sms_balances
- settings, role_has_permissions, roles, representatives, pool
- platforms, metta_users, financial_request, detail_financial_request
- countries, balance_operations

**What It Checks**:
- ✓ created_at column exists
- ✓ updated_at column exists
- ✓ created_by column exists
- ✓ updated_by column exists

**Examples**:

```bash
# Check all default tables
php artisan auditing:check-tables

# Check specific tables
php artisan auditing:check-tables users deals orders

# Check with verbose output (shows all columns)
php artisan auditing:check-tables -v

# Check specific tables with verbose output
php artisan auditing:check-tables vip pool -v
```

**Output Example**:
```
Checking default tables for auditing fields...

✅ user_contacts - All auditing fields present
✅ vip - All auditing fields present
✅ user_earns - All auditing fields present
❌ some_table - Missing: created_by, updated_by

🎉 All 21 tables have complete auditing fields!
```

---

### `auditing:find-models`

**Purpose**: Scan all Eloquent models to verify which ones have the HasAuditing trait.

**Signature**: 
```bash
php artisan auditing:find-models {--missing}
```

**Options**:
- `--missing`: Show only models that are missing the HasAuditing trait

**What It Checks**:
- Scans `App\Models` directory
- Scans `Core\Models` directory
- Verifies if each model uses the `HasAuditing` trait
- Checks if timestamps are enabled/disabled
- Identifies tables without models

**Examples**:

```bash
# Show all models with trait status
php artisan auditing:find-models

# Show only models missing the trait
php artisan auditing:find-models --missing
```

**Output Example**:
```
=== Scanning Models for HasAuditing Trait ===

📁 App\Models Directory:
  ✅ Deal [TS:✓]
  ✅ Order [TS:✓]
  ✅ Item [TS:✓]
  ❌ SomeModel [TS:✗] - Missing HasAuditing trait

📁 Core\Models Directory:
  ✅ Platform [TS:✓]
  ✅ Setting [TS:✓]
  ❌ history [TS:✓] - Missing HasAuditing trait

📊 Summary:
  App\Models: 50/50 models have HasAuditing trait
  Core\Models: 12/24 models have HasAuditing trait
  Total: 62/74 models

📋 Tables Without Models:
  ⚠️  transactions - No model found (may be package-managed)
  ⚠️  states - No model found (may be package-managed)
```

**Legend**:
- ✅ = Has HasAuditing trait
- ❌ = Missing HasAuditing trait
- `[TS:✓]` = Timestamps enabled
- `[TS:✗]` = Timestamps disabled
- `[TS:?]` = Timestamp status unknown

---

### `auditing:verify`

**Purpose**: Comprehensive verification of the entire auditing system.

**Signature**: 
```bash
php artisan auditing:verify
```

**What It Checks**:
1. **Trait Existence**: Verifies `App\Traits\HasAuditing` exists
2. **Model Verification**: Checks sample models for trait usage
3. **Database Columns**: Verifies database has auditing columns
4. **Model Count**: Counts models with/without trait

**Examples**:

```bash
php artisan auditing:verify
```

**Output Example**:
```
=== Verifying Auditing System ===

Test 1: Checking if HasAuditing trait exists
✅ HasAuditing trait found

Test 2: Checking models for HasAuditing trait
  ✅ Deal - Has trait
  ✅ Order - Has trait
  ✅ Item - Has trait

Test 3: Checking database columns
  ✅ deals - Has both columns
  ✅ orders - Has both columns
  ✅ items - Has both columns

Test 4: Scanning all models
Models WITH HasAuditing trait: 62
Models WITHOUT trait (12): Amount, NotificationsSettings, ...

=== Verification Complete ===

To test in practice:
1. php artisan tinker
2. Auth::loginUsingId(1)
3. $deal = Deal::create(["name" => "Test", "description" => "Test"])
4. $deal->created_by (should show user ID)
5. $deal->creator->name (should show user name)
```

---

## Usage Workflow

### Initial Setup Verification
After implementing auditing fields, run all three commands:

```bash
# 1. Verify database structure
php artisan auditing:check-tables

# 2. Verify models
php artisan auditing:find-models

# 3. Comprehensive verification
php artisan auditing:verify
```

### Regular Maintenance
When adding new tables or models:

```bash
# Check specific new tables
php artisan auditing:check-tables new_table_name

# Find models without trait
php artisan auditing:find-models --missing

# Full verification
php artisan auditing:verify
```

### Debugging Issues
If auditing isn't working for a specific table/model:

```bash
# 1. Check if table has columns
php artisan auditing:check-tables problem_table -v

# 2. Check if model has trait
php artisan auditing:find-models | grep ProblemModel

# 3. Test manually in tinker
php artisan tinker
> Auth::loginUsingId(1)
> $model = ProblemModel::create([...])
> $model->created_by // Should show user ID
```

---

## Return Codes

All commands follow standard exit codes:

- **0**: Success (all checks passed)
- **1**: Failure (some checks failed)

This makes them suitable for CI/CD pipelines:

```bash
# In CI/CD script
php artisan auditing:check-tables || exit 1
php artisan auditing:verify || exit 1
```

---

## Related Files

- **Commands**:
  - `app/Console/Commands/CheckTablesAuditing.php`
  - `app/Console/Commands/FindModelsForAudit.php`
  - `app/Console/Commands/VerifyAuditing.php`

- **Trait**:
  - `app/Traits/HasAuditing.php`

- **Migration**:
  - `database/migrations/2025_11_10_090000_add_missing_auditing_fields.php`

- **Documentation**:
  - `AUDITING_IMPLEMENTATION_COMPLETE.md`
  - `AUDITING_CHECKLIST.md`
  - `AUDITABLE_TRAIT_USAGE_GUIDE.md`

---

## Tips

1. **Add to CI/CD**: Include these commands in your deployment pipeline
2. **Pre-commit Hook**: Run `auditing:verify` before committing new models
3. **Regular Audits**: Schedule weekly runs to ensure consistency
4. **New Developers**: Include in onboarding to understand the audit system

---

## Future Enhancements

Potential improvements for these commands:

1. **Auto-fix Mode**: Automatically add trait to models missing it
2. **Migration Generator**: Generate migrations for tables missing columns
3. **Report Export**: Export results to JSON/CSV for documentation
4. **Integration Test**: Run actual create/update tests
5. **Performance Metrics**: Show how many records have audit data populated

