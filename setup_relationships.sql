-- SQL Script to setup Football Category-Type Relationships
-- Run this in your MySQL database

-- First, let's see what data exists
SELECT 'FOOTBALL CATEGORIES:' as info;
SELECT id, name, active FROM football_categories WHERE active = 1 ORDER BY id;

SELECT 'FOOTBALL TYPES:' as info;
SELECT id, name, code, min_players, max_players, active FROM football_types WHERE active = 1 ORDER BY id;

-- Check if junction table exists
SELECT 'JUNCTION TABLE CHECK:' as info;
SHOW TABLES LIKE 'football_categories_types';

-- Show existing relationships
SELECT 'EXISTING RELATIONSHIPS:' as info;
SELECT 
    fc.name as category_name, 
    ft.name as type_name, 
    ft.code
FROM football_categories_types fct
JOIN football_categories fc ON fct.football_category_id = fc.id  
JOIN football_types ft ON fct.football_type_id = ft.id
ORDER BY fc.name, ft.name;

-- Example: Insert relationships (ADJUST IDs based on your data above!)
-- Uncomment and modify these based on the results from the SELECT statements above:

-- Example relationships (replace with your actual IDs):
-- INSERT INTO football_categories_types (football_category_id, football_type_id, created, modified) VALUES
-- (10, 2, NOW(), NOW()),  -- -12 category → 6x6 type (adjust IDs!)
-- (11, 2, NOW(), NOW()),  -- -15 category → 6x6 type  
-- (11, 3, NOW(), NOW()),  -- -15 category → 11x11 type
-- (12, 1, NOW(), NOW()),  -- Senior category → 5x5 type
-- (12, 2, NOW(), NOW()),  -- Senior category → 6x6 type
-- (12, 3, NOW(), NOW());  -- Senior category → 11x11 type

-- After inserting, verify the relationships:
-- SELECT 
--     fc.name as category_name, 
--     ft.name as type_name, 
--     ft.code
-- FROM football_categories_types fct
-- JOIN football_categories fc ON fct.football_category_id = fc.id  
-- JOIN football_types ft ON fct.football_type_id = ft.id
-- ORDER BY fc.name, ft.name;