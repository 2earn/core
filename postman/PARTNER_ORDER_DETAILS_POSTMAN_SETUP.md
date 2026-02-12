# Partner Order Details API - Postman Setup Guide

## Problem You Experienced

When testing `POST /api/partner/orders/details` in Postman, you got validation errors:

```json
{
    "status": "Failed",
    "message": "Validation failed",
    "errors": {
        "order_id": ["The order id field is required."],
        "item_id": ["The item id field is required."],
        "qty": ["The qty field is required."],
        "unit_price": ["The unit price field is required."],
        "created_by": ["The created by field is required."]
    }
}
```

**Even though you sent all the fields!**

## Why This Happened

You were using **unsubstituted Postman variables**:

```json
{
  "order_id": "{{order_id}}",      ❌ This is a STRING, not replaced
  "item_id": "{{item_id}}",        ❌ This is a STRING, not replaced
  ...
}
```

Instead of:

```json
{
  "order_id": 1,                   ✅ Actual numeric value
  "item_id": 1,                    ✅ Actual numeric value
  ...
}
```

## The Fix

### Option 1: Use the Pre-Built Environment (Recommended)

1. **Import the environment file:**
   - Open Postman
   - Click **Environments** (left sidebar)
   - Click **Import** button
   - Select `postman/Partner-API-Dev-Environment.json`
   - Click **Import**

2. **Activate the environment:**
   - Click the environment dropdown (top right)
   - Select **2Earn Partner API - Development**

3. **Update variables to match your database:**
   - Click the **Environment** tab at the top
   - Update these values with IDs from your database:
     - `base_url`: `http://localhost` (or your server URL)
     - `order_id`: `1` (use an actual order ID)
     - `item_id`: `1` (use an actual item ID)
     - `user_id`: `1` (use an actual user ID)
     - `detail_id`: `1` (use an actual detail ID)

4. **Test the endpoint:**
   - Open the **Create Order Detail** request
   - Click **Send**
   - You should now see a 201 success response!

### Option 2: Manually Set Up Environment

1. **Create a new environment:**
   - Click **Environments** (left sidebar)
   - Click **Create new**
   - Name it: `2Earn Partner API - Development`

2. **Add these variables:**

| Variable | Initial Value | Type |
|----------|---------------|------|
| base_url | http://localhost | String |
| order_id | 1 | String |
| item_id | 1 | String |
| user_id | 1 | String |
| detail_id | 1 | String |

3. **Save and select the environment**

4. **Update variables with real IDs from your database**

### Option 3: Use Literal Values (No Variables)

If you don't want to use environment variables, just paste actual values directly in the request body:

```json
{
  "order_id": 1,
  "item_id": 1,
  "qty": 1,
  "unit_price": 80.00,
  "shipping": 0,
  "created_by": 1
}
```

Replace `1` with actual IDs from your database.

## Important: Database IDs

**Before testing, make sure these IDs exist in your database:**

```sql
-- Check orders
SELECT id FROM orders LIMIT 1;

-- Check items
SELECT id FROM items LIMIT 1;

-- Check users
SELECT id FROM users LIMIT 1;
```

Use the IDs returned by these queries in your Postman request.

## Complete Testing Workflow

1. **Set up environment** (use Option 1 if available)
2. **Get valid IDs from your database**
3. **Update environment variables with those IDs**
4. **Open the "Create Order Detail" request in Postman**
5. **Verify Headers tab has:**
   ```
   Content-Type: application/json
   ```
6. **Click Send**
7. **You should see:**
   ```
   Status: 201 Created
   ```

## Expected Success Response

When everything is set up correctly:

```json
{
    "status": "Success",
    "message": "Order detail created successfully",
    "data": {
        "id": 1,
        "order_id": 1,
        "item_id": 1,
        "qty": 1,
        "unit_price": 80.00,
        "shipping": 0,
        "total_amount": 80.00,
        "created_by": 1,
        "created_at": "2026-02-12T10:00:00.000000Z",
        "updated_at": "2026-02-12T10:00:00.000000Z"
    }
}
```

## Helpful Error Messages

The API now provides better error messages:

### If you forgot to set up variables:
```json
{
    "status": "Failed",
    "message": "Postman variables are not properly substituted. Please ensure all environment variables are set.",
    "warning": "The following fields contain unsubstituted Postman variables: order_id, item_id, created_by",
    "unsubstituted_vars": {
        "order_id": "{{order_id}}",
        "item_id": "{{item_id}}",
        "created_by": "{{user_id}}"
    }
}
```

This tells you exactly which variables need to be set!

### If database records don't exist:
```json
{
    "status": "Failed",
    "message": "Validation failed",
    "errors": {
        "order_id": ["The selected order id is invalid."],
        "item_id": ["The selected item id is invalid."],
        "created_by": ["The selected created by is invalid."]
    }
}
```

Use valid IDs from your database.

## Checklist Before Testing

- [ ] Environment is created and selected
- [ ] All variables are set with valid database IDs
- [ ] Request has `Content-Type: application/json` header
- [ ] Request body is valid JSON (no syntax errors)
- [ ] You're using the POST method, not GET
- [ ] Your IP is in the approved list (127.0.0.1 for localhost)

## Quick Reference

**Endpoint:** `POST /api/partner/orders/details`

**Request Variables:**
- `{{base_url}}` → Server URL
- `{{order_id}}` → Order ID (must exist in orders table)
- `{{item_id}}` → Item ID (must exist in items table)
- `{{user_id}}` → User ID (must exist in users table)

**HTTP Method:** POST  
**Content-Type:** application/json  
**Success Code:** 201  

## Need More Help?

1. Check the detailed guide: `ai generated docs/PARTNER_ORDER_DETAILS_API_FIX_GUIDE.md`
2. Check logs: `storage/logs/laravel.log`
3. Make sure all IDs exist in the database
4. Verify your IP is approved (127.0.0.1 for localhost)

