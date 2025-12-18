# Partner Payment Implementation

## Overview
Complete implementation of the PartnerPayment model, migration, and service for tracking payments between users and partners.

## Files Created

### 1. Migration
**File:** `database/migrations/2024_12_18_000001_create_partner_payments_table.php`

Creates the `partner_payments` table with the following fields:
- `id` - Primary key
- `amount` - Payment amount (decimal 15,2)
- `method` - Payment method (string)
- `payment_date` - When payment was made
- `user_id` - User who made the payment
- `partner_id` - Partner who received the payment
- `demand_id` - Optional link to financial request (string, 9 chars to match financial_request.numeroReq)
- `validated_by` - User who validated the payment
- `validated_at` - When payment was validated
- `created_by`, `updated_by` - Audit fields
- `timestamps` - Created at, Updated at

**Foreign Keys:**
- `user_id` → `users.id`
- `partner_id` → `users.id`
- `demand_id` → References `financial_request.numeroReq` (no FK constraint due to string-based key)
- `validated_by` → `users.id`
- `created_by` → `users.id`
- `updated_by` → `users.id`

### 2. Model
**File:** `app/Models/PartnerPayment.php`

**Features:**
- Uses `HasFactory` and `HasAuditing` traits
- Proper casting for decimals and dates
- Relationships: `user()`, `partner()`, `demand()`, `validator()`
- Helper methods: `isValidated()`, `validate()`

**Relationships:**
```php
$payment->user        // User who made payment
$payment->partner     // Partner who received payment
$payment->demand      // Associated financial request
$payment->validator   // User who validated
```

### 3. Service
**File:** `app/Services/PartnerPayment/PartnerPaymentService.php`

**Available Methods:**

#### Create & Update
- `create(array $data): PartnerPayment` - Create new payment
- `update(int $paymentId, array $data): PartnerPayment` - Update payment
- `validatePayment(int $paymentId, int $validatorId): PartnerPayment` - Validate payment
- `delete(int $paymentId): bool` - Delete unvalidated payment

#### Retrieve
- `getById(int $paymentId): PartnerPayment` - Get single payment
- `getByPartnerId(int $partnerId, array $filters = []): Collection` - Get partner's payments
- `getByUserId(int $userId, array $filters = []): Collection` - Get user's payments
- `getByDemandId(int $demandId): Collection` - Get payments by demand
- `getPendingPayments(): Collection` - Get unvalidated payments
- `getValidatedPayments(array $filters = []): Collection` - Get validated payments

#### Analytics
- `getTotalPaymentsByPartner(int $partnerId, bool $validatedOnly = false): float`
- `getTotalPaymentsByUser(int $userId, bool $validatedOnly = false): float`

## Usage Examples

### Creating a Payment
```php
use App\Services\PartnerPayment\PartnerPaymentService;

$service = new PartnerPaymentService();

$payment = $service->create([
    'amount' => 1500.50,
    'method' => 'bank_transfer',
    'payment_date' => now(),
    'user_id' => 1,
    'partner_id' => 2,
    'demand_id' => 123, // optional
]);
```

### Validating a Payment
```php
$payment = $service->validatePayment(
    paymentId: 1,
    validatorId: 5
);
```

### Getting Payments with Filters
```php
// Get partner's validated payments
$payments = $service->getByPartnerId(2, [
    'validated' => true,
    'method' => 'bank_transfer',
    'from_date' => '2024-01-01',
    'to_date' => '2024-12-31',
]);

// Get pending payments
$pending = $service->getPendingPayments();
```

### Getting Payment Totals
```php
// Total validated payments for partner
$total = $service->getTotalPaymentsByPartner(
    partnerId: 2,
    validatedOnly: true
);
```

### Updating a Payment
```php
$payment = $service->update(1, [
    'amount' => 2000.00,
    'method' => 'cash',
]);
```

## Running the Migration

```bash
php artisan migrate
```

To rollback:
```bash
php artisan migrate:rollback
```

## Model Relationships Usage

```php
$payment = PartnerPayment::with(['user', 'partner', 'demand', 'validator'])->find(1);

// Access relationships
echo $payment->user->name;          // User who paid
echo $payment->partner->name;       // Partner who received
echo $payment->demand->amount;      // Associated demand amount
echo $payment->validator->name;     // Who validated

// Check if validated
if ($payment->isValidated()) {
    echo "Payment validated by: " . $payment->validator->name;
}
```

## Filter Options

When using `getByPartnerId()` or `getByUserId()`, you can pass these filters:

- `validated` (bool) - Filter by validation status
- `method` (string) - Filter by payment method
- `from_date` (date) - Filter payments from this date
- `to_date` (date) - Filter payments until this date

## Business Rules

1. **Cannot delete validated payments** - The service prevents deletion of validated payments
2. **Cannot validate twice** - A payment cannot be validated more than once
3. **Automatic timestamps** - Payment date defaults to `now()` if not provided
4. **Audit trail** - All changes are tracked via `HasAuditing` trait

## Next Steps

1. Run the migration: `php artisan migrate`
2. Create a controller if needed
3. Add API routes for CRUD operations
4. Create validation rules/requests
5. Add authorization policies if needed

## Notes

- The migration uses proper foreign key constraints with cascading deletes for users
- The demand_id foreign key uses 'set null' on delete to preserve payment history
- All monetary values use `decimal(15,2)` for precision
- The service includes comprehensive error handling and logging
- All retrieval methods use eager loading for better performance

