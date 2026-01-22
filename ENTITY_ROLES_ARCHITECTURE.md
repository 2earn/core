# Entity Roles Architecture - UserPartnerController

## Database Structure

### Entity Roles Table (`entity_roles`)

This table stores the relationship between users and their roles on different entities (Platforms, Partners, etc.)

| Column | Type | Description |
|--------|------|-------------|
| `id` | bigint | Primary key |
| `user_id` | bigint | FK to users table - the user who has the role |
| `name` | string | Role name (owner, partner, manager, etc.) |
| `roleable_id` | bigint | ID of the entity (Platform or Partner) |
| `roleable_type` | string | Type of entity (App\Models\Platform or App\Models\Partner) |
| `created_by` | bigint | User who created this role assignment |
| `updated_by` | bigint | User who last updated this role assignment |
| `created_at` | timestamp | When the role was created |
| `updated_at` | timestamp | When the role was last updated |

## Example Data Structure

### Scenario: Multiple users with different roles on multiple platforms

```
User 1 (John):
  - Platform A: owner, manager
  - Platform B: partner
  - Platform C: owner

User 2 (Jane):
  - Platform A: partner
  - Platform B: owner
  - Platform D: owner, manager

User 3 (Bob):
  - Platform A: manager
  - Platform C: partner
```

### Corresponding Database Records (`entity_roles` table)

| id | user_id | name | roleable_id | roleable_type | created_at |
|----|---------|------|-------------|---------------|------------|
| 1 | 1 (John) | owner | 1 (Platform A) | App\Models\Platform | 2026-01-15 |
| 2 | 1 (John) | manager | 1 (Platform A) | App\Models\Platform | 2026-01-15 |
| 3 | 1 (John) | partner | 2 (Platform B) | App\Models\Platform | 2026-01-16 |
| 4 | 1 (John) | owner | 3 (Platform C) | App\Models\Platform | 2026-01-17 |
| 5 | 2 (Jane) | partner | 1 (Platform A) | App\Models\Platform | 2026-01-15 |
| 6 | 2 (Jane) | owner | 2 (Platform B) | App\Models\Platform | 2026-01-16 |
| 7 | 2 (Jane) | owner | 4 (Platform D) | App\Models\Platform | 2026-01-18 |
| 8 | 2 (Jane) | manager | 4 (Platform D) | App\Models\Platform | 2026-01-18 |
| 9 | 3 (Bob) | manager | 1 (Platform A) | App\Models\Platform | 2026-01-15 |
| 10 | 3 (Bob) | partner | 3 (Platform C) | App\Models\Platform | 2026-01-17 |

## API Response Example

When calling `GET /api/partner/platforms?user_id=1` (for John):

```json
{
  "status": true,
  "message": "Platforms retrieved successfully",
  "data": {
    "platforms": [
      {
        "id": 1,
        "name": "Platform A",
        "description": "First platform",
        "type": 1,
        "link": "https://platforma.com",
        "enabled": true,
        "roles": ["owner", "manager"],
        "role_details": [
          {
            "id": 1,
            "name": "owner",
            "created_at": "2026-01-15T10:00:00",
            "updated_at": "2026-01-15T10:00:00"
          },
          {
            "id": 2,
            "name": "manager",
            "created_at": "2026-01-15T10:05:00",
            "updated_at": "2026-01-15T10:05:00"
          }
        ],
        "business_sector": {
          "id": 5,
          "name": "Technology"
        },
        "logo": {
          "id": 10,
          "path": "/uploads/logos/platform-a.png"
        }
      },
      {
        "id": 2,
        "name": "Platform B",
        "description": "Second platform",
        "type": 2,
        "link": "https://platformb.com",
        "enabled": true,
        "roles": ["partner"],
        "role_details": [
          {
            "id": 3,
            "name": "partner",
            "created_at": "2026-01-16T11:00:00",
            "updated_at": "2026-01-16T11:00:00"
          }
        ],
        "business_sector": {
          "id": 3,
          "name": "Finance"
        },
        "logo": {
          "id": 11,
          "path": "/uploads/logos/platform-b.png"
        }
      },
      {
        "id": 3,
        "name": "Platform C",
        "description": "Third platform",
        "type": 1,
        "link": "https://platformc.com",
        "enabled": true,
        "roles": ["owner"],
        "role_details": [
          {
            "id": 4,
            "name": "owner",
            "created_at": "2026-01-17T09:00:00",
            "updated_at": "2026-01-17T09:00:00"
          }
        ],
        "business_sector": {
          "id": 5,
          "name": "Technology"
        },
        "logo": null
      }
    ],
    "total": 3
  }
}
```

## Understanding the Relationship

