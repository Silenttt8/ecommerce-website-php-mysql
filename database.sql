-- database.sql
-- Script untuk membuat database dan tabel produk

CREATE DATABASE IF NOT EXISTS ecommerce_db CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;

USE ecommerce_db;

CREATE TABLE IF NOT EXISTS products (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(255) NOT NULL,
  price DECIMAL(10,2) NOT NULL,
  description TEXT
);

-- Anda dapat menambahkan data contoh di sini jika diperlukan
-- INSERT INTO products (name, price, description) VALUES ('Produk 1', 10000.00, 'Deskripsi produk 1');
