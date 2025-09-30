-- =======================
-- Create Database
-- =======================
SHOW DATABASES;
SHOW TABLES;

Drop table company_personnel;
CREATE DATABASE ChainledgerDB;

USE ChainledgerDB;

SELECT * FROM users;
SELECT * FROM security;
SELECT * FROM security_logs;
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
    username VARCHAR(100) UNIQUE NOT NULL,
    date_registered TIMESTAMP DEFAULT CURRENT_TIMESTAMP
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
    password VARCHAR(255),
    FOREIGN KEY (account_id) REFERENCES users(account_id) ON DELETE CASCADE,
    FOREIGN KEY (username) REFERENCES users(username) ON DELETE CASCADE
);


-- =======================
-- Company Personnel Table
-- =======================
CREATE TABLE company_personnel (
    personnel_id INT AUTO_INCREMENT PRIMARY KEY,
    account_id INT NOT NULL,
    username VARCHAR(100) NOT NULL,
    company_role ENUM('Business Owner', 'Manager', 'Staff') NOT NULL,
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
    FOREIGN KEY (account_id) REFERENCES users(account_id) ON DELETE CASCADE,
    FOREIGN KEY (username) REFERENCES users(username) ON DELETE CASCADE
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
    currency VARCHAR(10) DEFAULT 'PHP',
    transaction_type ENUM('DEPOSIT', 'WITHDRAWAL', 'TRANSFER', 'PAYMENT') NOT NULL,
    status ENUM('PENDING', 'COMPLETED', 'FAILED', 'CANCELLED') NOT NULL,
    -- Foreign key relationships
    FOREIGN KEY (account_id) REFERENCES users(account_id) ON DELETE CASCADE,
    FOREIGN KEY (username) REFERENCES users(username) ON DELETE CASCADE
);

-- ==========================
-- security_logs tables
-- ==========================  
CREATE TABLE security_logs (
    security_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    account_id INT NOT NULL,
    username VARCHAR(100) NOT NULL,
    action ENUM('LOGIN', 'LOGOUT', 'FAILED_LOGIN', 'PASSWORD_CHANGE', 'ACCOUNT_LOCKED') NOT NULL,
    ip_address VARCHAR(45),
    device_info TEXT,
    timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    user_agent TEXT,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (account_id) REFERENCES users(account_id) ON DELETE CASCADE,
    FOREIGN KEY (username) REFERENCES users(username) ON DELETE CASCADE
);