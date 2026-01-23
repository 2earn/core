# Items List API - ItemService Integration & Search Feature

## Summary of Changes

This document summarizes the refactoring of the Items List API endpoint to use `ItemService` and the addition of search functionality.

## Date
January 23, 2026

## Changes Made

### 1. ItemService Enhancement
**File:** `app/Services/Items/ItemService.php`

**Added Method:** `getItemsByPlatform()`
```php
public function getItemsByPlatform(int $platformId, ?string $search = null, int $perPage = 15)
```

**Features:**
- Filters items by platform ID
- Optional search functionality across multiple fields (name, ref, description)
- Eager loads relationships (deal, platform)
- Returns paginated results
- Ordered by creation date (newest first)

**Search Implementation:**
- Multi-field search using OR conditions
- Searches in: `name`, `ref`, `description`
- Case-insensitive partial matching using LIKE
- Only applies when search parameter is provided

### 2. Controller Refactoring
**File:** `app/Http/Controllers/Api/partner/ItemsPartnerController.php`

**Method:** `listItems()`

**Before:**
```php
// Direct Eloquent query in controller
$itemsQuery = Item::where('platform_id', $platformId)
    ->with(['deal:id,name,validated', 'platform:id,name'])
    ->orderBy('created_at', 'desc');
$items = $itemsQuery->paginate($perPage);
```

**After:**
```php
// Using ItemService
$items = $this->itemService->getItemsByPlatform($platformId, $search, $perPage);
```

**Benefits:**
- ✅ Better separation of concerns
- ✅ Reusable service method
- ✅ Easier to test and maintain
- ✅ Consistent with other endpoints

**New Validation:**
Added `search` parameter validation:
```php
'search' => 'nullable|string|max:255',
```

**New Response Field:**
Added `search` to response data for transparency:
```json
{
  "data": {
    "platform_id": 1,
    "search": "Red",
    "items": [...],
    ...
  }
}
```

### 3. Test Coverage
**File:** `tests/Feature/Api/Partner/ItemsPartnerControllerTest.php`

**New Tests Added (4):**

1. ✅ `test_can_search_items_by_name()`
   - Creates items with different names
   - Searches for partial name match
   - Verifies only matching items returned
   
2. ✅ `test_can_search_items_by_ref()`
   - Tests search by SKU/reference
   - Validates exact and partial ref matching

3. ✅ `test_can_search_items_by_description()`
   - Tests search in item descriptions
   - Confirms multi-field search works

4. ✅ `test_search_returns_empty_when_no_matches()`
   - Edge case testing
   - Verifies graceful handling of no results

**Test Results:**
```
✅ 16 tests passing
✅ 83 assertions
✅ Duration: 2.97s
```

### 4. Documentation Updates

**Files Updated:**
- `ITEMS_LIST_API_ENDPOINT.md` - Full API documentation
- `ITEMS_LIST_QUICK_REFERENCE.md` - Quick reference card

**Documentation Changes:**
- Added `search` parameter documentation
- Added search use case examples
- Added search feature to features list
- Updated test coverage section
- Added search examples in multiple formats (cURL, JavaScript)

## API Usage Examples

### Basic Search
```bash
GET /api/partner/items?platform_id=1&search=Red
```

### Search by SKU
```bash
GET /api/partner/items?platform_id=1&search=SKU-123
```

### Combined Search and Pagination
```bash
GET /api/partner/items?platform_id=1&search=premium&page=2&per_page=20
```

### JavaScript Example
```javascript
const response = await fetch('/api/partner/items?platform_id=1&search=Red');
const data = await response.json();
const items = data.data.items; // Filtered results
```

## Search Behavior

### Fields Searched
1. **name** - Item name (partial match)
2. **ref** - Item reference/SKU (partial match)
3. **description** - Item description (partial match)

### Search Logic
- **OR condition** - Matches if search term found in ANY field
- **Case-insensitive** - `Red` matches `red`, `RED`, `Red`
- **Partial matching** - `SKU` matches `SKU-123`, `ABC-SKU-001`
- **LIKE operator** - Uses SQL LIKE with wildcards (`%search%`)

### Examples

**Search: "Red"**
- Matches: "Red Shirt", "Dark Red Pants", "Blue shirt (red buttons)"
- Fields: name, description

**Search: "SKU-123"**
- Matches: "SKU-123", "SKU-1234"
- Fields: ref, name, description

## Performance Considerations

### Optimizations
1. **Eager Loading**: Prevents N+1 queries
   ```php
   ->with(['deal:id,name,validated', 'platform:id,name'])
   ```

2. **Indexed Queries**: Uses indexed `platform_id` column

3. **Pagination**: Limits result set size

4. **Service Layer**: Centralizes query logic for reusability

### Query Example
```sql
SELECT * FROM items 
WHERE platform_id = 1 
  AND (name LIKE '%Red%' OR ref LIKE '%Red%' OR description LIKE '%Red%')
ORDER BY created_at DESC 
LIMIT 15 OFFSET 0;
```

## Backward Compatibility

✅ **Fully backward compatible**
- `search` parameter is optional
- Existing API calls work unchanged
- No breaking changes to response structure
- Only adds `search` field to response data

## Files Modified

1. ✅ `app/Services/Items/ItemService.php` - Added `getItemsByPlatform()` method
2. ✅ `app/Http/Controllers/Api/partner/ItemsPartnerController.php` - Refactored to use service
3. ✅ `tests/Feature/Api/Partner/ItemsPartnerControllerTest.php` - Added 4 search tests
4. ✅ `ai generated docs/ITEMS_LIST_API_ENDPOINT.md` - Updated documentation
5. ✅ `ai generated docs/ITEMS_LIST_QUICK_REFERENCE.md` - Updated quick reference

## Benefits

### For Developers
- ✅ Cleaner controller code
- ✅ Reusable service method
- ✅ Better testability
- ✅ Easier to maintain

### For API Users
- ✅ Search functionality across multiple fields
- ✅ Faster item discovery
- ✅ Better user experience
- ✅ Flexible querying options

### For System
- ✅ Optimized queries with eager loading
- ✅ Indexed database access
- ✅ Pagination for large datasets
- ✅ Efficient search implementation

## Status: ✅ COMPLETE

All changes implemented, tested, and documented.
