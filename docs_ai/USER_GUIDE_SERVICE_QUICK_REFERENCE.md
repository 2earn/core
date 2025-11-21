# User Guide Service Quick Reference

## Service Location
`app/Services/UserGuide/UserGuideService.php`

## Livewire Component Usage

### Dependency Injection (Livewire v3)
```php
use App\Services\UserGuide\UserGuideService;

class MyComponent extends Component
{
    protected UserGuideService $userGuideService;

    public function boot(UserGuideService $userGuideService)
    {
        $this->userGuideService = $userGuideService;
    }
}
```

## Available Methods

### 1. Get User Guide by ID
```php
$guide = $this->userGuideService->getById($id);
```
**Returns**: `UserGuide|null`
**Eager loads**: `user` relationship

### 2. Get User Guide by ID or Fail
```php
$guide = $this->userGuideService->getByIdOrFail($id);
```
**Returns**: `UserGuide`
**Throws**: `ModelNotFoundException` if not found
**Eager loads**: `user` relationship

### 3. Get Paginated User Guides
```php
$guides = $this->userGuideService->getPaginated($search, $perPage);
```
**Parameters**:
- `$search` (string|null): Optional search term
- `$perPage` (int): Items per page (default: 10)

**Returns**: `LengthAwarePaginator`
**Searches in**: title, description
**Eager loads**: `user` relationship

### 4. Get All User Guides
```php
$guides = $this->userGuideService->getAll();
```
**Returns**: `Collection`
**Eager loads**: `user` relationship

### 5. Create User Guide
```php
$guide = $this->userGuideService->create([
    'title' => 'Guide Title',
    'description' => 'Guide Description',
    'file_path' => 'uploads/guides/file.pdf',
    'user_id' => Auth::id(),
    'routes' => ['route.name1', 'route.name2']
]);
```
**Returns**: `UserGuide`

### 6. Update User Guide
```php
$updated = $this->userGuideService->update($id, [
    'title' => 'Updated Title',
    'description' => 'Updated Description',
    'file_path' => 'uploads/guides/newfile.pdf',
    'routes' => ['route.name1', 'route.name2']
]);
```
**Returns**: `bool`

### 7. Delete User Guide
```php
$deleted = $this->userGuideService->delete($id);
```
**Returns**: `bool|null`
**Throws**: `ModelNotFoundException` if not found

### 8. Search User Guides
```php
$guides = $this->userGuideService->search('search term');
```
**Returns**: `Collection`
**Searches in**: title, description
**Eager loads**: `user` relationship

### 9. Get User Guides by Route Name
```php
$guides = $this->userGuideService->getByRouteName('dashboard');
```
**Returns**: `Collection`
**Uses**: `whereJsonContains` on routes field
**Eager loads**: `user` relationship

### 10. Get User Guides by User ID
```php
$guides = $this->userGuideService->getByUserId($userId);
```
**Returns**: `Collection`
**Eager loads**: `user` relationship

### 11. Check if User Guide Exists
```php
$exists = $this->userGuideService->exists($id);
```
**Returns**: `bool`

### 12. Get Total Count
```php
$count = $this->userGuideService->count();
```
**Returns**: `int`

### 13. Get Recent User Guides
```php
$guides = $this->userGuideService->getRecent(5);
```
**Parameters**:
- `$limit` (int): Number of guides to return (default: 5)

**Returns**: `Collection`
**Eager loads**: `user` relationship

## Example: Show Component
```php
<?php

namespace App\Livewire;

use App\Services\UserGuide\UserGuideService;
use Livewire\Component;

class UserGuideShow extends Component
{
    public $guide;
    protected UserGuideService $userGuideService;

    public function boot(UserGuideService $userGuideService)
    {
        $this->userGuideService = $userGuideService;
    }

    public function mount($id)
    {
        $this->guide = $this->userGuideService->getByIdOrFail($id);
    }

    public function render()
    {
        return view('livewire.user-guide-show', [
            'guide' => $this->guide
        ])->extends('layouts.master')->section('content');
    }
}
```

