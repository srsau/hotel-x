DROP DATABASE IF EXISTS hotel;
CREATE DATABASE hotel;
USE hotel;

DROP TABLE IF EXISTS users;
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL,
    name VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,
    preferred_currency VARCHAR(10) DEFAULT NULL,
    verified TINYINT(1) DEFAULT 0,
    role ENUM('customer', 'admin') NOT NULL DEFAULT 'customer',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

DROP TABLE IF EXISTS rooms;
CREATE TABLE rooms (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT NOT NULL,
    image_url VARCHAR(255) NOT NULL,
    images JSON DEFAULT NULL,
    price_per_night DECIMAL(10, 2) NOT NULL,
    capacity INT NOT NULL,
    floor INT NOT NULL,
    popular TINYINT(1) DEFAULT 0,
    available_rooms INT NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

DROP TABLE IF EXISTS email_verifications;
CREATE TABLE email_verifications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    verification_code VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

DROP TABLE IF EXISTS bookings;
CREATE TABLE bookings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    room_id INT NOT NULL,
    check_in_date DATE NOT NULL,
    check_out_date DATE NOT NULL,
    addons JSON,  -- Remove default value
    total_price DECIMAL(10, 2) NOT NULL, 
    status ENUM('valid', 'canceled') DEFAULT 'valid',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (room_id) REFERENCES rooms(id) ON DELETE CASCADE,
    UNIQUE (room_id, check_in_date, check_out_date)
);

DROP TABLE IF EXISTS facilities;
CREATE TABLE facilities (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE
);

DROP TABLE IF EXISTS room_facilities;
CREATE TABLE room_facilities (
    room_id INT NOT NULL,
    facility_id INT NOT NULL,
    PRIMARY KEY (room_id, facility_id),
    FOREIGN KEY (room_id) REFERENCES rooms(id) ON DELETE CASCADE,
    FOREIGN KEY (facility_id) REFERENCES facilities(id) ON DELETE CASCADE
);

DROP TABLE IF EXISTS addons;
CREATE TABLE addons (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE,
    price DECIMAL(10, 2) NOT NULL 
);

DROP TABLE IF EXISTS analytics;
CREATE TABLE analytics (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ip_address VARCHAR(45) NOT NULL,
    page VARCHAR(255) NOT NULL,
    session_id VARCHAR(255) NOT NULL,
    load_count INT NOT NULL DEFAULT 1,
    user_agent VARCHAR(255) NOT NULL,
    device_type ENUM('pc', 'mobile', 'tablet', 'unknown') NOT NULL DEFAULT 'unknown',
    browser_name VARCHAR(50) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

DELETE FROM rooms;
INSERT INTO rooms (name, description, image_url, images, price_per_night, capacity, floor, popular, available_rooms) VALUES
('Camera-1', 'Cameră modernă cu vedere la munte.', '/images/solo-room-1.jpg', '["/images/solo-room-1.jpg"]', 120.00, 2, 1, 1, 1),
('Camera-2', 'Cameră confortabilă lângă piscină.', '/images/big-room-1.jpg', '["/images/big-room-1.jpg"]', 90.00, 2, 2, 0, 10),
('Camera-3', 'Suită spațioasă cu pat king-size.', '/images/ap-1.jpg', '["/images/ap-1.1.jpg", "/images/ap-1.2.jpg", "/images/ap-1.3.jpg"]', 200.00, 4, 3, 1, 3),
('Camera-4', 'Cameră confortabilă cu vedere la grădină.', '/images/solo-room-1.jpg', '["/images/solo-room-1.jpg"]', 110.00, 2, 1, 0, 5),
('Camera-5', 'Cameră spațioasă cu balcon.', '/images/big-room-1.jpg', '["/images/big-room-1.jpg"]', 130.00, 3, 2, 1, 4),
('Camera-6', 'Suită de lux cu vedere la mare.', '/images/ap-1.jpg', '["/images/ap-1.1.jpg", "/images/ap-1.2.jpg"]', 250.00, 4, 3, 1, 2),
('Camera-7', 'Cameră dublă cu acces la piscină.', '/images/big-room-1.jpg', '["/images/big-room-1.jpg"]', 95.00, 2, 1, 0, 8),
('Camera-8', 'Cameră single modernă.', '/images/solo-room-1.jpg', '["/images/solo-room-1.jpg"]', 85.00, 1, 1, 0, 6);

INSERT INTO facilities (name) VALUES 
('WiFi'), 
('Aer Condiționat'), 
('TV'), 
('Mini Bar'), 
('Balcon'), 
('Acces la Piscină');

INSERT INTO room_facilities (room_id, facility_id) VALUES 
(1, 1), (1, 2), (1, 3), 
(2, 1), (2, 3), (2, 6),
(3, 1), (3, 2), (3, 4), (3, 5),
(4, 1), (4, 2), (4, 3),
(5, 1), (5, 4), (5, 5),
(6, 1), (6, 2), (6, 6),
(7, 1), (7, 3), (7, 6),
(8, 1), (8, 2), (8, 3);

INSERT INTO addons (name, price) VALUES
('Mic-dejun', 50.00),
('Prânz', 100.00),
('Late checkout', 150.00);
