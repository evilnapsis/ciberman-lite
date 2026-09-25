-- Esquema de Base de Datos para Ciberman 2
-- Sistema de Control y Alquiler de Ciber Café

CREATE DATABASE IF NOT EXISTS ciberman;
USE ciberman;

-- Tabla de usuarios y operadores
CREATE TABLE IF NOT EXISTS user (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50),
    lastname VARCHAR(50),
    username VARCHAR(50) UNIQUE,
    email VARCHAR(255),
    password VARCHAR(60),
    image VARCHAR(255) DEFAULT '',
    status INT DEFAULT 1, -- 1: Activo, 2: Inactivo
    kind INT DEFAULT 1,   -- 1: Administrador, 2: Operador
    created_at DATETIME
);

-- Tabla de equipos / computadoras / cabinas
CREATE TABLE IF NOT EXISTS equipment (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(50),
    name VARCHAR(50) NOT NULL,
    description VARCHAR(255),
    price_hour DOUBLE DEFAULT 15.0,
    price_half DOUBLE DEFAULT 8.0,
    created_at DATETIME
);

-- Tabla de rentas de tiempo
CREATE TABLE IF NOT EXISTS rent (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    price DOUBLE DEFAULT 0.0,
    start_date DATE,
    finish_date DATE,
    bonus_mins INT DEFAULT 0,
    start_time TIME,
    finish_time TIME,
    person_name VARCHAR(255) DEFAULT 'Público General',
    equipment_id INT NOT NULL,
    user_id INT NOT NULL,
    created_at DATETIME,
    FOREIGN KEY (user_id) REFERENCES user(id),
    FOREIGN KEY (equipment_id) REFERENCES equipment(id) ON DELETE CASCADE
);

-- Inserción de usuario administrador inicial (password: admin -> sha1(md5("admin")))
INSERT INTO user (name, lastname, username, email, password, status, kind, created_at)
VALUES ("Administrador", "Sistema", "admin", "admin@ciberman.local", SHA1(MD5("admin")), 1, 1, NOW())
ON DUPLICATE KEY UPDATE id=id;

-- Inserción de equipos de demostración
INSERT INTO equipment (code, name, description, price_hour, price_half, created_at) VALUES
('PC-01', 'Computadora 01', 'Windows 11, Core i5, 16GB RAM, Auriculares', 15.00, 8.00, NOW()),
('PC-02', 'Computadora 02', 'Windows 11, Core i5, 16GB RAM, Auriculares', 15.00, 8.00, NOW()),
('PC-03', 'Computadora 03', 'Windows 11, Core i7, 32GB RAM, Edición/Juegos', 20.00, 10.00, NOW()),
('CAB-04', 'Cabina 04', 'Terminal de navegación rápida e impresión', 12.00, 6.00, NOW());
