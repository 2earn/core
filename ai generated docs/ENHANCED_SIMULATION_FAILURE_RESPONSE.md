# Enhanced Simulation Failure Response

## Summary
Updated all simulation failure responses in `OrderSimulationController` to include comprehensive information about the failed simulation, including simulation details, datetime, and user information.

## Date
February 6, 2026

## Changes Made

### Updated Methods
All three methods in `OrderSimulationController` now return enhanced error responses when simulation fails:
1. `processOrder()`
2. `simulateOrder()`
3. `runSimulation()`

### Enhanced Error Response Structure

**Before:**
```json
{
    "status": "Failed",
    "message": "Simulation Failed."
}
```

**After:**
```json
{
    "status": "Failed",
    "message": "Simulation Failed.",
    "simulation_result": false,
    "simulation_details": "Details from order or 'No details available'",
    "simulation_datetime": "2026-02-06T10:30:45+00:00",
    "user": {
        "id": 123,
        "name": "John Doe",
        "email": "john@example.com"
    }
}
```

## Fields Included

### `simulation_result`
- **Type:** boolean
- **Value:** `false` (indicating simulation failed)
- **Purpose:** Explicit flag for programmatic checking

### `simulation_details`
- **Type:** string
- **Source:** `$order->simulation_details`
- **Fallback:** `"No details available"` if null
- **Purpose:** Provides detailed information about why the simulation failed

### `simulation_datetime`
- **Type:** string (ISO 8601 format)
- **Source:** `$order->simulation_datetime`
- **Fallback:** Current timestamp if null
- **Purpose:** Timestamp when the simulation was attempted

### `user`
- **Type:** object
- **Fields:**
  - `id`: User ID from order
  - `name`: User's name (null if not available)
  - `email`: User's email (null if not available)
- **Purpose:** Identifies which user's order failed simulation

## Benefits

1. **Better Debugging:** Developers can quickly identify why simulations fail
2. **User Context:** Know which user is affected by the failure
3. **Temporal Tracking:** Track when simulation failures occur
4. **Consistent API:** All three endpoints now return the same enhanced error structure
5. **Client-Side Handling:** Frontend can display more meaningful error messages to users

## API Response Examples

### Example 1: Simulation Failure with Details
```json
{
    "status": "Failed",
    "message": "Simulation Failed.",
    "simulation_result": false,
    "simulation_details": "Insufficient balance for discount application",
    "simulation_datetime": "2026-02-06T14:22:15+00:00",
    "user": {
        "id": 456,
        "name": "Jane Smith",
        "email": "jane.smith@example.com"
    }
}
```

### Example 2: Simulation Failure without Previous Details
```json
{
    "status": "Failed",
    "message": "Simulation Failed.",
    "simulation_result": false,
    "simulation_details": "No details available",
    "simulation_datetime": "2026-02-06T14:25:30+00:00",
    "user": {
        "id": 789,
        "name": "Bob Johnson",
        "email": "bob@example.com"
    }
}
```

## HTTP Status Code
- **422 Unprocessable Entity** - Indicates the simulation logic could not process the order

## Related Models

### Order Model Fields Used
- `simulation_result` (boolean, casted)
- `simulation_details` (string)
- `simulation_datetime` (datetime)
- `user_id` (foreign key)

### Order Relationships Used
- `user()` - BelongsTo relationship to User model

## Testing Recommendations

1. **Test with failed simulation:** Verify all fields are returned correctly
2. **Test with null details:** Ensure fallback message appears
3. **Test with null datetime:** Ensure current timestamp is used
4. **Test with deleted user:** Verify graceful handling of null user data

## Backward Compatibility

⚠️ **Breaking Change:** Clients expecting the old simple error response structure will need to be updated to handle the new fields. However, the core `status` and `message` fields remain unchanged, so minimal changes should be required.
