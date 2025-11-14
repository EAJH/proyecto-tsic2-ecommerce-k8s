<?php
session_start();

// Validamos los datos que llegan por la URL (GET)
$id = filter_var($_GET['id'], FILTER_VALIDATE_INT);
$estado = filter_var($_GET['estado'], FILTER_VALIDATE_INT); // 1 para guardar, 0 para quitar

if ($id) {
    // Inicializar la sesión si no existe
    if (!isset($_SESSION['wishlist_items'])) {
        $_SESSION['wishlist_items'] = [];
    }

    if ($estado === 1) {
        // Añadir a la wishlist
        $_SESSION['wishlist_items'][$id] = true;
    } elseif ($estado === 0) {
        // Quitar de la wishlist
        unset($_SESSION['wishlist_items'][$id]);
    }
}

// Redirigir siempre de vuelta al carrito
header('Location: carrito.php');
exit;
?>