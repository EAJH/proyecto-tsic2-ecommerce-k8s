USE tsic2;

INSERT INTO Clientes (email, passwd, rol) 
VALUES 
('admin@tienda.com', 'admin123', 1),  -- Usuario Admin
('cliente@correo.com', 'cliente123', 0);  -- Usuario Cliente

INSERT INTO Productos (nombre, precio, cantidad, imagen)
VALUES
('Playera azul', 150.00, 50, 'src/img/playeras/playeraAzul.jpg'),
('Playera Deportiva 1', 250.00, 50, 'src/img/playeras/playeraDeportiva1.jpeg'),
('Playera Elegante 1', 200.00, 50, 'src/img/playeras/playeraElegante1.jpeg'),
('Playera Elegante 2', 200.00, 50, 'src/img/playeras/playeraElegante2.jpeg'),
('Playera negra', 150.00, 50, 'src/img/playeras/playeraNegra.jpeg'),
('Playera roja', 150.00, 50, 'src/img/playeras/playeraRoja.jpeg'),
('Chamarra negra con cuero', 500.00, 50, 'src/img/chamarras/chamarraNegraHombre.jpeg'),
('Chamarra verde abrigada', 600.00, 50, 'src/img/chamarras/chamarraVerdeHombre.jpeg'),
('Chamarra vino impermeable', 400.00, 50, 'src/img/chamarras/chamarraVinoHombre.jpeg'),
('Pantalon de mezclilla clasico', 250.00, 50, 'src/img/pantalones/mezclillaAzulHombre.jpeg'),
('Pantalon crema estilo pants', 350.00, 50, 'src/img/pantalones/pantalonCremaHombre.jpeg'),
('Pantalon verde multibolsa', 200.00, 50, 'src/img/pantalones/pantalonVerdeHombre.jpeg');