## Example: Index Component with Search
```php
<?php

namespace App\Livewire;

use App\Services\UserGuide\UserGuideService;
use Livewire\Component;
use Livewire\WithPagination;

class UserGuideIndex extends Component
{
    use WithPagination;

    public $search = '';
    protected UserGuideService $userGuideService;

    public function boot(UserGuideService $userGuideService)
    {
        $this->userGuideService = $userGuideService;
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $userGuides = $this->userGuideService->getPaginated($this->search, 10);
        
        return view('livewire.user-guide-index', compact('userGuides'))
            ->extends('layouts.master')
            ->section('content');
    }
}
```

## Example: Create/Update Component
```php
<?php

namespace App\Livewire;

use App\Services\UserGuide\UserGuideService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class UserGuideCreateUpdate extends Component
{
    public $userGuideId;
    public $title = '';
    public $description = '';
    public $routes = [];
    protected UserGuideService $userGuideService;

    public function boot(UserGuideService $userGuideService)
    {
        $this->userGuideService = $userGuideService;
    }

    public function mount($id = null)
    {
        if ($id) {
            $guide = $this->userGuideService->getByIdOrFail($id);
            $this->userGuideId = $id;
            $this->title = $guide->title;
            $this->description = $guide->description;
            $this->routes = $guide->routes ?? [];
        }
    }

    public function save()
    {
        $this->validate();

        if ($this->userGuideId) {
            // Update existing guide
            $this->userGuideService->update($this->userGuideId, [
                'title' => $this->title,
                'description' => $this->description,
                'routes' => $this->routes,
            ]);
        } else {
            // Create new guide
            $guide = $this->userGuideService->create([
                'title' => $this->title,
                'description' => $this->description,
                'user_id' => Auth::id(),
                'routes' => $this->routes,
            ]);
        }

        return redirect()->route('user_guides_index');
    }
}
```

## Example: Delete Operation
```php
public function delete($id)
{
    try {
        $this->userGuideService->delete($id);
        session()->flash('success', 'User guide deleted successfully.');
    } catch (\Exception $e) {
        session()->flash('error', 'Failed to delete user guide.');
    }
}
```

## Relationships Loaded

All methods automatically eager load:
- `user` - The creator/owner of the guide

## Search Behavior

The search functionality searches in:
- `title` field (LIKE %search%)
- `description` field (LIKE %search%)

Searches are case-insensitive in most databases.

## Ordering

By default, all methods return results ordered by:
- Latest created first (`latest()`)

## Benefits

✅ Clean Livewire component code
✅ Reusable query logic
✅ Testable in isolation
✅ Consistent eager loading
✅ Type-safe with return types
✅ Easy to mock for testing

## Testing

### Mock the service in Livewire tests:
```php
use App\Services\UserGuide\UserGuideService;
use Mockery;

$mockService = Mockery::mock(UserGuideService::class);
$mockService->shouldReceive('getByIdOrFail')
    ->once()
    ->with(1)
    ->andReturn($expectedGuide);

$this->app->instance(UserGuideService::class, $mockService);

// Test your Livewire component
Livewire::test(UserGuideShow::class, ['id' => 1])
    ->assertOk();
```

### Unit test service methods:
```php
use App\Services\UserGuide\UserGuideService;

public function test_get_paginated_with_search()
{
    UserGuide::factory()->create(['title' => 'Laravel Guide']);
    UserGuide::factory()->create(['title' => 'PHP Guide']);
    
    $service = new UserGuideService();
    $results = $service->getPaginated('Laravel', 10);
    
    $this->assertCount(1, $results);
    $this->assertEquals('Laravel Guide', $results->first()->title);
}
```

## Common Patterns

### Fetch with Error Handling
```php
try {
    $guide = $this->userGuideService->getByIdOrFail($id);
} catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
    session()->flash('error', 'User guide not found.');
    return redirect()->route('user_guides_index');
}
```

### Check Before Action
```php
if ($this->userGuideService->exists($id)) {
    // Perform action
}
```

### Get Recent for Dashboard
```php
$recentGuides = $this->userGuideService->getRecent(5);
```

### Filter by Current User
```php
$myGuides = $this->userGuideService->getByUserId(Auth::id());
```

## Notes

- All methods that return collections include the `user` relationship
- The service does not handle file uploads (that remains in components)
- The service does not handle translations (that remains in components)
- The service focuses purely on data access operations

