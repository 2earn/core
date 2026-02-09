# Balance Operation Service API Endpoints - Quick Start

## 🚀 Overview

All methods from the `BalanceOperationService` have been successfully exposed as RESTful API endpoints. The implementation follows Laravel best practices with proper validation, error handling, and authentication.

## 📁 Files Modified/Created

### Modified Files
1. **`app/Http/Controllers/BalancesOperationsController.php`**
   - Added dependency injection for `BalanceOperationService`
   - Implemented 8 new API endpoint methods
   - Added comprehensive validation and error handling

2. **`routes/api.php`**
   - Added 7 new RESTful routes
   - Kept 2 existing routes for backward compatibility

### Created Files
1. **`ai generated docs/BALANCE_OPERATION_API_ENDPOINTS.md`** - Complete API documentation
2. **`ai generated docs/BALANCE_OPERATION_API_IMPLEMENTATION.md`** - Implementation summary
3. **`ai generated docs/Balance_Operation_API.postman_collection.json`** - Postman collection for testing
4. **`tests/Feature/Api/BalanceOperationApiTest.php`** - Comprehensive test suite
5. **`ai generated docs/BALANCE_OPERATION_API_README.md`** - This file

## 🔗 API Endpoints

All endpoints are under `/api/v1/balance/operations` and require authentication via Laravel Sanctum.

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/` | Get operations (DataTables format) |
| GET | `/filtered` | Get filtered/paginated operations |
| GET | `/all` | Get all operations |
| GET | `/{id}` | Get single operation by ID |
| POST | `/` | Create new operation |
| PUT | `/{id}` | Update operation |
| DELETE | `/{id}` | Delete operation |
| GET | `/category/{categoryId}/name` | Get category name |
| GET | `/categories` | Get categories (DataTables) |

## 🔐 Authentication

All endpoints require a Sanctum bearer token:

```bash
Authorization: Bearer YOUR_SANCTUM_TOKEN
```

## 📝 Quick Examples

### Get Filtered Operations
```bash
curl -X GET "http://localhost/api/v1/balance/operations/filtered?search=transfer&per_page=20" \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Accept: application/json"
```

### Create Operation
```bash
curl -X POST "http://localhost/api/v1/balance/operations" \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "operation": "Transfer",
    "io": "I",
    "source": "system",
    "note": "Test transfer"
  }'
```

### Update Operation
```bash
curl -X PUT "http://localhost/api/v1/balance/operations/1" \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "note": "Updated note"
  }'
```

### Delete Operation
```bash
curl -X DELETE "http://localhost/api/v1/balance/operations/1" \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Accept: application/json"
```

## 🧪 Testing

### Using Postman
1. Import the collection: `ai generated docs/Balance_Operation_API.postman_collection.json`
2. Set your `api_token` variable
3. Update `base_url` if needed
4. Start testing!

### Using PHPUnit
```bash
php artisan test --filter=BalanceOperationApiTest
```

## 📊 Validation Rules

### Create Operation (POST)
- `operation` - **required**, string, max 255
- `io` - optional, string
- `source` - optional, string
- `mode` - optional, string
- `amounts_id` - optional, integer
- `note` - optional, string
- `modify_amount` - optional, boolean
- `parent_id` - optional, integer, must exist in balance_operations
- `operation_category_id` - optional, integer, must exist in operation_categories

### Update Operation (PUT)
Same fields as create, but all are optional.

## 🔍 Search Functionality

The `/filtered` endpoint searches across:
- `id`
- `operation`
- `balance_id`
- `parent_operation_id`

## 📦 Response Formats

### Success Response (200/201)
```json
{
  "id": 1,
  "operation": "Transfer",
  "io": "I",
  "source": "system",
  "note": "Test note",
  "parent": {
    "id": 10,
    "operation": "Parent Operation"
  },
  "opeartionCategory": {
    "id": 1,
    "name": "Transfer"
  },
  "created_at": "2026-02-09T10:00:00.000000Z",
  "updated_at": "2026-02-09T10:00:00.000000Z"
}
```

### Error Response (404)
```json
{
  "message": "Operation not found"
}
```

### Validation Error (422)
```json
{
  "message": "The given data was invalid.",
  "errors": {
    "operation": [
      "The operation field is required."
    ]
  }
}
```

## 🎯 Service Methods Mapping

| Service Method | API Endpoint |
|---------------|--------------|
| `getFilteredOperations($search, $perPage)` | `GET /filtered` |
| `getOperationById($id)` | `GET /{id}` |
| `getAllOperations()` | `GET /all` |
| `createOperation($data)` | `POST /` |
| `updateOperation($id, $data)` | `PUT /{id}` |
| `deleteOperation($id)` | `DELETE /{id}` |
| `getOperationCategoryName($categoryId)` | `GET /category/{categoryId}/name` |

## 📚 Documentation

For detailed documentation, see:
- **API Reference**: `ai generated docs/BALANCE_OPERATION_API_ENDPOINTS.md`
- **Implementation Details**: `ai generated docs/BALANCE_OPERATION_API_IMPLEMENTATION.md`

## ✅ Verification

Run this command to verify all routes are registered:
```bash
php artisan route:list --path=api/v1/balance/operations
```

Expected output: 9 routes

## 🛠️ Next Steps

1. **Test the endpoints** using Postman or the provided test suite
2. **Add rate limiting** if needed for production
3. **Set up monitoring** for critical operations
4. **Review permissions** to ensure proper access control
5. **Consider adding bulk operations** for efficiency

## 📞 Support

For questions or issues:
1. Check the full documentation in `ai generated docs/BALANCE_OPERATION_API_ENDPOINTS.md`
2. Review the test file for usage examples
3. Check Laravel logs for detailed error messages

## 📅 Implementation Date
February 9, 2026

---

**Status**: ✅ Complete and Ready for Use

All service methods have been successfully exposed as API endpoints with proper authentication, validation, and error handling.

