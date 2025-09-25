-- =======================
-- Create Database
-- =======================
SHOW DATABASES;
SHOW TABLES;
CREATE DATABASE ChainledgerDB;
USE ChainledgerDB;
SELECT * FROM users;
SELECT * FROM security;

-- =======================
-- Users Table
-- =======================
CREATE TABLE users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    account_id INT UNIQUE NOT NULL, -- unique per account
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    birthdate DATE,
    gender ENUM('Male','Female','Other'),
    username VARCHAR(100) UNIQUE NOT NULL
);

-- =======================
-- Security Table
-- =======================
CREATE TABLE security (
    security_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    account_id INT NOT NULL,
    username VARCHAR(100) NOT NULL,
    security_question VARCHAR(255) NOT NULL,
    security_answer VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
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
CREATE TABLE owners (
    owner_id INT AUTO_INCREMENT PRIMARY KEY,
    account_id INT NOT NULL,
    username VARCHAR(100) NOT NULL,
    company_role ENUM('Business Owner') DEFAULT 'Business Owner',
    FOREIGN KEY (account_id) REFERENCES company_personnel(account_id) ON DELETE CASCADE
);

-- =======================
-- Managers Table
-- =======================
CREATE TABLE managers (
    manager_id INT AUTO_INCREMENT PRIMARY KEY,
    account_id INT NOT NULL,
    username VARCHAR(100) NOT NULL,
    company_role ENUM('Manager') DEFAULT 'Manager',
    FOREIGN KEY (account_id) REFERENCES company_personnel(account_id) ON DELETE CASCADE
);

-- =======================
-- Staffs Table
-- =======================
CREATE TABLE staffs (
    staff_id INT AUTO_INCREMENT PRIMARY KEY,
    account_id INT NOT NULL,
    username VARCHAR(100) NOT NULL,
    company_role ENUM('Employee') DEFAULT 'Employee',
    FOREIGN KEY (account_id) REFERENCES company_personnel(account_id) ON DELETE CASCADE
);
