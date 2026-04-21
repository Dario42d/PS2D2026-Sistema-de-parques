<?php
$host = 'localhost';
$db   = 'Parques'; // nombre de la base de datos del jomel que nose cual es 
$user = 'root';
$pass = 'tu_password';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
     $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
     http_response_code(500);
     echo json_encode(['mensaje' => 'Error de conexión a la base de datos']);
     exit;
}
?>