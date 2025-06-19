CREATE DATABASE IF NOT EXISTS bookwise CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE bookwise;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'user') NOT NULL DEFAULT 'user'
);

CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE
);

CREATE TABLE books (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    author VARCHAR(150) NOT NULL,
    year YEAR NOT NULL,
    category_id INT NOT NULL,
    cover VARCHAR(255),
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE
);

ALTER TABLE books ADD synopsis TEXT;

-- Insert admin default --
INSERT INTO users (username, password, role) VALUES ('admin', '{PASSWORD_HASH}', 'admin');