CREATE DATABASE IF NOT EXISTS ecommerce;
USE ecommerce;

CREATE TABLE produtos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    preco DECIMAL(10,2) NOT NULL
);

INSERT INTO produtos (nome, preco) VALUES
('Camisa Polo', 89.90),
('Calça Jeans', 129.90),
('Tênis Esportivo', 249.90),
('Relógio Digital', 199.90);
