# Remove Platform from Item API Endpoint - Documentation

## Overview
This document describes the endpoint that removes the platform_id association from an item, setting it to null while keeping the item itself intact.

## Endpoint Details
**Route:** `DELETE /api/partner/items/{id}/platform`

**Route Name:** `api_partner_items_remove_platform`

**Controller Method:** `ItemsPartnerController@removePlatformFromItem`

**Middleware:** `check.url` (IP validation)

## Request Parameters

### URL Parameters
- `id` (required, integer)
  - The ID of the item to remove the platform from

### Request Body
- `updated_by` (required, integer, exists in users table)
  - The ID of the user performing the update

## Request Example

```bash
# Remove platform from item
DELETE /api/partner/items/123/platform
Content-Type: application/json

{
    "updated_by": 1
}
```

## Response Format

### Success Response (200 - OK)
```json
{
    "status": "Success",
    "message": "Platform removed from item successfully",
    "data": {
        "id": 123,
        "name": "Test Item",
        "ref": "ITEM-001",
        "platform_id": null,
        "old_platform_id": 5
    }
}
```

### Error Responses

#### Item Not Found (404 - NOT FOUND)
```json
{
    "status": "Failed",
    "message": "Item not found"
}
```

#### Validation Failed (422 - UNPROCESSABLE ENTITY)
```json
{
    "status": "Failed",
    "message": "Validation failed",
    "errors": {
        "updated_by": [
            "The updated by field is required."
        ]
    }
}
```

#### Server Error (500 - INTERNAL SERVER ERROR)
```json
{
    "status": "Failed",
    "message": "Failed to remove platform from item",
    "error": "Detailed error message"
}
```

#### Unauthorized IP (403 - FORBIDDEN)
```json
{
    "error": "Unauthorized. Invalid IP."
}
```

## Business Logic

1. **Validation:**
   - Validates that `updated_by` is provided and exists in the users table
   - Validates that the item exists

2. **Platform Removal:**
   - Stores the old platform_id before removal
   - Sets platform_id to null
   - Records the updated_by user

3. **Logging:**
   - Logs successful platform removal with item_id, old_platform_id, and updated_by
   - Logs errors with full stack trace

4. **Idempotent Operation:**
   - Can be called multiple times on the same item
   - If platform_id is already null, operation still succeeds
   - Returns old_platform_id as null in this case

## Use Cases

1. **Unassign Item from Platform:**
   - Remove platform association while keeping the item in the system
   - Useful when reorganizing items or correcting assignments

2. **Platform Migration:**
   - Part of the process when moving items between platforms
   - First remove old platform, then assign new platform

3. **Cleanup Operations:**
   - Remove platform associations for items that should become platform-independent

## Testing

The endpoint is covered by the following tests in `ItemsPartnerControllerTest.php`:

- `test_can_remove_platform_from_item` - Successful removal
- `test_remove_platform_from_item_not_found` - Item doesn't exist
- `test_remove_platform_from_item_fails_without_updated_by` - Missing required field
- `test_remove_platform_from_item_with_invalid_user` - Invalid user ID
- `test_can_remove_platform_from_item_already_without_platform` - Idempotent operation

## Related Endpoints

- `GET /api/partner/items/{id}` - Show item details (including platform assignment)
- `PUT /api/partner/items/{id}` - Update item (can also update platform_id)
- `GET /api/partner/items` - List items with platform filter

## Security

- **IP Validation:** Endpoint requires valid IP address (configured via `check.url` middleware)
- **User Validation:** Requires valid user ID for audit trail
- **No Authentication Token:** Currently uses IP-based authentication

## Notes

- The operation does not delete the item, only removes the platform association
- The old platform_id is returned in the response for audit purposes
- The operation updates the `updated_at` timestamp of the item
- Logs are created for both success and failure cases with appropriate detail level
