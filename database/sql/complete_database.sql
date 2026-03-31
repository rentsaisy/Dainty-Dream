-- ============================================
-- DAINTY DREAM IMS - COMPLETE DATABASE SCHEMA
-- Simplified Structure (Matching Current Database)
-- ============================================

CREATE DATABASE IF NOT EXISTS dainty_dream;
USE dainty_dream;

-- ============================================
-- LARAVEL SYSTEM TABLES
-- ============================================

-- Sessions Table (For session management)
CREATE TABLE IF NOT EXISTS sessions (
    id VARCHAR(255) PRIMARY KEY,
    user_id BIGINT UNSIGNED NULL,
    ip_address VARCHAR(45) NULL,
    user_agent TEXT NULL,
    payload LONGTEXT NOT NULL,
    last_activity INT NOT NULL,
    INDEX idx_user_id (user_id),
    INDEX idx_last_activity (last_activity)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Cache Table (For caching)
CREATE TABLE IF NOT EXISTS cache (
    `key` VARCHAR(255) PRIMARY KEY,
    value MEDIUMTEXT NOT NULL,
    expiration INT NOT NULL,
    INDEX idx_expiration (expiration)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Password Reset Tokens (For password recovery)
CREATE TABLE IF NOT EXISTS password_reset_tokens (
    email VARCHAR(255) PRIMARY KEY,
    token VARCHAR(255) NOT NULL,
    created_at TIMESTAMP NULL,
    INDEX idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Jobs Table (For job queue)
CREATE TABLE IF NOT EXISTS jobs (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    queue VARCHAR(255) NOT NULL,
    payload LONGTEXT NOT NULL,
    attempts TINYINT UNSIGNED NOT NULL DEFAULT 0,
    reserved_at INT UNSIGNED NULL,
    available_at INT UNSIGNED NOT NULL,
    created_at INT UNSIGNED NOT NULL,
    INDEX idx_queue (queue),
    INDEX idx_reserved_at (reserved_at),
    INDEX idx_available_at (available_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- MASTER DATA TABLES (m_ prefix)
-- ============================================

-- Users Table - For admin and employee authentication
CREATE TABLE m_users (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin','employee') DEFAULT 'employee',
    status ENUM('active','inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Categories Table - Product classification
CREATE TABLE m_categories (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Suppliers Table - Vendor information (simplified)
CREATE TABLE m_suppliers (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    city VARCHAR(100) NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Customers Table - Customer information (simplified)
CREATE TABLE m_customers (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    city VARCHAR(100) NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Products Table - Inventory items
CREATE TABLE m_products (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    sku VARCHAR(100) UNIQUE NOT NULL,
    name VARCHAR(255) NOT NULL,
    category_id BIGINT UNSIGNED NOT NULL,
    supplier_id BIGINT UNSIGNED NOT NULL,
    product_condition VARCHAR(50) NULL,
    quantity INT DEFAULT 0,
    FOREIGN KEY (category_id) REFERENCES m_categories(id) ON DELETE RESTRICT,
    FOREIGN KEY (supplier_id) REFERENCES m_suppliers(id) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Stock Movements Table - Activity log for inventory
CREATE TABLE m_stock_movements (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    product_id BIGINT UNSIGNED NOT NULL,
    user_id BIGINT UNSIGNED NOT NULL,
    type ENUM('in','out') NOT NULL COMMENT 'in=stock in, out=stock out',
    quantity INT NOT NULL,
    notes TEXT NULL,
    FOREIGN KEY (product_id) REFERENCES m_products(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES m_users(id) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- TRANSACTION TABLES (t_ prefix)
-- ============================================

-- Incoming Transactions Table - Stock purchases from suppliers
CREATE TABLE t_incoming_transactions (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    product_id BIGINT UNSIGNED NOT NULL,
    supplier_id BIGINT UNSIGNED NOT NULL,
    user_id BIGINT UNSIGNED NOT NULL,
    quantity INT NOT NULL,
    transaction_date DATE NOT NULL,
    FOREIGN KEY (product_id) REFERENCES m_products(id) ON DELETE RESTRICT,
    FOREIGN KEY (supplier_id) REFERENCES m_suppliers(id) ON DELETE RESTRICT,
    FOREIGN KEY (user_id) REFERENCES m_users(id) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Outgoing Transactions Table - Sales to customers
CREATE TABLE t_outgoing_transactions (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    product_id BIGINT UNSIGNED NOT NULL,
    customer_id BIGINT UNSIGNED NOT NULL,
    user_id BIGINT UNSIGNED NOT NULL,
    quantity INT NOT NULL,
    transaction_date DATE NOT NULL,
    FOREIGN KEY (product_id) REFERENCES m_products(id) ON DELETE RESTRICT,
    FOREIGN KEY (customer_id) REFERENCES m_customers(id) ON DELETE RESTRICT,
    FOREIGN KEY (user_id) REFERENCES m_users(id) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- INSERT SAMPLE DATA
-- ============================================

-- Insert Users
INSERT INTO m_users (name, email, password, role, status) VALUES
('Admin User', 'admin@thriftims.com', '$2y$12$60Nso0yA0TDGqJKqaqUb9.d9EdlS/ul1gKhm9vWqCqIqyGu1qb7Xy', 'admin', 'active'),
('Employee User', 'employee@thriftims.com', '$2y$12$KWJDuS5UhE0pGRGPiJMwWeVG0oH6cOHp8L2L4W5i8qqP9YzS3RYVA', 'employee', 'active');

-- Insert Categories
INSERT INTO m_categories (name) VALUES
('Clothing'),
('Shoes');

-- Insert Suppliers
INSERT INTO m_suppliers (name, city) VALUES
('Supplier A', 'Surabaya');

-- Insert Products
INSERT INTO m_products (sku, name, category_id, supplier_id, product_condition, quantity) VALUES
('SKU001', 'T-Shirt', 1, 1, NULL, 50);

-- Insert Customers
INSERT INTO m_customers (name, city) VALUES
('Budi', 'Surabaya');

-- Insert Stock Movement
INSERT INTO m_stock_movements (product_id, user_id, type, quantity, notes) VALUES
(1, 1, 'in', 10, 'Initial stock');

-- Insert Incoming Transaction
INSERT INTO t_incoming_transactions (product_id, supplier_id, user_id, quantity, transaction_date) VALUES
(1, 1, 1, 10, DATE_SUB(CURDATE(), INTERVAL 5 DAY));

-- Insert Outgoing Transaction
INSERT INTO t_outgoing_transactions (product_id, customer_id, user_id, quantity, transaction_date) VALUES
(1, 1, 2, 2, DATE_SUB(CURDATE(), INTERVAL 1 DAY));