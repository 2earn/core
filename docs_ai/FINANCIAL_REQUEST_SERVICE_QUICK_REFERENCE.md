# FinancialRequestService - Quick Reference

## Overview
Service layer for financial request operations. Handles all database queries, business logic, and transactions related to financial requests.

**Location**: `app/Services/FinancialRequest/FinancialRequestService.php`

---

## Available Methods

### Request Retrieval

<div style="margin-left: 20px;">

#### `getByNumeroReq(string $numeroReq): ?FinancialRequest`
Get a financial request by its number.
```php
$request = $service->getByNumeroReq('REQ123456');
if ($request) {
    echo $request->amount;
}
```

#### `getRequestWithUserDetails(string $numeroReq)`
Get request with sender information (joined with users table).
```php
$details = $service->getRequestWithUserDetails('REQ123456');
echo $details->name; // Sender name
echo $details->mobile; // Sender mobile
```

#### `getDetailRequest(string $numeroReq, int $userId): ?detail_financial_request`
Get detail request for specific user and request.
```php
$detail = $service->getDetailRequest('REQ123456', 789);
if ($detail) {
    echo $detail->response; // null, 1, 2, or 3
}
```

</div>

---

### Request Lists

<div style="margin-left: 20px;">

#### `getRequestsToUser(int $userId)`
Get all financial requests sent TO the user.
```php
$requests = $service->getRequestsToUser(123);
foreach ($requests as $request) {
    echo "{$request->numeroReq}: {$request->amount}";
}
```

#### `getRequestsFromUser(int $userId, bool $showCanceled = false)`
Get all financial requests sent FROM the user.
```php
// Without canceled requests
$requests = $service->getRequestsFromUser(123);

// With canceled requests
$allRequests = $service->getRequestsFromUser(123, true);
```

</div>

---

### Notification Counts

<div style="margin-left: 20px;">

#### `countRequestsInOpen(int $userId): int`
Count open requests sent to user (pending, unread).
```php
$count = $service->countRequestsInOpen(123);
echo "You have {$count} pending requests";
```

#### `countRequestsOutAccepted(int $userId): int`
Count accepted requests sent by user (unread).
```php
$count = $service->countRequestsOutAccepted(123);
echo "{$count} of your requests were accepted";
```

#### `countRequestsOutRefused(int $userId): int`
Count refused requests sent by user (unread).
```php
$count = $service->countRequestsOutRefused(123);
echo "{$count} of your requests were refused";
```

</div>

---

### Notification Management

<div style="margin-left: 20px;">

#### `resetOutGoingNotification(int $userId): int`
Mark accepted/refused outgoing requests as read.
```php
$updated = $service->resetOutGoingNotification(123);
echo "Marked {$updated} notifications as read";
```

#### `resetInComingNotification(int $userId): int`
Mark incoming request details as read.
```php
$updated = $service->resetInComingNotification(123);
```

</div>

---

### Request Actions (Transactional)

<div style="margin-left: 20px;">

#### `acceptFinancialRequest(string $numeroReq, int $acceptingUserId): bool`
Accept a financial request. **Uses transaction.**

**What it does:**
- Rejects all other pending responses (response = 3)
- Accepts current user's response (response = 1)
- Updates main request (status = 1)

```php
try {
    $service->acceptFinancialRequest('REQ123456', 789);
    // Success
} catch (\Exception $e) {
    // Error - transaction rolled back
    Log::error('Accept failed: ' . $e->getMessage());
}
```

#### `rejectFinancialRequest(string $numeroReq, int $rejectingUserId): bool`
Reject a financial request. **Uses transaction.**

**What it does:**
- Updates user's response to rejected (response = 2)
- If all users rejected, marks main request as refused (status = 5)

```php
try {
    $service->rejectFinancialRequest('REQ123456', 789);
    // Success - user 789 rejected
    // If all rejected, main request marked as refused
} catch (\Exception $e) {
    // Error - transaction rolled back
    Log::error('Reject failed: ' . $e->getMessage());
}
```

