# Clean Unused Translations - Quick Reference

## Command
```bash
php artisan translate:clean-unused [options]
```

## Options
- `--dry-run` - Preview without making changes
- `--lang=XX` - Clean specific language (ar, fr, en, etc.)
- `--backup` - Create timestamped backups

## Quick Usage

### Preview Mode (Safe)
```bash
php artisan translate:clean-unused --dry-run
```

### Clean All Languages
```bash
php artisan translate:clean-unused
```

### Clean with Backup
```bash
php artisan translate:clean-unused --backup
```

### Clean Specific Language
```bash
php artisan translate:clean-unused --lang=ar --backup
```

## What It Does
1. ✅ Scans codebase for translation usage
2. ✅ Identifies unused translation keys
3. ✅ Removes them from language files
4. ✅ Shows detailed statistics

## Scans These Locations
- `resources/views/` - Blade templates
- `app/Livewire/` - Livewire components
- `app/Http/Controllers/` - Controllers
- `app/` - All app files

## Detects These Patterns
- `__('key')`
- `trans('key')`
- `@lang('key')`
- `{{ __('key') }}`
- `{{ trans('key') }}`

## Output Summary
```
┌──────────┬────────────┬──────┬─────────┬───────────┐
│ Language │ Total Keys │ Kept │ Removed │ % Removed │
├──────────┼────────────┼──────┼─────────┼───────────┤
│ AR       │ 3666       │ 2845 │ 821     │ 22.4%     │
│ FR       │ 3001       │ 2845 │ 156     │ 5.2%      │
└──────────┴────────────┴──────┴─────────┴───────────┘
```

## Recommended Workflow
```bash
# 1. Preview first
php artisan translate:clean-unused --dry-run

# 2. Review output

# 3. Clean with backup
php artisan translate:clean-unused --backup

# 4. Test application

# 5. If needed, restore backup
```

## Restore Backup
```bash
# List backups
ls resources/lang/*.backup_*

# Restore
cp resources/lang/ar.json.backup_TIMESTAMP resources/lang/ar.json
```

## ⚠️ Important Notes
- Always use `--dry-run` first
- May miss dynamically constructed keys
- Create backups for safety
- Test thoroughly after cleanup

## Integration
```bash
# Full maintenance workflow
php artisan translate:sync-tabs
php artisan translate:clean-unused --backup
php artisan cache:clear
```

## See Full Documentation
[CLEAN_UNUSED_TRANSLATIONS_COMMAND.md](./CLEAN_UNUSED_TRANSLATIONS_COMMAND.md)

