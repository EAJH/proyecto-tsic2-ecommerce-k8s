<?php
// Inicia la sesión para poder acceder a ella
session_start();

// 1. Vacía el arreglo de la sesión
// Esto elimina todas las variables, como ['usuario_id'] y ['rol']
$_SESSION = [];

// 2. Destruye la sesión en el servidor
session_destroy();

// 3. Redirige al usuario de vuelta a la página de login
header('Location: login.php');
exit;
?>