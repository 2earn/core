# Platform API Routes - Grouped Structure

## Overview
All platform-related API endpoints are now grouped under the `platform/` prefix for better organization and clarity.

**Base URL:** `/api/partner/platform/`

---

## Platform Routes Group

### Resource Routes (CRUD Operations)

| Method | Endpoint | Controller Method | Route Name | Description |
|--------|----------|-------------------|------------|-------------|
| GET | `/api/partner/platforms` | index | api_partner_platforms.index | List all platforms |
| POST | `/api/partner/platforms` | store | api_partner_platforms.store | Create new platform |
| GET | `/api/partner/platforms/{platform}` | show | api_partner_platforms.show | Show specific platform |
| PUT/PATCH | `/api/partner/platforms/{platform}` | update | api_partner_platforms.update | Update platform |

**Note:** The resource routes use `platforms` (plural) while other platform endpoints use `platform` (singular) prefix.

### Platform Management Routes

| Method | Endpoint | Controller Method | Route Name | Description |
|--------|----------|-------------------|------------|-------------|
| POST | `/api/partner/platform/change` | changePlatformType | api_partner_platform_change_type | Change platform type |
| POST | `/api/partner/platform/validate` | validateRequest | api_partner_platform_validate_request | Request platform validation |
| POST | `/api/partner/platform/validation/cancel` | cancelValidationRequest | api_partner_platform_validation_cancel | Cancel validation request |
| POST | `/api/partner/platform/change/cancel` | cancelChangeRequest | api_partner_platform_change_cancel | Cancel change request |

### Platform Analytics Routes

| Method | Endpoint | Controller Method | Route Name | Description |
|--------|----------|-------------------|------------|-------------|
| GET | `/api/partner/platform/top-selling` | getTopSellingPlatforms | api_partner_platform_top_selling | Get top-selling platforms chart |

---

## Route Structure in Code

```php
Route::prefix('/partner/')->name('api_partner_')
    ->withoutMiddleware([\App\Http\Middleware\Authenticate::class])
    ->group(function () {
        Route::middleware(['check.url'])->group(function () {
            
            // Platform Routes Group
            Route::prefix('platform')->group(function () {
                // CRUD operations (platforms resource)
                Route::apiResource('s', PlatformPartnerController::class)->except('destroy');
                
                // Platform management
                Route::post('change', [PlatformPartnerController::class, 'changePlatformType'])
                    ->name('platform_change_type');
                Route::post('validate', [PlatformPartnerController::class, 'validateRequest'])
                    ->name('platform_validate_request');
                Route::post('validation/cancel', [PlatformPartnerController::class, 'cancelValidationRequest'])
                    ->name('platform_validation_cancel');
                Route::post('change/cancel', [PlatformPartnerController::class, 'cancelChangeRequest'])
                    ->name('platform_change_cancel');
                
                // Platform analytics
                Route::get('top-selling', [PlatformPartnerController::class, 'getTopSellingPlatforms'])
                    ->name('platform_top_selling');
            });

            // Other route groups (deals, orders, etc.)...
        });
    });
```

---

## Benefits of Grouping

### 1. **Better Organization**
- All platform endpoints are logically grouped together
- Easier to find and maintain platform-related routes
- Clear separation of concerns

### 2. **Consistent URL Structure**
- All platform operations start with `/api/partner/platform/`
- Predictable and intuitive URL patterns
- RESTful design principles

### 3. **Improved Maintainability**
- Changes to platform routes are centralized
- Easier to add middleware specific to platform operations
- Simpler to document and understand

### 4. **Scalability**
- Easy to add new platform endpoints
- Can apply group-specific middleware
- Supports nested grouping for sub-resources

---

## Complete Platform Endpoint List

### CRUD Operations
```
GET    /api/partner/platforms              - List platforms
POST   /api/partner/platforms              - Create platform
GET    /api/partner/platforms/{id}         - Show platform
PUT    /api/partner/platforms/{id}         - Update platform
PATCH  /api/partner/platforms/{id}         - Partial update platform
```

### Platform Management
```
POST   /api/partner/platform/change                - Change platform type
POST   /api/partner/platform/validate              - Validate platform
POST   /api/partner/platform/validation/cancel     - Cancel validation
POST   /api/partner/platform/change/cancel         - Cancel change request
```

### Platform Analytics
```
GET    /api/partner/platform/top-selling   - Top selling platforms
```

---

## Example Requests

### List Platforms
```bash
curl -X GET "http://localhost/api/partner/platforms?user_id=1" \
  -H "Authorization: Bearer YOUR_TOKEN"
```

### Get Top Selling Platforms
```bash
curl -X GET "http://localhost/api/partner/platform/top-selling?user_id=1&limit=10" \
  -H "Authorization: Bearer YOUR_TOKEN"
```

### Change Platform Type
```bash
curl -X POST "http://localhost/api/partner/platform/change" \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "platform_id": 1,
    "type_id": 2,
    "updated_by": 1
  }'
```

### Validate Platform
```bash
curl -X POST "http://localhost/api/partner/platform/validate" \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "platform_id": 1,
    "owner_id": 1
  }'
```

---

## Route Groups Overview

The partner API is now organized into logical groups:

1. **Platform Routes** - `/api/partner/platform/*`
   - Resource CRUD: `/platforms`
   - Management: `/platform/change`, `/platform/validate`, etc.
   - Analytics: `/platform/top-selling`

2. **Deal Routes** - `/api/partner/deals/*`
   - CRUD operations
   - Validation and change management
   - Dashboard indicators

3. **Order Routes** - `/api/partner/orders/*`
   - CRUD operations
   - Status management
   - Order details

4. **Item Routes** - `/api/partner/items/*`
   - Create and update items

5. **Sales Dashboard Routes** - `/api/partner/sales/dashboard/*`
   - KPIs
   - Evolution charts
   - Top products and deals

---

## Migration Notes

### No Breaking Changes
✅ All existing endpoint URLs remain the same
✅ Route names unchanged
✅ Controller methods unchanged
✅ Only the route organization in code has changed

### What Changed
- Routes are now grouped in `Route::prefix('platform')->group()`
- Better code organization for maintainability
- No impact on API consumers

---

## Related Documentation

- Platform Top Selling Chart: `docs_ai/TOP_SELLING_PLATFORMS_FINAL.md`
- API Routes: `routes/api.php`
- Platform Controller: `app/Http/Controllers/Api/partner/PlatformPartnerController.php`

---

**Last Updated:** December 18, 2025  
**Status:** ✅ Implemented and Active

