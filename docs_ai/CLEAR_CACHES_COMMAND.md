# Clear Caches Console Command

## Overview

The `clear-caches` command is a Laravel console command that clears all application caches and regenerates the autoloader. This is the console command version of the `clear-caches.bat` batch file.

---

## 📦 Installation

The command is automatically registered by Laravel. No additional setup required!

File location: `app/Console/Commands/ClearCachesCommand.php`

---

## 🚀 Usage

### Basic Usage (Clear All Caches)

```bash
php artisan clear-caches
```

This will:
1. Clear configuration cache
2. Clear application cache
3. Clear route cache
4. Clear compiled views
5. Regenerate composer autoloader

---

### Advanced Usage (Selective Clearing)

#### Clear only configuration cache:
```bash
php artisan clear-caches --config
```

#### Clear only application cache:
```bash
php artisan clear-caches --cache
```

#### Clear only route cache:
```bash
php artisan clear-caches --route
```

#### Clear only compiled views:
```bash
php artisan clear-caches --view
```

#### Regenerate only autoloader:
```bash
php artisan clear-caches --autoload
```

#### Combine multiple options:
```bash
php artisan clear-caches --config --cache --route
```

---

## 📖 Command Options

| Option | Description |
|--------|-------------|
| `--config` | Clear only configuration cache |
| `--cache` | Clear only application cache |
| `--route` | Clear only route cache |
| `--view` | Clear only compiled views |
| `--autoload` | Regenerate only composer autoloader |
| `--all` | Clear all caches (default when no options specified) |

---

## 🎯 When to Use

### Use this command when:

✅ You get "Target class [Balances] does not exist" error  
✅ You've modified `config/app.php`  
✅ You've added new service providers  
✅ You've made changes to configuration files  
✅ You've added new classes or namespaces  
✅ Application is behaving strangely after code changes  

---

## 📊 Example Output

```
========================================
 Service Provider Optimization
 Cache Clear Command
========================================

[1/5] Clearing configuration cache...
✓ Configuration cache cleared

[2/5] Clearing application cache...
✓ Application cache cleared

[3/5] Clearing route cache...
✓ Route cache cleared

[4/5] Clearing compiled views...
✓ Compiled views cleared

[5/5] Regenerating autoloader...
✓ Autoloader regenerated

========================================
 ✓ ALL CACHES CLEARED SUCCESSFULLY!
========================================

 ✓ Configuration cache cleared
 ✓ Application cache cleared
 ✓ Route cache cleared
 ✓ Compiled views cleared
 ✓ Autoloader

The service provider optimization is now active.
You can now test the /buy-action route.
```

---

## 🔧 Troubleshooting

### Command not found?

1. Make sure the file exists:
   ```bash
   ls app/Console/Commands/ClearCachesCommand.php
   ```

2. Clear the command cache:
   ```bash
   php artisan clear-compiled
   composer dump-autoload
   ```

3. Verify it's listed:
   ```bash
   php artisan list | grep clear-caches
   ```

### Composer dump-autoload fails?

The command will continue even if autoloader regeneration fails. You can manually run:
```bash
composer dump-autoload
```

---

## 💡 Comparison with Batch File

### Batch File (`clear-caches.bat`)
✅ No Laravel needed  
✅ Can run from Windows Explorer  
❌ Windows only  
❌ Less flexible  
❌ Can't be called from code  

### Console Command (`php artisan clear-caches`)
✅ Cross-platform (Windows, Linux, Mac)  
✅ More flexible with options  
✅ Can be called from other commands  
✅ Better error handling  
✅ Integrated with Laravel  
❌ Requires terminal/command line  

---

## 🎓 Advanced Examples

### Use in deployment scripts:
```bash
# After deployment
php artisan clear-caches
php artisan config:cache
php artisan route:cache
```

### Use in other commands:
```php
// Inside another command
$this->call('clear-caches');

// Or with options
$this->call('clear-caches', ['--config' => true]);
```

### Use in code (not recommended, but possible):
```php
Artisan::call('clear-caches');
```

---

## 🔍 What Each Cache Does

### Configuration Cache (`--config`)
- Clears: `bootstrap/cache/config.php`
- When: After modifying any `config/*.php` files
- Impact: Forces Laravel to re-read all config files

### Application Cache (`--cache`)
- Clears: Cache driver data (file, redis, memcached, etc.)
- When: After modifying cached data structures
- Impact: All cached data is removed

### Route Cache (`--route`)
- Clears: `bootstrap/cache/routes-*.php`
- When: After modifying route files
- Impact: Forces Laravel to re-parse route definitions

### View Cache (`--view`)
- Clears: `storage/framework/views/*.php`
- When: After Blade template issues
- Impact: Recompiles all Blade templates on next request

### Autoloader (`--autoload`)
- Regenerates: `vendor/composer/autoload_*.php`
- When: After adding new classes or namespaces
- Impact: Updates class map for faster loading

---

## 📚 Related Commands

```bash
# Individual Laravel commands
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# Composer command
composer dump-autoload

# Clear everything + rebuild for production
php artisan optimize:clear
php artisan optimize
```

---

## ⚡ Quick Reference

| Task | Command |
|------|---------|
| Fix service provider error | `php artisan clear-caches` |
| After config changes | `php artisan clear-caches --config` |
| After route changes | `php artisan clear-caches --route` |
| After adding new classes | `php artisan clear-caches --autoload` |
| Complete cache clear | `php artisan clear-caches` |
| Clear specific caches | `php artisan clear-caches --config --cache` |

---

## 🎯 For Service Provider Optimization

This command was specifically created to help with the service provider optimization. After implementing the `DeferredServiceProvider`:

1. Run this command:
   ```bash
   php artisan clear-caches
   ```

2. Test services work:
   ```bash
   php artisan tinker
   >>> app('Balances')
   ```

3. Test the application:
   - Access `/buy-action` route
   - Verify no errors

---

## ✅ Success Indicators

After running the command, you should:

- ✅ See all checkmarks (✓) in the output
- ✅ See "ALL CACHES CLEARED SUCCESSFULLY!"
- ✅ Be able to access `/buy-action` without errors
- ✅ Be able to resolve services in tinker

---

## 🆘 Getting Help

View command help:
```bash
php artisan clear-caches --help
```

List all options:
```bash
php artisan help clear-caches
```

---

## 📝 Notes

- This command is safe to run multiple times
- It won't delete any important data
- It only clears caches and regenerates the autoloader
- In production, you may want to recache after clearing:
  ```bash
  php artisan clear-caches
  php artisan config:cache
  php artisan route:cache
  ```

---

**Tip**: Add this command to your deployment scripts to ensure caches are always fresh after updates!

