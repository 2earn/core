# 2Earn API v2 - OrderService & NewsService Documentation

## Overview

This document describes the REST API endpoints for **OrderService** and **NewsService** exposed under the `/api/v2/` prefix.

## Table of Contents

- [Base URL](#base-url)
- [Authentication](#authentication)
- [OrderService Endpoints](#orderservice-endpoints)
- [NewsService Endpoints](#newsservice-endpoints)
- [Postman Collection](#postman-collection)
- [Response Format](#response-format)
- [Error Handling](#error-handling)

---

## Base URL

```
http://your-domain.com/api/v2/
```

## Authentication

These endpoints are configured without the default Laravel authentication middleware. However, you may need to implement your own authentication mechanism based on your requirements.

---

## OrderService Endpoints

### 1. Get All Orders (Paginated)

**Endpoint:** `GET /api/v2/orders`

**Description:** Retrieve all orders with pagination.

**Query Parameters:**
- `per_page` (optional, integer): Number of items per page (default: 5, max: 100)

**Response:**
```json
{
  "status": true,
  "data": [...],
  "pagination": {
    "current_page": 1,
    "per_page": 5,
    "total": 50,
    "last_page": 10
  }
}
```

---

### 2. Get User Orders

**Endpoint:** `GET /api/v2/orders/users/{userId}`

**Description:** Get orders for a specific user with optional filters.

**Path Parameters:**
- `userId` (required, integer): User ID

**Query Parameters:**
- `page` (optional, integer): Page number
- `limit` (optional, integer): Items per page (default: 5, max: 100)
- `platform_id` (optional, integer): Filter by platform ID
- `status` (optional, string): Filter by order status
- `search` (optional, string): Search term

**Response:**
```json
{
  "status": true,
  "data": {
    "orders": [...],
    "total": 25
  }
}
```

---

### 3. Find User Order

**Endpoint:** `GET /api/v2/orders/users/{userId}/{orderId}`

**Description:** Find a specific order for a user.

**Path Parameters:**
- `userId` (required, integer): User ID
- `orderId` (required, integer): Order ID

**Response:**
```json
{
  "status": true,
  "data": {
    "id": 1,
    "user_id": 1,
    "platform_id": 1,
    "status": "Ready",
    ...
  }
}
```

---

### 4. Get Pending Orders Count

**Endpoint:** `GET /api/v2/orders/users/{userId}/pending-count`

**Description:** Get count of pending orders for a user by status.

**Path Parameters:**
- `userId` (required, integer): User ID

**Request Body:**
```json
{
  "statuses": ["Ready", "Simulated"]
}
```

**Response:**
```json
{
  "status": true,
  "count": 5
}
```

---

### 5. Get Orders By IDs

**Endpoint:** `GET /api/v2/orders/users/{userId}/by-ids`

**Description:** Get multiple orders by IDs for a specific user.

**Path Parameters:**
- `userId` (required, integer): User ID

**Request Body:**
```json
{
  "order_ids": [1, 2, 3],
  "statuses": ["Ready", "Simulated"]
}
```

**Response:**
```json
{
  "status": true,
  "data": [...]
}
```

---

### 6. Get Dashboard Statistics

**Endpoint:** `GET /api/v2/orders/dashboard/statistics`

**Description:** Get comprehensive order dashboard statistics.

**Query Parameters:**
- `start_date` (optional, date): Start date for filtering
- `end_date` (optional, date): End date for filtering
- `deal_id` (optional, integer): Filter by deal ID
- `product_id` (optional, integer): Filter by product ID
- `user_id` (optional, integer): Filter by user ID

**Response:**
```json
{
  "status": true,
  "data": {
    "summary": {
      "total_orders": 100,
      "total_revenue": 50000.00,
      "total_paid": 48000.00,
      "total_items_sold": 250,
      "average_order_value": 500.00
    },
    "orders_by_status": {...},
    "orders_by_deal": [...],
    "top_products": [...],
    "orders_list": [...]
  }
}
```

---

### 7. Create Order

**Endpoint:** `POST /api/v2/orders`

**Description:** Create a new order.

**Request Body:**
```json
{
  "user_id": 1,
  "platform_id": 1,
  "note": "Product purchase"
}
```

**Response:**
```json
{
  "status": true,
  "data": {
    "id": 1,
    "user_id": 1,
    "platform_id": 1,
    ...
  }
}
```

---

### 8. Create Orders From Cart

**Endpoint:** `POST /api/v2/orders/from-cart`

**Description:** Create multiple orders from cart items.

**Request Body:**
```json
{
  "user_id": 1,
  "orders_data": [
    {
      "platform_id": 1,
      "items": [
        {
          "item_id": 5,
          "qty": 2,
          "unit_price": 50.00,
          "total_amount": 100.00,
          "shipping": 5.00
        }
      ]
    }
  ]
}
```

**Response:**
```json
{
  "status": true,
  "order_ids": [1, 2, 3]
}
```

---

### 9. Cancel Order

**Endpoint:** `POST /api/v2/orders/{orderId}/cancel`

**Description:** Cancel an order by deleting it.

**Path Parameters:**
- `orderId` (required, integer): Order ID

**Response:**
```json
{
  "status": true,
  "message": "Order canceled successfully"
}
```

---

### 10. Make Order Ready

**Endpoint:** `POST /api/v2/orders/{orderId}/make-ready`

**Description:** Make an order ready for processing.

**Path Parameters:**
- `orderId` (required, integer): Order ID

**Response:**
```json
{
  "status": true,
  "message": "Order is ready",
  "data": {
    "id": 1,
    "status": "Ready",
    ...
  }
}
```

---

## NewsService Endpoints

### 1. Get All News (Paginated)

**Endpoint:** `GET /api/v2/news`

**Description:** Retrieve all news with pagination and optional search.

**Query Parameters:**
- `search` (optional, string): Search term to filter by title
- `per_page` (optional, integer): Number of items per page (default: 10, max: 100)

**Response:**
```json
{
  "status": true,
  "data": [...],
  "pagination": {
    "current_page": 1,
    "per_page": 10,
    "total": 50,
    "last_page": 5
  }
}
```

---

### 2. Get All News (Non-Paginated)

**Endpoint:** `GET /api/v2/news/all`

**Description:** Get all news without pagination, optionally with relationships.

**Request Body (optional):**
```json
{
  "with": ["mainImage", "hashtags", "likes"]
}
```

**Response:**
```json
{
  "status": true,
  "data": [...]
}
```

---

### 3. Get Enabled News

**Endpoint:** `GET /api/v2/news/enabled`

**Description:** Get only enabled news items.

**Response:**
```json
{
  "status": true,
  "data": [...]
}
```

---

### 4. Get News By ID

**Endpoint:** `GET /api/v2/news/{id}`

**Description:** Get a specific news item by ID, optionally with relationships.

**Path Parameters:**
- `id` (required, integer): News ID

**Request Body (optional):**
```json
{
  "with": ["mainImage", "hashtags"]
}
```

**Response:**
```json
{
  "status": true,
  "data": {
    "id": 1,
    "title": "Breaking News",
    "content": "...",
    "enabled": true,
    ...
  }
}
```

---

### 5. Get News With Relations

**Endpoint:** `GET /api/v2/news/{id}/with-relations`

**Description:** Get news with standard relationships (mainImage, likes, comments).

**Path Parameters:**
- `id` (required, integer): News ID

**Response:**
```json
{
  "status": true,
  "data": {
    "id": 1,
    "title": "Breaking News",
    "mainImage": {...},
    "likes": [...],
    "comments": [...]
  }
}
```

---

### 6. Check If User Liked News

**Endpoint:** `GET /api/v2/news/{id}/has-user-liked`

**Description:** Check if a specific user has liked a news item.

**Path Parameters:**
- `id` (required, integer): News ID

**Query Parameters:**
- `user_id` (required, integer): User ID

**Response:**
```json
{
  "status": true,
  "has_liked": true
}
```

---

### 7. Create News

**Endpoint:** `POST /api/v2/news`

**Description:** Create a new news item.

**Request Body:**
```json
{
  "title": "Breaking News",
  "content": "This is the news content...",
  "enabled": true,
  "published_at": "2024-01-01 10:00:00",
  "created_by": 1
}
```

**Response:**
```json
{
  "status": true,
  "data": {
    "id": 1,
    "title": "Breaking News",
    ...
  }
}
```

---

### 8. Update News

**Endpoint:** `PUT /api/v2/news/{id}`

**Description:** Update an existing news item.

**Path Parameters:**
- `id` (required, integer): News ID

**Request Body:**
```json
{
  "title": "Updated Title",
  "content": "Updated content...",
  "enabled": false
}
```

**Response:**
```json
{
  "status": true,
  "message": "News updated successfully"
}
```

---

### 9. Delete News

**Endpoint:** `DELETE /api/v2/news/{id}`

**Description:** Delete a news item.

**Path Parameters:**
- `id` (required, integer): News ID

**Response:**
```json
{
  "status": true,
  "message": "News deleted successfully"
}
```

---

### 10. Duplicate News

**Endpoint:** `POST /api/v2/news/{id}/duplicate`

**Description:** Duplicate an existing news item.

**Path Parameters:**
- `id` (required, integer): News ID

**Response:**
```json
{
  "status": true,
  "message": "News duplicated successfully",
  "data": {
    "id": 2,
    "title": "Breaking News (Copy)",
    ...
  }
}
```

---

### 11. Add Like To News

**Endpoint:** `POST /api/v2/news/{id}/like`

**Description:** Add a like to a news item.

**Path Parameters:**
- `id` (required, integer): News ID

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
  "message": "Like added successfully"
}
```

---

### 12. Remove Like From News

**Endpoint:** `DELETE /api/v2/news/{id}/like`

**Description:** Remove a like from a news item.

**Path Parameters:**
- `id` (required, integer): News ID

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

---

## Postman Collection

A complete Postman collection has been generated at:
```
/postman/2Earn_API_v2_OrderService_NewsService_Collection.json
```

**To import:**
1. Open Postman
2. Click "Import" button
3. Select the JSON file
4. The collection will be imported with all endpoints pre-configured

**Environment Variable:**
- `base_url`: Set this to your application URL (e.g., `http://localhost` or `https://your-domain.com`)

---

## Response Format

All endpoints follow a consistent response format:

### Success Response
```json
{
  "status": true,
  "data": {...},
  "message": "Optional success message"
}
```

### Error Response
```json
{
  "status": false,
  "message": "Error description",
  "errors": {
    "field_name": ["Validation error message"]
  }
}
```

---

## Error Handling

### HTTP Status Codes

- **200 OK**: Request successful
- **201 Created**: Resource created successfully
- **400 Bad Request**: Invalid request or business logic error
- **404 Not Found**: Resource not found
- **422 Unprocessable Entity**: Validation error
- **500 Internal Server Error**: Server error

### Common Error Scenarios

1. **Validation Errors (422)**
   ```json
   {
     "status": false,
     "errors": {
       "user_id": ["The user id field is required."]
     }
   }
   ```

2. **Not Found (404)**
   ```json
   {
     "status": false,
     "message": "Order not found"
   }
   ```

3. **Server Error (500)**
   ```json
   {
     "status": false,
     "message": "An error occurred while processing your request"
   }
   ```

---

## Service Methods Exposed

### OrderService

The following methods from `OrderService` are exposed:

- `getOrdersQuery()` - Used internally for filtering
- `getUserOrders()` - Get user orders with filters
- `findUserOrder()` - Find specific user order
- `getUserPurchaseHistoryQuery()` - For purchase history (can be exposed separately)
- `getOrderDashboardStatistics()` - Dashboard statistics
- `createOrder()` - Create single order
- `getAllOrdersPaginated()` - Get all orders paginated
- `getPendingOrdersCount()` - Count pending orders
- `getOrdersByIdsForUser()` - Get multiple orders by IDs
- `createOrdersFromCartItems()` - Create orders from cart
- `createOrderWithDetails()` - Create order with details (used internally)
- `cancelOrder()` - Cancel order
- `makeOrderReady()` - Make order ready
- `validateOrder()` - Validate and execute order (can be exposed separately)

### NewsService

The following methods from `NewsService` are exposed:

- `getById()` - Get news by ID
- `getByIdOrFail()` - Get news or throw exception (used internally)
- `getPaginated()` - Get paginated news
- `getAll()` - Get all news
- `getEnabledNews()` - Get enabled news
- `create()` - Create news
- `update()` - Update news
- `delete()` - Delete news
- `duplicate()` - Duplicate news
- `hasUserLiked()` - Check if user liked
- `getWithRelations()` - Get with custom relations
- `getNewsWithRelations()` - Get with standard relations
- `addLike()` - Add like
- `removeLike()` - Remove like

---

## Implementation Files

### Controllers
- `/app/Http/Controllers/Api/v2/OrderController.php` - Order endpoints
- `/app/Http/Controllers/Api/v2/NewsController.php` - News endpoints

### Routes
- `/routes/api.php` - All route definitions under `api/v2/` prefix

### Services
- `/app/Services/Orders/OrderService.php` - Order business logic
- `/app/Services/News/NewsService.php` - News business logic

---

## Notes

1. **Route Naming Convention**: All routes are named with `api_v2_` prefix followed by the resource name
   - Example: `api_v2_orders_index`, `api_v2_news_show`

2. **Middleware**: Routes are configured with `withoutMiddleware([\App\Http\Middleware\Authenticate::class])`
   - Add your own authentication middleware as needed

3. **Validation**: All inputs are validated using Laravel's Validator facade

4. **Error Logging**: Errors are logged in the service layer for debugging

5. **Database Transactions**: Order creation operations use transactions for data integrity

---

## Support

For questions or issues, please contact the development team or refer to the main application documentation.

---

**Generated:** February 9, 2026  
**Version:** 1.0  
**API Version:** v2

