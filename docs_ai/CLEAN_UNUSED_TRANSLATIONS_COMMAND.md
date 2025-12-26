# Clean Unused Translations Command

## Overview
The `translate:clean-unused` command scans your codebase to identify and remove translation keys that are no longer being used. This helps keep your translation files clean and maintainable.

---

## Command Signature
```bash
php artisan translate:clean-unused {--dry-run} {--lang=} {--backup}
```

---

## Options

### `--dry-run`
Preview what would be removed without actually modifying files.

**Usage:**
```bash
php artisan translate:clean-unused --dry-run
```

### `--lang=`
Clean a specific language file only.

**Usage:**
```bash
php artisan translate:clean-unused --lang=ar
php artisan translate:clean-unused --lang=fr
```

### `--backup`
Create timestamped backups before removing unused keys.

**Usage:**
```bash
php artisan translate:clean-unused --backup
```

---

## How It Works

### 1. Scan Phase
The command scans these directories for translation usage:
- `resources/views/` - Blade templates
- `app/Livewire/` - Livewire components
- `app/Http/Controllers/` - Controllers
- `app/` - All other app files

### 2. Pattern Detection
Searches for these translation patterns:
- `__('key')` - Translation helper
- `trans('key')` - Trans function
- `@lang('key')` - Blade directive
- `{{ __('key') }}` - Blade echo with helper
- `{{ trans('key') }}` - Blade echo with trans

### 3. Comparison
Compares keys in language files against keys found in code.

### 4. Cleanup
Removes keys that aren't used anywhere in the codebase.

---

## Usage Examples

### Preview Mode (Recommended First)
See what would be removed without making changes:
```bash
php artisan translate:clean-unused --dry-run
```

### Clean All Languages
Remove unused keys from all language files:
```bash
php artisan translate:clean-unused
```

### Clean Specific Language
Clean only Arabic translations:
```bash
php artisan translate:clean-unused --lang=ar
```

### Clean with Backup
Create backups before cleaning:
```bash
php artisan translate:clean-unused --backup
```

### Combined Options
```bash
# Preview Arabic cleanup
php artisan translate:clean-unused --lang=ar --dry-run

# Clean all with backup
php artisan translate:clean-unused --backup

# Preview all languages
php artisan translate:clean-unused --dry-run
```

---

## Output Example

### Dry Run Mode
```
🧹 Starting cleanup of unused translation keys...

📂 Scanning codebase for translation usage...
 1250/1250 [============================] 100%
✅ Found 2845 unique translation keys in use

🔍 Processing ar.json...
   ✓ 821 unused keys found
🔍 Processing fr.json...
   ✓ 156 unused keys found
🔍 Processing en.json...
   ✓ 89 unused keys found

═══════════════════════════════════════════════════
           CLEANUP SUMMARY
═══════════════════════════════════════════════════

┌──────────┬────────────┬──────┬─────────┬───────────┐
│ Language │ Total Keys │ Kept │ Removed │ % Removed │
├──────────┼────────────┼──────┼─────────┼───────────┤
│ AR       │ 3666       │ 2845 │ 821     │ 22.4%     │
│ FR       │ 3001       │ 2845 │ 156     │ 5.2%      │
│ EN       │ 2934       │ 2845 │ 89      │ 3.0%      │
└──────────┴────────────┴──────┴─────────┴───────────┘

📊 Keys in use across codebase: 2845
🗑️  Total unused keys found: 1066

🔍 DRY RUN MODE - No files were modified
Run without --dry-run to actually remove unused keys

Sample of unused keys (first 10):
  • old_feature_button
  • deprecated_message
  • unused_label
  • test_translation
  • removed_feature_text
  • legacy_error_message
  • old_dashboard_title
  • unused_notification
  • test_key_delete_me
  • old_menu_item

═══════════════════════════════════════════════════
```

### Actual Cleanup
```
🧹 Starting cleanup of unused translation keys...

📂 Scanning codebase for translation usage...
 1250/1250 [============================] 100%
✅ Found 2845 unique translation keys in use

🔍 Processing ar.json...
   💾 Backup created: ar.json.backup_20251226163045
   ✓ 821 unused keys found
🔍 Processing fr.json...
   💾 Backup created: fr.json.backup_20251226163046
   ✓ 156 unused keys found
🔍 Processing en.json...
   💾 Backup created: en.json.backup_20251226163047
   ✓ 89 unused keys found

═══════════════════════════════════════════════════
           CLEANUP SUMMARY
═══════════════════════════════════════════════════

┌──────────┬────────────┬──────┬─────────┬───────────┐
│ Language │ Total Keys │ Kept │ Removed │ % Removed │
├──────────┼────────────┼──────┼─────────┼───────────┤
│ AR       │ 3666       │ 2845 │ 821     │ 22.4%     │
│ FR       │ 3001       │ 2845 │ 156     │ 5.2%      │
│ EN       │ 2934       │ 2845 │ 89      │ 3.0%      │
└──────────┴────────────┴──────┴─────────┴───────────┘

📊 Keys in use across codebase: 2845
🗑️  Total unused keys found: 1066

✅ Cleanup completed successfully!
💾 Backups were created for all modified files

═══════════════════════════════════════════════════
```

---

## Features

