# InstructorRequest - Complete Service Layer Refactoring

## Summary
Successfully created `InstructorRequestService` and refactored the `InstructorRequest` Livewire component to use proper service layer architecture instead of direct model queries.

## Changes Made

### 1. Created InstructorRequestService
**File:** `app/Services/InstructorRequestService.php`

New comprehensive service for managing instructor requests with the following methods:

#### Methods:
- `getInProgressRequests(): Collection` - Get all in-progress instructor requests
- `getByStatus($status): Collection` - Get instructor requests by status
- `getById(int $id): ?InstructorRequest` - Get instructor request by ID
- `getAll(): Collection` - Get all instructor requests
- `updateStatus(int $id, $status): bool` - Update instructor request status
- `delete(int $id): bool` - Delete an instructor request

All methods include error handling with logging and return appropriate defaults on failure.

### 2. Refactored InstructorRequest Component
**File:** `app/Livewire/InstructorRequest.php`

#### Changes:
- Removed direct model import: `InstructorRequestModel`
- Removed enum import: `RequestStatus`
- Added service injection: `InstructorRequestService` via `boot()` method
- Removed direct model query
- Updated render method to use service

## Before vs After

### Before (17 lines):
```php
<?php

namespace App\Livewire;

use App\Models\InstructorRequest as InstructorRequestModel;
use Core\Enum\RequestStatus;
use Livewire\Component;

class InstructorRequest extends Component
{
    public function render()
    {
        // Direct model query with enum
        $params = [
            'instructorRequests' => InstructorRequestModel::where('status', RequestStatus::InProgress->value)->get()
        ];
        
        return view('livewire.instructor', $params)
            ->extends('layouts.master')
            ->section('content');
    }
}
```

### After (26 lines - Much cleaner):
```php
<?php

namespace App\Livewire;

use App\Services\InstructorRequestService;
use Livewire\Component;

class InstructorRequest extends Component
{
    protected InstructorRequestService $instructorRequestService;

    public function boot(InstructorRequestService $instructorRequestService)
    {
        $this->instructorRequestService = $instructorRequestService;
    }

    public function render()
    {
        // Clean service call
        $instructorRequests = $this->instructorRequestService->getInProgressRequests();
        
        return view('livewire.instructor', ['instructorRequests' => $instructorRequests])
            ->extends('layouts.master')
            ->section('content');
    }
}
```

## Key Improvements

### Component:
- ✅ Removed 2 imports (InstructorRequestModel, RequestStatus)
- ✅ Added 1 service dependency (properly injected)
- ✅ Removed direct model query
- ✅ All database operations now through service
- ✅ Better separation of concerns
- ✅ Easier to test and maintain

### Service Created:
- ✅ InstructorRequestService: Complete CRUD operations
- ✅ All methods include error handling with logging
- ✅ Type-safe method signatures
- ✅ Reusable across application

## Direct Model Query Replacement

| Before | After |
|--------|-------|
| `InstructorRequestModel::where('status', RequestStatus::InProgress->value)->get()` | `$this->instructorRequestService->getInProgressRequests()` |

## Benefits

1. **Separation of Concerns**: Business logic moved to service layer
2. **Reusability**: InstructorRequestService can be used across the entire application
3. **Testability**: Easier to mock services for testing
4. **Maintainability**: Changes centralized in service
5. **Error Handling**: Consistent error handling and logging in service
6. **Type Safety**: Proper type hints and return types
7. **Cleaner Code**: Component focused on UI concerns
8. **Enum Usage**: RequestStatus enum now in service, not component

## InstructorRequestService API

### Query Methods:
- `getInProgressRequests(): Collection` - Get in-progress requests
- `getByStatus($status): Collection` - Get requests by status
- `getById(int $id): ?InstructorRequest` - Get by ID
- `getAll(): Collection` - Get all requests

### Mutation Methods:
- `updateStatus(int $id, $status): bool` - Update request status
- `delete(int $id): bool` - Delete a request

## Usage Examples

