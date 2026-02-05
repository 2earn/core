# SimulationOrder Model - Complete Documentation

## Overview
The `SimulationOrder` model stores simulation data every time `Ordering::simulate($order)` is executed. This provides a complete audit trail of all order simulations for debugging, analytics, and compliance.

---

## Database Schema

### Table: `simulation_orders`

| Column | Type | Description |
|--------|------|-------------|
| `id` | bigint (PK) | Auto-increment primary key |
| `order_id` | bigint (FK) | Foreign key to orders table |
| `order_deal` | JSON | Complete order deal simulation data |
| `bfssTables` | JSON | BFS tables data from simulation |
| `simulation_data` | JSON | Complete simulation result |
| `created_at` | timestamp | When simulation was created |
| `updated_at` | timestamp | When simulation was last updated |

**Indexes:**
- Primary key on `id`
- Index on `order_id`
- Foreign key `order_id` references `orders(id)` ON DELETE CASCADE

---

## Model Features

### Fillable Fields
```php
protected $fillable = [
    'order_id',
    'order_deal',
    'bfssTables',
    'simulation_data',
];
```

### Casts
```php
protected $casts = [
    'order_deal' => 'array',
    'bfssTables' => 'array',
    'simulation_data' => 'array',
];
```

All JSON fields are automatically cast to arrays for easy manipulation.

---

## Model Methods

### 1. createFromSimulation()
Creates a new simulation order record from simulation data.

**Signature:**
```php
public static function createFromSimulation(int $orderId, ?array $simulation): self
```

**Parameters:**
- `$orderId` (int) - The order ID
- `$simulation` (array|null) - Complete simulation result from `Ordering::simulate()`

**Returns:** `SimulationOrder` instance

**Example:**
```php
$simulation = Ordering::simulate($order);
$simulationOrder = SimulationOrder::createFromSimulation(123, $simulation);
```

**Stored Data:**
- `order_id`: The order ID
- `order_deal`: Extracted from `$simulation['order_deal']`
- `bfssTables`: Extracted from `$simulation['bfssTables']`
- `simulation_data`: Complete simulation array

---

### 2. getLatestForOrder()
Retrieves the most recent simulation for a specific order.

**Signature:**
```php
public static function getLatestForOrder(int $orderId): ?self
```

**Parameters:**
- `$orderId` (int) - The order ID

**Returns:** `SimulationOrder` instance or `null`

**Example:**
```php
$latestSimulation = SimulationOrder::getLatestForOrder(123);
if ($latestSimulation) {
    echo "Final amount: " . $latestSimulation->simulation_data['final_amount'];
}
```

---

### 3. getForOrder()
Retrieves all simulations for a specific order, ordered by most recent first.

**Signature:**
```php
public static function getForOrder(int $orderId)
```

**Parameters:**
- `$orderId` (int) - The order ID

**Returns:** `Collection` of `SimulationOrder` instances

**Example:**
```php
$simulations = SimulationOrder::getForOrder(123);
foreach ($simulations as $sim) {
    echo "Simulated at: " . $sim->created_at . "\n";
    echo "Final amount: " . $sim->simulation_data['final_amount'] . "\n";
}
```

---

## Relationships

### order()
Belongs to an Order.

**Signature:**
```php
public function order(): BelongsTo
```

**Example:**
```php
$simulation = SimulationOrder::find(1);
$order = $simulation->order;
echo "Order total: " . $order->total_order;
```

---

## Integration with OrderSimulationController

The model is automatically used in all three controller methods:

### 1. processOrder()
```php
$simulation = Ordering::simulate($order);
if ($simulation) {
    SimulationOrder::createFromSimulation($orderId, $simulation);
}
```

### 2. simulateOrder()
```php
$simulation = Ordering::simulate($order);
if ($simulation) {
    SimulationOrder::createFromSimulation($orderId, $simulation);
}
```

### 3. runSimulation()
```php
$simulation = Ordering::simulate($order);
if ($simulation) {
    SimulationOrder::createFromSimulation($orderId, $simulation);
}
```

---

## Data Structure Examples

### simulation_data Field
```json
{
  "total_amount": 100.00,
  "partner_discount": 5.00,
  "amount_after_partner_discount": 95.00,
  "earn_discount": 10.00,
  "amount_after_earn_discount": 85.00,
  "deal_discount_percentage": 15,
  "deal_discount": 12.75,
  "amount_after_deal_discount": 72.25,
  "total_discount": 27.75,
  "final_discount": 27.75,
  "final_discount_percentage": 27.75,
  "lost_discount": 0,
  "final_amount": 72.25
}
```

### order_deal Field
```json
{
  "deal_id": 5,
  "deal_name": "Summer Sale",
  "discount_percentage": 15,
  "deal_type": "percentage",
  "valid_from": "2026-01-01",
  "valid_to": "2026-12-31"
}
```

### bfssTables Field
```json
{
  "bfs1": {
    "balance": 100.00,
    "type": "BFS1",
    "used": 50.00
  },
  "bfs2": {
    "balance": 200.00,
    "type": "BFS2",
    "used": 22.25
  }
}
```

---

## Usage Examples

### Example 1: Create Simulation
```php
use App\Models\SimulationOrder;
use App\Services\Orders\Ordering;

$order = Order::find(123);
$simulation = Ordering::simulate($order);

// Save simulation
$simulationRecord = SimulationOrder::createFromSimulation(
    $order->id,
    $simulation
);

echo "Simulation saved with ID: " . $simulationRecord->id;
```

### Example 2: Retrieve Latest Simulation
```php
$latest = SimulationOrder::getLatestForOrder(123);

if ($latest) {
    echo "Last simulated: " . $latest->created_at->diffForHumans();
    echo "Final amount: $" . $latest->simulation_data['final_amount'];
}
```

