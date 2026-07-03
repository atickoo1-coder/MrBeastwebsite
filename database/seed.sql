-- ============================================================
-- Seed Data for mrbeast_store
-- ============================================================
-- Default admin: admin / Admin@123
-- Default customer: john@example.com / Password123
-- 16 products matching the frontend JS product data
-- ============================================================

USE `mrbeast_store`;

-- ---------------------------------------------------------
-- Seed: admins
-- Password: Admin@123
-- ---------------------------------------------------------
INSERT INTO `admins` (`username`, `email`, `password`) VALUES
('admin', 'admin@mrbeaststore.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi')
ON DUPLICATE KEY UPDATE `username` = `username`;

-- ---------------------------------------------------------
-- Seed: categories
-- ---------------------------------------------------------
INSERT INTO `categories` (`id`, `name`, `slug`, `description`, `status`) VALUES
(1, 'T-Shirts', 't-shirts', 'All t-shirts and tees including glow-in-the-dark, limited edition, and signature designs.', 1),
(2, 'Hoodies & Sweatshirts', 'hoodies-sweatshirts', 'Premium hoodies and sweatshirts featuring Beast branding and exclusive collections.', 1),
(3, 'Shorts & Pants', 'shorts-pants', 'Comfortable shorts and pants for every occasion, from board shorts to flame shorts.', 1),
(4, 'Jerseys', 'jerseys', 'Baseball jerseys, soccer jerseys, and authentic Beast edition sportswear.', 1),
(5, 'Hats', 'hats', 'Beanies, caps, and hats featuring the iconic MrBeast logo and designs.', 1),
(6, 'Accessories', 'accessories', 'Socks, water bottles, baseballs, and other MrBeast branded accessories.', 1),
(7, 'Collections', 'collections', 'Special themed collections including Glow in the Dark, Naruto, Camo, and limited drops.', 1),
(8, 'Feastables', 'feastables', 'MrBeast Feastables chocolate bars and food-related merchandise.', 1)
ON DUPLICATE KEY UPDATE `name` = VALUES(`name`);

-- ---------------------------------------------------------
-- Seed: products
-- Matches the 16 products from frontend js/products.js
-- All products reference the correct category_id (1-8)
-- Includes colors, sizes, badges, and type fields
-- Images use picsum.photos placeholders matching the JS data
-- ---------------------------------------------------------
INSERT INTO `products` (`id`, `name`, `description`, `price`, `category_id`, `stock_quantity`, `sku`, `status`, `image`, `image_hover`, `colors`, `sizes`, `badges`, `type`) VALUES
(1,
 '500 Million Subscribers Tee',
 'Celebrate 500 million subscribers with this exclusive collector\'s edition tee. Premium cotton construction with bold front graphic.',
 24.99, 1, 250, 'MB-TEE-500M', 1,
 'https://picsum.photos/seed/tee1a/600/600', 'https://picsum.photos/seed/tee1b/600/600',
 '["#000000", "#ffffff", "#ff4d00"]', '["SM", "MD", "LG", "XL", "2XL"]', '["new", "limited"]', 'T-Shirt'),
(2,
 '500 Million Subscribers Hoodie',
 'Limited edition 500M celebration hoodie. Heavyweight fleece with embroidered details and custom drawcords.',
 44.99, 2, 150, 'MB-HOOD-500M', 1,
 'https://picsum.photos/seed/hoodie1a/600/600', 'https://picsum.photos/seed/hoodie1b/600/600',
 '["#000000", "#ffffff"]', '["SM", "MD", "LG", "XL", "2XL", "3XL"]', '["new", "limited"]', 'Hoodies & Sweatshirts'),
(3,
 'Beast Vibes Wave Tee',
 'Catch the wave with this summer-inspired tee. Soft cotton blend with a unique screen-printed design.',
 24.99, 1, 300, 'MB-TEE-WAVE', 1,
 'https://picsum.photos/seed/beastvibes1a/600/600', 'https://picsum.photos/seed/beastvibes1b/600/600',
 '["#0047ab", "#000000", "#ffffff"]', '["SM", "MD", "LG", "XL", "2XL"]', '["new"]', 'T-Shirt'),
(4,
 'Panther Print Board Shorts',
 'Make a statement with these all-over print board shorts. Quick-dry fabric with elastic waistband and mesh lining.',
 34.99, 3, 175, 'MB-SHRT-PANTHER', 1,
 'https://picsum.photos/seed/shorts1a/600/600', 'https://picsum.photos/seed/shorts1b/600/600',
 '["#1a3a5c", "#000000"]', '["SM", "MD", "LG", "XL", "2XL"]', '["new"]', 'Shorts & Pants'),
(5,
 'Authentic Beast Edition Baseball Jersey',
 'Official Beast Athletics baseball jersey. Mesh fabric with moisture-wicking technology and customized numbering.',
 59.99, 4, 100, 'MB-JERSEY-BB', 1,
 'https://picsum.photos/seed/jersey1a/600/600', 'https://picsum.photos/seed/jersey1b/600/600',
 '["#ffffff", "#000000"]', '["SM", "MD", "LG", "XL", "2XL", "3XL"]', '["restock", "trending"]', 'Jersey'),
(6,
 'MB Panther Baseball Shorts',
 'Performance baseball shorts with panther graphic. Built-in compression liner and zippered pockets.',
 34.99, 3, 200, 'MB-SHRT-BBPANTHER', 1,
 'https://picsum.photos/seed/panthershorts1a/600/600', 'https://picsum.photos/seed/panthershorts1b/600/600',
 '["#000000"]', '["SM", "MD", "LG", "XL", "2XL"]', '["restock", "trending"]', 'Shorts & Pants'),
