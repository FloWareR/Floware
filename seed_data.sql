INSERT INTO `Products` (`name`, `description`, `sku`, `barcode`, `price`, `cost`, `quantity`) VALUES 
('Laptop Pro', 'High end laptop', 'LAP-001', '123456789', 1500.00, 1000.00, 50),
('Wireless Mouse', 'Ergonomic mouse', 'MOU-001', '123456790', 25.00, 10.00, 200),
('Mechanical Keyboard', 'RGB Keyboard', 'KEY-001', '123456791', 120.00, 60.00, 150),
('Monitor 27"', '4K Display', 'MON-001', '123456792', 300.00, 200.00, 80),
('USB-C Hub', 'Multi-port adapter', 'HUB-001', '123456793', 45.00, 20.00, 300);

INSERT INTO `Customers` (`first_name`, `last_name`, `email`, `phone_number`, `address`, `type`, `company_name`, `payment_method`) VALUES 
('John', 'Doe', 'john.doe@example.com', '555-0100', '123 Elm St', 'regular', NULL, 'credit_card'),
('Jane', 'Smith', 'jane.smith@example.com', '555-0101', '456 Oak St', 'vip', 'TechCorp', 'paypal'),
('Bob', 'Johnson', 'bob.johnson@example.com', '555-0102', '789 Pine St', 'wholesale', 'Distributors Inc', 'cash'),
('Alice', 'Williams', 'alice.williams@example.com', '555-0103', '321 Maple St', 'regular', NULL, 'debit_card'),
('Charlie', 'Brown', 'charlie.brown@example.com', '555-0104', '654 Birch St', 'regular', NULL, 'credit_card');

INSERT INTO `Orders` (`user_id`, `customer_id`, `total_amount`, `status`) VALUES 
(1, 1, 1525.00, 'completed'),
(1, 2, 345.00, 'pending'),
(1, 3, 3000.00, 'completed'),
(1, 4, 120.00, 'canceled'),
(1, 5, 1665.00, 'pending');

INSERT INTO `Order_Items` (`order_id`, `product_id`, `quantity`, `price`, `total`) VALUES 
(1, 1, 1, 1500.00, 1500.00),
(1, 2, 1, 25.00, 25.00),
(2, 4, 1, 300.00, 300.00),
(2, 5, 1, 45.00, 45.00),
(3, 1, 2, 1500.00, 3000.00),
(4, 3, 1, 120.00, 120.00),
(5, 1, 1, 1500.00, 1500.00),
(5, 3, 1, 120.00, 120.00),
(5, 5, 1, 45.00, 45.00);
