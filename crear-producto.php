<?php
session_start();

// GUARDIA DE SEGURIDAD
if ( !isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 1 ) {
    header('Location: login.php');
    exit; 
}

require 'db.php';
$error_mensaje = '';

// LÓGICA PARA PROCESAR EL FORMULARIO
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $db = conectarDB();
    
    // Datos del Producto
    $nombre = trim($_POST['nombre']);
    $imagen = trim($_POST['imagen']);
    
    // Datos de la Primera Variante
    $talla = trim($_POST['talla']);
    $precio = trim($_POST['precio']);
    $stock = trim($_POST['stock']);

    if (empty($nombre) || empty($talla) || empty($precio) || empty($stock)) {
        $error_mensaje = "Todos los campos son obligatorios.";
    } else {
        try {
            // Usamos una transacción: si algo falla, no se guarda nada
            $db->beginTransaction();

            // 1. Insertar en la tabla Productos
            $stmt = $db->prepare("INSERT INTO Productos (nombre, imagen) VALUES (?, ?)");
            $stmt->execute([$nombre, $imagen]);
            
            // 2. Obtener el ID del producto que acabamos de crear
            $producto_id = $db->lastInsertId();

            // 3. Insertar en la tabla Variantes
            $stmt = $db->prepare("INSERT INTO Variantes (producto_id, talla, precio, stock) VALUES (?, ?, ?, ?)");
            $stmt->execute([$producto_id, $talla, $precio, $stock]);

            // 4. Confirmar los cambios
            $db->commit();
            
            // 5. Redirigir al inventario
            header('Location: inventario.php');
            exit;

        } catch (Exception $e) {
            $db->rollBack(); // Revertir cambios si algo falló
            $error_mensaje = "Error al crear el producto: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear Producto</title>
    <link rel="stylesheet" href="build/css/app.css">
    </head>
<body>
    <header>
        <div class="header-inventario">
            <h1>Crear Nuevo Producto</h1>
            <div class="header-navegacion">
                <a href="inventario.php">Volver al Inventario</a>
            </div>
        </div>
    </header>

    <main>
        <form class="formulario-login" action="crear-producto.php" method="POST">
            <h2>Datos del Producto</h2>

            <?php if (!empty($error_mensaje)): ?>
                <div class="alerta error"><?php echo $error_mensaje; ?></div>
            <?php endif; ?>

            <div class="campo">
                <label for="nombre">Nombre del Producto:</label>
                <input type="text" id="nombre" name="nombre" required>
            </div>
            <div class="campo">
                <label for="imagen">Ruta de Imagen (ej. playeras/NOMBRE_IMG.jpeg):</label>
                <input type="text" id="imagen" name="imagen">
            </div>

            <h2>Primera Variante (Talla/Precio/Stock)</h2>
            <div class="campo">
                <label for="talla">Talla (ej. Mediana, 32, Única):</label>
                <input type="text" id="talla" name="talla" required>
            </div>
            <div class="campo">
                <label for="precio">Precio:</label>
                <input type="number" id="precio" name="precio" step="0.01" required>
            </div>
            <div class="campo">
                <label for="stock">Stock (Cantidad):</label>
                <input type="number" id="stock" name="stock" required>
            </div>

            <button class="boton boton-amarillo" type="submit">Guardar Producto</button>
        </form>
    </main>

    <footer class="footer-sesion">
        <p class="copyright">Todos los derechos reservados. Juárez Herrera Erick Adrián &copy; </p>
    </footer>
</body>
</html>
