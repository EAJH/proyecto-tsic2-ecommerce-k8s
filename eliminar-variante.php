<?php
session_start();

// GUARDIA DE SEGURIDAD
if ( !isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 1 ) {
    header('Location: login.php');
    exit; 
}

require 'db.php';

// 1. Obtener y validar el ID de la URL
$variante_id = filter_var($_GET['id'], FILTER_VALIDATE_INT);

if ($variante_id) {
    try {
        $db = conectarDB();
        
        // 2. Preparar y ejecutar el borrado
        $stmt = $db->prepare("DELETE FROM Variantes WHERE id = ?");
        $stmt->execute([$variante_id]);

    } catch (Exception $e) {
        // (Opcional: podrías guardar el error en una sesión para mostrarlo)
        // $_SESSION['error_mensaje'] = "Error al borrar: " . $e->getMessage();
    }
}

// 3. Redirigir siempre de vuelta al inventario
header('Location: inventario.php');
exit;