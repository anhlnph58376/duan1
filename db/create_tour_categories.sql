-- Create minimal tour_categories table and sample data
CREATE TABLE IF NOT EXISTS `tour_categories` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(191) NOT NULL,
  `slug` VARCHAR(191) DEFAULT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Sample categories
INSERT INTO `tour_categories` (`name`, `slug`, `created_at`) VALUES
('Trong nước', 'trong-nuoc', NOW()),
('Quốc tế', 'quoc-te', NOW());
