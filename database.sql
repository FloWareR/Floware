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
  `subscribed` BOOLEAN DEFAULT FALSE,
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
  FOREIGN KEY (`product_id`) REFERENCES `Products` (`id`) ON DELETE CASCADE
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

CREATE TABLE IF NOT EXISTS One_Time_Codes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(10) NOT NULL,
    expires_at DATETIME NOT NULL DEFAULT (CURRENT_TIMESTAMP + INTERVAL 24 HOUR),
    used BOOLEAN DEFAULT FALSE
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

-- Seed default admin user (password: adminpassword)
INSERT INTO `Users` (`username`, `password`, `email`, `role`) VALUES ('admin', '$2y$10$mRLF33yHBL6CHZ/F2UHZF.rUo38jegCdNtahjv7APE/3hwwnRTL/u', 'admin@floware.studio', 'admin');

-- Seed default Users
INSERT INTO `Users` (`username`, `password`, `email`, `role`) VALUES 
('manager', '$2y$10$mRLF33yHBL6CHZ/F2UHZF.rUo38jegCdNtahjv7APE/3hwwnRTL/u', 'manager@floware.studio', 'manager'),
('staff', '$2y$10$mRLF33yHBL6CHZ/F2UHZF.rUo38jegCdNtahjv7APE/3hwwnRTL/u', 'staff@floware.studio', 'staff');

-- Seed default Categories
INSERT INTO `Categories` (`name`, `description`) VALUES 
('Electronics', 'Gadgets, appliances, and electronic devices.'),
('Clothing', 'Apparel and accessories for men and women.'),
('Home & Garden', 'Furniture, decor, and gardening tools.'),
('Sports', 'Sporting goods and outdoor equipment.');

-- Seed default Suppliers
INSERT INTO `Suppliers` (`name`, `contact_person`, `phone`, `email`, `address`) VALUES 
('TechSource Solutions', 'Alice Smith', '555-0101', 'alice@techsource.com', '123 Tech Lane, Silicon Valley'),
('Global Apparel Co.', 'Bob Johnson', '555-0102', 'bob@globalapparel.com', '456 Fashion Blvd, New York'),
('Home Essentials Inc.', 'Carol Williams', '555-0103', 'carol@homeessentials.com', '789 Comfort St, Chicago');

-- Seed default Products
INSERT INTO `Products` (`name`, `description`, `sku`, `barcode`, `price`, `cost`, `quantity`) VALUES 
('Wireless Mouse', 'Ergonomic wireless mouse with USB receiver.', 'ELEC-001', '1234567890123', 29.99, 15.00, 100),
('Mechanical Keyboard', 'RGB mechanical keyboard with blue switches.', 'ELEC-002', '1234567890124', 89.99, 45.00, 50),
('Cotton T-Shirt', '100% cotton casual t-shirt.', 'CLOT-001', '1234567890125', 19.99, 8.00, 200),
('Jeans', 'Classic blue denim jeans.', 'CLOT-002', '1234567890126', 49.99, 20.00, 150),
('Coffee Maker', 'Drip coffee maker with programmable timer.', 'HOME-001', '1234567890127', 59.99, 30.00, 30),
('Yoga Mat', 'Non-slip exercise yoga mat.', 'SPOR-001', '1234567890128', 25.00, 10.00, 75);

-- Seed default Product_Categories
INSERT INTO `Product_Categories` (`product_id`, `category_id`) VALUES 
(1, 1), -- Wireless Mouse -> Electronics
(2, 1), -- Mechanical Keyboard -> Electronics
(3, 2), -- Cotton T-Shirt -> Clothing
(4, 2), -- Jeans -> Clothing
(5, 3), -- Coffee Maker -> Home & Garden
(6, 4); -- Yoga Mat -> Sports

-- Seed default Customers
INSERT INTO `Customers` (`first_name`, `last_name`, `email`, `phone_number`, `address`, `type`, `company_name`, `payment_method`) VALUES 
('John', 'Doe', 'johndoe@example.com', '555-0201', '321 Maple St, Springfield', 'regular', NULL, 'credit_card'),
('Jane', 'Smith', 'janesmith@example.com', '555-0202', '654 Oak St, Springfield', 'vip', NULL, 'paypal'),
('Acme Corp', 'Buyer', 'buyer@acmecorp.com', '555-0203', '987 Industrial Pkwy, Metropolis', 'wholesale', 'Acme Corporation', 'debit_card');

-- Seed default Orders
INSERT INTO `Orders` (`user_id`, `customer_id`, `total_amount`, `status`) VALUES 
(1, 1, 119.98, 'completed'),
(2, 2, 49.99, 'pending'),
(3, 3, 299.90, 'completed');

-- Seed default Order_Items
INSERT INTO `Order_Items` (`order_id`, `product_id`, `quantity`, `price`, `total`) VALUES 
(1, 1, 1, 29.99, 29.99),
(1, 2, 1, 89.99, 89.99),
(2, 4, 1, 49.99, 49.99),
(3, 1, 10, 29.99, 299.90);

-- Seed default Inventory_Transactions
INSERT INTO `Inventory_Transactions` (`product_id`, `transaction_type`, `quantity`, `user_id`) VALUES 
(1, 'restock', 100, 1),
(2, 'restock', 50, 1),
(3, 'restock', 200, 1),
(4, 'restock', 150, 1),
(5, 'restock', 30, 1),
(6, 'restock', 75, 1),
(1, 'sale', -1, 1),
(2, 'sale', -1, 1),
(4, 'sale', -1, 2),
(1, 'sale', -10, 3);
