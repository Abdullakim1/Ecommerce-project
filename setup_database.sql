-- Create database
CREATE DATABASE IF NOT EXISTS luxury_ecommerce;

-- Create user and grant privileges
CREATE USER IF NOT EXISTS 'luxury_user'@'localhost' IDENTIFIED BY 'luxury123';
GRANT ALL PRIVILEGES ON luxury_ecommerce.* TO 'luxury_user'@'localhost';
FLUSH PRIVILEGES;
