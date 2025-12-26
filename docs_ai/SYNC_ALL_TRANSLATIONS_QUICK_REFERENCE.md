# Sync All Translations - Quick Reference

## Command
```bash
php artisan translate:sync-all [options]
```

## What It Does
Executes **4 translation steps** in sequence:

1. ✅ **Sync Keys** - Scan code and add missing keys
2. ✅ **Merge All** - Merge all 7 language files  
3. ✅ **Clean Unused** - Remove unused translation keys
4. ✅ **Update Database** - Import translations to database

## Options
- `--skip-sync` - Skip sync-tabs step
- `--skip-merge` - Skip merge-all step
- `--skip-clean` - Skip clean-unused step

## Quick Usage

### Full Sync (Recommended)
```bash
php artisan translate:sync-all
```

### Skip Cleanup (Faster)
```bash
php artisan translate:sync-all --skip-clean
```

### Only Sync & Database Update
```bash
php artisan translate:sync-all --skip-merge --skip-clean
```

## Steps Breakdown

| Step | Command | Duration | Creates Backup |
|------|---------|----------|----------------|
| 1 | translate:sync-tabs | ~3s | No |
| 2 | translate:merge-all | ~2s | No |
| 3 | translate:clean-unused | ~6s | ✅ Yes |
| 4 | TranslationFilesToDatabase | ~1s | No |

**Total**: ~12 seconds

## Output Summary
```
═══════════════════════════════════════════════════
                   SUMMARY
═══════════════════════════════════════════════════

┌────┬─────────────────┬─────────┬─────────┐
│    │ Step            │ Status  │ Time    │
├────┼─────────────────┼─────────┼─────────┤
│ ✅ │ Sync Keys       │ Success │ 3.245s  │
│ ✅ │ Merge All       │ Success │ 2.156s  │
│ ✅ │ Clean Unused    │ Success │ 5.789s  │
│ ✅ │ Update Database │ Success │ 1.234s  │
└────┴─────────────────┴─────────┴─────────┘

⏱️  Total execution time: 12.424s
```

## When to Use

### ✅ Daily Operations
```bash
php artisan translate:sync-all
```

### ✅ After Code Changes
```bash
php artisan translate:sync-all --skip-merge --skip-clean
```

### ✅ Before Deployment
```bash
php artisan translate:sync-all
php artisan cache:clear
```

### ✅ After Translation Updates
```bash
php artisan translate:sync-all --skip-sync --skip-clean
```

## What Each Step Does

### Step 1: Sync Keys
- Scans: views, Livewire, controllers
- Finds: `__()`, `trans()`, `@lang()`
- Adds missing keys to database

### Step 2: Merge All
- Merges: ar, fr, en, es, tr, de, ru
- Source: `new trans/*.json`
- Target: `resources/lang/*.json`

### Step 3: Clean Unused
- Scans code for usage
- Removes unused keys
- Creates backups automatically

### Step 4: Update Database
- Imports from JSON files
- Updates translatetabs table
- Logs execution time

## Integration

### Deployment Script
```bash
git pull
composer install
php artisan translate:sync-all
php artisan cache:clear
```

### Cron Job
```bash
# Daily at 3 AM
0 3 * * * cd /path/to/2earn && php artisan translate:sync-all
```

## Benefits
- ✅ One command for everything
- ✅ Automatic error handling
- ✅ Time tracking per step
- ✅ Flexible with skip options
- ✅ Creates backups
- ✅ Comprehensive summary

## Return Codes
- `0` = All steps successful
- `1` = One or more steps failed

## See Full Documentation
[SYNC_ALL_TRANSLATIONS_COMMAND.md](./SYNC_ALL_TRANSLATIONS_COMMAND.md)

