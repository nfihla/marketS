-- Siyanda Market — run this in phpMyAdmin or MySQL CLI
-- http://localhost/phpmyadmin

CREATE DATABASE IF NOT EXISTS siyanda_market
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE siyanda_market;

CREATE TABLE IF NOT EXISTS users (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    full_name   VARCHAR(120)  NOT NULL,
    email       VARCHAR(180)  NOT NULL UNIQUE,
    password    VARCHAR(255)  NOT NULL,
    role        ENUM('buyer', 'seller', 'admin') NOT NULL DEFAULT 'buyer',
    created_at  TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at  TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_users_email (email),
    INDEX idx_users_role  (role)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
