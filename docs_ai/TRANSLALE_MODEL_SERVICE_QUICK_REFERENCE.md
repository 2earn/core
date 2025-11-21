# TranslaleModel Service Quick Reference

## Service Location
`app/Services/Translation/TranslaleModelService.php`

## Livewire Component Usage

### Dependency Injection (Livewire v3)
```php
use App\Services\Translation\TranslaleModelService;

class MyComponent extends Component
{
    protected TranslaleModelService $translationService;

    public function boot(TranslaleModelService $translationService)
    {
        $this->translationService = $translationService;
    }
}
```

## Available Methods

### 1. Get Translation by ID
```php
$translation = $this->translationService->getById($id);
```
**Returns**: `TranslaleModel|null`

### 2. Get All Translations
```php
$translations = $this->translationService->getAll();
```
**Returns**: `Collection`

### 3. Get Paginated Translations with Search
```php
$translations = $this->translationService->getPaginated($search, $perPage);
```
**Parameters**:
- `$search` (string|null): Optional search term (case-insensitive)
- `$perPage` (int): Items per page (default: 10)

**Returns**: `LengthAwarePaginator`

**Searches in**: name, value, valueFr, valueEn, valueTr, valueEs, valueRu, valueDe

### 4. Check if Translation Exists
```php
$exists = $this->translationService->exists('user.login');
```
**Returns**: `bool`
**Note**: Case-insensitive check

### 5. Create Translation
```php
// Simple create with defaults
$translation = $this->translationService->create('user.login');

// Create with custom values
$translation = $this->translationService->create('user.login', [
    'value' => 'تسجيل الدخول',
    'valueFr' => 'Connexion',
    'valueEn' => 'Login',
    'valueTr' => 'Giriş',
    'valueEs' => 'Iniciar sesión',
    'valueRu' => 'Вход',
    'valueDe' => 'Anmeldung'
]);
```
**Returns**: `TranslaleModel`

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
$updated = $this->translationService->update($id, [
    'value' => 'تحديث',
    'valueFr' => 'Mise à jour',
    'valueEn' => 'Update',
    'valueTr' => 'Güncelleme',
    'valueEs' => 'Actualizar',
    'valueRu' => 'Обновить',
    'valueDe' => 'Aktualisieren'
]);
```
**Returns**: `bool`

### 7. Delete Translation
```php
$deleted = $this->translationService->delete($id);
```
**Returns**: `bool|null`

### 8. Search Translations
```php
$results = $this->translationService->search('login');
```
**Returns**: `Collection`
**Note**: Case-insensitive search across all language fields

### 9. Get All as Key-Value Arrays
```php
$allTranslations = $this->translationService->getAllAsKeyValueArrays();
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
$count = $this->translationService->count();
```
**Returns**: `int`

### 11. Get by Name Pattern
```php
$translations = $this->translationService->getByNamePattern('user.%');
```
**Returns**: `Collection`

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

## Example: Add Translation Component

```php
<?php

namespace App\Livewire;

use App\Services\Translation\TranslaleModelService;
use Livewire\Component;

class AddTranslation extends Component
{
    public $name;
    protected TranslaleModelService $translationService;

    public function boot(TranslaleModelService $translationService)
    {
        $this->translationService = $translationService;
    }

    public function save()
    {
        if (!$this->translationService->exists($this->name)) {
            $this->translationService->create($this->name);
            session()->flash('success', 'Translation added successfully');
        } else {
            session()->flash('error', 'Translation already exists');
        }
    }

    public function render()
    {
        return view('livewire.add-translation');
    }
}
```

## Example: List Translations with Search

```php
<?php

namespace App\Livewire;

use App\Services\Translation\TranslaleModelService;
use Livewire\Component;
use Livewire\WithPagination;

class ListTranslations extends Component
{
    use WithPagination;

    public $search = '';
    protected TranslaleModelService $translationService;

    public function boot(TranslaleModelService $translationService)
    {
        $this->translationService = $translationService;
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $translations = $this->translationService->getPaginated($this->search, 10);
        
        return view('livewire.list-translations', compact('translations'));
    }
}
```

## Example: Edit Translation

```php
<?php

namespace App\Livewire;

use App\Services\Translation\TranslaleModelService;
use Livewire\Component;

