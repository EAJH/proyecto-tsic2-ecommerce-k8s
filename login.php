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
    
    require 'db.php';
    
    $email = trim($_POST['email']);
    $password_ingresada = trim($_POST['password']);

    if (empty($email) || empty($password_ingresada)) {
        $error_mensaje = "Por favor, completa todos los campos.";
    } else {
        
        try {
            $db = conectarDB();
            
            $stmt = $db->prepare("SELECT * FROM Clientes WHERE email = ?");
            $stmt->execute([$email]);
            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($usuario) {
                
                // =========================================================
                // ¡CAMBIO IMPORTANTE!
                // Ahora usamos password_verify() para comparar la contraseña
                // ingresada con el HASH guardado en la base de datos.
                // =========================================================
                if (password_verify($password_ingresada, $usuario['passwd'])) {

                    // ¡Login Exitoso! Guardar en Sesión
                    session_regenerate_id(); // Regenera ID por seguridad
                    $_SESSION['usuario_id'] = $usuario['id'];
                    $_SESSION['rol'] = $usuario['rol'];
                    
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
// (El HTML de tu login.php se queda exactamente igual)
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
<body class="body-sesion">

    <div class="login-wrapper">
        <header class="header-sesion inicio">
            <h1>Iniciar Sesión</h1>
        </header>

        <main class="main">
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

                <button class="boton-amarillo boton-login-enviar" type="submit">Entrar</button>
            </form>

            <a class="icono-login-registrate" href="registro.php">¿No tienes cuenta? Regístrate</a>
        </main>

        <footer class="footer-sesion">
            <p class="copyright">Todos los derechos reservados. Juárez Herrera Erick Adrián &copy; </p>
        </footer>
    </div>
    
    
</body>
</html>