DROP DATABASE IF EXISTS hotel;
CREATE DATABASE hotel;
USE hotel;

DROP TABLE IF EXISTS users;
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('customer', 'admin') NOT NULL DEFAULT 'customer'
);

INSERT INTO users (username, password, role) VALUES 
('customer', 'customer', 'customer'), 
('admin', 'admin', 'admin');

DROP TABLE IF EXISTS rooms;
CREATE TABLE rooms (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL
);

INSERT INTO rooms (name) VALUES 
('Room-1'), 
('Room-2');
