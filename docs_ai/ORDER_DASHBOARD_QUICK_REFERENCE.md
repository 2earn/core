# Order Dashboard - Quick Reference

## 🚀 Quick Start

### Use in Blade View
```blade
@livewire('order-dashboard')
```

### Use with User Filter
```blade
@livewire('order-dashboard', ['userId' => auth()->id()])
```

---

## 📊 What It Shows

### Summary Cards (5)
1. **Total Orders** - Count of all orders
2. **Total Revenue** - Sum of all order amounts
3. **Total Paid** - Sum of paid amounts
4. **Items Sold** - Total quantity sold
5. **Avg Order Value** - Average per order

### Tables (2)
1. **Orders by Deal** - Top 10 deals by revenue
2. **Top Products** - Top 10 products by revenue

---

## 🔍 Filters Available

- **Start Date** - Filter from date
- **End Date** - Filter to date
- **Deal** - Select specific deal
- **Product** - Select specific product (requires deal)
- **Reset** - Clear all filters

---

## 📁 Files Created

```
app/Services/Orders/OrderService.php          (+165 lines)
app/Livewire/OrderDashboard.php               (125 lines)
resources/views/livewire/order-dashboard.blade.php  (265 lines)
```

---

## 🎨 Features

✅ Date range filtering (default: last 30 days)
✅ Deal and product filtering
✅ Live updates (no page refresh)
✅ Loading states
✅ Empty state messaging
✅ Error handling
✅ Responsive design (mobile-ready)
✅ Color-coded metrics
✅ Icons for visual clarity
✅ Hover effects on tables

---

## 🧪 Test It

### Seed Test Data
```bash
php artisan db:seed --class=OrdersTableSeeder
```

### Access Component
Add to any view:
```blade
@livewire('order-dashboard')
```

---

## 📈 Data Sources

**Orders Table:**
- `payment_datetime` - For date filtering
- `total_order` - For revenue
- `paid_cash` - For paid amount

**Order Details Table:**
- `qty` - For items sold
- `total_amount` - For product revenue

**Items Table:**
- `deal_id` - Links to deals
- `name`, `ref` - Product info

**Deals Table:**
- `name` - Deal name
- Links items to deals

---

## 🎯 Use Cases

1. **Admin Dashboard** - Monitor all orders
2. **Partner Dashboard** - Filter by their deals
3. **Product Performance** - See top sellers
4. **Revenue Tracking** - Monitor income
5. **Date Comparison** - Compare periods

---

## 🔧 Customization

### Change Default Date Range
```php
// In OrderDashboard.php mount()
$this->startDate = now()->subDays(7)->format('Y-m-d'); // Last 7 days
```

### Filter by User
```php
@livewire('order-dashboard', ['userId' => 123])
```

### Add to Route
```php
Route::get('/orders/dashboard', function () {
    return view('pages.order-dashboard');
});
```

---

## ✅ Status
**READY TO USE** - No additional setup required!

