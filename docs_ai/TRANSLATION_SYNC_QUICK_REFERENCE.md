# Translation Sync - Quick Reference

## 🚀 Quick Start

```bash
# Complete translation sync (recommended)
php artisan translate:sync-all
```

---

## 📋 What It Does (6 Steps)

1. **📝 Sync Keys** - Extract translation keys from code
2. **🔄 Merge Files** - Merge all translation files
3. **🧹 Clean Unused** - Remove unused keys
4. **💾 Update DB** - Import files to database
5. **🌐 Seed Missing** - Add missing translation keys
6. **📤 Export Files** - Export database back to files ⭐ NEW!

---

## ⚡ Commands

### Main Command
```bash
php artisan translate:sync-all
```

### With Options
```bash
# Skip specific steps
php artisan translate:sync-all --skip-sync
php artisan translate:sync-all --skip-merge
php artisan translate:sync-all --skip-clean

# Skip multiple steps
php artisan translate:sync-all --skip-sync --skip-merge
```

---

## 📂 Files Updated

### After Sync
```
✅ lang/ar.json    (Arabic)
✅ lang/en.json    (English)
✅ lang/fr.json    (French)
✅ lang/tr.json    (Turkish)
✅ lang/es.json    (Spanish)
✅ lang/ru.json    (Russian)
✅ lang/de.json    (German)
✅ Database: translatetabs table
```

---

## 🔍 Check Status

```bash
# View logs
tail -f storage/logs/laravel.log

# Check in database
php artisan tinker
>>> \Core\Models\translatetabs::count();
>>> \Core\Models\translatetabs::latest()->first();

# Check in files
cat lang/en.json | grep "Your Key"
```

---

## ⏱️ Expected Time

```
Complete Sync: 5-13 seconds
```

---

## ✅ Success Output

```
═══════════════════════════════════════════════════
                   SUMMARY                         
═══════════════════════════════════════════════════

  ✅  Sync Keys         Success  1.234s
  ✅  Merge All         Success  0.567s
  ✅  Clean Unused      Success  0.890s
  ✅  Update Database   Success  2.345s
  ✅  Seed Missing Keys Success  0.456s
  ✅  Export to Files   Success  1.789s

⏱️  Total execution time: 7.281s

🎉 All translation synchronization steps completed!
═══════════════════════════════════════════════════
```

---

## 🆘 Troubleshooting

### Export Failed?
```bash
# Manually run export
php artisan tinker
>>> (new \App\Jobs\TranslationDatabaseToFiles)->handle();
```

### Database Issues?
```bash
# Re-import from files
php artisan tinker
>>> (new \App\Jobs\TranslationFilesToDatabase)->handle();
```

### Check Logs
```bash
tail -100 storage/logs/laravel.log | grep Translation
```

---

## 📖 Full Documentation

See: `docs_ai/TRANSLATION_WORKFLOW_ENHANCEMENT.md`

---

*Quick Reference v1.0 - December 30, 2025*

