<?php
session_start();

// GUARDIA DE SEGURIDAD
if ( !isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 1 ) {
    header('Location: login.php');
    exit; 
}

require 'db.php';
$db = conectarDB();
$error_mensaje = '';

// 1. Obtener el ID de la variante de la URL
$variante_id = filter_var($_GET['id'], FILTER_VALIDATE_INT);
if (!$variante_id) {
    header('Location: inventario.php');
    exit;
}

// 2. LÓGICA DE ACTUALIZACIÓN (SI ES POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $talla = trim($_POST['talla']);
    $precio = trim($_POST['precio']);
    $stock = trim($_POST['stock']);

    if (empty($talla) || empty($precio) || empty($stock)) {
        $error_mensaje = "Todos los campos son obligatorios.";
    } else {
        try {
            $stmt = $db->prepare("UPDATE Variantes SET talla = ?, precio = ?, stock = ? WHERE id = ?");
            $stmt->execute([$talla, $precio, $stock, $variante_id]);
            
            header('Location: inventario.php');
            exit;
        } catch (Exception $e) {
            $error_mensaje = "Error al actualizar: " . $e->getMessage();
        }
    }
}

// 3. OBTENER DATOS ACTUALES (PARA RELLENAR EL FORMULARIO)
$stmt = $db->prepare("
    SELECT V.*, P.nombre AS producto_nombre 
    FROM Variantes AS V 
    JOIN Productos AS P ON V.producto_id = P.id 
    WHERE V.id = ?
");
$stmt->execute([$variante_id]);
$variante = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$variante) {
    header('Location: inventario.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Variante</title>
    <link rel="stylesheet" href="build/css/app.css">
</head>
<body>
    <header>
        <div class="header-inventario">
            <h1>Editar Variante</h1>
            <div class="header-navegacion">
               <a href="inventario.php">Volver al Inventario</a>
            </div>
        </div>    
    </header>

    <main>
        <h2>Producto: <?php echo htmlspecialchars($variante['producto_nombre']); ?></h2>

        <form class="formulario-login" action="editar-variante.php?id=<?php echo $variante_id; ?>" method="POST">
            
            <?php if (!empty($error_mensaje)): ?>
                <div class="alerta error"><?php echo $error_mensaje; ?></div>
            <?php endif; ?>

            <div class="campo">
                <label for="talla">Talla:</label>
                <input type="text" id="talla" name="talla" value="<?php echo htmlspecialchars($variante['talla']); ?>" required>
            </div>
            <div class="campo">
                <label for="precio">Precio:</label>
                <input type="number" id="precio" name="precio" step="0.01" value="<?php echo htmlspecialchars($variante['precio']); ?>" required>
            </div>
            <div class="campo">
                <label for="stock">Stock (Cantidad):</label>
                <input type="number" id="stock" name="stock" value="<?php echo htmlspecialchars($variante['stock']); ?>" required>
            </div>

            <button class="boton boton-amarillo" type="submit">Actualizar Variante</button>
        </form>
    </main>

    <footer class="footer-sesion">
        <p class="copyright">Todos los derechos reservados. Juárez Herrera Erick Adrián &copy; </p>
    </footer>
</body>
</html>