DROP DATABASE IF EXISTS hotel;
CREATE DATABASE hotel;
USE hotel;

DROP TABLE IF EXISTS users;
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL,
    name VARCHAR(255) NOT NULL,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    verified TINYINT(1) DEFAULT 0,
    role ENUM('customer', 'admin') NOT NULL DEFAULT 'customer',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO users (email, name, username, password, verified, role) VALUES 
('customer@example.com', 'Customer', 'customer', 'customer', 1, 'customer'), 
('admin@example.com', 'Admin', 'admin', 'admin', 1, 'admin');

DROP TABLE IF EXISTS rooms;
CREATE TABLE rooms (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL
);

INSERT INTO rooms (name) VALUES 
('Room-1'), 
('Room-2');

DROP TABLE IF EXISTS email_verifications;
CREATE TABLE email_verifications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    verification_code VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
