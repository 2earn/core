# Partner Order Details API - Validation Error Troubleshooting

**Date:** February 12, 2026  
**Status:** 🔧 TROUBLESHOOTING

---

## Issue

The Partner Order Details API endpoint is returning validation errors even when all required fields are present in the request:

**Endpoint:** `POST /api/partner/orders/details`

**Request Body Sent:**
```json
{
  "order_id": {{order_id}},
  "item_id": {{item_id}},
  "qty": 1,
  "unit_price": 80.00,
  "shipping": 0,
  "created_by": {{user_id}}
}
```

**Error Response:**
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

---

## Root Cause Analysis

When all required fields are reported as missing, this typically indicates that the request body is **not being parsed** by Laravel. This can happen due to several reasons:

### **1. Postman Variables Not Being Substituted** ⚠️
If you're using Postman variables like `{{order_id}}`, `{{item_id}}`, `{{user_id}}`, they must be defined in your environment or they won't be replaced.

**Solution:** Check that you have environment variables set OR use actual values.

### **2. Content-Type Header Missing or Incorrect**
Laravel needs `Content-Type: application/json` to parse the JSON body.

**Solution:** Ensure the header is set correctly in Postman.

### **3. IP Address Not Approved** 🚫
The `check.url` middleware requires your IP to be in the approved list.

**Solution:** Add your IP to the approved list or test from an approved IP.

### **4. JSON Syntax Error**
If there's a syntax error in the JSON (like trailing commas, unquoted keys, etc.), Laravel won't parse it.

**Solution:** Validate your JSON using a JSON validator.

---

## Fixes Applied

### **1. Added Middleware to Controller**

Added `check.url` middleware to the `OrderDetailsPartnerController` constructor for consistency:

```php
public function __construct()
{
    $this->middleware('check.url');
}
```

**File:** `app/Http/Controllers/Api/partner/OrderDetailsPartnerController.php`

### **2. Added Debug Logging**

Added comprehensive logging to help diagnose the issue:

```php
Log::info(self::LOG_PREFIX . 'Store request received', [
    'all' => $request->all(),
    'input' => $request->input(),
    'has_json' => $request->isJson(),
    'content' => $request->getContent(),
    'content_type' => $request->header('Content-Type'),
    'method' => $request->method()
]);
```

This will log:
- All request data
- Whether the request is JSON
- The raw content
- The Content-Type header
- The HTTP method

**Check logs:** `storage/logs/laravel.log`

### **3. Updated Postman Collection**

Added example with actual values in the description so users can copy/paste to test:

**File:** `postman/collections/Partner/Partner Orders API.postman_collection.json`

---

## Troubleshooting Steps

### **Step 1: Verify Environment Variables** 🔍

In Postman, check your environment variables:

1. Click the **Environment** dropdown (top right)
2. Click the **eye icon** to view environment
3. Verify these variables exist with valid values:
   - `base_url` = `http://localhost` (or your server URL)
   - `order_id` = `1` (or a valid order ID from your database)
   - `item_id` = `1` (or a valid item ID from your database)
   - `user_id` = `1` (or a valid user ID from your database)

**If variables are missing or undefined**, create them or use actual values instead.

---

### **Step 2: Test with Actual Values** ✅

Instead of using variables, try sending the request with actual numeric values:

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

**Important:** Make sure `order_id`, `item_id`, and `created_by` exist in your database!

---

### **Step 3: Verify Content-Type Header** 📋

In Postman:
1. Open the **Create Order Detail** request
2. Go to the **Headers** tab
3. Verify you have:
   ```
   Content-Type: application/json
   ```
4. Make sure it's **enabled** (checkbox is checked)

---

### **Step 4: Check IP Address** 🌐

The `CheckApprovedUrl` middleware only allows specific IPs:

**Approved IPs:**
- `127.0.0.1` (localhost)
- `102.31.213.136`
- `102.152.40.227`
- `102.152.59.248`

**To check your IP:**
1. If testing locally, use `127.0.0.1`
2. If testing from remote, check your public IP: https://whatismyipaddress.com/

**If your IP is not approved:**
- Option A: Test from localhost (`127.0.0.1`)
- Option B: Add your IP to the approved list in `app/Http/Middleware/CheckApprovedUrl.php`

---

### **Step 5: Check Laravel Logs** 📄

Check the logs to see what's being received:

**Location:** `storage/logs/laravel.log`

Look for entries with `[OrderDetailsPartnerController]` prefix.

You should see something like:
```
[2026-02-12 10:00:00] local.INFO: [OrderDetailsPartnerController] Store request received {
    "all": {"order_id": 1, "item_id": 1, "qty": 1, ...},
    "has_json": true,
    "content_type": "application/json"
}
```

**If `all` is empty `{}`:**
- The request body isn't being parsed
- Check Content-Type header
- Check if JSON is valid

**If you see `Unauthorized. Invalid IP`:**
- Your IP is not approved
- Follow Step 4 above

---

### **Step 6: Validate Database Records** 💾

Make sure the referenced records exist:

