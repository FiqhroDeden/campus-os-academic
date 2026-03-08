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
