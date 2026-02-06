# Slow Test Groups - Quick Reference

## Date: February 6, 2026

---

## 🔴 **TOP 5 SLOWEST TEST GROUPS**

### 1. ShareBalanceServiceTest - **UP TO 1.36s per test**
- `get shares soldes data returns paginated results` - 1.36s
- `get shares soldes data supports custom per page` - 1.32s
- `get shares soldes query includes required fields` - 1.30s
- `get shares soldes query returns collection` - 1.25s
- `get shares soldes data with custom sorting` - 1.20s

**Fix:** Mock database queries instead of hitting real DB

### 2. CommunicationBoardServiceTest - **UP TO 0.75s per test**
- `get communication board items formats with type` - 0.75s
- `get communication board items merges all types` - 0.41s
- `get communication board items includes news` - 0.40s

**Fix:** Mock data sources (surveys, news, events)

### 3. CountriesServiceTest - **UP TO 0.79s per test**
- `get all returns empty collection when no countries` - 0.79s

**Fix:** Database truncation issue - use transactions

### 4. TranslateTabsServiceTest - **UP TO 0.53s per test**
- `get statistics returns statistics` - 0.53s
- `get all as key value arrays returns formatted arrays` - 0.28s

**Fix:** Mock translation data

### 5. DealChangeRequestServiceTest - **UP TO 0.43s per test**
- `get paginated requests works` - 0.43s
- `get statistics returns statistics array` - 0.35s
- `count pending requests works` - 0.25s

**Fix:** Mock deal relationships and statistics

---

## 📊 **QUICK STATS**

| Metric | Value |
|--------|-------|
| **Slowest Test** | 1.36s (ShareBalanceServiceTest) |
| **Tests > 1.0s** | ~5 tests |
| **Tests > 0.5s** | ~8 tests |
| **Tests > 0.3s** | ~20 tests |
| **Total Test Count** | 1100+ tests |
| **Estimated Full Suite** | 5+ minutes (timed out) |

---

## ⚡ **QUICK FIXES** (Expected Impact)

### Fix #1: Mock ShareBalanceService
**Command:**
```bash
php artisan test --filter=ShareBalanceServiceTest
```
**Before:** 1.36s per test  
**After:** ~0.05s per test  
**Time Saved:** 1.31s × 5 tests = **6.5 seconds**

### Fix #2: Mock CommunicationBoardService
**Before:** 0.75s per test  
**After:** ~0.10s per test  
**Time Saved:** 0.65s × 4 tests = **2.6 seconds**

### Fix #3: Fix CountriesService Empty Test
**Before:** 0.79s  
**After:** ~0.05s  
**Time Saved:** **0.74 seconds**

**Total Quick Wins: ~10 seconds saved per test run**

---

## 🎯 **ACTION PLAN**

### This Week (Phase 1)
1. ✅ Mock ShareBalanceService tests
2. ✅ Fix CountriesService empty test  
3. ✅ Mock CommunicationBoardService tests

### Next Week (Phase 2)
1. Add database indexes for slow tables
2. Reduce test data volume
3. Implement query caching

### This Month (Phase 3)
1. Refactor slow services
2. Enable parallel test execution
3. Optimize pagination queries

---

## 🔍 **HOW TO PROFILE TESTS**

### Run with profiling
```bash
php artisan test --profile
```

### Run specific slow test group
```bash
php artisan test --filter=ShareBalanceServiceTest --profile
```

### Run and show slow tests only
```bash
php artisan test --profile | Select-String -Pattern "s$"
```

---

## 📈 **EXPECTED IMPROVEMENTS**

| Phase | Time Saved | Cumulative |
|-------|-----------|------------|
| Phase 1 | 10-15s | 10-15s |
| Phase 2 | 20-30s | 30-45s |
| Phase 3 | 60-120s | 90-165s |

**Goal:** Reduce test suite from **5+ minutes** to **under 1 minute**

---

## 🚨 **CRITICAL TESTS TO FIX FIRST**

1. **ShareBalanceServiceTest** (5 tests > 1.2s each) - CRITICAL
2. **CountriesServiceTest** (1 test = 0.79s) - QUICK WIN
3. **CommunicationBoardServiceTest** (4 tests > 0.3s) - MEDIUM
4. **TranslateTabsServiceTest** (2 tests > 0.3s) - LOW
5. **DealChangeRequestServiceTest** (3 tests > 0.2s) - LOW

---

## 📝 **NOTES**

- Tests over **0.20s** are considered slow
- Tests over **0.50s** are considered very slow  
- Tests over **1.0s** are critical issues
- Most tests (~95%) run under 0.20s ✅
- Focus on the 5% that are slow for maximum impact

---

## 📚 **DOCUMENTATION**

Full analysis: `SLOW_TESTS_ANALYSIS_REPORT.md`
