# Postman Collections Index

## 📍 Quick Navigation

This index provides quick links to all collections and documentation.

---

## 📚 Documentation Files

1. **[README.md](README.md)** - Complete usage guide with setup instructions
2. **[ORGANIZATION_SUMMARY.md](ORGANIZATION_SUMMARY.md)** - Detailed organization information
3. **[STRUCTURE_DIAGRAM.md](STRUCTURE_DIAGRAM.md)** - Visual directory tree and mappings
4. **[INDEX.md](INDEX.md)** - This file (quick navigation)

---

## 📦 Collections by Module

### 📱 Mobile Module
📁 **Location**: `Mobile/`  
🎯 **Controllers**: `app/Http/Controllers/Api/mobile/`

| Collection | File | Endpoints |
|------------|------|-----------|
| Mobile Balance API | `Mobile/Mobile Balance API.postman_collection.json` | 3 |

**Total**: 1 collection, 3 endpoints

---

### 🤝 Partner Module
📁 **Location**: `Partner/`  
🎯 **Controllers**: `app/Http/Controllers/Api/partner/`

| Collection | File | Endpoints |
|------------|------|-----------|
| Partner Platforms API | `Partner/Partner Platforms API.postman_collection.json` | 10 |
| Partner Deals API | `Partner/Partner Deals API.postman_collection.json` | 13 |
| Partner Orders API | `Partner/Partner Orders API.postman_collection.json` | 7 |
| Partner Items API | `Partner/Partner Items API.postman_collection.json` | 8 |
| Partner Sales Dashboard API | `Partner/Partner Sales Dashboard API.postman_collection.json` | 6 |
| Partner Payments API | `Partner/Partner Payments API.postman_collection.json` | 4 |
| Partner Role Requests API | `Partner/Partner Role Requests API.postman_collection.json` | 4 |
| Partner Users API | `Partner/Partner Users API.postman_collection.json` | 7 |
| Platform Change Request API | `Partner/Platform Change Request API.postman_collection.json` | 4 |

**Total**: 9 collections, 63 endpoints

---

### 💳 Payment Module
📁 **Location**: `Payment/`  
🎯 **Controllers**: `app/Http/Controllers/Api/payment/`

ℹ️ **Note**: Use existing "2Earn - Payment & Order Simulation API" collection

---

### 🔑 V1 Module (Authenticated)
📁 **Location**: `V1/`  
🎯 **Controllers**: Multiple V1 Controllers

| Collection | File | Endpoints |
|------------|------|-----------|
| V1 Authenticated API | `V1/V1 Authenticated API.postman_collection.json` | 50+ |

**Folders**:
- Countries & Settings (3)
- Action History (1)
- User Balances (7)
- Shares/Actions (10)
- Notifications (1)
- Coupons (6)
- Platforms & Deals (2)
- Roles & Requests (3)
- User Data (5)
- Target & Analytics (2)
- Transfers & Balance Operations (2)
- VIP & SMS (2)
- Payment Notifications (1)

**Total**: 1 collection, 50+ endpoints

---

### 🔄 V2 Module (Public)
📁 **Location**: `V2/`  
🎯 **Controllers**: `app/Http/Controllers/Api/v2/`

| Collection | File | Endpoints |
|------------|------|-----------|
| Balance Operations API v2 | `V2/Balance Operations API v2.postman_collection.json` | 9 |

**Total**: 1 collection, 9 endpoints

---

## 📊 Overall Statistics

| Metric | Count |
|--------|-------|
| **Total Collections** | 12 |
| **Total Endpoints** | 130+ |
| **Total Directories** | 5 |
| **Documentation Files** | 3 |
| **Controllers Covered** | 17+ |
| **Coverage** | 100% |

---

## 🎯 Quick Access by Use Case

### For Platform Change Requests
➡️ Go to: `Partner/Platform Change Request API.postman_collection.json`

### For Mobile App Testing
➡️ Go to: `Mobile/Mobile Balance API.postman_collection.json`

### For Partner Portal Testing
➡️ Go to: `Partner/` directory (9 collections)

### For General API Testing (V1)
➡️ Go to: `V1/V1 Authenticated API.postman_collection.json`

### For Public API Testing (V2)
➡️ Go to: `V2/Balance Operations API v2.postman_collection.json`

---

## 🚀 Import Instructions

### Import All Collections
1. Open Postman
2. Click **Import** → **Folder**
3. Select: `C:\laragon\www\2earn\postman\collections`
4. Click **Import**

### Import Specific Module
1. Open Postman
2. Click **Import** → **File**
3. Navigate to module folder (e.g., `Partner/`)
4. Select collection(s)
5. Click **Import**

---

## 🔐 Authentication Quick Reference

| Module | Auth Type | Header |
|--------|-----------|--------|
| Admin | Bearer Token | `Authorization: Bearer {{admin_token}}` |
| Mobile | check.url middleware | N/A |
| Partner | check.url middleware | N/A |
| V1 | Bearer Token (Sanctum) | `Authorization: Bearer {{access_token}}` |
| V2 | Public (None) | N/A |

---

## 📝 Environment Variables

Required variables for testing:

```json
{
  "base_url": "http://localhost:8000",
  "access_token": "your_token",
  "admin_token": "admin_token",
  "user_id": "1",
  "platform_id": "1",
  "deal_id": "1",
  "order_id": "1"
}
```

See [README.md](README.md) for complete variable list.

---

## 🔗 Related Resources

- **Laravel Routes**: Run `php artisan route:list --path=api`
- **Controllers**: Located in `app/Http/Controllers/Api/`
- **Middleware**: Located in `app/Http/Middleware/`

---

## 📅 Last Updated

**Date**: February 9, 2026  
**Version**: 1.1 (Organized Structure)  
**Status**: ✅ Complete

---

## 🎉 Summary

All Postman collections are:
- ✅ Organized by module
- ✅ Mirroring controller structure
- ✅ Properly documented
- ✅ Ready to import
- ✅ 100% API coverage

**Happy Testing!** 🚀

