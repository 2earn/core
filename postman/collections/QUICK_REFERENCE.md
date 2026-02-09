# Quick Reference - Postman Collections Setup

## 🚀 Quick Start

### 1. Import Collections
Import all 5 JSON files from `postman/collections/` into Postman

### 2. Create Environment
Create a new environment with these variables:

```
base_url = http://localhost
user_id = 1
platform_id = 1
order_id = 1
sanctum_token = your_token_here
admin_token = your_admin_token_here
```

### 3. Test URLs
All URLs now follow this format:
```
{{base_url}}/api/[endpoint]
```

---

## 📦 Available Collections

| Collection | Endpoints | Use Case |
|-----------|-----------|----------|
| **Mobile API** | 4 | Mobile app operations |
| **Partner API** | 63+ | Partner portal management |
| **Admin API** | 9 | Admin operations |
| **Payment API** | 3 | Order processing & simulation |
| **Balance Operation API** | 9 | Balance operations |

---

## 🔗 Sample URLs

### Mobile
- Get User: `http://localhost/api/mobile/users?user_id=1`
- Get Balances: `http://localhost/api/mobile/balances?user_id=1`
- Cash Balance: `http://localhost/api/mobile/cash-balance`

### Partner
- Top Platforms: `http://localhost/api/partner/platforms/top-selling`
- List Deals: `http://localhost/api/partner/deals/deals`
- List Orders: `http://localhost/api/partner/orders/orders`

### Admin
- Platform Change Requests: `http://localhost/api/admin/platform-change-requests`
- Partner Requests: `http://localhost/api/partner/partner-requests`

### Payment
- Process Order: `http://localhost/api/order/process`
- Simulate Order: `http://localhost/api/order/simulate`

### Balance
- Get Operations: `http://localhost/api/v2/balance/operations`
- Filtered Operations: `http://localhost/api/v2/balance/operations/filtered`
- Get All: `http://localhost/api/v2/balance/operations/all`

---

## ⚙️ Environment Configurations

### Local Development
```
base_url: http://localhost
```

### Staging
```
base_url: https://staging.yourdomain.com
```

### Production
```
base_url: https://api.yourdomain.com
```

---

## ✅ Verification Checklist

- [ ] All 5 collections imported
- [ ] Environment created with required variables
- [ ] `base_url` set to `http://localhost` (NO `/api` suffix)
- [ ] Authentication tokens configured
- [ ] Test request sent successfully
- [ ] Response received from server

---

## 🔧 Troubleshooting

### Issue: 404 Not Found
**Solution:** Ensure `base_url` does NOT end with `/api`

### Issue: URLs have double `/api/api/`
**Solution:** Remove `/api` from `base_url` variable

### Issue: Authentication failed
**Solution:** Update `sanctum_token` or `admin_token` with valid tokens

---

## 📊 Collection Statistics

- **Total Collections:** 5
- **Total Endpoints:** 88+
- **Total Size:** ~95 KB
- **URL Format:** `{{base_url}}/api/...`
- **Last Updated:** February 9, 2026

---

**Ready to test!** 🎉