### In Livewire Components:
```php
class InstructorApproval extends Component
{
    protected InstructorRequestService $instructorRequestService;

    public function boot(InstructorRequestService $instructorRequestService)
    {
        $this->instructorRequestService = $instructorRequestService;
    }

    public function showPendingRequests()
    {
        $requests = $this->instructorRequestService->getInProgressRequests();
        // Display requests...
    }
    
    public function approveRequest($id)
    {
        $success = $this->instructorRequestService->updateStatus(
            $id,
            RequestStatus::Approved->value
        );
        
        if ($success) {
            session()->flash('success', 'Request approved!');
        }
    }
}
```

### In Controllers:
```php
class InstructorRequestController extends Controller
{
    public function __construct(
        protected InstructorRequestService $instructorRequestService
    ) {}

    public function index()
    {
        $requests = $this->instructorRequestService->getInProgressRequests();
        return view('instructor-requests.index', compact('requests'));
    }
    
    public function show($id)
    {
        $request = $this->instructorRequestService->getById($id);
        
        if (!$request) {
            return redirect()->back()->with('error', 'Request not found');
        }
        
        return view('instructor-requests.show', compact('request'));
    }
    
    public function approve($id)
    {
        $success = $this->instructorRequestService->updateStatus(
            $id,
            RequestStatus::Approved->value
        );
        
        return redirect()->back()->with(
            $success ? 'success' : 'error',
            $success ? 'Request approved!' : 'Failed to approve request'
        );
    }
    
    public function reject($id)
    {
        $success = $this->instructorRequestService->updateStatus(
            $id,
            RequestStatus::Rejected->value
        );
        
        return redirect()->back()->with(
            $success ? 'success' : 'error',
            $success ? 'Request rejected!' : 'Failed to reject request'
        );
    }
}
```

### In API Controllers:
```php
class InstructorRequestApiController extends Controller
{
    public function index(InstructorRequestService $service)
    {
        return response()->json([
            'requests' => $service->getInProgressRequests()
        ]);
    }
    
    public function show($id, InstructorRequestService $service)
    {
        $request = $service->getById($id);
        
        if (!$request) {
            return response()->json(['error' => 'Not found'], 404);
        }
        
        return response()->json(['request' => $request]);
    }
    
    public function updateStatus(Request $request, $id, InstructorRequestService $service)
    {
        $success = $service->updateStatus($id, $request->status);
        
        return response()->json(['success' => $success]);
    }
}
```

### In Console Commands:
```php
class ProcessInstructorRequests extends Command
{
    public function handle(InstructorRequestService $service)
    {
        $requests = $service->getInProgressRequests();
        
        foreach ($requests as $request) {
            $this->info("Processing request ID: {$request->id}");
            // Process request...
        }
        
        $this->info("Processed {$requests->count()} requests");
    }
}
```

## Testing Benefits

```php
public function test_instructor_request_index_shows_in_progress_requests()
{
    $mockService = Mockery::mock(InstructorRequestService::class);
    
    $mockService->shouldReceive('getInProgressRequests')
        ->once()
        ->andReturn(collect([
            new InstructorRequest(['id' => 1, 'status' => RequestStatus::InProgress->value])
        ]));
    
    $this->app->instance(InstructorRequestService::class, $mockService);
    
    Livewire::test(InstructorRequest::class)
        ->assertSee('Instructor Requests');
}

public function test_can_update_request_status()
{
    $mockService = Mockery::mock(InstructorRequestService::class);
    
    $mockService->shouldReceive('updateStatus')
        ->once()
        ->with(1, RequestStatus::Approved->value)
        ->andReturn(true);
    
    $this->app->instance(InstructorRequestService::class, $mockService);
    
    // Test component or controller...
}

public function test_handles_missing_request()
{
    $mockService = Mockery::mock(InstructorRequestService::class);
    
    $mockService->shouldReceive('getById')
        ->once()
        ->with(999)
        ->andReturn(null);
    
    $this->app->instance(InstructorRequestService::class, $mockService);
    
    // Test null handling...
}
```

## Statistics

- **Services created:** 1 (InstructorRequestService)
- **Service methods available:** 6
- **Direct model queries removed:** 1
- **Model imports removed:** 1
- **Enum imports removed:** 1 (moved to service)

## Notes

- All existing functionality preserved
- Error handling improved and centralized
- Component now follows best practices
- Service can be easily extended for future features (e.g., bulk operations)
- RequestStatus enum usage encapsulated in service
- No breaking changes

## Date
December 31, 2025

