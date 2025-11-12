<?php
// ===============================================
// 1. INICIAR LA SESIÓN
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
$exito_mensaje = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Incluir la conexión a la BD
    require 'db.php';
    
    // 1. Obtener y limpiar datos del formulario
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $password_confirm = trim($_POST['password_confirm']);

    // 2. Validaciones
    if (empty($email) || empty($password) || empty($password_confirm)) {
        $error_mensaje = "Por favor, completa todos los campos.";
    } elseif ($password !== $password_confirm) {
        $error_mensaje = "Las contraseñas no coinciden.";
    } elseif (strlen($password) < 6) {
        $error_mensaje = "La contraseña debe tener al menos 6 caracteres.";
    } else {
        
        try {
            $db = conectarDB();
            
            // 3. Revisar si el email ya existe
            $stmt = $db->prepare("SELECT id FROM Clientes WHERE email = ?");
            $stmt->execute([$email]);
            
            if ($stmt->fetch()) {
                $error_mensaje = "El correo electrónico ya está registrado.";
            } else {
                
                // 4. HASHEAR LA CONTRASEÑA
                $password_hash = password_hash($password, PASSWORD_DEFAULT);

                // 5. Insertar el nuevo usuario en la BD (rol 0 por defecto)
                $stmt = $db->prepare("INSERT INTO Clientes (email, passwd, rol) VALUES (?, ?, 0)");
                $stmt->execute([$email, $password_hash]);

                // 6. Mostrar mensaje de éxito
                $exito_mensaje = "¡Registro exitoso! Ya puedes iniciar sesión.";
                
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
    <title>Registrarse</title>
    <!-- Tu CSS de Gulp -->
    <link rel="stylesheet" href="build/css/app.css">
</head>
<body class="body-sesion">

    <div class="login-wrapper">
        <header class="header-sesion inicio">
            <h1>Crear Cuenta</h1>
        </header>

        <main class="main">
            <form class="formulario-login" action="registro.php" method="POST">
                
                <!-- Mostrar mensajes de error o éxito -->
                <?php if (!empty($error_mensaje)): ?>
                    <div class="alerta error">
                        <?php echo $error_mensaje; ?>
                    </div>
                <?php endif; ?>
                
                <?php if (!empty($exito_mensaje)): ?>
                    <div class="alerta exito">
                        <?php echo $exito_mensaje; ?>
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
                
                <div class="campo">
                    <label for="password_confirm">Confirmar Contraseña:</label>
                    <input type="password" id="password_confirm" name="password_confirm" required>
                </div>

                <button class="boton-amarillo boton-login-enviar" type="submit">Registrarse</button>
            </form>
            
            <a class="icono-login-registrate" href="login.php">¿Ya tienes cuenta? Inicia sesión</a>
        </main>

        <footer class="footer-sesion">
            <p class="copyright">Todos los derechos reservados. Juárez Herrera Erick Adrián &copy; </p>
        </footer>
    </div>
    
</body>
</html>