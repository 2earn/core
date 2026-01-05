# Performance Optimization Guide

## Boot Time Optimization (Reduced from 904ms)

### ✅ Applied Optimizations

#### 1. Configuration Caching
```bash
php artisan config:cache
```
- Caches all configuration files into a single file
- Reduces config loading time significantly
- Run after any config changes

#### 2. Route Caching
```bash
php artisan route:cache
```
- Caches all route definitions
- Speeds up route registration
- Run after any route changes

#### 3. Event Caching
```bash
php artisan event:cache
```
- Caches event listeners and subscribers
- Reduces event service provider boot time
- Run after any event/listener changes

#### 4. Composer Autoload Optimization
```bash
composer dump-autoload -o
```
- Creates optimized class maps
- Faster class loading
- Run after composer updates

#### 5. Deferred Service Providers
The following service providers now use deferred loading:
- `RepositoryServiceProvider` - Only loads when repository services are needed
- `DeferredServiceProvider` - Already deferred (Sponsorship, Targeting, Communication, etc.)

**Benefits:**
- Services are only instantiated when actually used
- Reduces initial boot time
- Maintains functionality

#### 6. Event Discovery Disabled
- Set `shouldDiscoverEvents()` to return `false` in EventServiceProvider
- Prevents automatic scanning of listeners
- Register observers explicitly in boot() method

### 📊 Expected Results

**Before:**
- Boot Time: ~904ms

**After:**
- Boot Time: ~300-500ms (50-60% improvement)

### 🚀 Additional Recommendations

#### 1. Laravel Octane (For Production)
Consider using Laravel Octane for persistent worker processes:
```bash
composer require laravel/octane
php artisan octane:install
```

**Benefits:**
- Boot once, serve thousands of requests
- Eliminates bootstrap overhead entirely
- 3-5x performance improvement

#### 2. OPcache Configuration (php.ini)
Ensure OPcache is enabled and optimized:
```ini
opcache.enable=1
opcache.memory_consumption=256
opcache.interned_strings_buffer=16
opcache.max_accelerated_files=20000
opcache.validate_timestamps=0  ; In production
opcache.revalidate_freq=0
opcache.save_comments=1
opcache.fast_shutdown=1
```

#### 3. Preloading (PHP 7.4+)
Create a preload file for frequently used classes:
```php
// preload.php
opcache_compile_file(__DIR__ . '/vendor/autoload.php');
// Add more critical files
```

Add to php.ini:
```ini
opcache.preload=/path/to/preload.php
```

#### 4. Database Query Optimization
- Use eager loading to prevent N+1 queries
- Index frequently queried columns
- Use query result caching where appropriate

#### 5. View Caching
```bash
php artisan view:cache
```

#### 6. Queue Jobs for Heavy Tasks
Move non-critical operations to queue workers:
- Email sending
- Report generation
- Data processing

### 🔄 Maintenance Commands

Run these commands after deployment:
```bash
php artisan optimize
# This runs: config:cache, route:cache, view:cache

# Or run individually:
php artisan config:cache
php artisan route:cache
php artisan event:cache
php artisan view:cache
composer dump-autoload -o
```

Clear cache during development:
```bash
php artisan optimize:clear
# This clears: config, route, view, event caches
```

### 📝 Monitoring

Monitor application performance:
```bash
# Check current cache status
php artisan cache:table
php artisan config:clear  # To see if config is cached

# Use Laravel Debugbar in development
# Monitor timing in production with APM tools
```

### ⚠️ Important Notes

1. **Clear caches during development** - Cached configs/routes won't reflect changes
2. **Run cache commands after deployment** - Automate in deployment scripts
3. **Test after changes** - Ensure all functionality works as expected
4. **Monitor memory usage** - Deferred providers reduce boot time but may increase memory slightly

### 🎯 File Changes Summary

1. **RepositoryServiceProvider.php**
   - Implements `DeferrableProvider` interface
   - Added `provides()` method listing all services
   - Services now load on-demand

2. **EventServiceProvider.php**
   - Removed `$observers` property
   - Moved observer registration to `boot()` method
   - Added `shouldDiscoverEvents()` returning false

3. **Optimization Commands Run**
   - Config cache
   - Route cache
   - Event cache
   - Composer autoload optimization

### 📈 Measuring Impact

Use Laravel's built-in profiling:
```php
// In AppServiceProvider boot() - development only
if (config('app.debug')) {
    \Log::info('Application booted', [
        'boot_time' => microtime(true) - LARAVEL_START,
        'memory' => memory_get_peak_usage(true) / 1024 / 1024 . ' MB'
    ]);
}
```

Or use Laravel Debugbar to see timing breakdowns.

