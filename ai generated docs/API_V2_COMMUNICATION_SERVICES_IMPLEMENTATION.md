# API v2 Services Implementation - CommunicationBoard, CommissionBreakDown & Event

## Overview

This document describes the implementation of three services exposed as API endpoints under the v2 prefix:
- **CommunicationBoardService** - Manages communication board items (surveys, news, events)
- **CommissionBreakDownService** - Manages commission breakdowns for deals
- **EventService** - Manages events with likes and comments

All endpoints are accessible under the `/api/v2/` prefix.

---

## Table of Contents

1. [Communication Board Service](#communication-board-service)
2. [Commission Break Down Service](#commission-break-down-service)
3. [Event Service](#event-service)
4. [Postman Collection](#postman-collection)
5. [Response Format](#response-format)

---

## Communication Board Service

### Base URL
`/api/v2/communication-board`

### Endpoints

#### 1. Get All Communication Board Items
**GET** `/api/v2/communication-board`

Returns all communication board items (surveys, news, events) merged and sorted by creation date.

**Response:**
```json
{
  "status": true,
  "data": [
    {
      "type": "App\\Models\\Survey",
      "value": { /* survey object */ }
    },
    {
      "type": "App\\Models\\News",
      "value": { /* news object */ }
    },
    {
      "type": "App\\Models\\Event",
      "value": { /* event object */ }
    }
  ]
}
```

#### 2. Get All Communication Board Items (Alternative)
**GET** `/api/v2/communication-board/all`

Alias for the index endpoint.

---

## Commission Break Down Service

### Base URL
`/api/v2/commission-breakdowns`

### Endpoints

#### 1. Get Commission Breakdown by ID
**GET** `/api/v2/commission-breakdowns/:id`

**Parameters:**
- `id` (path, required) - Commission Breakdown ID

**Response:**
```json
{
  "status": true,
  "data": {
    "id": 1,
    "deal_id": 1,
    "order_id": 1,
    "type": 1,
    "trigger": 1,
    "new_turnover": 1000.00,
    "old_turnover": 500.00,
    "purchase_value": 300.00,
    "commission_percentage": 10.5,
    "commission_value": 31.50,
    "camembert": 100.00,
    "cash_company_profit": 20.00,
    "cash_jackpot": 5.00,
    "cash_tree": 3.50,
    "cash_cashback": 3.00,
    "deal_paid_amount": 300.00,
    "additional_amount": 0.00,
    "created_at": "2026-02-10 10:00:00",
    "updated_at": "2026-02-10 10:00:00"
  }
}
```

#### 2. Get Commission Breakdowns by Deal
**GET** `/api/v2/commission-breakdowns/by-deal`

**Query Parameters:**
- `deal_id` (required, integer) - Deal ID
- `order_by` (optional, string) - Order by field (id, created_at, commission_value, camembert). Default: `id`
- `order_direction` (optional, string) - Order direction (ASC, DESC). Default: `ASC`

**Example:**
```
GET /api/v2/commission-breakdowns/by-deal?deal_id=1&order_by=created_at&order_direction=DESC
```

**Response:**
```json
{
  "status": true,
  "data": [
    { /* commission breakdown object */ },
    { /* commission breakdown object */ }
  ]
}
```

#### 3. Calculate Commission Totals for Deal
**GET** `/api/v2/commission-breakdowns/deals/:dealId/totals`

**Parameters:**
- `dealId` (path, required) - Deal ID

**Response:**
```json
{
  "status": true,
  "data": {
    "jackpot": 50.00,
    "earn_profit": 200.00,
    "proactive_cashback": 30.00,
    "tree_remuneration": 35.00
  }
}
```

#### 4. Create Commission Breakdown
**POST** `/api/v2/commission-breakdowns`

**Request Body:**
```json
{
  "deal_id": 1,
  "order_id": 1,
  "type": 1,
  "trigger": 1,
  "new_turnover": 1000.00,
  "old_turnover": 500.00,
  "purchase_value": 300.00,
  "commission_percentage": 10.5,
  "commission_value": 31.50,
  "camembert": 100.00,
  "cash_company_profit": 20.00,
  "cash_jackpot": 5.00,
  "cash_tree": 3.50,
  "cash_cashback": 3.00,
  "deal_paid_amount": 300.00,
  "additional_amount": 0.00
}
```

**Validation Rules:**
- `deal_id` - required, integer, must exist in deals table
- `order_id` - nullable, integer, must exist in orders table
- `type` - required, integer
- `trigger` - nullable, integer
- All monetary fields - nullable, numeric

**Response:**
```json
{
  "status": true,
  "data": { /* created commission breakdown object */ },
  "message": "Commission breakdown created successfully"
}
```

#### 5. Update Commission Breakdown
**PUT** `/api/v2/commission-breakdowns/:id`

**Parameters:**
- `id` (path, required) - Commission Breakdown ID

**Request Body:** (all fields are optional)
```json
{
  "commission_percentage": 12.0,
  "commission_value": 36.00,
  "cash_company_profit": 22.00
}
```

**Response:**
```json
{
  "status": true,
  "message": "Commission breakdown updated successfully"
}
```

#### 6. Delete Commission Breakdown
**DELETE** `/api/v2/commission-breakdowns/:id`

**Parameters:**
- `id` (path, required) - Commission Breakdown ID

**Response:**
```json
{
  "status": true,
  "message": "Commission breakdown deleted successfully"
}
```

---

## Event Service

### Base URL
`/api/v2/events`

### Endpoints

#### 1. Get All Events (Paginated)
**GET** `/api/v2/events`

**Query Parameters:**
- `per_page` (optional, integer, 1-100) - Number of items per page. Default: `10`
- `search` (optional, string) - Search term for filtering events by title

**Example:**
```
GET /api/v2/events?per_page=20&search=conference
```

**Response:**
```json
{
  "status": true,
  "data": [
    {
      "id": 1,
      "title": "Annual Conference",
      "content": "Event content...",
      "enabled": 1,
      "published_at": "2026-02-15 10:00:00",
      "comments_count": 5,
      "likes_count": 10,
      "created_at": "2026-02-10 10:00:00",
      "updated_at": "2026-02-10 10:00:00"
    }
  ],
  "pagination": {
    "current_page": 1,
    "per_page": 10,
    "total": 50,
    "last_page": 5
  }
}
```

#### 2. Get All Events (Non-Paginated)
**GET** `/api/v2/events/all`

Returns all events without pagination, ordered by newest first.

**Response:**
```json
{
  "status": true,
  "data": [
    { /* event object */ },
    { /* event object */ }
  ]
}
```

#### 3. Get Enabled Events
**GET** `/api/v2/events/enabled`

Returns only enabled events.

**Response:**
```json
{
  "status": true,
  "data": [
    { /* enabled event object */ }
  ]
}
```

#### 4. Get Event by ID
**GET** `/api/v2/events/:id`

**Parameters:**
- `id` (path, required) - Event ID

**Response:**
```json
{
  "status": true,
  "data": {
    "id": 1,
    "title": "Event Title",
    "content": "Event content...",
    "enabled": 1,
    "published_at": "2026-02-15 10:00:00",
    "created_at": "2026-02-10 10:00:00",
    "updated_at": "2026-02-10 10:00:00"
  }
}
```

#### 5. Get Event with Relationships
**GET** `/api/v2/events/:id/with-relationships`

Returns event with related data: mainImage, likes, comments.user

**Parameters:**
- `id` (path, required) - Event ID

**Response:**
```json
{
  "status": true,
  "data": {
    "id": 1,
    "title": "Event Title",
    "content": "Event content...",
    "main_image": { /* image object */ },
    "likes": [
      { /* like object */ }
    ],
    "comments": [
      {
        "id": 1,
        "content": "Great event!",
        "user": { /* user object */ }
      }
    ]
  }
}
```

#### 6. Get Event with Main Image
**GET** `/api/v2/events/:id/with-main-image`

Returns event with main image relationship loaded.

**Parameters:**
- `id` (path, required) - Event ID

#### 7. Check if User Liked Event
**GET** `/api/v2/events/:id/has-user-liked`

**Parameters:**
- `id` (path, required) - Event ID

**Query Parameters:**
- `user_id` (required, integer) - User ID to check

**Example:**
```
GET /api/v2/events/1/has-user-liked?user_id=5
```

**Response:**
```json
{
  "status": true,
  "data": {
    "has_liked": true
  }
}
```

#### 8. Create Event
**POST** `/api/v2/events`

**Request Body:**
```json
{
  "title": "New Event Title",
  "content": "Event content goes here",
  "enabled": true,
  "published_at": "2026-02-15 10:00:00"
}
```

**Validation Rules:**
- `title` - required, string, max 255 characters
- `content` - required, string
- `enabled` - nullable, boolean
- `published_at` - nullable, date

**Response:**
```json
{
  "status": true,
  "data": { /* created event object */ },
  "message": "Event created successfully"
}
```

#### 9. Update Event
**PUT** `/api/v2/events/:id`

**Parameters:**
- `id` (path, required) - Event ID

**Request Body:** (all fields are optional)
```json
{
  "title": "Updated Event Title",
  "content": "Updated event content",
  "enabled": true
}
```

**Response:**
```json
{
  "status": true,
  "message": "Event updated successfully"
}
```

#### 10. Delete Event
**DELETE** `/api/v2/events/:id`

**Parameters:**
- `id` (path, required) - Event ID

**Response:**
```json
{
  "status": true,
  "message": "Event deleted successfully"
}
```

#### 11. Add Like to Event
**POST** `/api/v2/events/:id/like`

**Parameters:**
- `id` (path, required) - Event ID

**Request Body:**
```json
{
  "user_id": 1
}
```

**Validation Rules:**
- `user_id` - required, integer, must exist in users table

**Response:**
```json
{
  "status": true,
  "message": "Like added successfully"
}
```

#### 12. Remove Like from Event
**DELETE** `/api/v2/events/:id/like`

**Parameters:**
- `id` (path, required) - Event ID

**Request Body:**
```json
{
  "user_id": 1
}
```

**Response:**
```json
{
  "status": true,
  "message": "Like removed successfully"
}
```

#### 13. Add Comment to Event
**POST** `/api/v2/events/:id/comment`

**Parameters:**
- `id` (path, required) - Event ID

**Request Body:**
```json
{
  "user_id": 1,
  "content": "This is a comment on the event"
}
```

**Validation Rules:**
- `user_id` - required, integer, must exist in users table
- `content` - required, string

**Response:**
```json
{
  "status": true,
  "message": "Comment added successfully"
}
```

---

## Postman Collection

A complete Postman collection has been generated and saved to:
```
/postman/2Earn_API_v2_CommunicationBoard_CommissionBreakDown_Event_Services_Collection.json
```

### Import Instructions

1. Open Postman
2. Click "Import" button
3. Select the JSON file
4. Configure the `base_url` variable to match your environment (default: `http://localhost`)

### Collection Variables

The collection includes a variable that you can configure:
- `base_url` - The base URL of your API (default: `http://localhost`)

---

## Response Format

All endpoints follow a consistent response format:

### Success Response
```json
{
  "status": true,
  "data": { /* response data */ },
  "message": "Optional success message"
}
```

### Error Response (4xx, 5xx)
```json
{
  "status": false,
  "message": "Error message description",
  "errors": { /* validation errors if applicable */ }
}
```

### Validation Error Response (422)
```json
{
  "status": false,
  "errors": {
    "field_name": [
      "Validation error message"
    ]
  }
}
```

---

## Implementation Files

### Controllers
- `app/Http/Controllers/Api/v2/CommunicationBoardController.php`
- `app/Http/Controllers/Api/v2/CommissionBreakDownController.php`
- `app/Http/Controllers/Api/v2/EventController.php`

### Services
- `app/Services/CommunicationBoardService.php`
- `app/Services/CommissionBreakDownService.php`
- `app/Services/EventService.php`

### Routes
All routes are defined in `routes/api.php` under the `/api/v2/` prefix group.

---

## Route Names

All routes follow a naming convention for easy reference in Laravel:

### Communication Board
- `api_v2_communication_board_index`
- `api_v2_communication_board_all`

### Commission Breakdowns
- `api_v2_commission_breakdowns_show`
- `api_v2_commission_breakdowns_by_deal`
- `api_v2_commission_breakdowns_totals`
- `api_v2_commission_breakdowns_store`
- `api_v2_commission_breakdowns_update`
- `api_v2_commission_breakdowns_destroy`

### Events
- `api_v2_events_index`
- `api_v2_events_all`
- `api_v2_events_enabled`
- `api_v2_events_show`
- `api_v2_events_show_with_relationships`
- `api_v2_events_show_with_main_image`
- `api_v2_events_has_user_liked`
- `api_v2_events_store`
- `api_v2_events_add_like`
- `api_v2_events_remove_like`
- `api_v2_events_add_comment`
- `api_v2_events_update`
- `api_v2_events_destroy`

---

## Notes

1. **Authentication**: These routes are under the `withoutMiddleware([\App\Http\Middleware\Authenticate::class])` group, so authentication may need to be configured based on your requirements.

2. **Error Handling**: All controllers include try-catch blocks to handle exceptions gracefully.

3. **Service Layer**: The implementation follows the service layer pattern, keeping business logic separate from controllers.

4. **Validation**: Request validation is performed in controllers using Laravel's Validator facade.

5. **Logging**: Errors are logged in the service layer for debugging purposes.

---

## Testing

Use the provided Postman collection to test all endpoints. Make sure to:
1. Set the correct `base_url` variable
2. Update IDs in path parameters to match your database
3. Ensure required relationships exist (users, deals, orders) before testing

---

*Generated on February 10, 2026*

