# Sync All Translations Command

## Overview
The `translate:sync-all` command provides a complete translation synchronization workflow by executing all translation-related commands in sequence and updating the database.

---

## Command Signature
```bash
php artisan translate:sync-all [options]
```

## Options
- `--skip-sync` - Skip the sync-tabs step
- `--skip-merge` - Skip the merge-all step
- `--skip-clean` - Skip the clean-unused step

---

## What It Does

This command executes **4 steps** in sequence:

### Step 1: Sync Translation Keys
**Command**: `translate:sync-tabs`
- Scans codebase for translation keys
- Adds missing keys to database
- Updates translatetabs table

### Step 2: Merge All Translations
**Command**: `translate:merge-all`
- Merges all 7 language files (ar, fr, en, es, tr, de, ru)
- Creates backups
- Updates resources/lang/*.json files

### Step 3: Clean Unused Keys
**Command**: `translate:clean-unused --backup`
- Identifies unused translation keys
- Removes them from language files
- Creates backups automatically

### Step 4: Update Database
**Job**: `TranslationFilesToDatabase`
- Imports translations from files to database
- Updates translatetabs table
- Logs execution time

---

## Usage

### Full Synchronization (All Steps)
```bash
php artisan translate:sync-all
```

### Skip Specific Steps
```bash
# Skip sync-tabs
php artisan translate:sync-all --skip-sync

# Skip merge-all
php artisan translate:sync-all --skip-merge

# Skip clean-unused
php artisan translate:sync-all --skip-clean

# Multiple skips
php artisan translate:sync-all --skip-sync --skip-merge
```

---

## Output Example

```
═══════════════════════════════════════════════════
        FULL TRANSLATION SYNCHRONIZATION
═══════════════════════════════════════════════════

📝 Step 1/4: Syncing translation keys from code...
   Command: translate:sync-tabs

Scanning for translation keys...
- Scanning Blade views...
  Found 2845 unique keys in views
- Scanning Livewire components...
  Found 156 unique keys in Livewire
- Scanning Controllers...
  Found 89 unique keys in Controllers
Total unique translation keys found: 2890
Total missing keys: 45
Total added to translatetab: 45
45 missing translation keys added to translatetab.

   ✅ Sync completed in 3.245s

🔄 Step 2/4: Merging all translation files...
   Command: translate:merge-all

🚀 Starting merge for all translation files...

📝 Processing Arabic (ar)...
   ✅ Arabic merged successfully!

📝 Processing French (fr)...
   ✅ French merged successfully!

... (all 7 languages)

═══════════════════════════════════════════════════
                 MERGE SUMMARY
═══════════════════════════════════════════════════

┌────┬──────┬──────────┬─────────┬──────┐
│    │ Code │ Language │ Status  │ Note │
├────┼──────┼──────────┼─────────┼──────┤
│ ✅ │ AR   │ Arabic   │ Success │ -    │
│ ✅ │ FR   │ French   │ Success │ -    │
│ ✅ │ EN   │ English  │ Success │ -    │
│ ✅ │ ES   │ Spanish  │ Success │ -    │
│ ✅ │ TR   │ Turkish  │ Success │ -    │
│ ✅ │ DE   │ German   │ Success │ -    │
│ ✅ │ RU   │ Russian  │ Success │ -    │
└────┴──────┴──────────┴─────────┴──────┘

🎉 All translations merged successfully!

   ✅ Merge completed in 2.156s

🧹 Step 3/4: Cleaning unused translation keys...
   Command: translate:clean-unused

🧹 Starting cleanup of unused translation keys...

📂 Scanning codebase for translation usage...
 1250/1250 [============================] 100%
✅ Found 2890 unique translation keys in use

🔍 Processing ar.json...
   💾 Backup created: ar.json.backup_20251226170045
   ✓ 156 unused keys found

... (all languages)

═══════════════════════════════════════════════════
           CLEANUP SUMMARY
═══════════════════════════════════════════════════

┌──────────┬────────────┬──────┬─────────┬───────────┐
│ Language │ Total Keys │ Kept │ Removed │ % Removed │
├──────────┼────────────┼──────┼─────────┼───────────┤
│ AR       │ 3666       │ 2890 │ 776     │ 21.2%     │
│ FR       │ 3001       │ 2890 │ 111     │ 3.7%      │
│ EN       │ 2934       │ 2890 │ 44      │ 1.5%      │
│ ES       │ 2934       │ 2890 │ 44      │ 1.5%      │
│ TR       │ 2934       │ 2890 │ 44      │ 1.5%      │
│ DE       │ 2934       │ 2890 │ 44      │ 1.5%      │
│ RU       │ 2934       │ 2890 │ 44      │ 1.5%      │
└──────────┴────────────┴──────┴─────────┴───────────┘

✅ Cleanup completed successfully!
💾 Backups were created for all modified files

   ✅ Cleanup completed in 5.789s

💾 Step 4/4: Updating database from files...
   Job: TranslationFilesToDatabase

   ✅ Database update completed in 1.234s

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

🎉 All translation synchronization steps completed successfully!
═══════════════════════════════════════════════════
```

---

## Workflow

The command follows this workflow:

```
1. Sync Keys (translate:sync-tabs)
   ↓
   Scans code → Finds translation keys → Adds to database
   
2. Merge All (translate:merge-all)
   ↓
   Reads new trans/*.json → Merges into resources/lang/*.json
   
3. Clean Unused (translate:clean-unused)
   ↓
   Scans code usage → Removes unused keys → Creates backups
   
4. Update Database (TranslationFilesToDatabase)
   ↓
   Reads resources/lang/*.json → Updates translatetabs table
```

---

## Use Cases

### Daily Translation Sync
```bash
# Full sync every day
php artisan translate:sync-all
```

### After Code Changes
```bash
# Sync only keys and database
php artisan translate:sync-all --skip-merge --skip-clean
```

### After Translation Updates
```bash
# Merge and update database only
php artisan translate:sync-all --skip-sync --skip-clean
```

### Manual Cleanup
```bash
# Just clean and update
php artisan translate:sync-all --skip-sync --skip-merge
```

---

## Benefits

### ✅ Complete Automation
- All 4 steps in one command
- No need to remember multiple commands
- Consistent execution order

### ✅ Time Tracking
- Shows execution time for each step
- Total time calculated
- Easy to identify slow steps

### ✅ Error Handling
- Continues even if one step fails
- Shows which steps succeeded/failed
- Returns appropriate exit code

### ✅ Flexibility
- Skip unnecessary steps
- Customize workflow
- Backup creation included

### ✅ Comprehensive Summary
- Beautiful table with results
- Clear status indicators
- Time breakdown

---

## When to Use

### ✅ Use This Command When:
- Deploying new code with translation changes
- Regular maintenance (daily/weekly)
- After adding new features
- Before major releases
- Setting up new environments

### ⚠️ Consider Individual Commands When:
- Testing specific functionality
- Debugging translation issues
- Need more control over process
- Want to see detailed output per step

---

## Comparison

### Before (Manual Process)
```bash
# 4 separate commands
php artisan translate:sync-tabs
php artisan translate:merge-all
php artisan translate:clean-unused --backup
# Then run TranslationFilesToDatabase job manually
```
**Time**: ~5 minutes (manual)
**Risk**: Easy to forget steps

### After (Automated)
```bash
# 1 command
php artisan translate:sync-all
```
**Time**: ~1 minute (automated)
**Risk**: None, all steps guaranteed

---

## Integration

### With Deployment Scripts
```bash
#!/bin/bash
# deploy.sh

git pull
composer install --no-dev

# Sync all translations
php artisan translate:sync-all

# Cache everything
php artisan cache:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### With Cron Jobs
```bash
# Sync translations daily at 3 AM
0 3 * * * cd /path/to/2earn && php artisan translate:sync-all >> /var/log/translations.log 2>&1
```

### In CI/CD Pipeline
```yaml
# .gitlab-ci.yml
deploy:
  script:
    - composer install
    - php artisan translate:sync-all
    - php artisan cache:clear
```

---

## Logging

The command logs to Laravel's default log:

```php
// storage/logs/laravel.log
[2025-12-26 17:00:45] local.INFO: App\Jobs\TranslationFilesToDatabase : 1.234s
```

---

## Error Handling

### If Step 1 Fails
- Command continues to Step 2
- Error logged and displayed
- Exit code will be 1 (FAILURE)

### If Step 2 Fails
- Command continues to Step 3
- Individual language failures shown
- Summary indicates which failed

### If Step 3 Fails
- Command continues to Step 4
- Cleanup errors displayed
- Backups preserved

### If Step 4 Fails
- Command stops
- Exception logged
- Database may be partially updated

---

## Return Codes

| Code | Meaning |
|------|---------|
| 0 | SUCCESS - All steps completed successfully |
| 1 | FAILURE - One or more steps failed |

---

## Performance

### Average Execution Times
- **Sync Keys**: 2-5 seconds
- **Merge All**: 1-3 seconds
- **Clean Unused**: 5-10 seconds
- **Update Database**: 1-2 seconds
- **Total**: ~10-20 seconds

### Large Projects
- More translation keys = longer sync
- More files = longer cleanup scan
- More languages = longer merge

---

## Best Practices

### 1. Run Regularly
```bash
# Daily sync
php artisan translate:sync-all
```

### 2. After Code Changes
```bash
# Quick sync without cleanup
php artisan translate:sync-all --skip-clean
```

### 3. Before Deployment
```bash
# Full sync with all steps
php artisan translate:sync-all
```

### 4. Monitor Performance
- Check execution times
- Optimize slow steps
- Review logs regularly

### 5. Backup First
- Cleanup step creates backups automatically
- Keep backups for a few days
- Test after running

---

## Troubleshooting

### Command Takes Too Long
**Solution**: Skip cleanup step for faster execution
```bash
php artisan translate:sync-all --skip-clean
```

### Step Fails Repeatedly
**Solution**: Run individual command with verbose output
```bash
php artisan translate:sync-tabs -v
```

### Database Not Updated
**Solution**: Check TranslationFilesToDatabase job logs
```bash
tail -f storage/logs/laravel.log
```

---

## Related Commands

### Individual Commands
```bash
php artisan translate:sync-tabs       # Step 1
php artisan translate:merge-all       # Step 2
php artisan translate:clean-unused    # Step 3
```

### Cache Commands
```bash
php artisan cache:clear
php artisan config:cache
php artisan view:cache
```

---

## File Location

**Command**: `app/Console/Commands/SyncAllTranslations.php`

---

## Summary

✅ **One command** to rule them all
✅ **4 steps** executed automatically
✅ **Time tracking** for each step
✅ **Error resilient** - continues on failures
✅ **Flexible** - skip unnecessary steps
✅ **Comprehensive summary** with statistics
✅ **Production ready** with logging

The `translate:sync-all` command is **the ultimate solution** for translation management in the 2earn application! 🚀

