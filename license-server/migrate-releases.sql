-- Migration: Add releases table
-- Run this on the production database at campusos.devlecta.com

CREATE TABLE IF NOT EXISTS `releases` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `product_slug` VARCHAR(100) NOT NULL,
    `version` VARCHAR(20) NOT NULL,
    `file_name` VARCHAR(255) NOT NULL,
    `file_size` BIGINT DEFAULT 0,
    `file_hash` VARCHAR(64) DEFAULT '',
    `changelog` TEXT DEFAULT NULL,
    `requires_wp` VARCHAR(10) DEFAULT '6.0',
    `tested_wp` VARCHAR(10) DEFAULT '6.9',
    `requires_php` VARCHAR(10) DEFAULT '8.0',
    `is_active` TINYINT(1) DEFAULT 1,
    `downloads` INT DEFAULT 0,
    `released_by` VARCHAR(100) DEFAULT '',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY `idx_product_version` (`product_slug`, `version`),
    INDEX `idx_active` (`product_slug`, `is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
