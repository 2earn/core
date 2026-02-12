# Deal Partner API - Request Body Only Update

**Date:** February 12, 2026  
**Status:** ✅ COMPLETED

---

## Overview

Updated the `DealPartnerController` and Postman collection to use **request body parameters only** (no query parameters). All data is now sent as JSON in the request body for consistency and better RESTful API practices.

---

## Changes Made

### 1. **StoreDealRequest.php** ✅

**File:** `app/Http/Requests/StoreDealRequest.php`

**Changes:**
- ❌ Removed `prepareForValidation()` method that was merging query parameters
- ✅ Request now validates only body parameters
- ✅ All fields remain validated as before

**Impact:** Query parameters are no longer accepted or merged. All data must be in the request body.

---

### 2. **DealPartnerController.php - store() Method** ✅

**File:** `app/Http/Controllers/Api/partner/DealPartnerController.php`

**Before:**
```php
$userId = $request->input('user_id') ?? $request->query('user_id');
$createdBy = $request->input('created_by') ?? $request->query('created_by');
```

**After:**
```php
$userId = $request->input('user_id');
$createdBy = $request->input('created_by');
```

**Impact:** Method now expects all parameters in request body only.

---

### 3. **Postman Collection Updates** ✅

**File:** `postman/collections/Partner/Partner Deals API.postman_collection.json`

All endpoints updated to use request body only:

#### **Create Deal**
- **Method:** POST
- **URL:** `{{base_url}}/api/partner/deals/deals` (no query params)
- **Body:**
```json
{
  "user_id": {{user_id}},
  "created_by": {{user_id}},
  "platform_id": {{platform_id}},
  "name": "New Deal 2026",
  "description": "This is a test deal for commission",
  "initial_commission": 5.0,
  "final_commission": 10.0,
  "type": "percentage",
  "status": "pending",
  "start_date": "2026-02-12",
  "end_date": "2026-12-31",
  "target_turnover": 10000,
  "second_target_turnover": 20000,
  "current_turnover": 0,
  "discount": 5,
  "is_turnover": true,
  "notes": "Deal created via API"
}
```

#### **Update Deal**
- **Method:** PUT
- **URL:** `{{base_url}}/api/partner/deals/deals/{{deal_id}}` (no query params)
- **Body:**
```json
{
  "user_id": {{user_id}},
  "requested_by": {{user_id}},
  "name": "Updated Deal Title",
  "description": "Updated description",
  "initial_commission": 7.5,
  "final_commission": 12.5,
  "discount": 10
}
```

#### **Change Deal Status**
- **Method:** PATCH
- **URL:** `{{base_url}}/api/partner/deals/{{deal_id}}/status` (no query params)
- **Body:**
```json
{
  "user_id": {{user_id}},
  "status": 1
}
```

#### **Validate Deal Request**
- **Method:** POST
- **URL:** `{{base_url}}/api/partner/deals/validate` (no query params)
- **Body:**
```json
{
  "user_id": {{user_id}},
  "deal_id": {{deal_id}},
  "notes": "Please validate this deal"
}
```

#### **Cancel Validation Request**
- **Method:** POST
- **URL:** `{{base_url}}/api/partner/deals/validation/cancel` (no query params)
- **Body:**
```json
{
  "validation_request_id": {{validation_request_id}}
}
```

#### **Cancel Change Request**
- **Method:** POST
- **URL:** `{{base_url}}/api/partner/deals/change/cancel` (no query params)
- **Body:**
```json
{
  "change_request_id": {{change_request_id}}
}
```

---

## API Endpoints Summary

| Endpoint | Method | Body Params | Description |
|----------|--------|-------------|-------------|
| `/api/partner/deals/deals` | POST | user_id, created_by, platform_id, name, description, etc. | Create new deal |
| `/api/partner/deals/deals/{id}` | PUT | user_id, requested_by, + fields to update | Update deal (creates change request) |
| `/api/partner/deals/{id}/status` | PATCH | user_id, status | Change deal status |
| `/api/partner/deals/validate` | POST | user_id, deal_id, notes | Submit validation request |
| `/api/partner/deals/validation/cancel` | POST | validation_request_id | Cancel validation request |
| `/api/partner/deals/change/cancel` | POST | change_request_id | Cancel change request |

---

## Required Fields

### **Create Deal (POST /api/partner/deals/deals)**

**Required in body:**
- `user_id` - integer, exists:users,id
- `created_by` - integer, exists:users,id
- `platform_id` - integer, exists:platforms,id
- `name` - string, max:255
- `description` - string
- `initial_commission` - numeric, min:0, max:100
- `final_commission` - numeric, min:0, max:100, >=initial_commission
- `type` - string
- `status` - string
- `start_date` - date
- `end_date` - date, >=start_date

**Optional in body:**
- `target_turnover` - numeric
- `second_target_turnover` - numeric
- `current_turnover` - numeric (defaults to 0)
- `items_profit_average` - numeric
- `is_turnover` - boolean
- `discount` - numeric
- `earn_profit` - numeric
- `jackpot` - numeric
- `tree_remuneration` - numeric
- `proactive_cashback` - numeric
- `total_commission_value` - numeric
- `total_unused_cashback_value` - numeric
- `cash_company_profit` - numeric
- `cash_jackpot` - numeric
- `cash_tree` - numeric
- `cash_cashback` - numeric
- `notes` - string, max:1000

### **Update Deal (PUT /api/partner/deals/deals/{id})**

**Required in body:**
- `requested_by` - integer, exists:users,id

**Optional in body:** (any field you want to update)
- `user_id` - integer, exists:users,id
- `name` - string, max:255
- `description` - string
- `initial_commission` - numeric, min:0, max:100
- `final_commission` - numeric, min:0, max:100
- `type` - string
- `status` - string
- `current_turnover` - numeric
- `target_turnover` - numeric
- And all other deal fields...

