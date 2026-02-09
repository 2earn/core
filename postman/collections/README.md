# 2Earn API Postman Collections

This directory contains comprehensive Postman collections for all API endpoints in the 2Earn platform. All API endpoints are prefixed with `/api` as per the application's routing structure.

## 📋 Directory Structure

Collections are organized to mirror the `app/Http/Controllers/Api` structure:

```
postman/collections/
├── Mobile/             → app/Http/Controllers/Api/mobile/
│   └── Mobile Balance API.postman_collection.json
│
├── Partner/            → app/Http/Controllers/Api/partner/
│   ├── Partner Deals API.postman_collection.json
│   ├── Partner Items API.postman_collection.json
│   ├── Partner Orders API.postman_collection.json
│   ├── Partner Payments API.postman_collection.json
│   ├── Partner Platforms API.postman_collection.json
│   ├── Partner Role Requests API.postman_collection.json
│   ├── Partner Sales Dashboard API.postman_collection.json
│   ├── Partner Users API.postman_collection.json
│   └── Platform Change Request API.postman_collection.json
│
├── V1/                 → app/Http/Controllers/ (V1 APIs)
│   └── V1 Authenticated API.postman_collection.json
│
├── V2/                 → app/Http/Controllers/Api/v2/
│   └── Balance Operations API v2.postman_collection.json
│
└── Payment/            → app/Http/Controllers/Api/payment/
    (Use existing "2Earn - Payment & Order Simulation API")
```

---

## 📦 Collection Overview

**Total Collections**: 12 organized collections
**Total Endpoints**: 130+ API endpoints
**Structure**: Mirrors `app/Http/Controllers/Api`

**Notes**: 
- Platform Change Request API has been moved to Partner module as the controller is now located in `app/Http/Controllers/Api/partner/`
- Balance Operations API v2 mirrors the controller in `app/Http/Controllers/Api/v2/`

---

## 🔐 Authentication

### V1 Authenticated API (Sanctum)
- **Location**: `V1/V1 Authenticated API.postman_collection.json`
- **Prefix**: `/api/v1/`
- **Authentication**: Bearer Token (Laravel Sanctum)
- **Header**: `Authorization: Bearer {{access_token}}`

### Partner APIs
- **Location**: `Partner/` directory
- **Prefix**: `/api/partner/`
- **Middleware**: `check.url` (Custom URL validation)
- **Authentication**: Not required (middleware-based security)

### Mobile APIs
- **Location**: `Mobile/` directory
- **Prefix**: `/api/mobile/`
- **Middleware**: `check.url`
- **Authentication**: Not required (middleware-based security)

### Balance Operations v2
- **Location**: `V2/` directory
- **Prefix**: `/api/v2/balance/operations`
- **Authentication**: Public API (No authentication required)

---

## 📁 Module Details


### 📱 Mobile Module (`Mobile/`)

**Controllers Covered**: `mobile/BalanceController.php`, `mobile/CashBalanceController.php`, `mobile/UserController.php`

#### Mobile Balance API
**File**: `Mobile/Mobile Balance API.postman_collection.json`
- Get user balances
- Get cash balance
- Create/add cash balance

**Endpoints**: 3 | **Base URL**: `/api/mobile/`

---

### 🤝 Partner Module (`Partner/`)

**Controllers Covered**: All 11 partner controllers

#### 1. Partner Platforms API
**File**: `Partner/Partner Platforms API.postman_collection.json`
- Get top selling platforms
- Platform roles management
- Change platform type
- Validation workflows
- CRUD operations

**Endpoints**: 10 | **Base URL**: `/api/partner/platforms`

#### 2. Partner Deals API
**File**: `Partner/Partner Deals API.postman_collection.json`
- Deal CRUD operations
- Change deal status
- Validation workflows
- Dashboard indicators
- Performance charts
- Product change tracking

**Endpoints**: 13 | **Base URL**: `/api/partner/deals`

#### 3. Partner Orders API
**File**: `Partner/Partner Orders API.postman_collection.json`
- Order CRUD operations
- Change order status
- Order details management

**Endpoints**: 7 | **Base URL**: `/api/partner/orders`

#### 4. Partner Items API
**File**: `Partner/Partner Items API.postman_collection.json`
- Item CRUD operations
- Platform associations
- Deal associations (bulk)

**Endpoints**: 8 | **Base URL**: `/api/partner/items`

#### 5. Partner Sales Dashboard API
**File**: `Partner/Partner Sales Dashboard API.postman_collection.json`
- KPIs and metrics
- Sales evolution charts
- Top products & deals
- Transaction lists

**Endpoints**: 6 | **Base URL**: `/api/partner/sales/dashboard`