### ✅ Safe by Default
- **Dry-run mode**: Preview changes before applying
- **Backup option**: Create timestamped backups
- **Progress tracking**: See scanning progress
- **Detailed reporting**: Know exactly what was removed

### ✅ Comprehensive Scanning
- Scans views, controllers, Livewire components
- Multiple translation pattern detection
- Recursive directory scanning
- Handles all Laravel translation methods

### ✅ Flexible Operation
- Clean all languages at once
- Target specific language files
- Choose whether to backup
- Preview mode for safety

### ✅ Clear Reporting
- Summary table with statistics
- Percentage of keys removed
- Total keys in use
- Sample of unused keys (in dry-run)

---

## Workflow

### Recommended Workflow
```bash
# 1. Preview what would be removed
php artisan translate:clean-unused --dry-run

# 2. Review the output

# 3. Clean with backup (if comfortable)
php artisan translate:clean-unused --backup

# 4. Test your application

# 5. If issues, restore from backup
cp resources/lang/ar.json.backup_TIMESTAMP resources/lang/ar.json
```

### Conservative Approach
```bash
# Clean one language at a time with backups
php artisan translate:clean-unused --lang=ar --backup --dry-run
php artisan translate:clean-unused --lang=ar --backup

# Test thoroughly before moving to next language
php artisan translate:clean-unused --lang=fr --backup
```

---

## Backup Files

Backup files are created with this format:
```
resources/lang/{lang}.json.backup_YYYYMMDDHHmmss
```

Example backups:
```
ar.json.backup_20251226163045
fr.json.backup_20251226163046
en.json.backup_20251226163047
```

### Restore from Backup
```bash
# List backups
ls resources/lang/*.backup_*

# Restore specific backup
cp resources/lang/ar.json.backup_20251226163045 resources/lang/ar.json
```

---

## Important Notes

### ⚠️ Cautions

1. **Dynamic Keys**: The command may not detect dynamically constructed keys:
   ```php
   // These may not be detected:
   __($variable)
   __("prefix.{$dynamic}.suffix")
   trans($someKey)
   ```

2. **JavaScript Usage**: Doesn't scan JavaScript files for translation usage.

3. **Database Content**: Keys used in database content won't be detected.

4. **Third-party Packages**: Keys used by packages might be removed.

### ✅ Best Practices

1. **Always use dry-run first**
   ```bash
   php artisan translate:clean-unused --dry-run
   ```

2. **Create backups**
   ```bash
   php artisan translate:clean-unused --backup
   ```

3. **Test thoroughly after cleanup**
   - Browse key pages
   - Test forms and messages
   - Check notifications
   - Verify email templates

4. **Keep backups for a while**
   - Don't delete backups immediately
   - Test for a few days
   - Then clean up old backups

5. **Clean regularly**
   - Monthly or quarterly cleanup
   - After major refactoring
   - Before major releases

---

## Troubleshooting

### False Positives

**Problem**: Used keys are being marked as unused

**Causes**:
- Dynamic key construction
- Keys in JavaScript
- Keys in database content

**Solution**:
- Review dry-run output carefully
- Keep those keys manually
- Add comments in code indicating usage

### Scanning Takes Too Long

**Problem**: Scan phase is very slow

**Solution**:
- Normal for large codebases
- Progress bar shows advancement
- Consider excluding vendor directory

### No Keys Found to Remove

**Problem**: Command reports 0 unused keys

**Possible reasons**:
- All keys are actually in use ✅
- Pattern matching issues
- Need to update scan directories

---

## Return Codes

| Code | Meaning |
|------|---------|
| 0 | SUCCESS - Operation completed |
| 1 | FAILURE - Error occurred |

---

## Integration with Other Commands

### After Cleanup
```bash
# Clean unused translations
php artisan translate:clean-unused --backup

# Clear cache
php artisan cache:clear

# Verify with sync
php artisan translate:sync-tabs
```

### Before Major Deployment
```bash
# 1. Sync keys from code
php artisan translate:sync-tabs

# 2. Merge new translations
php artisan translate:merge-all

# 3. Clean unused keys
php artisan translate:clean-unused --backup

# 4. Cache everything
php artisan cache:clear
php artisan config:cache
```

---

## Statistics

After running cleanup, you'll know:
- Total translation keys per language
- Number of keys actually in use
- Number of unused keys removed
- Percentage of cleanup per language
- Which keys were removed (dry-run)

---

## File Location

**Command**: `app/Console/Commands/CleanUnusedTranslations.php`

---

## See Also

- [Sync Translate Tabs Command](./SYNC_TRANSLATE_TABS_COMMAND.md)
- [Merge All Translations Command](./MERGE_ALL_TRANSLATIONS_COMMAND.md)
- [Translation Commands Summary](./TRANSLATION_COMMANDS_SUMMARY.md)

---

## Summary

✅ **Scans entire codebase** for translation usage
✅ **Removes unused keys** safely
✅ **Dry-run mode** for safety
✅ **Automatic backups** option
✅ **Detailed reporting** with statistics
✅ **Language-specific** or all-at-once cleanup
✅ **Progress tracking** during scan
✅ **Safe and reversible** operation

The `translate:clean-unused` command helps keep your translation files clean and maintainable by removing keys that are no longer used in your codebase! 🧹

