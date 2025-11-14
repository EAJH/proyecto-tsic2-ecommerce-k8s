<?php
session_start();
require 'db.php'; // Necesitamos la BD para actualizar el stock

// ===============================================
// 1. GUARDIA DE SEGURIDAD: ¿Estás logueado?
// ===============================================
if ( !isset($_SESSION['usuario_id']) ) {
    // Si no está logueado, lo mandamos al login
    $_SESSION['error_mensaje'] = "Debes iniciar sesión para finalizar tu compra.";
    header('Location: login.php');
    exit;
}

// ===============================================
// 2. GUARDIA DE SEGURIDAD: ¿El carrito está vacío?
// ===============================================
if (empty($_SESSION['carrito'])) {
    // Si el carrito está vacío, no hay nada que pagar
    header('Location: productos.php');
    exit;
}

// ===============================================
// 3. LÓGICA DE PROCESAMIENTO DEL PAGO
// ===============================================

$db = conectarDB();

try {
    // ¡Iniciamos una transacción!
    // Esto asegura que si UNA actualización de stock falla, NINGUNA se aplica.
    $db->beginTransaction();
    
    $carrito = $_SESSION['carrito'];
    $wishlist = $_SESSION['wishlist_items'] ?? [];
    $nuevo_carrito_session = []; // Carrito temporal para guardar solo lo de la wishlist

    // Preparamos la consulta UNA SOLA VEZ
    $stmt = $db->prepare("UPDATE Variantes SET stock = stock - ? WHERE id = ?");
    
    foreach ($carrito as $variante_id => $cantidad) {
        
        // ----- A. ACTUALIZAR LA BASE DE DATOS -----
        // Restamos la cantidad del stock
        $stmt->execute([$cantidad, $variante_id]);
        
        // ----- B. ACTUALIZAR LA SESIÓN (CARRITO) -----
        // Verificamos si este item debe borrarse o no
        if (isset($wishlist[$variante_id])) {
            // Si está en la wishlist, lo conservamos en la sesión
            $nuevo_carrito_session[$variante_id] = $cantidad;
        }
        // Si NO está en la wishlist, simplemente no lo añadimos al
        // $nuevo_carrito_session, borrándolo efectivamente.
    }
    
    // Si llegamos aquí, todas las actualizaciones de stock funcionaron
    $db->commit();
    
    // ----- C. FINALIZAR -----
    // Reemplazamos el carrito viejo por el nuevo (que solo tiene items de wishlist)
    $_SESSION['carrito'] = $nuevo_carrito_session;
    
    // Guardamos el mensaje de éxito para mostrarlo en carrito.php
    $_SESSION['exito_mensaje'] = "¡Pago exitoso! Gracias por tu compra.";

} catch (Exception $e) {
    // Si algo falló (ej. no había stock, la BD se cayó), revertimos todo
    $db->rollBack();
    
    // Guardamos un mensaje de error
    $_SESSION['error_mensaje'] = "Hubo un error al procesar tu pago. Por favor, inténtalo de nuevo. Error: " . $e->getMessage();
}

// 4. Redirigir de vuelta a la página del carrito
header('Location: carrito.php');
exit;
?>