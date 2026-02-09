# 2Earn API Postman Collections

This directory contains comprehensive Postman collections for all API endpoints in the 2Earn platform. All API endpoints are prefixed with `/api` as per the application's routing structure.

## 📋 Collection Overview

Total Collections: **12**

### Collection List

1. **V1 Authenticated API** - Core authenticated endpoints (Sanctum Auth)
2. **Balance Operations API v2** - Balance operations management (Public)
3. **Mobile Balance API** - Mobile app balance endpoints
4. **Partner Platforms API** - Partner platform management
5. **Partner Deals API** - Partner deal management
6. **Partner Orders API** - Partner order management
7. **Partner Items API** - Partner item/product management
8. **Partner Sales Dashboard API** - Partner sales analytics
9. **Partner Payments API** - Partner payment management
10. **Partner Role Requests API** - Partner role request management
11. **Partner Users API** - Partner user and role management
12. **Platform Change Request API** - Admin platform change requests

---

## 🔐 Authentication

### V1 Authenticated API (Sanctum)
- **Prefix**: `/api/v1/`
- **Authentication**: Bearer Token (Laravel Sanctum)
- **Header**: `Authorization: Bearer {{access_token}}`

### Partner APIs
- **Prefix**: `/api/partner/`
- **Middleware**: `check.url` (Custom URL validation)
- **Authentication**: Not required (middleware-based security)

### Mobile APIs
- **Prefix**: `/api/mobile/`
- **Middleware**: `check.url`
- **Authentication**: Not required (middleware-based security)

### Balance Operations v2
- **Prefix**: `/api/v2/balance/operations`
- **Authentication**: Public API (No authentication required)

---

## 📦 Detailed Collection Documentation

### 1. V1 Authenticated API
**File**: `V1 Authenticated API.postman_collection.json`

Comprehensive collection covering all authenticated endpoints including:
- **Countries & Settings**: Country lists, application settings, amounts
- **User Balances**: Balance queries, updates, status management
- **Shares/Actions**: Share price evolution, balance tracking
- **Notifications**: Notification history
- **Coupons**: Coupon and injector management
- **Platforms & Deals**: Platform and deal searches
- **Roles & Requests**: Role management
- **User Data**: Invitations, tree structure, SMS, purchases
- **Analytics**: Target data, Sankey diagrams
- **Transfers**: Balance transfers and cash additions
- **VIP & SMS**: VIP status, SMS sending
- **Payment Notifications**: PayTabs webhook handling

**Key Endpoints**: 50+ endpoints
**Base URL Variable**: `{{base_url}}/api/v1/`

---

### 2. Balance Operations API v2
**File**: `Balance Operations API v2.postman_collection.json`

Public API for balance operations management:
- Get operations (DataTables format)
- Filtered operations with search
- Get all operations
- Category management
- CRUD operations for balance operations

**Key Endpoints**: 9 endpoints
**Base URL**: `{{base_url}}/api/v2/balance/operations`
**Authentication**: None (Public API)

---

### 3. Mobile Balance API
**File**: `Mobile Balance API.postman_collection.json`

Mobile application balance endpoints:
- Get user balances
- Get cash balance
- Create/add cash balance

**Key Endpoints**: 3 endpoints
**Base URL**: `{{base_url}}/api/mobile/`
**Middleware**: `check.url`

---

### 4. Partner Platforms API
**File**: `Partner Platforms API.postman_collection.json`

Platform management for partners:
- Get top selling platforms
- Platform roles management
- Change platform type
- Validation requests (submit, cancel)
- Change requests (cancel)
- CRUD operations for platforms

**Key Endpoints**: 10 endpoints
**Base URL**: `{{base_url}}/api/partner/platforms`

---

### 5. Partner Deals API
**File**: `Partner Deals API.postman_collection.json`

Deal management system:
- List and get deals
- Create and update deals
- Change deal status
- Validation workflows
- Dashboard indicators
- Performance charts
- Product change tracking

**Key Endpoints**: 13 endpoints
**Base URL**: `{{base_url}}/api/partner/deals`

---

### 6. Partner Orders API
**File**: `Partner Orders API.postman_collection.json`

Order management:
- List and get orders
- Create and update orders
- Change order status
- Order details management
- Order line items (CRUD)

**Key Endpoints**: 7 endpoints
**Base URL**: `{{base_url}}/api/partner/orders`

---

### 7. Partner Items API
**File**: `Partner Items API.postman_collection.json`

Item/Product management:
- List and get items
- Create and update items
- Platform associations
- Deal associations (bulk operations)
- List items by deal

**Key Endpoints**: 8 endpoints
**Base URL**: `{{base_url}}/api/partner/items`

---

### 8. Partner Sales Dashboard API
**File**: `Partner Sales Dashboard API.postman_collection.json`

Sales analytics and reporting:
- KPIs (Key Performance Indicators)
- Sales evolution charts
- Top selling products
- Top selling deals
- Transaction lists and details

**Key Endpoints**: 6 endpoints
**Base URL**: `{{base_url}}/api/partner/sales/dashboard`

---

### 9. Partner Payments API
**File**: `Partner Payments API.postman_collection.json`

