-- Drop tables if they exist
DROP TABLE IF EXISTS `Product_Categories`;
DROP TABLE IF EXISTS `Inventory_Transactions`;
DROP TABLE IF EXISTS `Order_Items`;
DROP TABLE IF EXISTS `Orders`;
DROP TABLE IF EXISTS `Suppliers`;
DROP TABLE IF EXISTS `Products`;
DROP TABLE IF EXISTS `Categories`;
DROP TABLE IF EXISTS `Users`;
DROP TABLE IF EXISTS `Customers`;


-- Create Users table
CREATE TABLE IF NOT EXISTS `Users` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `username` VARCHAR(255),
  `password` VARCHAR(255),
  `email` VARCHAR(255),
  `role` ENUM('admin','manager','staff'),
  `profile_picture` VARCHAR(255), 
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Create Products table
CREATE TABLE IF NOT EXISTS `Products` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `name` VARCHAR(255),
  `description` TEXT,
  `sku` VARCHAR(255) UNIQUE,
  `barcode` VARCHAR(255),
  `price` DECIMAL(10,2),
  `cost` DECIMAL(10,2),
  `quantity` INT UNSIGNED,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

ALTER TABLE Products
ADD CONSTRAINT chk_quantity_non_negative CHECK (quantity >= 0);


-- Create Customers table
CREATE TABLE IF NOT EXISTS `Customers` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `first_name` VARCHAR(255),
  `last_name` VARCHAR(255),
  `email` VARCHAR(255),
  `phone_number` VARCHAR(16),
  `address` VARCHAR(255),
  `type` ENUM('regular','wholesale','vip'),
  `company_name` VARCHAR(255),
  `payment_method` ENUM('cash','credit_card','debit_card','paypal'),
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Create Suppliers table
CREATE TABLE IF NOT EXISTS `Suppliers` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `name` VARCHAR(255),
  `contact_person` VARCHAR(255),
  `phone` VARCHAR(20),
  `email` VARCHAR(255),
  `address` VARCHAR(255),
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Create Orders table
CREATE TABLE IF NOT EXISTS `Orders` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `user_id` INT,
  `customer_id` INT,
  `total_amount` DECIMAL(12,2),
  `status` ENUM('pending','completed','canceled'),
  `order_date` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `delivery_date` TIMESTAMP NULL DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`user_id`) REFERENCES `Users` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`customer_id`) REFERENCES `Customers` (`id`) ON DELETE CASCADE
);

-- Create Order_Items table
CREATE TABLE IF NOT EXISTS `Order_Items` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `order_id` INT,
  `product_id` INT,
  `quantity` INT,
  `price` DECIMAL(10,2),
  `total` DECIMAL(10,2),
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`order_id`) REFERENCES `Orders` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`product_id`) REFERENCES `Products` (`id`) ON DELETE RESTRICT
);

-- Create Inventory_Transactions table
CREATE TABLE IF NOT EXISTS `Inventory_Transactions` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `product_id` INT,
  `transaction_type` ENUM('restock','sale','return','adjustment'),
  `quantity` INT,
  `transaction_date` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `user_id` INT,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`product_id`) REFERENCES `Products` (`id`) ON DELETE RESTRICT,
  FOREIGN KEY (`user_id`) REFERENCES `Users` (`id`) ON DELETE CASCADE
);

-- Create Categories table
CREATE TABLE IF NOT EXISTS `Categories` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `name` VARCHAR(255),
  `description` TEXT,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Create Product_Categories table
CREATE TABLE IF NOT EXISTS `Product_Categories` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `product_id` INT,
  `category_id` INT,
  FOREIGN KEY (`product_id`) REFERENCES `Products` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`category_id`) REFERENCES `Categories` (`id`) ON DELETE CASCADE
);


-- Users table indexing
CREATE INDEX idx_username ON Users(username);
CREATE INDEX idx_email ON Users(email);

-- Products table indexing
CREATE INDEX idx_product_name ON Products(name);
CREATE INDEX idx_sku ON Products(sku);
CREATE INDEX idx_barcode ON Products(barcode);

-- Customers table indexing
CREATE INDEX idx_customer_email ON Customers(email);
CREATE INDEX idx_phone_number ON Customers(phone_number);
CREATE INDEX idx_last_name ON Customers(last_name);

-- Suppliers table indexing
CREATE INDEX idx_supplier_name ON Suppliers(name);
CREATE INDEX idx_supplier_email ON Suppliers(email);

-- Orders table indexing (already exists)
CREATE INDEX idx_user_id ON Orders(user_id);
CREATE INDEX idx_customer_id ON Orders(customer_id);

-- Order_Items table indexing
CREATE INDEX idx_order_id ON Order_Items(order_id);
CREATE INDEX idx_product_id ON Order_Items(product_id);

-- Inventory_Transactions table indexing
CREATE INDEX idx_product_id ON Inventory_Transactions(product_id);
CREATE INDEX idx_transaction_type ON Inventory_Transactions(transaction_type);

-- Categories table indexing
CREATE INDEX idx_category_name ON Categories(name);
