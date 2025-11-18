# Platform Type Change - Quick Reference

## 🎯 Endpoint
```
POST /api/partner/platform/change
```

## 📋 Request Parameters
- `platform_id` (required, integer) - Platform ID
- `type_id` (required, integer, 1|2|3) - New type ID

## 🔄 Type Transition Rules

```
Type 3 (Paiement) ──→ Type 1 (Full) ✅
Type 3 (Paiement) ──→ Type 2 (Hybrid) ✅
Type 2 (Hybrid) ────→ Type 1 (Full) ✅
Type 2 (Hybrid) ────→ Type 3 (Paiement) ❌
Type 1 (Full) ──────→ Any Type ❌ (Locked)
```

## 📝 Example Request
```bash
curl -X POST "http://localhost/api/partner/platform/change" \
  -H "Content-Type: application/json" \
  -d '{
    "platform_id": 123,
    "type_id": 2
  }'
```

## ✅ Success Response (201)
```json
{
  "status": true,
  "message": "Platform type change request created successfully",
  "data": {
    "id": 1,
    "platform_id": 123,
    "old_type": 3,
    "new_type": 2,
    "status": "pending",
    "created_at": "2025-11-18T14:06:38.000000Z",
    "updated_at": "2025-11-18T14:06:38.000000Z"
  }
}
```

## ❌ Error Responses

### Validation Error (422)
```json
{
  "status": "Failed",
  "message": "Validation failed",
  "errors": {
    "platform_id": ["The platform id field is required."]
  }
}
```

### Platform Not Found (404)
```json
{
  "status": "Failed",
  "message": "Platform not found"
}
```

### Type 1 Locked (403)
```json
{
  "status": "Failed",
  "message": "Type 1 (Full) platforms cannot change their type"
}
```

### Invalid Transition (403)
```json
{
  "status": "Failed",
  "message": "Type 2 platforms can only change to types: 1"
}
```

## 🗄️ Database Table
```
platform_type_change_requests
├── id
├── platform_id (FK to platforms)
├── old_type
├── new_type
├── status (default: 'pending')
├── created_at
└── updated_at
```

## 📂 Files
- **Migration:** `database/migrations/2025_11_18_140638_create_platform_type_change_requests_table.php`
- **Model:** `app/Models/PlatformTypeChangeRequest.php`
- **Controller:** `app/Http/Controllers/Api/partner/PlatformPartnerController.php`
- **Route:** `routes/api.php`

## 🔍 Testing Checklist
- [ ] Type 3 → Type 1 (should succeed)
- [ ] Type 3 → Type 2 (should succeed)
- [ ] Type 2 → Type 1 (should succeed)
- [ ] Type 2 → Type 3 (should fail with 403)
- [ ] Type 1 → Any (should fail with 403)
- [ ] Same type change (should fail with 422)
- [ ] Invalid platform_id (should fail with 404)
- [ ] Missing parameters (should fail with 422)

## 🔐 Security
- Uses `check.url` middleware
- No authentication required (partner API)
- Input validation
- SQL injection prevention (Eloquent)
- Foreign key constraints

## 📊 Status Values
- `pending` - Initial state (default)
- `approved` - Request approved (future)
- `rejected` - Request rejected (future)
- `completed` - Change applied (future)