</div>

---

### Recharge Requests

<div style="margin-left: 20px;">

#### `getRechargeRequestsIn(int $userId)`
Get incoming recharge requests.
```php
$requests = $service->getRechargeRequestsIn(123);
```

#### `getRechargeRequestsOut(int $userId)`
Get outgoing recharge requests.
```php
$requests = $service->getRechargeRequestsOut(123);
```

</div>

---

## Status Codes Reference

<div style="margin-left: 20px;">

### Main Request Status (`financial_request.status`)
- **0** = Open/Pending
- **1** = Accepted
- **3** = Canceled by sender
- **5** = Refused (all recipients rejected)

### Detail Response Status (`detail_financial_request.response`)
- **null** = Pending (no response yet)
- **1** = Accepted
- **2** = Rejected by recipient
- **3** = Auto-rejected (when another user accepted)

### Notification Status (`vu` field)
- **0** = Unread
- **1** = Read

</div>

---

## Usage in Livewire Components

<div style="margin-left: 20px;">

### Constructor Injection
```php
public function render(FinancialRequestService $financialRequestService)
{
    $requests = $financialRequestService->getRequestsToUser(auth()->id());
    return view('livewire.component', compact('requests'));
}
```

### Method Injection
```php
public function acceptRequest($numero, FinancialRequestService $service)
{
    try {
        $service->acceptFinancialRequest($numero, auth()->id());
        session()->flash('success', 'Request accepted!');
    } catch (\Exception $e) {
        session()->flash('error', 'Failed to accept request');
    }
}
```

</div>

---

## Usage in Controllers

<div style="margin-left: 20px;">

```php
use App\Services\FinancialRequest\FinancialRequestService;

class FinancialRequestController extends Controller
{
    protected $financialRequestService;

    public function __construct(FinancialRequestService $financialRequestService)
    {
        $this->financialRequestService = $financialRequestService;
    }

    public function index()
    {
        $requests = $this->financialRequestService->getRequestsToUser(auth()->id());
        return view('requests.index', compact('requests'));
    }

    public function accept(Request $request)
    {
        try {
            $this->financialRequestService->acceptFinancialRequest(
                $request->numeroReq,
                auth()->id()
            );
            return redirect()->back()->with('success', 'Request accepted');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to accept');
        }
    }
}
```

</div>

---

## Error Handling

<div style="margin-left: 20px;">

All transactional methods (`acceptFinancialRequest`, `rejectFinancialRequest`) throw exceptions on failure:

```php
try {
    $service->acceptFinancialRequest($numeroReq, $userId);
    // Success - committed to database
} catch (\Exception $e) {
    // Failure - rolled back
    // Error logged automatically with context
    // Re-throw or handle as needed
}
```

</div>

---

## Components Using This Service

<div style="margin-left: 20px;">

- ✅ `AcceptFinancialRequest` - Uses accept/reject methods
- ✅ `IncomingRequest` - Uses lists and counts methods
- ✅ (Add more as you refactor)

</div>

---

## Best Practices

<div style="margin-left: 20px;">

1. **Always use service methods** - Don't query models directly
2. **Handle exceptions** - Wrap transactional calls in try-catch
3. **Validate first** - Check request exists before accepting/rejecting
4. **Use type hints** - Let IDE autocomplete help you
5. **Inject in methods** - Use method injection for one-time use

</div>

---

## Transaction Safety

<div style="margin-left: 20px;">

### Methods with database transactions:
- ✅ `acceptFinancialRequest()` - 3 operations in transaction
- ✅ `rejectFinancialRequest()` - 2-3 operations in transaction
- ✅ `cancelFinancialRequest()` - 1 operation in transaction

### These methods guarantee:
- **Atomicity**: All updates succeed or all fail
- **Consistency**: No partial updates
- **Error logging**: Comprehensive context on failure
- **Rollback**: Automatic rollback on exception

</div>

---

**Last Updated**: December 3, 2025  
**Service Version**: Enhanced with accept/reject methods  
**Status**: Production Ready

