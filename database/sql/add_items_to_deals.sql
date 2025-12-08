-- SQL Script to Add More Items to Deals
-- This helps resolve the "only 1 item available" error in OrdersTableSeeder

-- =====================================================
-- STEP 1: Identify deals with less than 2 items
-- =====================================================
SELECT
    d.id as deal_id,
    d.name as deal_name,
    d.type as deal_type,
    COUNT(i.id) as items_count
FROM deals d
LEFT JOIN items i ON d.id = i.deal_id AND i.ref != '#0001'
GROUP BY d.id, d.name, d.type
HAVING items_count < 2
ORDER BY d.id;

-- =====================================================
-- STEP 2: Example - Add multiple items to a specific deal
-- =====================================================
-- Replace deal_id = 1 with your actual deal ID
-- Replace platform_id = 1 with your actual platform ID

INSERT INTO items (name, ref, price, deal_id, platform_id, stock, created_at, updated_at) VALUES
('Product Item 1', 'PROD001', 99.99, 1, 1, 100, NOW(), NOW()),
('Product Item 2', 'PROD002', 149.99, 1, 1, 100, NOW(), NOW()),
('Product Item 3', 'PROD003', 199.99, 1, 1, 100, NOW(), NOW()),
('Product Item 4', 'PROD004', 249.99, 1, 1, 100, NOW(), NOW()),
('Product Item 5', 'PROD005', 299.99, 1, 1, 100, NOW(), NOW());

-- =====================================================
-- STEP 3: Bulk add items to multiple deals
-- =====================================================
-- This creates 5 items for each deal that has less than 2 items

-- First, get the deal IDs that need items
-- Then run this for each deal_id:

-- For Deal ID 19 (example):
INSERT INTO items (name, ref, price, deal_id, platform_id, stock, created_at, updated_at) VALUES
('Item A for Deal 19', 'D19-A', 50.00, 19, 1, 50, NOW(), NOW()),
('Item B for Deal 19', 'D19-B', 75.00, 19, 1, 50, NOW(), NOW()),
('Item C for Deal 19', 'D19-C', 100.00, 19, 1, 50, NOW(), NOW()),
('Item D for Deal 19', 'D19-D', 125.00, 19, 1, 50, NOW(), NOW()),
('Item E for Deal 19', 'D19-E', 150.00, 19, 1, 50, NOW(), NOW());

-- =====================================================
-- STEP 4: Verify items were added
-- =====================================================
SELECT
    d.id as deal_id,
    d.name as deal_name,
    COUNT(i.id) as items_count
FROM deals d
LEFT JOIN items i ON d.id = i.deal_id AND i.ref != '#0001'
GROUP BY d.id, d.name
ORDER BY items_count ASC, d.id;

-- =====================================================
-- STEP 5: Check which deals are now ready for seeding
-- =====================================================
SELECT
    d.id as deal_id,
    d.name as deal_name,
    d.type as deal_type,
    COUNT(i.id) as items_count,
    CASE
        WHEN COUNT(i.id) >= 2 THEN '✓ Ready for seeding'
        ELSE '✗ Need more items'
    END as status
FROM deals d
LEFT JOIN items i ON d.id = i.deal_id AND i.ref != '#0001'
GROUP BY d.id, d.name, d.type
ORDER BY items_count ASC, d.id;

-- =====================================================
-- NOTES
-- =====================================================
-- 1. The OrdersTableSeeder requires deals to have at least 2 items
-- 2. Each order will randomly select 2-5 items from a single deal
-- 3. Replace deal_id, platform_id, and other values as needed
-- 4. Ensure 'ref' values are unique across all items
-- 5. Make sure platform_id exists in the platforms table