(7,
 'Glow in the Dark Beast Socks',
 'Light up the night with these glow-in-the-dark crew socks. Comfort knit with reinforced heel and toe.',
 9.99, 6, 500, 'MB-SOCKS-GLOW', 1,
 'https://picsum.photos/seed/socks1a/600/600', 'https://picsum.photos/seed/socks1b/600/600',
 '["#00ff00"]', '["One Size"]', '["new", "trending"]', 'Accessories'),
(8,
 'Beast Edition Baseball - 2 Pack',
 'Official MrBeast baseballs. Regulation size with Beast logo. Perfect for backyard practice or display.',
 12.99, 6, 400, 'MB-BALL-2PK', 1,
 'https://picsum.photos/seed/baseball1a/600/600', 'https://picsum.photos/seed/baseball1b/600/600',
 '[]', '["One Size"]', '["restock", "trending"]', 'Accessories'),
(9,
 'Beast Vibes Wave Board Shorts',
 'Summer-ready board shorts with wave pattern. 4-way stretch fabric with zip closure and drainage mesh.',
 34.99, 3, 180, 'MB-SHRT-WAVE', 1,
 'https://picsum.photos/seed/waveshorts1a/600/600', 'https://picsum.photos/seed/waveshorts1b/600/600',
 '["#0047ab", "#ff6b35", "#000000"]', '["SM", "MD", "LG", "XL", "2XL"]', '["new"]', 'Shorts & Pants'),
(10,
 'Beast FC Soccer Jersey',
 'Represent Beast FC in this premium soccer jersey. Breathable fabric with moisture management and official crest.',
 44.99, 4, 125, 'MB-JERSEY-FC', 1,
 'https://picsum.photos/seed/soccerjersey1a/600/600', 'https://picsum.photos/seed/soccerjersey1b/600/600',
 '["#0047ab", "#ffffff"]', '["SM", "MD", "LG", "XL", "2XL", "3XL"]', '["new", "trending"]', 'Jersey'),
(11,
 'All Summer Long Water Bottle',
 'Stay hydrated in style. Double-wall insulated stainless steel. Keeps drinks cold for 24 hours.',
 12.99, 6, 350, 'MB-BOTTLE-SUMMER', 1,
 'https://picsum.photos/seed/bottle1a/600/600', 'https://picsum.photos/seed/bottle1b/600/600',
 '[]', '["One Size"]', '["new"]', 'Accessories'),
(12,
 'Beast FC Soccer Shorts',
 'Beast FC match shorts. Lightweight performance fabric with elastic waist and team crest.',
 34.99, 3, 160, 'MB-SHRT-FC', 1,
 'https://picsum.photos/seed/soccershorts1a/600/600', 'https://picsum.photos/seed/soccershorts1b/600/600',
 '["#0047ab", "#000000"]', '["SM", "MD", "LG", "XL", "2XL"]', '["new", "trending"]', 'Shorts & Pants'),
(13,
 'Beast Edition Plate Hoodie',
 'Bold plate graphic hoodie. Heavyweight cotton fleece with ribbed cuffs and hem. Oversized fit.',
 44.99, 2, 90, 'MB-HOOD-PLATE', 1,
 'https://picsum.photos/seed/platehoodie1a/600/600', 'https://picsum.photos/seed/platehoodie1b/600/600',
 '["#000000", "#ffffff"]', '["SM", "MD", "LG", "XL", "2XL", "3XL"]', '["new", "trending"]', 'Hoodies & Sweatshirts'),
(14,
 'Glow In The Dark Panther Tee',
 'Panther graphic that glows in the dark. Ultra-soft ringspun cotton. A fan favorite.',
 24.99, 1, 275, 'MB-TEE-GLOWPANTHER', 1,
 'https://picsum.photos/seed/glowtee1a/600/600', 'https://picsum.photos/seed/glowtee1b/600/600',
 '["#000000"]', '["SM", "MD", "LG", "XL", "2XL"]', '[]', 'T-Shirt'),
(15,
 'MrBeast Panther Hat - Black',
 'Premium embroidered panther cap. Structured fit with curved brim and adjustable snapback closure.',
 24.99, 5, 220, 'MB-HAT-PANTHER', 1,
 'https://picsum.photos/seed/hat1a/600/600', 'https://picsum.photos/seed/hat1b/600/600',
 '["#000000", "#0047ab"]', '["One Size"]', '["trending"]', 'Hat'),
(16,
 'Beast Athletics Flame Shorts',
 'Flame graphic training shorts. Performance fabric with interior pocket and Beast Athletics branding.',
 34.99, 3, 190, 'MB-SHRT-FLAME', 1,
 'https://picsum.photos/seed/flameshorts1a/600/600', 'https://picsum.photos/seed/flameshorts1b/600/600',
 '["#ff6b35", "#000000"]', '["SM", "MD", "LG", "XL", "2XL"]', '["new", "trending"]', 'Shorts & Pants')
ON DUPLICATE KEY UPDATE `name` = VALUES(`name`);

-- ---------------------------------------------------------
-- Seed: sample customer account
-- Email: john@example.com
-- Password: Password123
-- ---------------------------------------------------------
INSERT INTO `users` (`first_name`, `last_name`, `email`, `password`, `phone`, `address_line1`, `city`, `state`, `zip_code`, `country`) VALUES
('John', 'Doe', 'john@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '+1 (555) 123-4567', '123 Main Street', 'New York', 'NY', '10001', 'United States')
ON DUPLICATE KEY UPDATE `email` = VALUES(`email`);
