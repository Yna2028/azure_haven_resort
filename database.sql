CREATE DATABASE IF NOT EXISTS azure_haven CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE azure_haven;
CREATE TABLE IF NOT EXISTS users (
 id INT AUTO_INCREMENT PRIMARY KEY,
 name VARCHAR(120) NOT NULL,
 email VARCHAR(190) NOT NULL UNIQUE,
 password VARCHAR(255) NOT NULL,
 role ENUM('user','admin') NOT NULL DEFAULT 'user',
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
CREATE TABLE IF NOT EXISTS rooms (
 id INT AUTO_INCREMENT PRIMARY KEY,
 name VARCHAR(120) NOT NULL,
 description VARCHAR(255) NOT NULL,
 price DECIMAL(10,2) NOT NULL,
 is_available TINYINT(1) NOT NULL DEFAULT 1
);
CREATE TABLE IF NOT EXISTS bookings (
 id INT AUTO_INCREMENT PRIMARY KEY,
 user_id INT NOT NULL,
 room_id INT NOT NULL,
 check_in DATE NOT NULL,
 check_out DATE NOT NULL,
 guests INT NOT NULL DEFAULT 2,
 status ENUM('Pending','Confirmed','Cancelled','Completed') NOT NULL DEFAULT 'Pending',
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 FOREIGN KEY(user_id) REFERENCES users(id) ON DELETE CASCADE,
 FOREIGN KEY(room_id) REFERENCES rooms(id)
);
INSERT IGNORE INTO rooms(id,name,description,price,is_available) VALUES
(1,'The Coastal Suite','An airy retreat with warm textures and a private balcony.',4800,1),
(2,'Garden Villa','A calm hideaway tucked among lush tropical greenery.',6200,1),
(3,'Oceanview Family Room','Room to reconnect, with sweeping blue-horizon views.',7500,1);
-- Demo admin account: admin@azurehaven.test / Admin123!
-- Password hash is for Admin123! (generated with PHP password_hash); replace after first login.
INSERT IGNORE INTO users(id,name,email,password,role) VALUES
(1,'Azure Haven Admin','admin@azurehaven.test','$2y$12$UGpW58FOVUTBjvE0JdaequdOdiQceiJarAEPYqEgEq.MFmej5UJqe','admin');