class EditTranslation extends Component
{
    public $translationId;
    public $arabicValue = '';
    public $frenchValue = '';
    public $englishValue = '';
    
    protected TranslaleModelService $translationService;

    public function boot(TranslaleModelService $translationService)
    {
        $this->translationService = $translationService;
    }

    public function mount($id)
    {
        $translation = $this->translationService->getById($id);
        
        if ($translation) {
            $this->translationId = $translation->id;
            $this->arabicValue = $translation->value;
            $this->frenchValue = $translation->valueFr;
            $this->englishValue = $translation->valueEn;
        }
    }

    public function save()
    {
        $this->translationService->update($this->translationId, [
            'value' => $this->arabicValue,
            'valueFr' => $this->frenchValue,
            'valueEn' => $this->englishValue,
        ]);

        session()->flash('success', 'Translation updated successfully');
    }

    public function render()
    {
        return view('livewire.edit-translation');
    }
}
```

## Example: Export Translations

```php
public function exportTranslations()
{
    // Get all translations as arrays
    $allTranslations = $this->translationService->getAllAsKeyValueArrays();
    
    // Use in your export logic
    file_put_contents('translations/ar.json', json_encode($allTranslations['ar']));
    file_put_contents('translations/fr.json', json_encode($allTranslations['fr']));
    // ... etc
}
```

## Search Behavior

### Case-Insensitive
All search operations are case-insensitive:
```php
// These will find the same results:
$service->search('Login');
$service->search('LOGIN');
$service->search('login');
```

### Multi-Field Search
Search looks in all fields:
- Translation name
- All 7 language value fields

### Example Search Results
Search term: "login"
- Matches "user.login" (name)
- Matches "Login" in English value
- Matches "تسجيل الدخول" if it contains the term

## Common Patterns

### Check Before Create
```php
if (!$this->translationService->exists($name)) {
    $this->translationService->create($name);
}
```

### Get and Update
```php
$translation = $this->translationService->getById($id);
if ($translation) {
    $this->translationService->update($id, [
        'valueFr' => 'New French Value'
    ]);
}
```

### Search with Pagination
```php
$results = $this->translationService->getPaginated($searchTerm, 25);
foreach ($results as $translation) {
    echo $translation->name . ': ' . $translation->valueFr;
}
```

### Get Specific Patterns
```php
// Get all user-related translations
$userTranslations = $this->translationService->getByNamePattern('user.%');

// Get all error messages
$errorTranslations = $this->translationService->getByNamePattern('error.%');
```

## Benefits

✅ Clean Livewire component code
✅ Reusable across application
✅ Testable in isolation
✅ Case-insensitive operations
✅ Multi-language support
✅ Type-safe with return types
✅ Easy to mock for testing
✅ Efficient database queries

## Testing

### Mock the service in Livewire tests:
```php
use App\Services\Translation\TranslaleModelService;
use Mockery;

$mockService = Mockery::mock(TranslaleModelService::class);
$mockService->shouldReceive('exists')
    ->once()
    ->with('user.login')
    ->andReturn(false);

$mockService->shouldReceive('create')
    ->once()
    ->with('user.login')
    ->andReturn($translation);

$this->app->instance(TranslaleModelService::class, $mockService);

Livewire::test(TranslateModelData::class)
    ->call('AddFieldTranslate', 'user.login')
    ->assertRedirect();
```

### Unit test service methods:
```php
use App\Services\Translation\TranslaleModelService;

public function test_exists_is_case_insensitive()
{
    TranslaleModel::factory()->create(['name' => 'User.Login']);
    
    $service = new TranslaleModelService();
    
    $this->assertTrue($service->exists('user.login'));
    $this->assertTrue($service->exists('USER.LOGIN'));
    $this->assertTrue($service->exists('User.Login'));
}
```

## Performance Tips

1. **Use Pagination**: For large datasets, always use `getPaginated()`
2. **Cache Results**: Consider caching `getAllAsKeyValueArrays()` result
3. **Bulk Operations**: For multiple updates, consider wrapping in transaction
4. **Index Fields**: Ensure database indexes on `name` field

## Notes

- All searches are case-insensitive using `DB::raw('upper(field))')`
- The `exists()` method checks name field only (case-insensitive)
- Default values automatically append language code to name
- Service does not handle validation (handled in component)
- Service does not handle file operations or exports (can be added)