```sql
-- Check if order exists
SELECT * FROM orders WHERE id = 1;

-- Check if item exists
SELECT * FROM items WHERE id = 1;

-- Check if user exists
SELECT * FROM users WHERE id = 1;
```

If any of these don't exist, you'll get validation errors like:
```json
{
    "errors": {
        "order_id": ["The selected order id is invalid."],
        "item_id": ["The selected item id is invalid."],
        "created_by": ["The selected created by is invalid."]
    }
}
```

---

### **Step 7: Test with cURL** 🔧

To eliminate Postman issues, test with cURL:

```bash
curl -X POST http://localhost/api/partner/orders/details \
  -H "Content-Type: application/json" \
  -d '{
    "order_id": 1,
    "item_id": 1,
    "qty": 1,
    "unit_price": 80.00,
    "shipping": 0,
    "created_by": 1
  }'
```

**Expected Success Response (201):**
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
    "created_at": "2026-02-12T10:00:00.000000Z"
  }
}
```

---

## Common Mistakes

### ❌ **Mistake 1: Variables Not Set**
```json
{
  "order_id": {{order_id}}  // Shows as undefined in Postman
}
```

**Fix:** Set environment variable or use actual value:
```json
{
  "order_id": 1
}
```

---

### ❌ **Mistake 2: Wrong Content-Type**
```
Content-Type: text/plain
Content-Type: application/x-www-form-urlencoded
```

**Fix:** Use JSON content type:
```
Content-Type: application/json
```

---

### ❌ **Mistake 3: Invalid JSON**
```json
{
  "order_id": 1,
  "item_id": 1,  // Trailing comma in last item
}
```

**Fix:** Remove trailing comma:
```json
{
  "order_id": 1,
  "item_id": 1
}
```

---

### ❌ **Mistake 4: Testing from Unauthorized IP**
If you're not on `127.0.0.1`, you'll get:
```json
{
  "error": "Unauthorized. Invalid IP."
}
```

**Fix:** Test from localhost or add your IP to the middleware.

---

## Quick Fix Checklist

Use this checklist to quickly diagnose the issue:

- [ ] Are environment variables set in Postman?
- [ ] Is `Content-Type: application/json` header present and enabled?
- [ ] Is the JSON syntax valid (no trailing commas, proper quotes)?
- [ ] Am I testing from an approved IP (`127.0.0.1`)?
- [ ] Do `order_id`, `item_id`, and `user_id` exist in the database?
- [ ] Have I checked the Laravel logs for errors?
- [ ] Does the request work with actual numeric values instead of variables?

---

## Testing Instructions

### **Option 1: Test with Environment Variables (Recommended)**

1. **Create/Update Postman Environment:**
   - Environment name: `2Earn Local`
   - Variables:
     ```
     base_url = http://localhost
     user_id = 1
     order_id = 1
     item_id = 1
     ```

2. **Select the environment** in Postman (top-right dropdown)

3. **Send the request** - variables will be substituted automatically

---

### **Option 2: Test with Actual Values**

1. **Edit the request body** to use actual values:
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

2. **Make sure these IDs exist** in your database

3. **Send the request**

---

### **Option 3: Create Test Data First**

If you don't have orders or items, create them first:

1. **Create a User** (if not exists)
2. **Create a Platform** (if not exists)
3. **Create an Order** using the Create Order endpoint
4. **Create an Item** using the Create Item endpoint
5. **Then create Order Detail** using those IDs

---

## Expected Behavior

### **Success Response (201 Created):**
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

**Note:** `total_amount` is auto-calculated: `(qty × unit_price) + shipping`

---

### **Validation Error (422):**
```json
{
  "status": "Failed",
  "message": "Validation failed",
  "errors": {
    "order_id": ["The selected order id is invalid."]
  }
}
```

This means the order ID doesn't exist in the database.

---

### **Unauthorized Error (403):**
```json
{
  "error": "Unauthorized. Invalid IP."
}
```

This means your IP is not in the approved list.

---

## Files Modified

1. ✅ `app/Http/Controllers/Api/partner/OrderDetailsPartnerController.php`
   - Added `check.url` middleware to constructor
   - Added debug logging

2. ✅ `postman/collections/Partner/Partner Orders API.postman_collection.json`
   - Updated description with example actual values

---

## Next Steps

1. **Follow the troubleshooting steps above** in order
2. **Check the Laravel logs** at `storage/logs/laravel.log`
3. **Verify your Postman environment variables**
4. **Test with actual values** instead of variables
5. **Ensure your IP is approved** or test from localhost

---

## Still Having Issues?

If you're still getting validation errors after following all steps:

1. **Share the Laravel log output** showing the request details
2. **Verify the exact JSON being sent** (copy from Postman's console)
3. **Check the database** to confirm the IDs exist
4. **Try the cURL command** to rule out Postman issues

---

## Summary

The issue is most likely one of these:

1. **Postman variables not set** → Set environment variables or use actual values
2. **Wrong Content-Type** → Ensure `Content-Type: application/json`
3. **IP not approved** → Test from `127.0.0.1` or add your IP
4. **Invalid database IDs** → Use IDs that actually exist in your database

**Follow the Quick Fix Checklist above to resolve the issue!** ✅

