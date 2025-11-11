<?php
// ===============================================
// 1. INICIAR LA SESIÓN (SIEMPRE PRIMERO)
// ===============================================
session_start();

// Si el usuario YA está logueado, redirígirlo al inicio.
if (isset($_SESSION['usuario_id'])) {
    header('Location: index.php');
    exit;
}

// ===============================================
// 2. LÓGICA DE PROCESAMIENTO (SOLO SI ES POST)
// ===============================================
$error_mensaje = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Incluir la conexión a la BD
    require 'db.php';
    
    // 1. Obtener y limpiar datos del formulario
    $email = trim($_POST['email']);
    $password_ingresada = trim($_POST['password']);

    // 2. Validar que no estén vacíos
    if (empty($email) || empty($password_ingresada)) {
        $error_mensaje = "Por favor, completa todos los campos.";
    } else {
        
        try {
            $db = conectarDB();
            
            // 3. Preparar la consulta 
            $stmt = $db->prepare("SELECT * FROM Clientes WHERE email = ?");
            $stmt->execute([$email]);
            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

            // 4. Verificar si el usuario existe
            if ($usuario) {
                
                // 5. Verificar la contraseña
                // =========================================================
                // MÉTODO CORRECTO (cuando uses password_hash() al registrar)
                // if (password_verify($password_ingresada, $usuario['Password'])) {
                // =========================================================

                // MÉTODO INSEGURO (Solo para tus datos de prueba actuales)
                if ($password_ingresada === $usuario['passwd']) {

                    // 6. ¡Login Exitoso! Guardar en Sesión
                    session_regenerate_id(); // Regenera ID por seguridad
                    $_SESSION['usuario_id'] = $usuario['id'];
                    $_SESSION['rol'] = $usuario['rol'];
                    
                    // 7. Redirigir al usuario
                    header('Location: index.php');
                    exit;

                } else {
                    $error_mensaje = "Contraseña incorrecta.";
                }
            } else {
                $error_mensaje = "No se encontró un usuario con ese correo.";
            }

        } catch (\PDOException $e) {
            $error_mensaje = "Error en la base de datos: " . $e->getMessage();
        }
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
    <title>Iniciar Sesión</title>
    <link rel="stylesheet" href="build/css/app.css">
</head>
<body>

    <header>
        <h1>Iniciar Sesión</h1>
        </header>

    <main>
        <form class="formulario-login" action="login.php" method="POST">
            
            <?php if (!empty($error_mensaje)): ?>
                <div class="alerta error">
                    <?php echo $error_mensaje; ?>
                </div>
            <?php endif; ?>

            <div class="campo">
                <label for="email">Correo Electrónico:</label>
                <input type="email" id="email" name="email" required>
            </div>
            
            <div class="campo">
                <label for="password">Contraseña:</label>
                <input type="password" id="password" name="password" required>
            </div>

            <button type="submit">Entrar</button>
        </form>

        <a href="registro.php">¿No tienes cuenta? Regístrate</a>
    </main>
    
</body>
</html>