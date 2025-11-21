# TranslateTabsService Quick Reference

## Service Location
`app/Services/Translation/TranslateTabsService.php`

## Model
**Core Model**: `Core\Models\translatetabs`
**Table**: `translatetab`

## Usage in Livewire Components

### Dependency Injection (Livewire v3)
```php
use App\Services\Translation\TranslateTabsService;

class MyComponent extends Component
{
    protected TranslateTabsService $translateService;

    public function boot(TranslateTabsService $translateService)
    {
        $this->translateService = $translateService;
    }
}
```

## Available Methods

### 1. Get Translation by ID
```php
$translation = $this->translateService->getById($id);
```
**Returns**: `translatetabs|null`

### 2. Get All Translations
```php
$translations = $this->translateService->getAll();
```
**Returns**: `Collection` (ordered by ID descending)

### 3. Get Paginated Translations with Search
```php
$translations = $this->translateService->getPaginated($search, $perPage);
```
**Parameters**:
- `$search` (string|null): Optional search term
- `$perPage` (int): Items per page (default: 10)

**Returns**: `LengthAwarePaginator`

**Searches in**: All language fields (case-insensitive + binary case-sensitive for name)

### 4. Check if Translation Exists (Case-Sensitive)
```php
$exists = $this->translateService->exists('User.Login'); // Case-sensitive!
```
**Returns**: `bool`
**Note**: Uses BINARY comparison for exact match

### 5. Create Translation
```php
// Simple create with defaults
$translation = $this->translateService->create('user.login');

// Create with custom values
$translation = $this->translateService->create('user.login', [
    'value' => 'تسجيل الدخول',
    'valueFr' => 'Connexion',
    'valueEn' => 'Login',
    'valueTr' => 'Giriş',
    'valueEs' => 'Iniciar sesión',
    'valueRu' => 'Вход',
    'valueDe' => 'Anmeldung'
]);
```
**Returns**: `translatetabs|null`

**Default values** (if not provided):
- `value`: `{name} AR`
- `valueFr`: `{name} FR`
- `valueEn`: `{name} EN`
- `valueTr`: `{name} TR`
- `valueEs`: `{name} ES`
- `valueRu`: `{name} RU`
- `valueDe`: `{name} DE`

### 6. Update Translation
```php
$updated = $this->translateService->update($id, [
    'value' => 'تحديث',
    'valueFr' => 'Mise à jour',
    'valueEn' => 'Update',
    // ... other languages
]);
```
**Returns**: `bool`

### 7. Delete Translation
```php
$deleted = $this->translateService->delete($id);
```
**Returns**: `bool`

### 8. Search Translations
```php
$results = $this->translateService->search('login');
```
**Returns**: `Collection`
**Note**: Case-insensitive search across all fields

### 9. Get All as Key-Value Arrays
```php
$allTranslations = $this->translateService->getAllAsKeyValueArrays();
```
**Returns**: `array`

**Structure**:
```php
[
    'ar' => ['key1' => 'value1', 'key2' => 'value2'],
    'fr' => ['key1' => 'value1', 'key2' => 'value2'],
    'en' => ['key1' => 'value1', 'key2' => 'value2'],
    'tr' => ['key1' => 'value1', 'key2' => 'value2'],
    'es' => ['key1' => 'value1', 'key2' => 'value2'],
    'ru' => ['key1' => 'value1', 'key2' => 'value2'],
    'de' => ['key1' => 'value1', 'key2' => 'value2']
]
```

### 10. Get Total Count
```php
$count = $this->translateService->count();
```
**Returns**: `int`

### 11. Get by Name Pattern
```php
$translations = $this->translateService->getByNamePattern('user.%');
```
**Returns**: `Collection`

### 12. Bulk Create Translations
```php
$translations = [
    ['name' => 'key1', 'value' => 'val1', 'valueFr' => 'val1'],
    ['name' => 'key2', 'value' => 'val2', 'valueFr' => 'val2']
];
$success = $this->translateService->bulkCreate($translations);
```
**Returns**: `bool`
**Note**: Uses transaction, skips existing keys

### 13. Get Statistics
```php
$stats = $this->translateService->getStatistics();
```
**Returns**: `array`

**Structure**:
```php
[
    'total_count' => 1234,
    'today_count' => 5,
    'this_week_count' => 23,
    'this_month_count' => 89
]
```

## Supported Languages

| Code | Language | Field |
|------|----------|-------|
| ar | Arabic | `value` |
| fr | French | `valueFr` |
| en | English | `valueEn` |
| tr | Turkish | `valueTr` |
| es | Spanish | `valueEs` |
| ru | Russian | `valueRu` |
| de | German | `valueDe` |

## Example: Add Translation

```php
public function addTranslation($key)
{
    if (!$this->translateService->exists($key)) {
        $this->translateService->create($key);
        session()->flash('success', 'Translation added successfully');
    } else {
        session()->flash('error', 'Translation key already exists');
    }
}
```

## Example: Update Translation

