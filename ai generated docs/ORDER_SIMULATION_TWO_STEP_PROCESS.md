# Order Simulation API - Two-Step Process

## Overview
The order simulation process has been split into **two separate operations** to provide more flexibility and control over order processing.

## Endpoints

### 1️⃣ Step 1: Simulate Order (Preview)
**Endpoint:** `POST /api/order/simulate`

**Purpose:** Performs simulation calculations without executing the order. Use this to preview the order details before committing.

#### Request
```json
{
    "order_id": 123
}
```

#### Success Response (200 OK)
```json
{
    "status": "Success",
    "message": "Order simulation completed successfully",
    "data": {
        "order_id": 123,
        "simulation": {
            "total_amount": 100.00,
            "partner_discount": 5.00,
            "amount_after_partner_discount": 95.00,
            "earn_discount": 10.00,
            "amount_after_earn_discount": 85.00,
            "deal_discount_percentage": 15,
            "deal_discount": 12.75,
            "amount_after_deal_discount": 72.25,
            "total_discount": 27.75,
            "final_discount": 27.75,
            "final_discount_percentage": 27.75,
            "lost_discount": 0,
            "final_amount": 72.25
        },
        "note": "Simulation complete. Use runSimulation endpoint to execute the order."
    }
}
```

#### Error Responses

**Invalid Order Status (423 LOCKED)**
```json
{
    "status": "Failed",
    "message": "Order status is not eligible for simulation.",
    "current_status": "Dispatched"
}
```

**Simulation Failed (422 UNPROCESSABLE ENTITY)**
```json
{
    "status": "Failed",
    "message": "Simulation failed."
}
```

**Validation Failed (422)**
```json
{
    "status": "Failed",
    "message": "Validation failed",
    "errors": {
        "order_id": ["The order id field is required."]
    }
}
```

---

### 2️⃣ Step 2: Run Simulation (Execute)
**Endpoint:** `POST /api/order/run-simulation`

**Purpose:** Performs simulation AND executes the order, updating balances and dispatching the order.

#### Request
```json
{
    "order_id": 123
}
```

#### Success Response (200 OK)
```json
{
    "order_id": "123",
    "status": "success",
    "amount": 100.00,
    "currency": "USD",
    "discount-available": 27.75,
    "lost-Discount": 0,
    "paid-with-BFS": 22.25,
    "paid-with-Cash": 50.00,
    "transaction_id": "TXN-123",
    "message": "Payment successfully completed",
    "timestamp": "2026-02-05T10:30:00+00:00"
}
```

#### Error Responses

**Order Not Dispatched (422)**
```json
{
    "status": "Failed",
    "message": "Order could not be dispatched",
    "order_id": 123,
    "current_status": "Ready",
    "order": {
        // Full order object with details
    }
}
```

**Invalid Order Status (423 LOCKED)**
```json
{
    "status": "Failed",
    "message": "Order status is not eligible for running simulation.",
    "current_status": "Dispatched"
}
```

**Simulation Failed (422)**
```json
{
    "status": "Failed",
    "message": "Simulation failed."
}
```

---

### 🔄 Original Endpoint (Still Available)
**Endpoint:** `POST /api/order/process`

**Purpose:** Original endpoint that performs both simulation and execution in one call. Kept for backward compatibility.

#### Request
```json
{
    "order_id": 123
}
```

#### Response
Same as the `run-simulation` endpoint.

---

## Workflow Examples

### Example 1: Two-Step Process (Recommended)

**Step 1: Preview the order**
```bash
curl -X POST "http://localhost:8000/api/order/simulate" \
     -H "Content-Type: application/json" \
     -d '{"order_id": 123}'
```

**Step 2: Execute the order (after user confirmation)**
```bash
curl -X POST "http://localhost:8000/api/order/run-simulation" \
     -H "Content-Type: application/json" \
     -d '{"order_id": 123}'
```

### Example 2: Direct Processing (Backward Compatible)

```bash
curl -X POST "http://localhost:8000/api/order/process" \
     -H "Content-Type: application/json" \
     -d '{"order_id": 123}'
```

---

## Business Logic

### Order Status Requirements
Both endpoints require the order status to be either:
- `Simulated` (value: appropriate enum value)
- `Ready` (value: appropriate enum value)

Any other status will return a **423 LOCKED** error.

### Simulation Process
The `Ordering::simulate($order)` method:
1. Calculates partner discounts
2. Applies earn discounts
3. Calculates deal discounts
4. Computes final amounts
5. Returns simulation data without modifying the database

### Execution Process
The `Ordering::run($simulation)` method:
1. Updates order status to `Dispatched`
2. Creates balance operations
3. Updates user balances (cash, discount, BFS)
4. Records commission breakdowns
5. Sends notifications
6. Persists all changes to database

---

## Use Cases