---

## Success Responses

### **Create Deal - 201 Created**
```json
{
  "status": true,
  "message": "Deal created successfully and validation request submitted",
  "data": {
    "deal": {
      "id": 123,
      "platform_id": 5,
      "name": "New Deal 2026",
      "description": "This is a test deal for commission",
      "initial_commission": 5.0,
      "final_commission": 10.0,
      "validated": false,
      "created_by_id": 1,
      "created_at": "2026-02-12T10:30:00.000000Z",
      "updated_at": "2026-02-12T10:30:00.000000Z"
    },
    "validation_request": {
      "id": 456,
      "deal_id": 123,
      "requested_by": 1,
      "status": "pending",
      "notes": "Deal created via API",
      "created_at": "2026-02-12T10:30:00.000000Z"
    }
  }
}
```

### **Update Deal - 201 Created**
```json
{
  "status": true,
  "message": "Deal change request submitted successfully. Awaiting approval.",
  "data": {
    "deal": { /* original deal object */ },
    "change_request": {
      "id": 789,
      "deal_id": 123,
      "requested_by": 1,
      "changes": {
        "name": {
          "old": "Old Name",
          "new": "Updated Deal Title"
        },
        "initial_commission": {
          "old": 5.0,
          "new": 7.5
        }
      },
      "status": "pending",
      "created_at": "2026-02-12T10:35:00.000000Z"
    }
  }
}
```

---

## Error Responses

### **422 Unprocessable Entity - Validation Error**
```json
{
  "message": "The given data was invalid.",
  "errors": {
    "user_id": ["The user id field is required."],
    "name": ["The deal name is required"],
    "final_commission": ["The final commission must be greater than or equal to the initial commission"]
  }
}
```

### **500 Internal Server Error**
```json
{
  "status": false,
  "message": "Failed to create deal: [error details]"
}
```

---

## Migration Guide

### **For Frontend/Mobile Developers**

#### **Before (with query params):**
```javascript
// ❌ OLD WAY - NO LONGER WORKS
fetch('http://localhost/api/partner/deals/deals?user_id=1&created_by=1&platform_id=5', {
  method: 'POST',
  headers: {
    'Content-Type': 'application/json'
  },
  body: JSON.stringify({
    name: 'Deal Name',
    description: 'Deal description',
    // ... other fields
  })
})
```

#### **After (body only):**
```javascript
// ✅ NEW WAY - CORRECT
fetch('http://localhost/api/partner/deals/deals', {
  method: 'POST',
  headers: {
    'Content-Type': 'application/json'
  },
  body: JSON.stringify({
    user_id: 1,
    created_by: 1,
    platform_id: 5,
    name: 'Deal Name',
    description: 'Deal description',
    initial_commission: 5.0,
    final_commission: 10.0,
    type: 'percentage',
    status: 'pending',
    start_date: '2026-02-12',
    end_date: '2026-12-31'
    // ... other fields
  })
})
```

---

## Testing

### **Using Postman**

1. **Import/Refresh** the updated collection
2. **Set environment variables:**
   - `base_url` = `http://localhost`
   - `user_id` = `1`
   - `platform_id` = `5`
   - `deal_id` = (any existing deal ID)
3. **Send requests** - all data is now in the body!

### **Using cURL**

```bash
# Create Deal
curl -X POST http://localhost/api/partner/deals/deals \
  -H "Content-Type: application/json" \
  -d '{
    "user_id": 1,
    "created_by": 1,
    "platform_id": 5,
    "name": "New Deal 2026",
    "description": "Test deal",
    "initial_commission": 5.0,
    "final_commission": 10.0,
    "type": "percentage",
    "status": "pending",
    "start_date": "2026-02-12",
    "end_date": "2026-12-31"
  }'
```

### **Automated Tests**

The existing test should still work:
```bash
php artisan test --filter="test_can_create_deal_successfully"
```

---

## Benefits of This Change

✅ **Consistency:** All parameters in one place (request body)  
✅ **RESTful:** Follows REST API best practices  
✅ **Cleaner URLs:** No long query strings  
✅ **Better Security:** Sensitive data not in URL  
✅ **Easier to Test:** Simple JSON payloads  
✅ **Better Documentation:** Clear request/response formats  

---

## Breaking Changes

⚠️ **WARNING:** This is a breaking change!

**What breaks:**
- Any code passing parameters as query strings (e.g., `?user_id=1&platform_id=5`)
- Old Postman requests with query parameters

**What still works:**
- Code already using request body for all parameters
- Tests using JSON body payloads

**Migration required for:**
- Frontend applications
- Mobile applications
- Third-party integrations
- Automated scripts

---

## Verification Checklist

- ✅ StoreDealRequest no longer merges query params
- ✅ DealPartnerController store() uses body params only
- ✅ DealPartnerController update() uses body params only
- ✅ Postman collection updated for Create Deal
- ✅ Postman collection updated for Update Deal
- ✅ Postman collection updated for Change Status
- ✅ Postman collection updated for Validate Request
- ✅ Postman collection updated for Cancel Validation
- ✅ Postman collection updated for Cancel Change
- ✅ No syntax errors in PHP files
- ✅ No syntax errors in JSON file
- ✅ Documentation created

---

## Files Modified

1. `app/Http/Requests/StoreDealRequest.php`
2. `app/Http/Controllers/Api/partner/DealPartnerController.php`
3. `postman/collections/Partner/Partner Deals API.postman_collection.json`

---

## Summary

All deal-related endpoints now use **request body parameters only**. This provides a cleaner, more consistent, and more RESTful API design. The Postman collection has been fully updated to reflect these changes.

🚀 **Ready for use!**

