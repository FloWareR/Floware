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
  `quantity` INT,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

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
  `total_amount` DECIMAL(10,2),
  `status` ENUM('pending','completed','canceled'),
  `order_date` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `delivery_date` TIMESTAMP NULL DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`user_id`) REFERENCES `Users` (`id`)
);

-- Create Order_Items table
CREATE TABLE IF NOT EXISTS `Order_Items` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `order_id` INT,
  `product_id` INT,
  `quantity` INT,
  `price` DECIMAL(10,2),
  `total` DECIMAL(10,2),
  FOREIGN KEY (`order_id`) REFERENCES `Orders` (`id`),
  FOREIGN KEY (`product_id`) REFERENCES `Products` (`id`)
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
  FOREIGN KEY (`product_id`) REFERENCES `Products` (`id`),
  FOREIGN KEY (`user_id`) REFERENCES `Users` (`id`)
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
  FOREIGN KEY (`product_id`) REFERENCES `Products` (`id`),
  FOREIGN KEY (`category_id`) REFERENCES `Categories` (`id`)
);
