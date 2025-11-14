<?php
// ===============================================
// 1. INICIAR Y CONECTAR
// ===============================================
session_start();
require 'db.php';
$db = conectarDB();

// ===============================================
// 2. LÓGICA DE GESTIÓN DEL CARRITO
// ===============================================

// A. Acción de "Vaciar Carrito" (¡LÓGICA MODIFICADA!)
if (isset($_GET['vaciar']) && $_GET['vaciar'] === 'true') {
    
    $carrito_actual = $_SESSION['carrito'] ?? [];
    $wishlist_items = $_SESSION['wishlist_items'] ?? [];
    $nuevo_carrito = []; // Un carrito temporal

    // Revisamos cada item del carrito
    foreach ($carrito_actual as $id => $cantidad) {
        // Si el item está en la wishlist, lo conservamos
        if (isset($wishlist_items[$id])) {
            $nuevo_carrito[$id] = $cantidad;
        }
    }
    
    $_SESSION['carrito'] = $nuevo_carrito; // Sobrescribimos el carrito
    
    header('Location: carrito.php'); 
    exit;
}

// B. Obtener el carrito de la sesión
$carrito_session = $_SESSION['carrito'] ?? [];
$wishlist_items = $_SESSION['wishlist_items'] ?? []; // Obtenemos las flags

$productos_carrito = []; 
$total_general = 0;

// C. Si el carrito NO está vacío, buscamos los detalles en la BD
if (!empty($carrito_session)) {
    
    $ids_variantes = array_keys($carrito_session);
    $placeholders = implode(',', array_fill(0, count($ids_variantes), '?'));
    
    $sql = "
        SELECT 
            V.id AS variante_id, V.talla, V.precio,
            P.nombre, P.imagen
        FROM Variantes AS V
        JOIN Productos AS P ON V.producto_id = P.id
        WHERE V.id IN ($placeholders)
    ";
    
    $stmt = $db->prepare($sql);
    $stmt->execute($ids_variantes);
    $productos_db = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // 4. Procesamos los resultados
    foreach ($productos_db as $producto) {
        $id = $producto['variante_id'];
        
        // Verificamos que siga en la sesión (por si acaso)
        if (!isset($carrito_session[$id])) continue; 
        
        $cantidad = $carrito_session[$id];
        $subtotal = $producto['precio'] * $cantidad;
        $total_general += $subtotal;
        
        // Lógica de imagen
        $ruta_imagen_db = htmlspecialchars($producto['imagen']);
        $ruta_sin_extension = str_replace(['.jpg', '.jpeg'], '', $ruta_imagen_db);
        $ruta_original = "build/img/" . $ruta_imagen_db;

        // Añadimos el producto procesado al array final
        $productos_carrito[] = [
            'id' => $id,
            'nombre' => $producto['nombre'],
            'imagen' => $ruta_original,
            'talla' => $producto['talla'],
            'precio' => $producto['precio'],
            'cantidad' => $cantidad,
            'subtotal' => $subtotal,
            'es_wishlist' => isset($wishlist_items[$id]) // ¡Flag de Wishlist!
        ];
    }
}

// ===============================================
// 3. RENDERIZADO DEL HTML (LA VISTA)
// ===============================================
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Carrito de Compras</title>
    <link rel="stylesheet" href="build/css/app.css">
    
    <style>
        .tabla-carrito { width: 90%; margin: 2rem auto; border-collapse: collapse; }
        .tabla-carrito th, .tabla-carrito td { border: 1px solid #ddd; padding: 12px; text-align: left; vertical-align: middle; }
        .tabla-carrito th { background-color: #f2f2f2; }
        .tabla-carrito img { width: 80px; height: auto; }
        .fila-total { font-size: 1.2rem; font-weight: bold; }
        .acciones-carrito { text-align: right; width: 90%; margin: 2rem auto; }
        .acciones-carrito a { text-decoration: none; padding: 10px 15px; border-radius: 5px; }
        .btn-pagar { background-color: #5cb85c; color: white; }
        .btn-vaciar { background-color: #d9534f; color: white; margin-right: 10px; }
        .col-wishlist { width: 100px; text-align: center; }
        .wishlist-toggle a { text-decoration: none; font-size: 1.5rem; }
    </style>
</head>
<body>

    <header class="inicio">
        <div class="contenedor contenido-header">
            <div class="barra">
                <a href="index.php" class="logo">
                   <img src="build/img/iconos/logo.webp" alt="Logo de la tienda">
                </a>
                <h1>Mi Carrito</h1>
            </div>
        </div>
    </header>

    <div class="carrito-ver-productos">
        <a href="productos.php">Ver productos</a>
    </div>

    <main>
        <div class="contenedor-carrito">

            <?php if(isset($_SESSION['error_mensaje'])): ?>
                <div class="alerta error">
                    <?php echo $_SESSION['error_mensaje']; unset($_SESSION['error_mensaje']); ?>
                </div>
            <?php endif; ?>
            <?php if(isset($_SESSION['exito_mensaje'])): ?>
                <div class="alerta exito">
                    <?php echo $_SESSION['exito_mensaje']; unset($_SESSION['exito_mensaje']); ?>
                </div>
            <?php endif; ?>


            <?php if (empty($productos_carrito)): ?>
                
                <h2>Tu carrito está vacío</h2>
                
            <?php else: ?>
            
                <table class="tabla-carrito">
                    <thead>
                        <tr>
                            <th class="col-wishlist">Wishlist</th>
                            <th>Imagen</th>
                            <th>Producto</th>
                            <th>Talla</th>
                            <th>Precio Unitario</th>
                            <th>Cantidad</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($productos_carrito as $producto): ?>
                            <tr>
                                <td class="col-wishlist wishlist-toggle">
                                    <?php if ($producto['es_wishlist']): ?>
                                        <a href="toggle_wishlist.php?id=<?php echo $producto['id']; ?>&estado=0" title="Quitar de Wishlist">❤️</a>
                                    <?php else: ?>
                                        <a href="toggle_wishlist.php?id=<?php echo $producto['id']; ?>&estado=1" title="Guardar en Wishlist">🤍</a>
                                    <?php endif; ?>
                                </td>
                                
                                <td><img src="<?php echo $producto['imagen']; ?>" alt="<?php echo htmlspecialchars($producto['nombre']); ?>"></td>
                                <td><?php echo htmlspecialchars($producto['nombre']); ?></td>
                                <td><?php echo htmlspecialchars($producto['talla']); ?></td>
                                <td>$<?php echo number_format($producto['precio'], 2); ?></td>
                                <td><?php echo $producto['cantidad']; ?></td>
                                <td>$<?php echo number_format($producto['subtotal'], 2); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr class="fila-total">
                            <td colspan="6">Total General:</td>
                            <td>$<?php echo number_format($total_general, 2); ?></td>
                        </tr>
                    </tfoot>
                </table>
                
                <div class="acciones-carrito">
                    <a href="carrito.php?vaciar=true" class="btn-vaciar" onclick="return confirm('¿Estás seguro de que deseas vaciar tu carrito? (Los items de Wishlist se conservarán)');">Vaciar Carrito</a>
                    <a href="pagar.php" class="btn-pagar">Proceder al Pago</a>
                </div>
            
            <?php endif; ?>

        </div>
    </main>

    <footer class="footer-sesion">
        <p class="copyright">Todos los derechos reservados. Juárez Herrera Erick Adrián &copy; </p>
    </footer>

</body>
</html>