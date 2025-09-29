-- =======================
-- Create Database
-- =======================
SHOW DATABASES;

SHOW TABLES;

CREATE DATABASE ChainledgerDB;

USE ChainledgerDB;

SELECT * FROM users;
SELECT * FROM security;
SELECT * FROM company_personnel;
SELECT * FROM company_owners;
SELECT * FROM transactions;

-- =======================
-- Users Table
-- =======================
CREATE TABLE users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    account_id INT UNIQUE NOT NULL, -- unique per account
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    birthdate DATE,
    gender ENUM('Male','Female'),
    username VARCHAR(100) UNIQUE NOT NULL
);

-- =======================
-- Security Table
-- =======================
CREATE TABLE security (
    security_id INT AUTO_INCREMENT PRIMARY KEY,
    account_id INT NOT NULL,
    username VARCHAR(100) NOT NULL,
    security_question VARCHAR(255) NOT NULL,
    security_answer VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,
    FOREIGN KEY (account_id) REFERENCES users(account_id) ON DELETE CASCADE
);

-- =======================
-- Company Personnel Table
-- =======================
CREATE TABLE company_personnel (
    personnel_id INT AUTO_INCREMENT PRIMARY KEY,
    account_id INT NOT NULL,
    username VARCHAR(100) NOT NULL,
    company_role ENUM('Business Owner', 'Manager', 'Employee') NOT NULL,
    FOREIGN KEY (account_id) REFERENCES users(account_id) ON DELETE CASCADE
);

-- =======================
-- Owners Table
-- =======================
CREATE TABLE  company_owners (
    owner_id INT AUTO_INCREMENT PRIMARY KEY,
    account_id INT NOT NULL,
    username VARCHAR(100) NOT NULL,
    company_role ENUM('Business Owner') DEFAULT 'Business Owner',
    FOREIGN KEY (account_id) REFERENCES company_personnel(account_id) ON DELETE CASCADE
);

-- =======================
-- Transaction Table
-- =======================
CREATE TABLE transactions (
    transaction_id INT AUTO_INCREMENT PRIMARY KEY,
    account_id INT NOT NULL,
    username VARCHAR(100) NOT NULL,
    detail ENUM('food', 'equipment', 'travel', 'health', 'maintenance', 'utilities') NOT NULL,
    merchant ENUM('gcash', 'maya', 'grabpay', 'paypal', 'googlepay') NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    transaction_date DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    
    -- Foreign key relationships
    FOREIGN KEY (account_id) REFERENCES users(account_id) ON DELETE CASCADE,
    FOREIGN KEY (username) REFERENCES users(username) ON DELETE CASCADE
);
