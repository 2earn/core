# Quick Reference: Order Simulation Two-Step API

## 🚀 Quick Start

### Step 1: Simulate (Preview)
```bash
POST /api/order/simulate
{
    "order_id": 123
}
```

**Response:** Simulation data (no execution)

### Step 2: Run (Execute)
```bash
POST /api/order/run-simulation
{
    "order_id": 123
}
```

**Response:** Order dispatched with transaction ID

---

## 📋 Endpoint Comparison

| Feature | `/simulate` | `/run-simulation` | `/process` (original) |
|---------|-------------|-------------------|------------------------|
| **Simulates order** | ✅ Yes | ✅ Yes | ✅ Yes |
| **Executes order** | ❌ No | ✅ Yes | ✅ Yes |
| **Updates balances** | ❌ No | ✅ Yes | ✅ Yes |
| **Dispatches order** | ❌ No | ✅ Yes | ✅ Yes |
| **Safe to retry** | ✅ Yes | ⚠️ No | ⚠️ No |
| **Use case** | Preview | Execute | Legacy |

---

## 🔄 Typical Workflow

```javascript
// 1. Get simulation preview
const preview = await fetch('/api/order/simulate', {
    method: 'POST',
    body: JSON.stringify({ order_id: 123 })
});
const simData = await preview.json();

// 2. Show user the preview
console.log(`Final amount: $${simData.data.simulation.final_amount}`);
console.log(`Total discount: $${simData.data.simulation.total_discount}`);

// 3. After user confirms, execute
const execute = await fetch('/api/order/run-simulation', {
    method: 'POST',
    body: JSON.stringify({ order_id: 123 })
});
const result = await execute.json();

// 4. Show success message
console.log(`Transaction ID: ${result.transaction_id}`);
```

---

## ✅ Eligible Order Statuses

Both endpoints require order status to be:
- **Ready**
- **Simulated**

Any other status returns **423 LOCKED** error.

---

## 📊 Response Examples

### Simulate Response (200)
```json
{
    "status": "Success",
    "data": {
        "simulation": {
            "total_amount": 100.00,
            "final_amount": 72.25,
            "total_discount": 27.75
        }
    }
}
```

### Run Simulation Response (200)
```json
{
    "order_id": "123",
    "status": "success",
    "transaction_id": "TXN-123",
    "amount": 100.00,
    "paid-with-Cash": 50.00,
    "paid-with-BFS": 22.25
}
```

---

## ❌ Common Errors

| Code | Error | Solution |
|------|-------|----------|
| 422 | Validation failed | Check order_id parameter |
| 423 | Invalid status | Order already dispatched or wrong status |
| 403 | Unauthorized IP | Add IP to whitelist |
| 422 | Simulation failed | Check order details and balances |

---

## 🔐 Security

- **Middleware:** `check.url` (IP validation)
- **Authentication:** None (IP-based)
- **Validation:** Order ID must exist

---

## 📝 Validation Rules

```php
'order_id' => 'required|integer|exists:orders,id'
```

---

## 🧪 Testing

```bash
# Run all tests
php artisan test tests/Feature/Api/Payment/OrderSimulationControllerTest.php

# Test simulate endpoint
php artisan test --filter=test_can_simulate_order_successfully

# Test run simulation endpoint
php artisan test --filter=test_can_run_simulation_successfully
```

---

## 📦 cURL Examples

### Simulate Order
```bash
curl -X POST "http://localhost:8000/api/order/simulate" \
     -H "Content-Type: application/json" \
     -d '{"order_id": 123}'
```

### Run Simulation
```bash
curl -X POST "http://localhost:8000/api/order/run-simulation" \
     -H "Content-Type: application/json" \
     -d '{"order_id": 123}'
```

### Process Order (Legacy)
```bash
curl -X POST "http://localhost:8000/api/order/process" \
     -H "Content-Type: application/json" \
     -d '{"order_id": 123}'
```

---

## 🎯 When to Use Each Endpoint

### Use `/simulate`
- ✅ Show price preview to users
- ✅ Validate order calculations
- ✅ Test different scenarios
- ✅ Admin review before execution

### Use `/run-simulation`
- ✅ Execute confirmed orders
- ✅ Process payments
- ✅ Dispatch orders
- ⚠️ Only after simulation review

### Use `/process` (Legacy)
- ✅ Backward compatibility
- ✅ Automated systems
- ✅ Trusted processes
- ⚠️ No preview step

---

## 🔍 Debugging

### Check Logs
```bash
tail -f storage/logs/laravel.log | grep "OrderSimulationController"
```

### Common Log Messages
- `Incoming order simulation request` - Request received
- `Simulation completed successfully` - Step 1 done
- `Order dispatched successfully` - Step 2 done
- `Order status not eligible` - Invalid status error

---

## 📚 Related Documentation

- [ORDER_SIMULATION_TWO_STEP_PROCESS.md](./ORDER_SIMULATION_TWO_STEP_PROCESS.md) - Full documentation
- [order-simulation-two-step-api.postman_collection.json](../order-simulation-two-step-api.postman_collection.json) - Postman collection

---

**Version:** 1.0  
**Date:** February 5, 2026  
**Status:** ✅ Production Ready