### Polymorphic Relationship
The `entity_roles` table uses a **polymorphic relationship** which means:
- A single table can store roles for different types of entities
- `roleable_type` determines what kind of entity it is (Platform, Partner, etc.)
- `roleable_id` points to the specific entity's ID

### One User, Multiple Roles Per Platform
A user can have **multiple roles** on a single platform:
- Example: John is both `owner` and `manager` of Platform A
- This results in **2 separate records** in the `entity_roles` table
- Both records have the same `user_id` and `roleable_id`, but different `name` values

### Query Explanation

The service method `getPlatformsWithRolesForUser()` does the following:

1. **Find all platforms** where the user has at least one role:
   ```sql
   SELECT * FROM platforms 
   WHERE EXISTS (
     SELECT 1 FROM entity_roles 
     WHERE entity_roles.user_id = ? 
     AND entity_roles.roleable_id = platforms.id
     AND entity_roles.roleable_type = 'App\Models\Platform'
   )
   ```

2. **Load only the user's roles** for each platform:
   ```sql
   SELECT * FROM entity_roles
   WHERE user_id = ?
   AND roleable_type = 'App\Models\Platform'
   AND roleable_id IN (platform_ids...)
   ```

3. **Result**: Each platform object contains a `roles` collection with only that user's roles

## Code Flow

### 1. Controller receives request
```php
GET /api/partner/platforms?user_id=1
```

### 2. EntityRoleService fetches platforms
```php
$platforms = $this->entityRoleService->getPlatformsWithRolesForUser($userId);
```

### 3. Service queries database
```php
Platform::whereHas('roles', function ($query) use ($userId) {
    $query->where('user_id', $userId);
})
->with(['roles' => function ($query) use ($userId) {
    $query->where('user_id', $userId);
}])
->get();
```

### 4. Controller transforms data
```php
$roles = $platform->roles->pluck('name')->toArray();
// Result: ['owner', 'manager']

$role_details = $platform->roles->map(function ($role) {
    return [
        'id' => $role->id,
        'name' => $role->name,
        'created_at' => $role->created_at,
        'updated_at' => $role->updated_at,
    ];
});
```

## Visual Representation

```
┌──────────────────────────────────────────────────────────────┐
│                      entity_roles Table                       │
├─────┬──────────┬─────────┬─────────────┬──────────────────────┤
│ id  │ user_id  │  name   │ roleable_id │   roleable_type      │
├─────┼──────────┼─────────┼─────────────┼──────────────────────┤
│ 1   │ 1 (John) │ owner   │ 1 (Plat A)  │ App\Models\Platform  │
│ 2   │ 1 (John) │ manager │ 1 (Plat A)  │ App\Models\Platform  │
│ 3   │ 1 (John) │ partner │ 2 (Plat B)  │ App\Models\Platform  │
│ 4   │ 2 (Jane) │ partner │ 1 (Plat A)  │ App\Models\Platform  │
│ 5   │ 2 (Jane) │ owner   │ 2 (Plat B)  │ App\Models\Platform  │
└─────┴──────────┴─────────┴─────────────┴──────────────────────┘

                            ▼
                    Query by user_id = 1
                            ▼

┌───────────────────────────────────────────────────────────┐
│              Platforms for John (User 1)                  │
├───────────────────────────────────────────────────────────┤
│ Platform A                                                │
│   └─ Roles: ['owner', 'manager']                         │
│   └─ Role Details:                                        │
│       - id: 1, name: 'owner', created_at: 2026-01-15     │
│       - id: 2, name: 'manager', created_at: 2026-01-15   │
├───────────────────────────────────────────────────────────┤
│ Platform B                                                │
│   └─ Roles: ['partner']                                  │
│   └─ Role Details:                                        │
│       - id: 3, name: 'partner', created_at: 2026-01-16   │
└───────────────────────────────────────────────────────────┘
```

## Key Points

1. **One record per role assignment**: Each role a user has on a platform gets its own row in `entity_roles`

2. **Multiple roles per user per platform**: A user can have multiple roles on the same platform (multiple rows with same user_id and roleable_id, different name)

3. **Efficient querying**: The service uses eager loading to avoid N+1 query problems

4. **Filtered results**: The `roles` relationship is filtered to show only the current user's roles, not all roles on the platform

5. **Response structure**: The API returns both:
   - `roles`: Simple array of role names
   - `role_details`: Full details of each role assignment

## Benefits of This Architecture

✅ **Flexible**: Can easily add new role types
✅ **Scalable**: Works for platforms, partners, or any future entity
✅ **Granular**: Track who created/updated each role assignment
✅ **Auditable**: Timestamps for each role change
✅ **Multi-role**: Users can have multiple roles on same entity
✅ **Type-safe**: Uses polymorphic relationships properly
