<?php
session_start();
require 'db.php'; // ¡Necesitamos la BD para consultar el stock!

// Verificamos que los datos lleguen por POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // 1. Obtener los datos del formulario
    $variante_id = filter_var($_POST['variante_id'], FILTER_VALIDATE_INT);
    $cantidad_deseada = filter_var($_POST['cantidad'], FILTER_VALIDATE_INT);
    $accion = $_POST['accion'] ?? ''; // Leemos qué botón se presionó

    // 2. Verificar que los datos sean válidos
    if ($variante_id && $cantidad_deseada > 0) {
        
        try {
            $db = conectarDB();
            
            // 3. Consultar el stock disponible para esta variante
            $stmt = $db->prepare("SELECT stock FROM Variantes WHERE id = ?");
            $stmt->execute([$variante_id]);
            $stock_disponible = $stmt->fetchColumn(); 

            // 4. Obtener la cantidad que el usuario YA tiene en su carrito (si la hay)
            $cantidad_actual_en_carrito = $_SESSION['carrito'][$variante_id] ?? 0;
            
            // 5. Calcular el total que el usuario QUIERE tener
            $total_deseado = $cantidad_actual_en_carrito + $cantidad_deseada;

            // 6. ¡LA VALIDACIÓN DE STOCK!
            if ($total_deseado > $stock_disponible) {
                // No hay suficiente stock
                // Guardamos un mensaje de error en la sesión
                $_SESSION['error_mensaje'] = "No hay suficiente stock. Solo quedan $stock_disponible unidades disponibles.";
                
                // Redirigimos de vuelta a la página de productos
                header('Location: productos.php');
                exit;
            }

            // 7. Inicializar el carrito si no existe
            if (!isset($_SESSION['carrito'])) {
                $_SESSION['carrito'] = [];
            }
            // Inicializar la wishlist si no existe
            if (!isset($_SESSION['wishlist_items'])) {
                $_SESSION['wishlist_items'] = [];
            }

            // 8. Añadir la cantidad al carrito
            $_SESSION['carrito'][$variante_id] = $total_deseado;

            // 9. ¡LÓGICA DE WISHLIST!
            // Si el botón fue "Añadir a Wishlist", marcamos el item
            if ($accion === 'Añadir a Wishlist') {
                $_SESSION['wishlist_items'][$variante_id] = true;
            }

            // 10. Redirigir al usuario a la página del carrito
            header('Location: carrito.php');
            exit;

        } catch (Exception $e) {
            $_SESSION['error_mensaje'] = "Error al añadir el producto: " . $e->getMessage();
            header('Location: productos.php');
            exit;
        }

    } else {
        // Si los datos no son válidos (ej. ID no seleccionado o cantidad = 0)
        $_SESSION['error_mensaje'] = "Datos inválidos. Por favor, selecciona una talla y cantidad.";
        header('Location: productos.php');
        exit;
    }
} else {
    // Si alguien intenta acceder a este archivo directamente por la URL (GET)
    header('Location: index.php');
    exit;
}
?>