# 2Earn API v2 - Service Collections Separation

## Overview
The combined Postman collection containing RoleService, PartnerService, and PartnerPaymentService has been separated into three individual collections for better organization and usability.

**Date:** February 9, 2026

---

## Separated Collections

### 1. RoleService Collection
**File:** `postman/2Earn_API_v2_RoleService_Collection.json`

**Endpoints:** 8 requests

#### Endpoints:
1. **GET** `/api/v2/roles` - Get All Roles (Paginated)
2. **GET** `/api/v2/roles/all` - Get All Roles (Non-Paginated)
3. **GET** `/api/v2/roles/:id` - Get Role By ID
4. **GET** `/api/v2/roles/:id/can-delete` - Check If Role Can Be Deleted
5. **GET** `/api/v2/roles/user-roles` - Get User Roles
6. **POST** `/api/v2/roles` - Create Role
7. **PUT** `/api/v2/roles/:id` - Update Role
8. **DELETE** `/api/v2/roles/:id` - Delete Role

---

### 2. PartnerService Collection
**File:** `postman/2Earn_API_v2_PartnerService_Collection.json`

**Endpoints:** 8 requests

#### Endpoints:
1. **GET** `/api/v2/partners` - Get All Partners
2. **GET** `/api/v2/partners/filtered` - Get Filtered Partners (Paginated)
3. **GET** `/api/v2/partners/:id` - Get Partner By ID
4. **GET** `/api/v2/partners/business-sectors/:businessSectorId` - Get Partners By Business Sector
5. **GET** `/api/v2/partners/search` - Search Partners By Company Name
6. **POST** `/api/v2/partners` - Create Partner
7. **PUT** `/api/v2/partners/:id` - Update Partner
8. **DELETE** `/api/v2/partners/:id` - Delete Partner

---

### 3. PartnerPaymentService Collection
**File:** `postman/2Earn_API_v2_PartnerPaymentService_Collection.json`

**Endpoints:** 13 requests

#### Endpoints:
1. **GET** `/api/v2/partner-payments` - Get All Payments (Paginated)
2. **GET** `/api/v2/partner-payments/:id` - Get Payment By ID
3. **GET** `/api/v2/partner-payments/partners/:partnerId` - Get Payments By Partner ID
4. **GET** `/api/v2/partner-payments/partners/:partnerId/total` - Get Total Payments By Partner
5. **GET** `/api/v2/partner-payments/pending` - Get Pending Payments
6. **GET** `/api/v2/partner-payments/validated` - Get Validated Payments
7. **GET** `/api/v2/partner-payments/stats` - Get Payment Statistics
8. **GET** `/api/v2/partner-payments/payment-methods` - Get Payment Methods
9. **POST** `/api/v2/partner-payments` - Create Payment
10. **PUT** `/api/v2/partner-payments/:id` - Update Payment
11. **POST** `/api/v2/partner-payments/:id/validate` - Validate Payment
12. **POST** `/api/v2/partner-payments/:id/reject` - Reject Payment
13. **DELETE** `/api/v2/partner-payments/:id` - Delete Payment

---

## Legacy Collection (Combined)
**File:** `postman/2Earn_API_v2_RoleService_PartnerService_PartnerPaymentService_Collection.json`

This file remains available for backward compatibility but is now considered legacy. It contains all 29 endpoints (8 Roles + 8 Partners + 13 Partner Payments) in a single collection.

---

## Benefits of Separation

### 1. **Improved Organization**
   - Each service has its own dedicated collection
   - Easier to locate specific endpoints
   - Better file management

### 2. **Better Testing Workflow**
   - Test individual services independently
   - Reduce clutter when working on specific features
   - Faster import and navigation in Postman

### 3. **Team Collaboration**
   - Different team members can work on different collections
   - Easier to share service-specific documentation
   - Reduced merge conflicts

### 4. **Maintenance**
   - Easier to update individual services
   - Clear separation of concerns
   - Better version control

---

## Import Instructions

### Importing Individual Collections

1. Open Postman
2. Click **Import** button
3. Select one of the following files:
   - `2Earn_API_v2_RoleService_Collection.json`
   - `2Earn_API_v2_PartnerService_Collection.json`
   - `2Earn_API_v2_PartnerPaymentService_Collection.json`
4. Click **Import**

### Importing All Collections

You can import all three collections at once by selecting all three files during the import process.

---

## Environment Variables

All collections use the same environment variable:
- `{{base_url}}` - Default: `http://localhost`

Make sure to set up your environment in Postman with the appropriate base URL for your environment (local, staging, production).

---

## Related Files

### Service Implementation
- `app/Services/v2/RoleService.php`
- `app/Services/v2/PartnerService.php`
- `app/Services/v2/PartnerPaymentService.php`

### Controllers
- `app/Http/Controllers/Api/v2/RoleController.php`
- `app/Http/Controllers/Api/v2/PartnerController.php`
- `app/Http/Controllers/Api/v2/PartnerPaymentController.php`

### Routes
- `routes/api_v2.php`

### Documentation
- `ai generated docs/API_V2_SERVICES_IMPLEMENTATION_SUMMARY.md`
- `ai generated docs/API_V2_ROLE_PARTNER_PARTNERPAYMENT_SERVICES.md`

---

## Summary

✅ **3 New Separate Collections Created**
- RoleService: 8 endpoints
- PartnerService: 8 endpoints  
- PartnerPaymentService: 13 endpoints

✅ **Legacy Combined Collection Retained**
- For backward compatibility

✅ **Total API Endpoints: 29**
- All endpoints available in both separate and combined formats

---

## Next Steps

1. Import the new separate collections into Postman
2. Configure environment variables
3. Test endpoints individually per service
4. Update any documentation or references to use the new separate collections
5. Optionally archive or remove the legacy combined collection once fully migrated

---

**Status:** ✅ Complete  
**Created:** February 9, 2026

