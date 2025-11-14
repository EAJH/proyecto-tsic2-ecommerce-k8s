/* * 01-SCHEMA.SQL
 * Este script crea la estructura de la base de datos (las tablas).
 */

-- 1. Selecciona la base de datos que se creó en docker-compose
USE tsic2;

-- 2. Tabla Clientes (Usuarios)
-- Almacena la información de inicio de sesión y roles
DROP TABLE IF EXISTS Clientes; -- Borra la tabla si ya existe (para pruebas)
CREATE TABLE Clientes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(100) NOT NULL UNIQUE,
    passwd VARCHAR(255) NOT NULL,
    rol TINYINT(1) NOT NULL DEFAULT 0  -- 0 = Cliente, 1 = Admin
);

-- 3. Tabla Productos (Información General)
-- Almacena solo la información general del producto
DROP TABLE IF EXISTS Productos; -- Borra la tabla si ya existe
CREATE TABLE Productos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(255) NOT NULL,
    categoria VARCHAR(100) NOT NULL,
    imagen VARCHAR(255) NULL
);

-- 4. Tabla Variantes (Inventario y Precios)
-- Almacena el stock, precio y talla de cada producto
DROP TABLE IF EXISTS Variantes; -- Borra la tabla si ya existe
CREATE TABLE Variantes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    producto_id INT NOT NULL,
    talla VARCHAR(50) NOT NULL,
    precio DECIMAL(10, 2) NOT NULL,
    stock INT NOT NULL DEFAULT 0,
    
    -- Crea la conexión con la tabla Productos
    FOREIGN KEY (producto_id) REFERENCES Productos(id)
        ON DELETE CASCADE -- Si borro un producto, sus variantes se borran
);