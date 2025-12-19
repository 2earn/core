# Partner Payment Quick Reference

## Quick Start

### 1. Model Location
```
app/Models/PartnerPayment.php
```

### 2. Service Location
```
app/Services/PartnerPayment/PartnerPaymentService.php
```

### 3. Migration Location
```
database/migrations/2024_12_18_000001_create_partner_payments_table.php
```

## Quick Usage

### Create Payment
```php
use App\Services\PartnerPayment\PartnerPaymentService;

$service = app(PartnerPaymentService::class);

$payment = $service->create([
    'amount' => 1500.50,
    'method' => 'bank_transfer',
    'user_id' => 1,
    'partner_id' => 2,
    'demand_id' => '123456789', // Optional, string format
]);
```

### Validate Payment
```php
$payment = $service->validatePayment($paymentId, $validatorUserId);
```

### Get Partner Payments
```php
// All payments for a partner
$payments = $service->getByPartnerId($partnerId);

// Validated only
$payments = $service->getByPartnerId($partnerId, ['validated' => true]);

// With date range
$payments = $service->getByPartnerId($partnerId, [
    'from_date' => '2024-01-01',
    'to_date' => '2024-12-31',
]);
```

### Get Pending Payments
```php
$pending = $service->getPendingPayments();
```

### Get Totals
```php
// Total for partner (validated only)
$total = $service->getTotalPaymentsByPartner($partnerId, true);

// Total for user
$total = $service->getTotalPaymentsByUser($userId);
```

## Model Relationships

```php
use App\Models\PartnerPayment;

$payment = PartnerPayment::with(['user', 'partner', 'demand', 'validator'])->find(1);

$payment->user;       // User who made the payment
$payment->partner;    // Partner who received the payment
$payment->demand;     // Financial request (if linked)
$payment->validator;  // User who validated the payment
```

## Helper Methods

```php
// Check if payment is validated
if ($payment->isValidated()) {
    // Payment has been validated
}

// Validate payment (on model instance)
$payment->validate($validatorUserId);
```

## Filter Options

Available filters for `getByPartnerId()` and `getByUserId()`:

| Filter | Type | Description |
|--------|------|-------------|
| `validated` | bool | Filter by validation status |
| `method` | string | Filter by payment method |
| `from_date` | date | Filter from this date |
| `to_date` | date | Filter until this date |

## Payment Methods (Examples)

Common payment methods (not restricted):
- `bank_transfer`
- `cash`
- `check`
- `online_payment`
- `mobile_payment`

## Status Checks

```php
// Is validated?
$isValidated = $payment->isValidated();
// OR
$isValidated = !is_null($payment->validated_at);

// Can delete?
$canDelete = !$payment->isValidated();
```

## Database Fields

| Field | Type | Nullable | Description |
|-------|------|----------|-------------|
| id | bigint | No | Primary key |
| amount | decimal(15,2) | No | Payment amount |
| method | varchar(50) | No | Payment method |
| payment_date | timestamp | Yes | When payment was made |
| user_id | bigint | No | User who made payment |
| partner_id | bigint | No | Partner who received payment |
| demand_id | varchar(9) | Yes | Financial request reference |
| validated_by | bigint | Yes | User who validated |
| validated_at | timestamp | Yes | When validated |
| created_by | bigint | Yes | Audit field |
| updated_by | bigint | Yes | Audit field |
| created_at | timestamp | Yes | Auto timestamp |
| updated_at | timestamp | Yes | Auto timestamp |

## Business Rules

✅ **CAN:**
- Create payment without demand_id
- Update unvalidated payments
- Delete unvalidated payments
- Validate payment once

❌ **CANNOT:**
- Validate already validated payment
- Delete validated payment
- Change validation after it's set

## Error Handling

All service methods throw exceptions on failure:

```php
try {
    $payment = $service->create($data);
} catch (\Exception $e) {
    // Handle error
    Log::error('Payment creation failed: ' . $e->getMessage());
}
```

## Example Controller Integration

```php
use App\Services\PartnerPayment\PartnerPaymentService;

class PartnerPaymentController extends Controller
{
    protected $paymentService;
    
    public function __construct(PartnerPaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0',
            'method' => 'required|string|max:50',
            'partner_id' => 'required|exists:users,id',
            'demand_id' => 'nullable|string|max:9',
        ]);
        
        $payment = $this->paymentService->create([
            'amount' => $validated['amount'],
            'method' => $validated['method'],
            'user_id' => auth()->id(),
            'partner_id' => $validated['partner_id'],
            'demand_id' => $validated['demand_id'] ?? null,
        ]);
        
        return response()->json($payment, 201);
    }
}
```

## Migration Status

✅ Migration has been run successfully
✅ Table `partner_payments` created
✅ All foreign keys applied (except demand_id)
✅ All indexes created

## Testing Checklist

- [ ] Create payment
- [ ] Update payment
- [ ] Validate payment
- [ ] Try to validate twice (should fail)
- [ ] Delete unvalidated payment
- [ ] Try to delete validated payment (should fail)
- [ ] Get payments by partner
- [ ] Get payments by user
- [ ] Get pending payments
- [ ] Get validated payments
- [ ] Calculate totals

## Related Models

- `App\Models\User` - For user_id, partner_id, validated_by
- `Core\Models\FinancialRequest` - For demand_id

## Next Implementation Steps

1. **Controller** - Create PartnerPaymentController
2. **Routes** - Add API routes
3. **Validation** - Create Form Request classes
4. **Authorization** - Add policies for access control
5. **Events** - Add events for payment created/validated
6. **Notifications** - Notify partners on payment
7. **Reports** - Add payment reports/exports
8. **Frontend** - Create UI components

## Support

For more details, see: `docs_ai/PARTNER_PAYMENT_IMPLEMENTATION.md`

