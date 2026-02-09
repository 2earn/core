# User Balances API v2 - Quick Reference

## Base URL
```
/api/v2/user-balances/
```

## Horizontal Balance Endpoints

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/horizontal/{userId}` | Get complete balance record |
| GET | `/horizontal/{userId}/field/{field}` | Get specific field value |
| GET | `/horizontal/{userId}/cash` | Get cash balance |
| GET | `/horizontal/{userId}/bfss/{type}` | Get BFSS balance by type |
| GET | `/horizontal/{userId}/discount` | Get discount balance |
| GET | `/horizontal/{userId}/tree` | Get tree balance |
| GET | `/horizontal/{userId}/sms` | Get SMS balance |
| PUT | `/horizontal/{userId}/calculated` | Update calculated balance |
| PUT | `/horizontal/{userId}/field` | Update balance field |
| POST | `/horizontal/{userId}/calculate` | Calculate new balance (simulation) |

## Vertical Balance Endpoints

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/vertical/{userId}/all` | Get all vertical balances |
| GET | `/vertical/{userId}/{balanceId}` | Get balance by type ID |
| PUT | `/vertical/{userId}/update-after-operation` | Update after operation |
| PUT | `/vertical/{userId}/calculated` | Update calculated balance |

## Balance Type IDs

| ID | Type | Description |
|----|------|-------------|
| 1 | CASH | Cash balance |
| 2 | BFSS | Business First Sharing System |
| 3 | DISCOUNT | Discount balance |
| 4 | TREE | Tree/referral balance |
| 5 | SMS | SMS credits |
| 6 | SHARE | Share balance |
| 7 | CHANCE | Chance/lottery balance |

## Quick Examples

### Get Cash Balance
```bash
GET /api/v2/user-balances/horizontal/1/cash
```

### Update Cash Balance
```bash
PUT /api/v2/user-balances/horizontal/1/field
Content-Type: application/json

{
  "balance_field": "cash_balance",
  "new_balance": 1500.50
}
```

### Calculate New Balance
```bash
POST /api/v2/user-balances/horizontal/1/calculate
Content-Type: application/json

{
  "balance_field": "cash_balance",
  "change_amount": 100.00
}
```

### Get All Vertical Balances
```bash
GET /api/v2/user-balances/vertical/1/all
```

### Update Vertical Balance After Operation
```bash
PUT /api/v2/user-balances/vertical/1/update-after-operation
Content-Type: application/json

{
  "balance_id": 1,
  "balance_change": 50.00,
  "last_operation_id": 123,
  "last_operation_value": 50.00,
  "last_operation_date": "2026-02-09 10:30:00"
}
```

## Response Format

### Success
```json
{
  "status": true,
  "data": { ... }
}
```

### Error
```json
{
  "status": false,
  "message": "Error description"
}
```

### Validation Error
```json
{
  "status": false,
  "errors": {
    "field": ["Error message"]
  }
}
```

## Services Exposed

1. **UserCurrentBalanceHorisontalService** - Single row per user
2. **UserCurrentBalanceVerticalService** - Multiple rows per user with operation history
3. **UserBalancesHelper** - Balance operation helpers

## Files Created

- Controller: `app/Http/Controllers/Api/v2/UserBalancesController.php`
- Routes: Added to `routes/api.php` under `/v2/user-balances/`
- Postman Collection: `postman/User_Balances_API_v2_Collection.json`
- Full Documentation: `ai generated docs/API_V2_USER_BALANCES_DOCUMENTATION.md`

