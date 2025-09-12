-- =====================================================
-- SPORTS RELATIONS DATA POPULATION SCRIPT
-- This script populates types and category-type relationships
-- for Basketball, Handball, Volleyball, and Beach Volleyball
-- =====================================================

USE rabatjeunesse;

-- =====================================================
-- BASKETBALL
-- =====================================================

-- Insert Basketball Types
INSERT INTO basketball_types (name, code, min_players, max_players, active, created, modified) VALUES
('Basketball 3x3', '3x3', 3, 6, 1, NOW(), NOW()),
('Basketball 5x5', '5x5', 5, 12, 1, NOW(), NOW());

-- Insert Basketball Category-Type Relationships
-- Categories: 1=-15, 2=-17, 3=-21, 4=+21
-- Types: 1=3x3, 2=5x5
INSERT INTO basketball_categories_types (basketball_category_id, basketball_type_id, created, modified) VALUES
(1, 1, NOW(), NOW()),  -- -15 with 3x3
(2, 1, NOW(), NOW()),  -- -17 with 3x3
(2, 2, NOW(), NOW()),  -- -17 with 5x5
(3, 2, NOW(), NOW()),  -- -21 with 5x5
(4, 2, NOW(), NOW());  -- +21 with 5x5

-- =====================================================
-- HANDBALL
-- =====================================================

-- Insert Handball Types
INSERT INTO handball_types (name, code, min_players, max_players, active, created, modified) VALUES
('Handball 5x5', '5x5', 5, 10, 1, NOW(), NOW()),
('Handball 7x7', '7x7', 7, 14, 1, NOW(), NOW());

-- Insert Handball Category-Type Relationships
-- Categories: 1=-15, 2=-17, 3=-19
-- Types: 1=5x5, 2=7x7
INSERT INTO handball_categories_types (handball_category_id, handball_type_id, created, modified) VALUES
(1, 1, NOW(), NOW()),  -- -15 with 5x5
(2, 1, NOW(), NOW()),  -- -17 with 5x5
(2, 2, NOW(), NOW()),  -- -17 with 7x7
(3, 2, NOW(), NOW());  -- -19 with 7x7

-- =====================================================
-- VOLLEYBALL
-- =====================================================

-- Insert Volleyball Types
INSERT INTO volleyball_types (name, code, min_players, max_players, active, created, modified) VALUES
('Volleyball 4x4', '4x4', 4, 8, 1, NOW(), NOW()),
('Volleyball 6x6', '6x6', 6, 12, 1, NOW(), NOW());

-- Insert Volleyball Category-Type Relationships
-- Categories: 1=-15, 2=-17, 3=-19
-- Types: 1=4x4, 2=6x6
INSERT INTO volleyball_categories_types (volleyball_category_id, volleyball_type_id, created, modified) VALUES
(1, 1, NOW(), NOW()),  -- -15 with 4x4
(2, 1, NOW(), NOW()),  -- -17 with 4x4
(2, 2, NOW(), NOW()),  -- -17 with 6x6
(3, 2, NOW(), NOW());  -- -19 with 6x6

-- =====================================================
-- BEACH VOLLEYBALL
-- =====================================================

-- Insert Beach Volleyball Types
INSERT INTO beachvolley_types (name, code, min_players, max_players, active, created, modified) VALUES
('Beach Volley 2x2', '2x2', 2, 4, 1, NOW(), NOW()),
('Beach Volley 4x4', '4x4', 4, 8, 1, NOW(), NOW());

-- Insert Beach Volleyball Category-Type Relationships
-- Categories: 1=-17, 2=-21, 3=+21
-- Types: 1=2x2, 2=4x4
INSERT INTO beachvolley_categories_types (beachvolley_category_id, beachvolley_type_id, created, modified) VALUES
(1, 1, NOW(), NOW()),  -- -17 with 2x2
(2, 1, NOW(), NOW()),  -- -21 with 2x2
(2, 2, NOW(), NOW()),  -- -21 with 4x4
(3, 1, NOW(), NOW()),  -- +21 with 2x2
(3, 2, NOW(), NOW());  -- +21 with 4x4

-- =====================================================
-- VERIFICATION QUERIES
-- =====================================================

-- Show final counts
SELECT 'Basketball Types' as Table_Name, COUNT(*) as Count FROM basketball_types
UNION ALL
SELECT 'Basketball Relationships', COUNT(*) FROM basketball_categories_types
UNION ALL
SELECT 'Handball Types', COUNT(*) FROM handball_types
UNION ALL
SELECT 'Handball Relationships', COUNT(*) FROM handball_categories_types
UNION ALL
SELECT 'Volleyball Types', COUNT(*) FROM volleyball_types
UNION ALL
SELECT 'Volleyball Relationships', COUNT(*) FROM volleyball_categories_types
UNION ALL
SELECT 'Beach Volleyball Types', COUNT(*) FROM beachvolley_types
UNION ALL
SELECT 'Beach Volleyball Relationships', COUNT(*) FROM beachvolley_categories_types
UNION ALL
SELECT 'Football Types (existing)', COUNT(*) FROM football_types
UNION ALL
SELECT 'Football Relationships (existing)', COUNT(*) FROM football_categories_types;

-- Show Basketball relationships with names
SELECT 'BASKETBALL RELATIONSHIPS:' as Info, '' as Category, '' as Type;
SELECT '' as Info, bc.name as Category, bt.name as Type 
FROM basketball_categories_types bct
JOIN basketball_categories bc ON bct.basketball_category_id = bc.id
JOIN basketball_types bt ON bct.basketball_type_id = bt.id
ORDER BY bc.id, bt.id;

-- Show Handball relationships with names  
SELECT 'HANDBALL RELATIONSHIPS:' as Info, '' as Category, '' as Type;
SELECT '' as Info, hc.name as Category, ht.name as Type
FROM handball_categories_types hct
JOIN handball_categories hc ON hct.handball_category_id = hc.id
JOIN handball_types ht ON hct.handball_type_id = ht.id
ORDER BY hc.id, ht.id;

-- Show Volleyball relationships with names
SELECT 'VOLLEYBALL RELATIONSHIPS:' as Info, '' as Category, '' as Type;
SELECT '' as Info, vc.name as Category, vt.name as Type
FROM volleyball_categories_types vct
JOIN volleyball_categories vc ON vct.volleyball_category_id = vc.id
JOIN volleyball_types vt ON vct.volleyball_type_id = vt.id
ORDER BY vc.id, vt.id;

-- Show Beach Volleyball relationships with names
SELECT 'BEACH VOLLEYBALL RELATIONSHIPS:' as Info, '' as Category, '' as Type;
SELECT '' as Info, bvc.name as Category, bvt.name as Type
FROM beachvolley_categories_types bvct
JOIN beachvolley_categories bvc ON bvct.beachvolley_category_id = bvc.id
JOIN beachvolley_types bvt ON bvct.beachvolley_type_id = bvt.id
ORDER BY bvc.id, bvt.id;

-- Script completed successfully!