-- Populate Football Categories and Types
-- Run this in your MySQL database

USE rabatjeunesse2025;

-- First, let's add football categories (age groups)
INSERT INTO football_categories (name, birth_date_start, birth_date_end, active, created, modified) VALUES
('-12', '2014-01-01', '2015-12-31', 1, NOW(), NOW()),
('-15', '2011-01-01', '2013-12-31', 1, NOW(), NOW()),
('-18', '2008-01-01', '2010-12-31', 1, NOW(), NOW()),
('Senior', '1989-01-01', '2007-12-31', 1, NOW(), NOW());

-- Add football types (field formats)
INSERT INTO football_types (name, code, min_players, max_players, active, created, modified) VALUES
('Football 5x5', '5x5', 5, 5, 1, NOW(), NOW()),
('Football 6x6', '6x6', 6, 6, 1, NOW(), NOW()),
('Football 11x11', '11x11', 11, 11, 1, NOW(), NOW());

-- Now create the relationships between categories and types
-- -12 can only play 6x6 (smaller field for younger players)
INSERT INTO football_categories_types (football_category_id, football_type_id, created, modified)
SELECT 
    (SELECT id FROM football_categories WHERE name = '-12') as category_id,
    (SELECT id FROM football_types WHERE code = '6x6') as type_id,
    NOW(),
    NOW();

-- -15 can play both 6x6 and 11x11 (transitional age)
INSERT INTO football_categories_types (football_category_id, football_type_id, created, modified)
SELECT 
    (SELECT id FROM football_categories WHERE name = '-15') as category_id,
    (SELECT id FROM football_types WHERE code = '6x6') as type_id,
    NOW(),
    NOW();

INSERT INTO football_categories_types (football_category_id, football_type_id, created, modified)
SELECT 
    (SELECT id FROM football_categories WHERE name = '-15') as category_id,
    (SELECT id FROM football_types WHERE code = '11x11') as type_id,
    NOW(),
    NOW();

-- -18 can play all formats
INSERT INTO football_categories_types (football_category_id, football_type_id, created, modified)
SELECT 
    (SELECT id FROM football_categories WHERE name = '-18') as category_id,
    (SELECT id FROM football_types WHERE code = '5x5') as type_id,
    NOW(),
    NOW();

INSERT INTO football_categories_types (football_category_id, football_type_id, created, modified)
SELECT 
    (SELECT id FROM football_categories WHERE name = '-18') as category_id,
    (SELECT id FROM football_types WHERE code = '6x6') as type_id,
    NOW(),
    NOW();

INSERT INTO football_categories_types (football_category_id, football_type_id, created, modified)
SELECT 
    (SELECT id FROM football_categories WHERE name = '-18') as category_id,
    (SELECT id FROM football_types WHERE code = '11x11') as type_id,
    NOW(),
    NOW();

-- Senior can play all formats
INSERT INTO football_categories_types (football_category_id, football_type_id, created, modified)
SELECT 
    (SELECT id FROM football_categories WHERE name = 'Senior') as category_id,
    (SELECT id FROM football_types WHERE code = '5x5') as type_id,
    NOW(),
    NOW();

INSERT INTO football_categories_types (football_category_id, football_type_id, created, modified)
SELECT 
    (SELECT id FROM football_categories WHERE name = 'Senior') as category_id,
    (SELECT id FROM football_types WHERE code = '6x6') as type_id,
    NOW(),
    NOW();

INSERT INTO football_categories_types (football_category_id, football_type_id, created, modified)
SELECT 
    (SELECT id FROM football_categories WHERE name = 'Senior') as category_id,
    (SELECT id FROM football_types WHERE code = '11x11') as type_id,
    NOW(),
    NOW();

-- Verify the data was inserted correctly
SELECT '=== FOOTBALL CATEGORIES ===' as info;
SELECT id, name, birth_date_start, birth_date_end, active FROM football_categories ORDER BY id;

SELECT '=== FOOTBALL TYPES ===' as info;
SELECT id, name, code, min_players, max_players, active FROM football_types ORDER BY id;

SELECT '=== CATEGORY-TYPE RELATIONSHIPS ===' as info;
SELECT 
    fc.name as category_name, 
    ft.name as type_name, 
    ft.code,
    fc.birth_date_start,
    fc.birth_date_end
FROM football_categories_types fct
JOIN football_categories fc ON fct.football_category_id = fc.id  
JOIN football_types ft ON fct.football_type_id = ft.id
ORDER BY fc.name, ft.name;