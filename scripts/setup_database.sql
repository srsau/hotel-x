-- DROP DATABASE IF EXISTS hotel;
-- CREATE DATABASE hotel;
-- USE hotel;

-- DROP TABLE IF EXISTS users;
-- CREATE TABLE users (
--     id INT AUTO_INCREMENT PRIMARY KEY,
--     email VARCHAR(255) NOT NULL,
--     name VARCHAR(255) NOT NULL,
--     username VARCHAR(50) NOT NULL UNIQUE,
--     password VARCHAR(255) NOT NULL,
--     verified TINYINT(1) DEFAULT 0,
--     role ENUM('customer', 'admin') NOT NULL DEFAULT 'customer',
--     created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
-- );


-- DROP TABLE IF EXISTS rooms;
-- CREATE TABLE rooms (
--     id INT AUTO_INCREMENT PRIMARY KEY,
--     name VARCHAR(100) NOT NULL,
--     description TEXT NOT NULL,
--     image_url VARCHAR(255) NOT NULL,
--     images JSON DEFAULT NULL,
--     price_per_night DECIMAL(10, 2) NOT NULL,
--     capacity INT NOT NULL,
--     floor INT NOT NULL,
--     popular TINYINT(1) DEFAULT 0,
--     created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
-- );

-- DROP TABLE IF EXISTS email_verifications;
-- CREATE TABLE email_verifications (
--     id INT AUTO_INCREMENT PRIMARY KEY,
--     user_id INT NOT NULL,
--     verification_code VARCHAR(255) NOT NULL,
--     created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
--     FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
-- );

-- DROP TABLE IF EXISTS bookings;
-- CREATE TABLE bookings (
--     id INT AUTO_INCREMENT PRIMARY KEY,
--     user_id INT NOT NULL,
--     room_id INT NOT NULL,
--     check_in_date DATE NOT NULL,
--     check_out_date DATE NOT NULL,
--     addons JSON DEFAULT NULL, 
--     total_price DECIMAL(10, 2) NOT NULL, 
--     created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
--     FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
--     FOREIGN KEY (room_id) REFERENCES rooms(id) ON DELETE CASCADE,
--     UNIQUE (room_id, check_in_date, check_out_date)
-- );

-- -- Tabelul facilități
-- DROP TABLE IF EXISTS facilities;
-- CREATE TABLE facilities (
--     id INT AUTO_INCREMENT PRIMARY KEY,
--     name VARCHAR(100) NOT NULL UNIQUE
-- );

-- -- Relație multe-la-multe între camere și facilități
-- DROP TABLE IF EXISTS room_facilities;
-- CREATE TABLE room_facilities (
--     room_id INT NOT NULL,
--     facility_id INT NOT NULL,
--     PRIMARY KEY (room_id, facility_id),
--     FOREIGN KEY (room_id) REFERENCES rooms(id) ON DELETE CASCADE,
--     FOREIGN KEY (facility_id) REFERENCES facilities(id) ON DELETE CASCADE
-- );

-- -- Tabel pentru opțiuni suplimentare (add-ons)
-- DROP TABLE IF EXISTS addons;
-- CREATE TABLE addons (
--     id INT AUTO_INCREMENT PRIMARY KEY,
--     name VARCHAR(100) NOT NULL UNIQUE,
--     price DECIMAL(10, 2) NOT NULL 
-- );

-- -- Inserări de test pentru camere
-- INSERT INTO rooms (name, description, image_url, images, price_per_night, capacity, floor, popular) VALUES
-- ('Room-1', 'Modern room with mountain view.', '/images/solo-room-1.jpg', '["/images/solo-room-1.jpg"]', 120.00, 2, 1, 1),
-- ('Room-2', 'Cozy room near the pool.', '/images/big-room-1.jpg', '["/images/big-room-1.jpg"]', 90.00, 2, 2, 0),
-- ('Room-3', 'Spacious suite with king-size bed.', '/images/ap-1.jpg', '["/images/ap-1.1.jpg", "/images/ap-1.2.jpg", "/images/ap-1.3.jpg"]', 200.00, 4, 3, 1);

-- -- Inserări de test pentru utilizatori
-- INSERT INTO users (email, name, username, password, verified, role) VALUES 
-- ('customer@example.com', 'Customer', 'customer', 'customer', 1, 'customer'), 
-- ('admin@example.com', 'Admin', 'admin', 'admin', 1, 'admin');

-- -- Inserări de test pentru facilități
-- INSERT INTO facilities (name) VALUES 
-- ('WiFi'), 
-- ('Air Conditioning'), 
-- ('TV'), 
-- ('Mini Bar'), 
-- ('Balcony'), 
-- ('Pool Access');

-- -- Asociere camere-facilități
-- INSERT INTO room_facilities (room_id, facility_id) VALUES 
-- (1, 1), (1, 2), (1, 3), 
-- (2, 1), (2, 3), (2, 6),
-- (3, 1), (3, 2), (3, 4), (3, 5);

-- -- Inserări de test pentru opțiuni suplimentare (add-ons)
-- INSERT INTO addons (name, price) VALUES
-- ('Mic-dejun', 50.00),
-- ('Prânz', 100.00),
-- ('Late checkout', 150.00);

-- -- Inserări de test pentru rezervări
-- INSERT INTO bookings (user_id, room_id, check_in_date, check_out_date, addons, total_price) VALUES
-- (1, 1, '2025-01-10', '2025-01-12', '{"breakfast": true, "lunch": false, "late_checkout": true}', 270.00),
-- (1, 2, '2025-01-15', '2025-01-18', '{"breakfast": false, "lunch": true, "late_checkout": false}', 390.00);
