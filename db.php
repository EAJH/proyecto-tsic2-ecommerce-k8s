<?php
// Archivo: db.php

/**
 * Crea una conexión a la base de datos usando PDO.
 * Lee las credenciales de las variables de entorno de Docker.
 * * @return PDO La instancia de la conexión a la base de datos.
 * @throws \PDOException Si la conexión falla.
 */
function conectarDB() : PDO {
    
    // Lee las variables de entorno de tu docker-compose.yml
    $host = getenv('DB_HOST');       // Esto será 'db'
    $db   = getenv('DB_DATABASE'); // 'tsic2'
    $user = getenv('DB_USER');       // 'admin'
    $pass = getenv('DB_PASSWORD'); // 'admin'
    $charset = 'utf8mb4';

    // Data Source Name (DSN)
    $dsn = "mysql:host=$host;dbname=$db;charset=$charset";

    $options = [
        // Configura PDO para que lance excepciones en caso de error
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        // Configura PDO para que devuelva los resultados como arreglos asociativos
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];

    try {
         // Intenta crear la conexión
         $pdo = new PDO($dsn, $user, $pass, $options);
         return $pdo;
         
    } catch (\PDOException $e) {
         // Si falla, lanza una excepción
         throw new \PDOException($e->getMessage(), (int)$e->getCode());
    }
}
?>