/* * 02-DATA.SQL
 * Este script llena las tablas con datos de ejemplo.
 */

-- 1. Selecciona la base de datos
USE tsic2;

-- 2. Insertar Usuarios de Ejemplo 
INSERT INTO Clientes (email, passwd, rol) 
VALUES 
('admin@tienda.com', '$2y$10$zFZKgQD2Udd0.EUjpIoPducxNSsIH5x7TbhotC94xs3tDE/Zb1Xci', 1),  -- Usuario Admin
('cliente@correo.com', '$2y$10$mEHkYR0tc04bEZY9g4yXoOWwOsZF3SRxTlFHh3SaxQ.ocmR/sJxZS', 0); -- Usuario Cliente


INSERT INTO Productos (nombre,categoria,imagen) 
VALUES 
('Playera azul','Playeras', 'playeras/playeraAzul.jpg'), 
('Playera Deportiva 1','Playeras', 'playeras/playeraDeportiva1.jpeg'),
('Playera Elegante 1','Playeras', 'playeras/playeraElegante1.jpeg'),
('Playera Elegante 2','Playeras', 'playeras/playeraElegante2.jpeg'),
('Playera negra','Playeras', 'playeras/playeraNegra.jpeg'),
('Playera roja','Playeras', 'playeras/playeraRoja.jpeg'),
('Chamarra negra con cuero','Chamarras', 'chamarras/chamarraNegraHombre.jpeg'),
('Chamarra verde abrigada','Chamarras', 'chamarras/chamarraVerdeHombre.jpeg'),
('Chamarra vino impermeable','Chamarras', 'chamarras/chamarraVinoHombre.jpeg'),
('Pantalon de mezclilla clasico','Pantalones', 'pantalones/mezclillaAzulHombre.jpeg'),
('Pantalon crema estilo pants','Pantalones', 'pantalones/pantalonCremaHombre.jpeg'),
('Pantalon verde multibolsa','Pantalones', 'pantalones/pantalonVerdeHombre.jpeg');


INSERT INTO Variantes (producto_id, talla, precio, stock)
VALUES
(1, 'Chica', 150.00, 50),
(1, 'Mediana', 150.00, 50),
(1, 'Grande', 150.00, 50),
(2, 'Chica', 250.00, 50),
(2, 'Mediana', 250.00, 50),
(2, 'Grande', 250.00, 50),
(3, 'Chica', 200.00, 50),
(3, 'Mediana', 200.00, 50),
(3, 'Grande', 200.00, 50),
(4, 'Chica', 200.00, 50),
(4, 'Mediana', 200.00, 50),
(4, 'Grande', 200.00, 50),
(5, 'Chica', 150.00, 50),
(5, 'Mediana', 150.00, 50),
(5, 'Grande', 150.00, 50),
(6, 'Chica', 150.00, 50),
(6, 'Mediana', 150.00, 50),
(6, 'Grande', 150.00, 50),
(7, 'Chica', 500.00, 50),
(7, 'Mediana', 500.00, 50),
(7, 'Grande', 500.00, 50),
(8, 'Chica', 600.00, 50),
(8, 'Mediana', 600.00, 50),
(8, 'Grande', 600.00, 50),
(9, 'Chica', 400.00, 50),
(9, 'Mediana', 400.00, 50),
(9, 'Grande', 400.00, 50),
(10, 'Chica', 250.00, 50),
(10, 'Mediana', 250.00, 50),
(10, 'Grande', 250.00, 50),
(11, 'Chica', 350.00, 50),
(11, 'Mediana', 350.00, 50),
(11, 'Grande', 350.00, 50),
(12, 'Chica', 200.00, 50),
(12, 'Mediana', 200.00, 50),
(12, 'Grande', 200.00, 50);