### Use Case 1: E-commerce Checkout with Preview
```javascript
// Step 1: Show preview to user
const simulationResponse = await fetch('/api/order/simulate', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ order_id: 123 })
});

const simulation = await simulationResponse.json();

// Display to user: 
// "Total: $100, Discount: $27.75, Final: $72.25"
displayOrderPreview(simulation.data.simulation);

// Step 2: After user confirms
const executeResponse = await fetch('/api/order/run-simulation', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ order_id: 123 })
});

const result = await executeResponse.json();
showSuccessMessage(result.message);
```

### Use Case 2: Admin Panel Order Management
```javascript
// Admin reviews simulation first
const preview = await simulateOrder(orderId);

if (preview.data.simulation.final_amount > 0) {
    // Admin approves and executes
    await runSimulation(orderId);
}
```

### Use Case 3: Automated Order Processing
```javascript
// For trusted/automated flows, use direct processing
await processOrder(orderId);
```

---

## Security

### IP Validation
All endpoints use the `check.url` middleware to validate IP addresses:
- Only whitelisted IPs can access these endpoints
- Returns **403 Forbidden** for unauthorized IPs

### Validation Rules
- `order_id`: Required, must be integer, must exist in orders table

---

## Error Handling

### Status Codes
| Code | Meaning | Description |
|------|---------|-------------|
| 200 | Success | Operation completed successfully |
| 422 | Unprocessable Entity | Validation failed or simulation failed |
| 423 | Locked | Order status not eligible |
| 403 | Forbidden | Invalid IP address |
| 500 | Internal Server Error | Unexpected error occurred |

### Error Response Format
```json
{
    "status": "Failed",
    "message": "Error description",
    "errors": {
        // Validation errors (if applicable)
    }
}
```

---

## Testing

### Test Coverage
The following test cases are included:

**simulateOrder Tests:**
- ✅ Successful simulation
- ✅ Missing order_id validation
- ✅ Invalid order_id validation
- ✅ Invalid order status (423 error)
- ✅ IP validation (403 error)

**runSimulation Tests:**
- ✅ Successful execution
- ✅ Missing order_id validation
- ✅ Invalid order_id validation
- ✅ Invalid order status (423 error)
- ✅ IP validation (403 error)

**Run Tests:**
```bash
php artisan test tests/Feature/Api/Payment/OrderSimulationControllerTest.php
```

---

## Logging

All operations are logged with the prefix `[OrderSimulationController]`:

### Log Levels

**INFO:**
- Incoming requests
- Validation passed
- Simulation completed
- Order dispatched

**WARNING:**
- Invalid order status
- Order not dispatched after execution

**ERROR:**
- Validation failures
- Simulation failures
- Exceptions with stack traces

### Example Log Entry
```
[2026-02-05 10:30:00] INFO: [OrderSimulationController] Incoming order simulation request
Context: {"request": {"order_id": 123}}

[2026-02-05 10:30:01] INFO: [OrderSimulationController] Simulation completed successfully
Context: {
    "order_id": 123,
    "simulation_summary": {
        "total_amount": 100.00,
        "final_amount": 72.25,
        "total_discount": 27.75
    }
}
```

---

## Migration from Single-Step to Two-Step

### Before (Single Step)
```javascript
await fetch('/api/order/process', {
    method: 'POST',
    body: JSON.stringify({ order_id: 123 })
});
```

### After (Two Steps)
```javascript
// Step 1: Preview
const preview = await fetch('/api/order/simulate', {
    method: 'POST',
    body: JSON.stringify({ order_id: 123 })
});

// Step 2: Execute
const result = await fetch('/api/order/run-simulation', {
    method: 'POST',
    body: JSON.stringify({ order_id: 123 })
});
```

### Backward Compatibility
The original `/api/order/process` endpoint remains unchanged and functional for existing integrations.

---

## Performance Considerations

### Simulation Endpoint
- **Response Time:** < 500ms
- **Database Operations:** Read-only (SELECT queries)
- **No Side Effects:** Safe to call multiple times

### Run Simulation Endpoint
- **Response Time:** 1-2 seconds
- **Database Operations:** Multiple writes (INSERT/UPDATE)
- **Side Effects:** Updates balances, creates records, sends notifications
- **Not Idempotent:** Should only be called once per order

---

## Best Practices

1. **Always simulate before running** - Show users the preview
2. **Handle errors gracefully** - Check status codes and display messages
3. **Don't retry run-simulation** - If it fails, investigate before retrying
4. **Log transaction IDs** - Store the transaction_id from response
5. **Monitor order status** - Track orders that fail to dispatch

---

## API Versioning

**Current Version:** 1.0  
**Date:** February 5, 2026  
**Status:** ✅ Production Ready

---

## Support

### Common Issues

**Issue:** Simulation returns empty data  
**Solution:** Check order status and ensure it's Ready or Simulated

**Issue:** Run simulation fails with 423  
**Solution:** Order may have already been dispatched

**Issue:** 403 Unauthorized IP  
**Solution:** Add IP to whitelist in `check.url` middleware

### Logs Location
```
storage/logs/laravel.log
```

Search for: `[OrderSimulationController]`

---

## Related Documentation
- Order Model Documentation
- Ordering Service Documentation
- Balance Operations Documentation
- Commission Breakdown Documentation
