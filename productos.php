<?php
// ===============================================
// 1. INICIAR Y CONECTAR
// ===============================================
session_start();
require 'db.php';
$db = conectarDB();

// ===============================================
// 2. LEER DATOS DE LOS FORMULARIOS (GET)
// ===============================================
$filtro_categoria = $_GET['categoria'] ?? '';
$busqueda_nombre = $_GET['busqueda'] ?? '';

// ===============================================
// 3. CONSTRUIR LA CONSULTA DINÁMICA
// ===============================================
$sql = "SELECT * FROM Productos WHERE 1=1";
$params = []; 

if (!empty($filtro_categoria)) {
    $sql .= " AND categoria = ?";
    $params[] = $filtro_categoria;
}

if (!empty($busqueda_nombre)) {
    $sql .= " AND nombre LIKE ?";
    $params[] = "%" . $busqueda_nombre . "%";
}

$stmt = $db->prepare($sql);
$stmt->execute($params);
$productos = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tienda de ropa. Productos</title>
    <link rel="stylesheet" href="build/css/app.css">
    <style>
        /* Estilos para las alertas (puedes moverlos a SASS) */
        .alerta { width: 90%; max-width: 1200px; margin: 1rem auto; text-align: center; padding: 1rem; border-radius: 5px; }
        .alerta.error { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .alerta.exito { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .form-carrito div { margin-bottom: 1rem; }
    </style>
</head>
<body>
    
    <header class="header inicio">
        <div class="contenedor contenido-header">
            <div class="barra">
                <a href="index.php" class="logo">
                   <img src="build/img/iconos/logo.webp" alt="Logo de la tienda">
                </a>
                <h1>Tienda de ropa Online para hombres</h1>
                <nav class="navegacion">
                    <div class="iconos-header">
                        <div class="icono">
                            <a href="carrito.php">
                                <img src="build/img/iconos/carrito-compras.svg" alt="Carrito de compras" loading="lazy">
                            </a>
                        </div>
                        <div class="icono">
                            <a href="inventario.php">
                                <img src="build/img/iconos/estrella.svg" alt="Inventario" loading="lazy">
                            </a>
                        </div>
                        <div class="icono">
                            <?php if (isset($_SESSION['usuario_id'])): ?>
                                <a href="logout.php" title="Cerrar Sesión">
                                    <img src="build/img/iconos/user-logout.svg" alt="Logout" loading="lazy">
                                </a>
                            <?php else: ?>
                                <a href="login.php" title="Iniciar Sesión">
                                    <img src="build/img/iconos/user.svg" alt="Login" loading="lazy">
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </nav> 
            </div>
        </div>
        <p class="slogan">Tu estilo, a un clic.</p>
    </header>

    <section class="contenido-barra-busqueda">
        
        <form action="productos.php" method="GET" class="form-busqueda-combinada form">
            
            <div class="campo-filtro">
                <label for="categoria">Categoría</label>
                <select name="categoria" id="categoria">
                    <option value="">-- Todas --</option>
                    <option value="Playeras" <?php echo ($filtro_categoria === 'Playeras') ? 'selected' : ''; ?>>Playeras</option>
                    <option value="Chamarras" <?php echo ($filtro_categoria === 'Chamarras') ? 'selected' : ''; ?>>Chamarras</option>
                    <option value="Pantalones" <?php echo ($filtro_categoria === 'Pantalones') ? 'selected' : ''; ?>>Pantalones</option>
                </select>
            </div>

            <div class="campo-busqueda">
                <label for="busqueda">Producto</label>
                <input type="text" placeholder="Buscar por nombre..." id="busqueda" name="busqueda" value="<?php echo htmlspecialchars($busqueda_nombre); ?>">
            </div>

            <input type="submit" value="Buscar" class="boton-buscar boton boton-verde">
        
        </form>

    </section>

    <?php if(isset($_SESSION['error_mensaje'])): ?>
        <div class="alerta error">
            <?php 
                echo $_SESSION['error_mensaje']; 
                unset($_SESSION['error_mensaje']); // Borra el mensaje
            ?>
        </div>
    <?php endif; ?>


    <main class="main-productos">
        <h2>Productos en venta</h2>
        <div class="contenedor-productos">
            
            <?php if (empty($productos)): ?>
                <p>No se encontraron productos que coincidan con tu búsqueda.</p>
            <?php else: ?>
                <?php foreach ($productos as $producto): ?>
                    
                    <?php
                    // Obtenemos las variantes (tallas, precios y stock)
                    $stmt_variantes = $db->prepare("SELECT talla, precio, id AS variante_id, stock FROM Variantes WHERE producto_id = ? ORDER BY precio ASC");
                    $stmt_variantes->execute([$producto['id']]);
                    $variantes = $stmt_variantes->fetchAll(PDO::FETCH_ASSOC);
                    $precio_display = !empty($variantes) ? $variantes[0]['precio'] : 0.00;
                    
                    // Lógica de rutas de imagen
                    $ruta_imagen_db = htmlspecialchars($producto['imagen']);
                    $ruta_sin_extension = str_replace(['.jpg', '.jpeg'], '', $ruta_imagen_db);
                    $ruta_webp = "build/img/" . $ruta_sin_extension . ".webp";
                    $ruta_original = "build/img/" . $ruta_imagen_db;
                    ?>

                    <div class="producto">
                        <picture>
                            <source srcset="<?php echo $ruta_webp; ?>" type="image/webp">
                            <source srcset="<?php echo $ruta_original; ?>" type="image/jpeg">
                            <img loading="lazy" src="<?php echo $ruta_original; ?>" alt="<?php echo htmlspecialchars($producto['nombre']); ?>">
                        </picture>
                        <div class="contenido-producto">
                            <h3><?php echo htmlspecialchars($producto['nombre']); ?></h3>
                            <p><?php echo htmlspecialchars($producto['descripcion'] ?? 'Descripción no disponible'); ?></p>
                            <p class="precio">$<?php echo htmlspecialchars($precio_display); ?></p>
                            
                            <form action="agregar_carrito.php" method="POST" class="form-carrito">
                                <div>
                                    <label for="talla-<?php echo $producto['id']; ?>">Talla:</label>
                                    <select name="variante_id" id="talla-<?php echo $producto['id']; ?>" required>
                                        <option value="" disabled selected>-- Seleccionar Talla --</option>
                                        <?php foreach ($variantes as $variante): ?>
                                            <option value="<?php echo $variante['variante_id']; ?>">
                                                <?php echo htmlspecialchars($variante['talla']); ?> ($<?php echo htmlspecialchars($variante['precio']); ?>)
                                                (Stock: <?php echo $variante['stock']; ?>)
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                
                                <div style="margin-top: 1rem;">
                                    <label for="cantidad-<?php echo $producto['id']; ?>">Cantidad:</label>
                                    <input 
                                        type="number" 
                                        id="cantidad-<?php echo $producto['id']; ?>" 
                                        name="cantidad" 
                                        value="1" 
                                        min="1" 
                                        max="10" 
                                        style="width: 60px;"
                                        required>
                                </div>
                                
                                <div class="botones-productos">
                                    <input type="submit" name="accion" value="Añadir al carrito" class="boton boton-amarillo" style="margin-top: 1rem;">
                                    <input type="submit" name="accion" value="Añadir a Wishlist" class="boton boton-verde" style="margin-top: 1rem;">
                                </div>
                                
                            </form>

                            

                        </div> 
                    </div> 
                <?php endforeach; ?>
            <?php endif; ?>

        </div> </main>

    <footer class="footer">
        <p class="copyright">Todos los derechos reservados. Juárez Herrera Erick Adrián &copy; </p>
    </footer>
    <script src="build/js/bundle.min.js"></script>
</body>
</html>