Payment management:
- List payments
- Get payment details
- Create payment demands
- Payment statistics summary

**Key Endpoints**: 4 endpoints
**Base URL**: `{{base_url}}/api/partner/payments`

---

### 10. Partner Role Requests API
**File**: `Partner Role Requests API.postman_collection.json`

Role request workflows:
- List role requests
- Get request details
- Create role requests
- Cancel requests

**Key Endpoints**: 4 endpoints
**Base URL**: `{{base_url}}/api/partner/role-requests`

---

### 11. Partner Users API
**File**: `Partner Users API.postman_collection.json`

User and platform role management:
- Get user information
- Partner platforms list
- Add/update/delete platform roles
- User discount balance
- Plan labels

**Key Endpoints**: 7 endpoints
**Base URL**: `{{base_url}}/api/partner/users`

---

### 12. Platform Change Request API
**File**: `Platform Change Request API.postman_collection.json`

Admin endpoints for platform change management:
- Get pending requests
- List all requests with filters
- Get request details
- Request statistics

**Key Endpoints**: 4 endpoints
**Base URL**: `{{base_url}}/api/admin/platform-change-requests`

---

## 🔧 Environment Variables

To use these collections effectively, create a Postman environment with the following variables:

### Required Variables
```json
{
  "base_url": "http://localhost:8000",
  "access_token": "your_sanctum_token_here",
  "admin_token": "your_admin_token_here",
  "user_id": "1",
  "platform_id": "1",
  "deal_id": "1",
  "order_id": "1",
  "item_id": "1",
  "role_id": "1",
  "change_request_id": "1"
}
```

### Optional Variables (for testing)
```json
{
  "idAmounts": "1",
  "idUser": "1",
  "idTarget": "1",
  "balance_id": "1",
  "coupon_id": "1",
  "injector_coupon_id": "1",
  "transaction_id": "TXN123456",
  "type": "BFS",
  "dealId": "1",
  "categoryId": "1",
  "platformId": "1"
}
```

---

## 🚀 Getting Started

### 1. Import Collections
1. Open Postman
2. Click **Import** button
3. Select all `.postman_collection.json` files from this directory
4. Click **Import**

### 2. Create Environment
1. Click **Environments** in Postman
2. Click **Create Environment**
3. Name it "2Earn Development" or "2Earn Production"
4. Add the variables listed above
5. Set initial values and current values
6. Click **Save**

### 3. Select Environment
- Use the environment dropdown in top-right corner
- Select your created environment

### 4. Test Endpoints
- Open any collection
- Select an endpoint
- Click **Send**
- Review the response

---

## 📝 API Route Structure

All routes follow the pattern:
```
{{base_url}}/api/{module}/{resource}/{action}
```

### Modules
- `v1` - Core authenticated endpoints
- `v2` - Version 2 public endpoints
- `partner` - Partner management endpoints
- `mobile` - Mobile app endpoints
- `admin` - Admin management endpoints
- `order` - Order simulation endpoints

---

## 🔍 Middleware Information

### Authentication Middleware
- **`auth:sanctum`**: Laravel Sanctum token authentication
- Used in: V1 Authenticated API

### Custom Middleware
- **`check.url`**: Custom URL validation middleware
- Used in: Partner APIs, Mobile APIs, Order APIs
- Validates request origin and permissions

### No Authentication
- Balance Operations API v2 (Public)
- Some admin endpoints (protected by other means)

---

## 📊 Testing Tips

### 1. Authentication Flow
```
1. Login/Register → Get access_token
2. Set access_token in environment
3. Test authenticated endpoints
```

### 2. Partner Workflow
```
1. Create Platform → Get platform_id
2. Create Deal → Get deal_id
3. Add Items to Deal
4. Create Orders
5. Track Sales Dashboard
```

### 3. Balance Operations
```
1. Get Categories
2. Create Operation
3. Filter Operations
4. Get Operation Details
5. Update/Delete as needed
```

---

## 🐛 Troubleshooting

### Common Issues

**401 Unauthorized**
- Check if access_token is set correctly
- Verify token hasn't expired
- Ensure Bearer prefix is included

**404 Not Found**
- Verify base_url is correct
- Check API prefix is included (`/api`)
- Confirm resource exists (check IDs)

**422 Unprocessable Entity**
- Review request body structure
- Check required fields
- Validate data types

**500 Internal Server Error**
- Check server logs
- Verify database connections
- Review middleware configurations

---

## 📅 Version History

- **v1.0** (Feb 9, 2026) - Initial collection generation
  - Created 12 comprehensive collections
  - Covered all API controllers
  - Added proper /api prefix to all endpoints
  - Organized by modules and resources

---

## 📞 Support

For API documentation issues or questions:
- Check Laravel routes: `php artisan route:list --path=api`
- Review controller documentation in `/app/Http/Controllers/Api`
- Check middleware in `/app/Http/Middleware`

---

## 📄 License

These Postman collections are part of the 2Earn platform and follow the same license as the main application.

---

**Generated**: February 9, 2026
**Total Endpoints**: 130+ endpoints across all collections
**Platform**: 2Earn API v1 & v2

