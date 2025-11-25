# Quick Start: Integrating Platform Change Request Routes

## Step 1: Add Routes to Your Application

### Option A: Add to existing routes/api.php

Open `routes/api.php` and add these routes in your admin section:

```php
use App\Http\Controllers\Api\Admin\PlatformChangeRequestController;

// Add within your existing admin routes group
Route::prefix('admin')->middleware(['auth:api', 'admin'])->group(function () {
    
    // ... your existing admin routes ...
    
    // Platform Change Request Management
    Route::get('platform-change-requests', [PlatformChangeRequestController::class, 'index']);
    Route::get('platform-change-requests/pending', [PlatformChangeRequestController::class, 'pending']);
    Route::get('platform-change-requests/statistics', [PlatformChangeRequestController::class, 'statistics']);
    Route::get('platform-change-requests/{id}', [PlatformChangeRequestController::class, 'show']);
    Route::post('platform-change-requests/{id}/approve', [PlatformChangeRequestController::class, 'approve']);
    Route::post('platform-change-requests/{id}/reject', [PlatformChangeRequestController::class, 'reject']);
    Route::post('platform-change-requests/bulk-approve', [PlatformChangeRequestController::class, 'bulkApprove']);
});
```

### Option B: Create separate route file

If you prefer separate files, include the sample route file:

1. Rename `routes/api_platform_change_requests_sample.php` to `routes/api_platform_change_requests.php`
2. In `routes/api.php`, add:
   ```php
   require __DIR__.'/api_platform_change_requests.php';
   ```

## Step 2: Test the Endpoints

### Test 1: Check if routes are registered
```bash
php artisan route:list --name=platform-change
```

Expected output should show all the new routes.

### Test 2: Create a change request (Partner)
```bash
curl -X PUT http://your-app.test/api/partner/platforms/1 \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -d '{
    "name": "Updated Platform Name",
    "description": "New description",
    "updated_by": 1
  }'
```

### Test 3: View pending requests (Admin)
```bash
curl http://your-app.test/api/admin/platform-change-requests/pending \
  -H "Authorization: Bearer YOUR_ADMIN_TOKEN"
```

### Test 4: Approve a request (Admin)
```bash
curl -X POST http://your-app.test/api/admin/platform-change-requests/1/approve \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer YOUR_ADMIN_TOKEN" \
  -d '{"reviewed_by": 1}'
```

## Step 3: Frontend Integration

### Display Change Request Count (Partner View)
```vue
<template>
  <div v-for="platform in platforms" :key="platform.id">
    <h3>{{ platform.name }}</h3>
    
    <!-- Show pending changes badge -->
    <span v-if="platform.change_requests_count > 0" class="badge badge-warning">
      {{ platform.change_requests_count }} pending change(s)
    </span>
    
    <!-- List recent change requests -->
    <div v-if="platform.changeRequests && platform.changeRequests.length">
      <h4>Recent Change Requests:</h4>
      <ul>
        <li v-for="request in platform.changeRequests" :key="request.id">
          <span :class="'status-' + request.status">{{ request.status }}</span>
          - {{ formatDate(request.created_at) }}
          <span v-if="request.rejection_reason">
            ({{ request.rejection_reason }})
          </span>
        </li>
      </ul>
    </div>
  </div>
</template>

<script>
export default {
  methods: {
    formatDate(date) {
      return new Date(date).toLocaleDateString();
    }
  }
}
</script>
```

