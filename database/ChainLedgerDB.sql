-- =======================
-- Create Database
-- =======================
CREATE DATABASE ChainledgerDB;
USE ChainledgerDB;
select * from users
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
    profile_image VARCHAR(255) DEFAULT NULL,
    date_registered TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)


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
    FOREIGN KEY (account_id) REFERENCES users(account_id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    FOREIGN KEY (username) REFERENCES users(username)
        ON DELETE CASCADE
        ON UPDATE CASCADE
)

-- =======================
-- Company Personnel Table
-- =======================
CREATE TABLE company_personnel (
    personnel_id INT AUTO_INCREMENT PRIMARY KEY,
    account_id INT NOT NULL,
    username VARCHAR(100) NOT NULL,
    company_role ENUM('Business Owner', 'Manager', 'Staff') NOT NULL,
    FOREIGN KEY (account_id) REFERENCES users(account_id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    FOREIGN KEY (username) REFERENCES users(username)
        ON DELETE CASCADE
        ON UPDATE CASCADE
)

-- =======================
-- Owners Table
-- =======================
CREATE TABLE company_owners (
    owner_id INT AUTO_INCREMENT PRIMARY KEY,
    account_id INT NOT NULL,
    username VARCHAR(100) NOT NULL,
    company_role ENUM('Business Owner') DEFAULT 'Business Owner',
    FOREIGN KEY (account_id) REFERENCES users(account_id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    FOREIGN KEY (username) REFERENCES users(username)
        ON DELETE CASCADE
        ON UPDATE CASCADE
)

-- =======================
-- Transaction Table
-- =======================
CREATE TABLE transactions (
    transaction_id INT AUTO_INCREMENT PRIMARY KEY,
    account_id INT NOT NULL,
    username VARCHAR(100) NOT NULL,
    detail ENUM('Food', 'Equipment', 'Transportation', 'Health', 'Maintenance', 'Utilities') NOT NULL,
    merchant ENUM('Gcash', 'Maya', 'Grabpay', 'Paypal', 'Googlepay') NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    currency VARCHAR(10) DEFAULT 'PHP',
    transaction_date DATE NOT NULL DEFAULT CURRENT_DATE,
    entry_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    transaction_type ENUM('DEPOSIT', 'WITHDRAWAL', 'TRANSFER', 'PAYMENT', 'REFUND') NOT NULL,
    status ENUM('PENDING', 'COMPLETED', 'FAILED', 'CANCELLED') NOT NULL,
    FOREIGN KEY (account_id) REFERENCES users(account_id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    FOREIGN KEY (username) REFERENCES users(username)
        ON DELETE CASCADE
        ON UPDATE CASCADE
)

-- ==========================
-- Security_logs Table
-- ========================== 
CREATE TABLE security_logs (
    security_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NULL,
    account_id INT NOT NULL,
    username VARCHAR(100) NOT NULL,
    action ENUM(
        'LOGIN', 
        'LOGOUT', 
        'PASSWORD_CHANGE', 
        'ACCOUNT_CREATED',
        'ACCOUNT_DELETED'
    ) NOT NULL,
    ip_address VARCHAR(45),
    device_info TEXT,
    user_agent TEXT,
    timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_account FOREIGN KEY (account_id) REFERENCES users(account_id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    CONSTRAINT fk_username FOREIGN KEY (username) REFERENCES users(username)
        ON DELETE CASCADE
        ON UPDATE CASCADE
)

-- ==========================
-- Archived_Accounts Table
-- ========================== 
CREATE TABLE archivedaccounts LIKE transactions;

ALTER TABLE archivedaccounts
ADD COLUMN old_account_id INT AFTER account_id,
ADD COLUMN old_username VARCHAR(255) AFTER old_account_id,
ADD COLUMN archived_at DATETIME DEFAULT CURRENT_TIMESTAMP;