#### 6. Partner Payments API
**File**: `Partner/Partner Payments API.postman_collection.json`
- Payment lists & details
- Create payment demands
- Statistics summary

**Endpoints**: 4 | **Base URL**: `/api/partner/payments`

#### 7. Partner Role Requests API
**File**: `Partner/Partner Role Requests API.postman_collection.json`
- List & get role requests
- Create requests
- Cancel requests

**Endpoints**: 4 | **Base URL**: `/api/partner/role-requests`

#### 8. Partner Users API
**File**: `Partner/Partner Users API.postman_collection.json`
- User information
- Platform roles (add/update/delete)
- Discount balance
- Plan labels

**Endpoints**: 7 | **Base URL**: `/api/partner/users`

#### 9. Platform Change Request API
**File**: `Partner/Platform Change Request API.postman_collection.json`
- Get pending change requests
- List all change requests with filters
- Get request by ID
- Get statistics

**Endpoints**: 4 | **Base URL**: `/api/partner/platform-change-requests`

---

### 🔑 V1 Module (`V1/`)

**Controllers Covered**: All V1 authenticated controllers

#### V1 Authenticated API
**File**: `V1/V1 Authenticated API.postman_collection.json`

Comprehensive collection with 50+ endpoints organized in folders:
- **Countries & Settings** (3 endpoints)
- **Action History** (1 endpoint)
- **User Balances** (7 endpoints)
- **Shares/Actions** (10 endpoints)
- **Notifications** (1 endpoint)
- **Coupons** (6 endpoints)
- **Platforms & Deals** (2 endpoints)
- **Roles & Requests** (3 endpoints)
- **User Data** (5 endpoints)
- **Target & Analytics** (2 endpoints)
- **Transfers & Balance Operations** (2 endpoints)
- **VIP & SMS** (2 endpoints)
- **Payment Notifications** (1 endpoint)

**Endpoints**: 50+ | **Base URL**: `/api/v1/`

---

### 🔄 V2 Module (`V2/`)

**Controllers Covered**: `v2/BalancesOperationsController.php`

#### Balance Operations API v2
**File**: `V2/Balance Operations API v2.postman_collection.json`
- Get operations (DataTables)
- Filtered operations with search
- Get all operations
- Category management
- CRUD operations

**Endpoints**: 9 | **Base URL**: `/api/v2/balance/operations`

---

## 🚀 Getting Started

### 1. Import Collections

**Option A: Import Entire Directory**
1. Open Postman
2. Click **Import** button
3. Select **Folder** tab
4. Choose `C:\laragon\www\2earn\postman\collections`
5. Click **Import** (will import all subdirectories)

**Option B: Import by Module**
1. Open Postman
2. Click **Import** button
3. Navigate to specific module folder (e.g., `Partner/`)
4. Select collection files
5. Click **Import**

### 2. Create Environment

1. Click **Environments** in Postman
2. Click **Create Environment**
3. Name it "2Earn Development" or "2Earn Production"
4. Add variables (see below)
5. Click **Save**

### 3. Environment Variables

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
  "change_request_id": "1",
  "idAmounts": "1",
  "idUser": "1",
  "transaction_id": "TXN123456"
}
```

### 4. Select Environment & Test

1. Use environment dropdown (top-right)
2. Select your environment
3. Open any collection
4. Select an endpoint
5. Click **Send**

---

## 📊 Controller to Collection Mapping

### Mobile Controllers
| Controller | Collection | Location |
|------------|-----------|----------|
| `BalanceController.php` | Mobile Balance API | `Mobile/` |
| `CashBalanceController.php` | Mobile Balance API | `Mobile/` |
| `UserController.php` | Mobile Balance API | `Mobile/` |

### Partner Controllers
| Controller | Collection | Location |
|------------|-----------|----------|
| `DealPartnerController.php` | Partner Deals API | `Partner/` |
| `DealProductChangeController.php` | Partner Deals API | `Partner/` |
| `ItemsPartnerController.php` | Partner Items API | `Partner/` |
| `OrderDetailsPartnerController.php` | Partner Orders API | `Partner/` |
| `OrderPartnerController.php` | Partner Orders API | `Partner/` |
| `PartnerPaymentController.php` | Partner Payments API | `Partner/` |
| `PartnerRolePartnerController.php` | Partner Role Requests API | `Partner/` |
| `PlanLabelPartnerController.php` | Partner Users API | `Partner/` |
| `PlatformPartnerController.php` | Partner Platforms API | `Partner/` |
| `PlatformChangeRequestController.php` | Platform Change Request API | `Partner/` |
| `SalesDashboardController.php` | Partner Sales Dashboard API | `Partner/` |
| `UserPartnerController.php` | Partner Users API | `Partner/` |

### Payment Controllers
| Controller | Collection | Location |
|------------|-----------|----------|
| `OrderSimulationController.php` | 2Earn - Payment & Order Simulation API | Existing |

### V2 Controllers
| Controller | Collection | Location |
|------------|-----------|----------|
| `v2/BalancesOperationsController.php` | Balance Operations API v2 | `V2/` |

---

## 🔍 API Route Structure

All routes follow the pattern:
```
{{base_url}}/api/{module}/{resource}/{action}
```

### Route Modules
- **`partner`** → Partner APIs in `Partner/` directory
- **`mobile`** → Mobile APIs in `Mobile/` directory
- **`v1`** → Authenticated V1 APIs in `V1/` directory
- **`v2`** → Public V2 APIs in `V2/` directory
- **`order`** → Order simulation APIs (existing collections)

---

## 📝 Workflow Examples

### Partner Deal Creation Workflow
```
1. Create Platform (Partner/Partner Platforms API)
   POST /api/partner/platforms/platforms