### Example 3: Compare Simulations
```php
$simulations = SimulationOrder::getForOrder(123);

foreach ($simulations as $sim) {
    echo "Date: " . $sim->created_at . "\n";
    echo "Final: $" . $sim->simulation_data['final_amount'] . "\n";
    echo "Discount: $" . $sim->simulation_data['total_discount'] . "\n";
    echo "---\n";
}
```

### Example 4: Analyze BFS Usage
```php
$simulation = SimulationOrder::getLatestForOrder(123);

if ($simulation && $simulation->bfssTables) {
    foreach ($simulation->bfssTables as $bfsType => $data) {
        echo "{$bfsType}: Balance={$data['balance']}, Used={$data['used']}\n";
    }
}
```

---

## Query Examples

### Find Simulations by Date Range
```php
$simulations = SimulationOrder::whereBetween('created_at', [
    '2026-02-01',
    '2026-02-28'
])->get();
```

### Find High-Value Simulations
```php
$highValue = SimulationOrder::whereRaw(
    "JSON_EXTRACT(simulation_data, '$.final_amount') > ?",
    [1000]
)->get();
```

### Count Simulations Per Order
```php
$count = SimulationOrder::where('order_id', 123)->count();
echo "Order has been simulated {$count} times";
```

### Get Orders with Multiple Simulations
```php
$multipleSimulations = SimulationOrder::select('order_id')
    ->groupBy('order_id')
    ->havingRaw('COUNT(*) > 1')
    ->get();
```

---

## Logging

When a simulation is saved, the following log entries are created:

### Success Log
```
[OrderSimulationController] Simulation saved to database
Context: {"order_id": 123}
```

### Failure Log
```
[OrderSimulationController] Failed to save simulation
Context: {
    "order_id": 123,
    "error": "Error message"
}
```

---

## Benefits

### 1. Complete Audit Trail
- Every simulation is recorded
- Can trace order processing history
- Useful for compliance and auditing

### 2. Debugging Support
- Compare simulations over time
- Identify calculation changes
- Track discount variations

### 3. Analytics
- Analyze discount patterns
- Calculate average discounts
- Track BFS usage trends

### 4. Testing & QA
- Verify simulation consistency
- Compare expected vs actual results
- Identify edge cases

---

## Performance Considerations

### Storage
- Each simulation record is typically 1-5 KB
- Indexed on `order_id` for fast lookups
- Consider archiving old simulations periodically

### Optimization Tips
```php
// Use select() to limit data retrieval
$simulations = SimulationOrder::select(['id', 'order_id', 'created_at'])
    ->where('order_id', 123)
    ->get();

// Eager load relationships
$simulations = SimulationOrder::with('order')
    ->where('order_id', 123)
    ->get();
```

---

## Testing

### Factory Usage
```php
// Create test simulation
$simulation = SimulationOrder::factory()->create([
    'order_id' => 123,
]);

// Create multiple simulations
SimulationOrder::factory()->count(5)->create([
    'order_id' => 123,
]);
```

### Test Example
```php
public function test_can_save_simulation()
{
    $order = Order::factory()->create();
    $simulationData = [
        'total_amount' => 100.00,
        'final_amount' => 75.00,
        'order_deal' => ['deal_id' => 1],
        'bfssTables' => ['bfs1' => ['balance' => 100]],
    ];
    
    $simulation = SimulationOrder::createFromSimulation(
        $order->id,
        $simulationData
    );
    
    $this->assertDatabaseHas('simulation_orders', [
        'order_id' => $order->id,
    ]);
    
    $this->assertEquals(75.00, $simulation->simulation_data['final_amount']);
}
```

---

## Maintenance

### Archive Old Simulations
```php
// Archive simulations older than 90 days
$archived = SimulationOrder::where('created_at', '<', now()->subDays(90))
    ->delete();
```

### Clean Up Orphaned Records
```php
// Remove simulations for deleted orders
$deleted = SimulationOrder::whereDoesntHave('order')->delete();
```

---

## API Access

### Get Simulation History (Example Endpoint)
```php
// Add to routes/api.php
Route::get('/orders/{orderId}/simulations', function ($orderId) {
    return SimulationOrder::getForOrder($orderId);
});
```

**Response:**
```json
[
  {
    "id": 1,
    "order_id": 123,
    "simulation_data": { /* ... */ },
    "created_at": "2026-02-05T10:30:00Z"
  },
  {
    "id": 2,
    "order_id": 123,
    "simulation_data": { /* ... */ },
    "created_at": "2026-02-05T09:15:00Z"
  }
]
```

---

## Security Considerations

- Simulations contain financial data - restrict access appropriately
- Consider encrypting sensitive fields if needed
- Audit who accesses simulation data
- Apply proper permission checks

---

## Migration File

Located at: `database/migrations/2026_02_05_162203_create_simulation_orders_table.php`

**Run Migration:**
```bash
php artisan migrate
```

**Rollback:**
```bash
php artisan migrate:rollback
```

---

## Summary

✅ **Model Created** - SimulationOrder with proper configuration  
✅ **Migration Run** - Table created successfully  
✅ **Integration Complete** - Auto-saves on every simulation  
✅ **Factory Created** - Ready for testing  
✅ **Relationships Defined** - Links to Order model  
✅ **Helper Methods** - Easy data retrieval  

Every time `Ordering::simulate($order)` is called, the complete simulation data is automatically saved to the database! 🎉

---

**Version:** 1.0  
**Date:** February 5, 2026  
**Status:** ✅ Production Ready
