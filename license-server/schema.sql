CREATE TABLE IF NOT EXISTS `licenses` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `license_key` VARCHAR(64) UNIQUE NOT NULL,
    `customer_email` VARCHAR(255) DEFAULT '',
    `customer_name` VARCHAR(255) DEFAULT '',
    `product_type` ENUM('theme','bundle') DEFAULT 'bundle',
    `max_activations` INT DEFAULT 1,
    `activated_domain` VARCHAR(255) DEFAULT NULL,
    `activated_at` DATETIME DEFAULT NULL,
    `expires_at` DATETIME DEFAULT NULL,
    `status` ENUM('active','expired','revoked','inactive') DEFAULT 'inactive',
    `notes` TEXT DEFAULT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX `idx_status` (`status`),
    INDEX `idx_domain` (`activated_domain`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `admin_users` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `username` VARCHAR(100) UNIQUE NOT NULL,
    `password_hash` VARCHAR(255) NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `api_logs` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `endpoint` VARCHAR(50),
    `license_key` VARCHAR(64),
    `domain` VARCHAR(255),
    `ip_address` VARCHAR(45),
    `response_code` INT,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX `idx_created` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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
