CREATE DATABASE IF NOT EXISTS csv_feed;
USE csv_feed;

CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    gtin VARCHAR(20) NOT NULL UNIQUE,
    language CHAR(2),
    title VARCHAR(255),
    picture TEXT,
    description TEXT,
    price DECIMAL(10,2),
    stock INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
