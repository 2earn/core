# API v2 Services - Quick Reference

## CommunicationBoardService Endpoints

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/api/v2/communication-board` | Get all communication board items |
| GET | `/api/v2/communication-board/all` | Get all items (alias) |

---

## CommissionBreakDownService Endpoints

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/api/v2/commission-breakdowns/:id` | Get commission breakdown by ID |
| GET | `/api/v2/commission-breakdowns/by-deal?deal_id={id}` | Get breakdowns by deal ID |
| GET | `/api/v2/commission-breakdowns/deals/:dealId/totals` | Calculate totals for a deal |
| POST | `/api/v2/commission-breakdowns` | Create new breakdown |
| PUT | `/api/v2/commission-breakdowns/:id` | Update breakdown |
| DELETE | `/api/v2/commission-breakdowns/:id` | Delete breakdown |

---

## EventService Endpoints

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/api/v2/events` | Get all events (paginated) |
| GET | `/api/v2/events/all` | Get all events (non-paginated) |
| GET | `/api/v2/events/enabled` | Get enabled events only |
| GET | `/api/v2/events/:id` | Get event by ID |
| GET | `/api/v2/events/:id/with-relationships` | Get event with relationships |
| GET | `/api/v2/events/:id/with-main-image` | Get event with main image |
| GET | `/api/v2/events/:id/has-user-liked?user_id={id}` | Check if user liked event |
| POST | `/api/v2/events` | Create new event |
| PUT | `/api/v2/events/:id` | Update event |
| DELETE | `/api/v2/events/:id` | Delete event |
| POST | `/api/v2/events/:id/like` | Add like to event |
| DELETE | `/api/v2/events/:id/like` | Remove like from event |
| POST | `/api/v2/events/:id/comment` | Add comment to event |

---

## Files Created

### Controllers
- `app/Http/Controllers/Api/v2/CommunicationBoardController.php`
- `app/Http/Controllers/Api/v2/CommissionBreakDownController.php`
- `app/Http/Controllers/Api/v2/EventController.php`

### Routes
- Updated `routes/api.php` with v2 routes

### Documentation
- `postman/2Earn_API_v2_CommunicationBoard_CommissionBreakDown_Event_Services_Collection.json`
- `ai generated docs/API_V2_COMMUNICATION_SERVICES_IMPLEMENTATION.md`

---

## Postman Collection Location
```
/postman/2Earn_API_v2_CommunicationBoard_CommissionBreakDown_Event_Services_Collection.json
```

---

## Response Format

**Success:**
```json
{
  "status": true,
  "data": { /* data */ }
}
```

**Error:**
```json
{
  "status": false,
  "message": "Error message"
}
```

---

## Common Query Parameters

### Pagination
- `per_page` - Items per page (1-100, default: 10)
- `search` - Search term

### Ordering (Commission Breakdowns)
- `order_by` - Field to order by (id, created_at, commission_value, camembert)
- `order_direction` - ASC or DESC

---

*For detailed documentation, see: `ai generated docs/API_V2_COMMUNICATION_SERVICES_IMPLEMENTATION.md`*