### Admin Approval Interface
```vue
<template>
  <div class="change-request-list">
    <h2>Pending Change Requests</h2>
    
    <div v-for="request in pendingRequests" :key="request.id" class="request-card">
      <h3>{{ request.platform.name }}</h3>
      <p>Requested by: {{ request.requested_by?.name }}</p>
      <p>Date: {{ formatDate(request.created_at) }}</p>
      
      <!-- Display changes -->
      <div class="changes">
        <h4>Proposed Changes:</h4>
        <table>
          <thead>
            <tr>
              <th>Field</th>
              <th>Current Value</th>
              <th>New Value</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(change, field) in request.changes" :key="field">
              <td>{{ field }}</td>
              <td>{{ change.old }}</td>
              <td>{{ change.new }}</td>
            </tr>
          </tbody>
        </table>
      </div>
      
      <!-- Actions -->
      <div class="actions">
        <button @click="approve(request.id)" class="btn btn-success">
          Approve
        </button>
        <button @click="showRejectModal(request.id)" class="btn btn-danger">
          Reject
        </button>
      </div>
    </div>
    
    
    <modal v-if="rejectModalVisible" @close="rejectModalVisible = false">
      <h3>Reject Change Request</h3>
      <textarea v-model="rejectionReason" placeholder="Reason for rejection"></textarea>
      <button @click="reject(selectedRequestId)">Confirm Reject</button>
    </modal>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  data() {
    return {
      pendingRequests: [],
      rejectModalVisible: false,
      selectedRequestId: null,
      rejectionReason: '',
      currentUserId: 1 // Get from auth
    }
  },
  
  mounted() {
    this.loadPendingRequests();
  },
  
  methods: {
    async loadPendingRequests() {
      const response = await axios.get('/api/admin/platform-change-requests/pending');
      this.pendingRequests = response.data.data;
    },
    
    async approve(requestId) {
      try {
        await axios.post(`/api/admin/platform-change-requests/${requestId}/approve`, {
          reviewed_by: this.currentUserId
        });
        
        alert('Change request approved successfully!');
        this.loadPendingRequests(); // Refresh list
      } catch (error) {
        alert('Failed to approve: ' + error.message);
      }
    },
    
    showRejectModal(requestId) {
      this.selectedRequestId = requestId;
      this.rejectModalVisible = true;
    },
    
    async reject(requestId) {
      if (!this.rejectionReason) {
        alert('Please provide a reason for rejection');
        return;
      }
      
      try {
        await axios.post(`/api/admin/platform-change-requests/${requestId}/reject`, {
          reviewed_by: this.currentUserId,
          rejection_reason: this.rejectionReason
        });
        
        alert('Change request rejected successfully!');
        this.rejectModalVisible = false;
        this.rejectionReason = '';
        this.loadPendingRequests(); // Refresh list
      } catch (error) {
        alert('Failed to reject: ' + error.message);
      }
    },
    
    formatDate(date) {
      return new Date(date).toLocaleDateString();
    }
  }
}
</script>

<style scoped>
.request-card {
  border: 1px solid #ddd;
  padding: 20px;
  margin-bottom: 20px;
  border-radius: 8px;
}

.changes table {
  width: 100%;
  border-collapse: collapse;
  margin: 15px 0;
}

.changes th,
.changes td {
  border: 1px solid #ddd;
  padding: 8px;
  text-align: left;
}

.changes th {
  background-color: #f5f5f5;
}

.actions {
  display: flex;
  gap: 10px;
  margin-top: 15px;
}

.btn {
  padding: 8px 16px;
  border: none;
  border-radius: 4px;
  cursor: pointer;
}

.btn-success {
  background-color: #28a745;
  color: white;
}

.btn-danger {
  background-color: #dc3545;
  color: white;
}
</style>
```

## Step 4: Add Middleware (if needed)

If you don't have an admin middleware, create one:

```bash
php artisan make:middleware AdminMiddleware
```

Then implement:

```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check() || !auth()->user()->isAdmin()) {
            return response()->json([
                'status' => 'Failed',
                'message' => 'Unauthorized'
            ], 403);
        }

        return $next($request);
    }
}
```

Register in `app/Http/Kernel.php`:

```php
protected $middlewareAliases = [
    // ... existing middleware
    'admin' => \App\Http\Middleware\AdminMiddleware::class,
];
```

## Step 5: Update Partner Interface Message

When a partner tries to update and sees the new response, update your success message:

```javascript
// Before
"Platform updated successfully"

// After
"Platform change request submitted successfully. Awaiting approval."
```

## Troubleshooting

### Routes not found
```bash
php artisan route:clear
php artisan cache:clear
php artisan route:list
```

### Database errors
```bash
php artisan migrate:status
php artisan migrate
```

### Class not found
```bash
composer dump-autoload
```

## You're Done! 🎉

Your platform change request system is now fully integrated and ready to use.

For more details, see:
- `PLATFORM_CHANGE_REQUEST_DOCUMENTATION.md` - Complete documentation
- `PLATFORM_CHANGE_REQUEST_QUICK_REFERENCE.md` - Quick reference
- `PLATFORM_CHANGE_REQUEST_IMPLEMENTATION_COMPLETE.md` - Implementation summary

