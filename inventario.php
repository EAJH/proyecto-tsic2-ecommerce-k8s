<?php
// ===============================================
// 1. INICIAR Y PROTEGER LA PÁGINA
// ===============================================
session_start();

// GUARDIA DE SEGURIDAD DE ADMINISTRADOR
// 1. ¿Está logueado?
if ( !isset($_SESSION['usuario_id']) ) {
    header('Location: login.php'); // No está logueado, fuera.
    exit; 
}

// 2. ¿Es Admin?
if ( $_SESSION['rol'] !== 1 ) {
    header('Location: index.php'); // Es cliente, no admin, fuera.
    exit;
}

// ===============================================
// 2. OBTENER LOS DATOS DEL INVENTARIO
// ===============================================
require 'db.php';
$db = conectarDB();

// Esta consulta junta las dos tablas para tener la info completa
$query = "
    SELECT 
        P.nombre, 
        V.talla, 
        V.precio, 
        V.stock,
        V.id AS variante_id 
    FROM Productos AS P
    JOIN Variantes AS V ON P.id = V.producto_id
    ORDER BY P.nombre, V.talla
";

$stmt = $db->query($query);
$inventario = $stmt->fetchAll(PDO::FETCH_ASSOC);

// ============================
// 3. RENDERIZADO DEL HTML 
// ============================
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventario</title>
    <link rel="stylesheet" href="build/css/app.css">
    
    <style>

        body {
            background-color: #f7f7f7; 
        }

        table { width: 80%; margin: 2rem auto; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }

        .acciones a, .btn-crear {
            text-decoration: none;
            padding: 5px 10px;
            margin: 0 2px;
            border-radius: 4px;
            color: white;
            font-weight: bold;
        }
        .btn-editar { background-color: #f0ad4e; }
        .btn-borrar { background-color: #d9534f; }
        .btn-crear { background-color: #5cb85c; display: inline-block; margin: 1rem 0; }

    </style>
</head>
<body>

    <header class="inicio">
        <div class="header-inventario">
            <h1>Panel de Inventario</h1>
            <div class="header-navegacion">
                <a href="index.php">Volver al Inicio</a> |
                <a href="logout.php">Cerrar Sesión</a>
            </div>
        </div>
        
        <div class="header-crear-producto">
            <a href="crear-producto.php" class="btn-crear">Añadir Nuevo Producto</a>
        </div>
        
    </header>

    <main>
        <h2>Inventario Actual</h2>
        <table>
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Talla</th>
                    <th>Precio</th>
                    <th>Stock</th>
                    <th>Acciones</th> </tr>
            </thead>
            <tbody>
                <?php if (empty($inventario)): ?>
                    <tr>
                        <td colspan="5">No hay productos en el inventario.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($inventario as $item): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($item['nombre']); ?></td>
                            <td><?php echo htmlspecialchars($item['talla']); ?></td>
                            <td>$<?php echo htmlspecialchars($item['precio']); ?></td>
                            <td><?php echo htmlspecialchars($item['stock']); ?></td>
                            
                            <td class="acciones">
                                <a href="editar-variante.php?id=<?php echo $item['variante_id']; ?>" class="btn-editar">Editar</a>
                                <a href="eliminar-variante.php?id=<?php echo $item['variante_id']; ?>" 
                                   class="btn-borrar" 
                                   onclick="return confirm('¿Estás seguro de que deseas eliminar esta variante? Esta acción no se puede deshacer.');">
                                   Borrar
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </main>

    <footer class="footer-sesion">
        <p class="copyright">Todos los derechos reservados. Juárez Herrera Erick Adrián &copy; </p>
    </footer>

</body>
</html>