```php
public function updateTranslation($id)
{
    $updated = $this->translateService->update($id, [
        'value' => $this->arabicValue,
        'valueFr' => $this->frenchValue,
        'valueEn' => $this->englishValue,
        'valueTr' => $this->turkishValue,
        'valueEs' => $this->spanishValue,
        'valueRu' => $this->russianValue,
        'valueDe' => $this->germanValue,
    ]);

    if ($updated) {
        session()->flash('success', 'Translation updated');
    }
}
```

## Example: Search with Pagination

```php
public function render()
{
    $translations = $this->translateService->getPaginated($this->search, 10);
    
    return view('livewire.translations', compact('translations'));
}
```

## Example: Bulk Import

```php
public function importTranslations(array $data)
{
    $translations = [];
    
    foreach ($data as $item) {
        $translations[] = [
            'name' => $item['key'],
            'value' => $item['ar'],
            'valueFr' => $item['fr'],
            'valueEn' => $item['en'],
            // ... etc
        ];
    }
    
    $success = $this->translateService->bulkCreate($translations);
    
    return $success ? 'Imported successfully' : 'Import failed';
}
```

## Example: Export Translations

```php
public function exportTranslations()
{
    $allTranslations = $this->translateService->getAllAsKeyValueArrays();
    
    // Export to JSON files
    file_put_contents('lang/ar.json', json_encode($allTranslations['ar'], JSON_PRETTY_PRINT));
    file_put_contents('lang/fr.json', json_encode($allTranslations['fr'], JSON_PRETTY_PRINT));
    // ... etc
}
```

## Example: Statistics Dashboard

```php
public function getDashboardStats()
{
    $stats = $this->translateService->getStatistics();
    
    return view('dashboard', [
        'total' => $stats['total_count'],
        'today' => $stats['today_count'],
        'week' => $stats['this_week_count'],
        'month' => $stats['this_month_count']
    ]);
}
```

## Search Behavior

### Case-Sensitive Binary Check (exists)
```php
// These are DIFFERENT keys:
$this->translateService->exists('User.Login');  // false
$this->translateService->exists('user.login');  // true
```

### Case-Insensitive Search (getPaginated, search)
```php
// These will find the SAME results:
$this->translateService->search('Login');
$this->translateService->search('LOGIN');
$this->translateService->search('login');
```

## Common Patterns

### Check Before Create
```php
if (!$this->translateService->exists($key)) {
    $this->translateService->create($key);
}
```

### Get and Update
```php
$translation = $this->translateService->getById($id);
if ($translation) {
    $this->translateService->update($id, [
        'valueFr' => 'New French Value'
    ]);
}
```

### Pattern-Based Filtering
```php
// Get all user-related translations
$userTranslations = $this->translateService->getByNamePattern('user.%');

// Get all error messages
$errorTranslations = $this->translateService->getByNamePattern('error.%');

// Get all validation messages
$validationTranslations = $this->translateService->getByNamePattern('validation.%');
```

## Error Handling

All service methods include error handling:
- Errors are logged automatically
- Safe fallbacks returned (null, false, empty collections)
- No exceptions thrown to component

Example:
```php
$translation = $this->translateService->getById($id);
if (!$translation) {
    // Handle not found case
    session()->flash('error', 'Translation not found');
}
```

## Performance Tips

1. **Use Pagination**: For large datasets
   ```php
   $this->translateService->getPaginated($search, 25);
   ```

2. **Cache Key-Value Arrays**: For repeated access
   ```php
   $arrays = Cache::remember('translations_arrays', 3600, function() {
       return $this->translateService->getAllAsKeyValueArrays();
   });
   ```

3. **Bulk Operations**: For multiple inserts
   ```php
   $this->translateService->bulkCreate($translations);
   ```

4. **Pattern Matching**: More efficient than searching
   ```php
   $this->translateService->getByNamePattern('user.%');
   ```

## Benefits

✅ Clean Livewire component code
✅ Reusable across application
✅ Testable in isolation
✅ Case-sensitive key checks preserved
✅ Multi-language support
✅ Type-safe with return types
✅ Comprehensive error handling
✅ Transaction support for bulk operations

## Testing

### Mock the service:
```php
$mockService = Mockery::mock(TranslateTabsService::class);
$mockService->shouldReceive('exists')
    ->with('user.login')
    ->andReturn(false);

$this->app->instance(TranslateTabsService::class, $mockService);
```

### Unit test:
```php
public function test_exists_is_case_sensitive()
{
    translatetabs::factory()->create(['name' => 'User.Login']);
    
    $service = new TranslateTabsService();
    
    $this->assertTrue($service->exists('User.Login'));
    $this->assertFalse($service->exists('user.login'));
}
```

## Notes

- **Case Sensitivity**: `exists()` is case-sensitive, `search()` is case-insensitive
- **Binary Check**: Uses `BINARY` SQL operator for exact matching
- **Default Values**: Automatically appends language code to name
- **Audit Trail**: Uses `HasAuditing` trait (created_by, updated_by)
- **Error Logging**: All errors logged to application log

