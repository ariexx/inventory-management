-- Create database if not exists
CREATE DATABASE IF NOT EXISTS inventory_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Create user with proper host permissions
CREATE USER IF NOT EXISTS 'inventory_user'@'%' IDENTIFIED BY 'your_secure_password';
CREATE USER IF NOT EXISTS 'inventory_user'@'localhost' IDENTIFIED BY 'your_secure_password';

-- Grant all privileges on the database
GRANT ALL PRIVILEGES ON inventory_db.* TO 'inventory_user'@'%';
GRANT ALL PRIVILEGES ON inventory_db.* TO 'inventory_user'@'localhost';

-- Flush privileges to ensure changes take effect
FLUSH PRIVILEGES;

-- Use the database
USE inventory_db;