2. Create Deal (Partner/Partner Deals API)
   POST /api/partner/deals/deals

3. Add Items (Partner/Partner Items API)
   POST /api/partner/items/deal/add-bulk

4. Submit for Validation (Partner/Partner Deals API)
   POST /api/partner/deals/validate

5. Monitor Performance (Partner/Partner Sales Dashboard API)
   GET /api/partner/sales/dashboard/kpis
```

### Mobile Balance Check Workflow
```
1. Get User (V1/V1 Authenticated API)
   GET /api/partner/users?user_id={{user_id}}

2. Get Balances (Mobile/Mobile Balance API)
   GET /api/mobile/balances?user_id={{user_id}}

3. Get Cash Balance (Mobile/Mobile Balance API)
   GET /api/mobile/cash-balance?user_id={{user_id}}
```

---

## 🐛 Troubleshooting

### Common Issues

**401 Unauthorized**
- Verify `access_token` is set in environment
- Check token hasn't expired
- Ensure `Bearer ` prefix is included

**404 Not Found**
- Confirm `base_url` is correct
- Verify `/api` prefix is in path
- Check resource ID exists

**422 Unprocessable Entity**
- Review request body structure
- Verify required fields are present
- Check data type validations

**500 Internal Server Error**
- Review server logs
- Check database connections
- Verify middleware configurations

---

## 🔧 Maintenance

### Adding New Endpoints
1. Identify the controller module (Admin, Mobile, Partner, etc.)
2. Navigate to corresponding directory
3. Open the relevant collection
4. Add new request to appropriate folder
5. Document parameters and body

### Creating New Collections
1. Determine the API module
2. Create collection in appropriate directory:
   - `Admin/` for admin controllers
   - `Mobile/` for mobile controllers
   - `Partner/` for partner controllers
   - `V1/` for v1 authenticated APIs
   - `V2/` for v2 public APIs
3. Follow naming convention: `[Module] [Feature] API`
4. Update this README

---

## 📄 Additional Resources

### Laravel Routes
```bash
# View all API routes
php artisan route:list --path=api

# View specific module routes
php artisan route:list --path=api/partner
php artisan route:list --path=api/admin
```

### Controller Locations
- **Admin**: `app/Http/Controllers/Api/Admin/`
- **Mobile**: `app/Http/Controllers/Api/mobile/`
- **Partner**: `app/Http/Controllers/Api/partner/`
- **Payment**: `app/Http/Controllers/Api/payment/`

---

## 📊 Statistics

- **Total Collections**: 12
- **Total Endpoints**: 130+
- **Total Size**: ~73 KB
- **Modules Covered**: 5 (Admin, Mobile, Partner, V1, V2)
- **Controllers Covered**: 16+
- **Organization**: 100% mirrors controller structure

---

## 📅 Version History

- **v1.1** (Feb 9, 2026) - Reorganized to mirror controller structure
  - Created module directories (Admin, Mobile, Partner, V1, V2)
  - Moved collections to appropriate directories
  - Updated documentation with directory structure
  
- **v1.0** (Feb 9, 2026) - Initial collection generation
  - Created 12 comprehensive collections
  - Covered all API controllers
  - Added proper /api prefix to all endpoints

---

## 📞 Support

For API documentation issues or questions:
- Check Laravel routes: `php artisan route:list --path=api`
- Review controller documentation in `/app/Http/Controllers/Api`
- Check middleware in `/app/Http/Middleware`
- Refer to module-specific collections in organized directories

---

## 📄 License

These Postman collections are part of the 2Earn platform and follow the same license as the main application.

---

**Last Updated**: February 9, 2026  
**Structure**: Organized by module  
**Total Endpoints**: 130+ across all collections  
**Platform**: 2Earn API v1 & v2

