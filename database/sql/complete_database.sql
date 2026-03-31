-- ============================================
-- CREATE DATABASE
-- ============================================
CREATE DATABASE IF NOT EXISTS dainty_dream;
USE dainty_dream;

-- ============================================
-- USERS (ADMIN + EMPLOYEE)
-- ============================================

CREATE TABLE m_users (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255),
    email VARCHAR(255) UNIQUE,
    password VARCHAR(255),
    role ENUM('admin','employee') DEFAULT 'employee',
    status ENUM('active','inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- ============================================
-- MASTER TABLES
-- ============================================

CREATE TABLE m_categories (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255)
);

CREATE TABLE m_suppliers (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255),
    city VARCHAR(100)
);

CREATE TABLE m_customers (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255),
    city VARCHAR(100)
);

CREATE TABLE m_products (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    sku VARCHAR(100) UNIQUE,
    name VARCHAR(255),
    category_id BIGINT UNSIGNED,
    supplier_id BIGINT UNSIGNED,
    product_condition VARCHAR(50),
    quantity INT DEFAULT 0,

    FOREIGN KEY (category_id) REFERENCES m_categories(id),
    FOREIGN KEY (supplier_id) REFERENCES m_suppliers(id)
);

CREATE TABLE m_stock_movements (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    product_id BIGINT UNSIGNED,
    user_id BIGINT UNSIGNED,
    type ENUM('in','out'),
    quantity INT,
    notes TEXT,

    FOREIGN KEY (product_id) REFERENCES m_products(id),
    FOREIGN KEY (user_id) REFERENCES m_users(id)
);

-- ============================================
-- TRANSACTION TABLES
-- ============================================

CREATE TABLE t_incoming_transactions (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    product_id BIGINT UNSIGNED,
    supplier_id BIGINT UNSIGNED,
    user_id BIGINT UNSIGNED,
    quantity INT,
    transaction_date DATE,

    FOREIGN KEY (product_id) REFERENCES m_products(id),
    FOREIGN KEY (supplier_id) REFERENCES m_suppliers(id),
    FOREIGN KEY (user_id) REFERENCES m_users(id)
);

CREATE TABLE t_outgoing_transactions (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    product_id BIGINT UNSIGNED,
    customer_id BIGINT UNSIGNED,
    user_id BIGINT UNSIGNED,
    quantity INT,
    transaction_date DATE,

    FOREIGN KEY (product_id) REFERENCES m_products(id),
    FOREIGN KEY (customer_id) REFERENCES m_customers(id),
    FOREIGN KEY (user_id) REFERENCES m_users(id)
);

-- ============================================
-- DEFAULT DATA
-- ============================================

-- ✅ ONLY 1 ADMIN (can create employees later)
INSERT INTO m_users (name,email,password,role) VALUES
('Admin','admin@dainty.com','123','admin');

INSERT INTO m_categories (name) VALUES
('Clothing'),('Shoes');

INSERT INTO m_suppliers (name,city) VALUES
('Supplier A','Surabaya');

INSERT INTO m_products (sku,name,category_id,supplier_id,quantity) VALUES
('SKU001','T-Shirt',1,1,50);

INSERT INTO m_customers (name,city) VALUES
('Budi','Surabaya');