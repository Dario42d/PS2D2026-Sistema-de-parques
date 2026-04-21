<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

require 'conexion.php';

$input = json_decode(file_get_contents('php://input'), true);

// 🔍 DEBUG opcional (puedes quitarlo después)
// var_dump($input); exit;

$nombre = $input['nombre'] ?? '';
$email = $input['email'] ?? '';
$password = $input['password'] ?? '';

if (!$nombre || !$email || !$password) {
    http_response_code(400);
    echo json_encode(['mensaje' => 'Todos los campos son obligatorios']);
    exit;
}

try {

    // Verificar email existente
    $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE email = ?");
    $stmt->execute([$email]);

    if ($stmt->fetch()) {
        http_response_code(409);
        echo json_encode(['mensaje' => 'El correo ya está registrado']);
        exit;
    }

    // Hash password
    $hash = password_hash($password, PASSWORD_BCRYPT);

    // Insertar usuario
    $stmt = $pdo->prepare("INSERT INTO usuarios (nombre, email, password_hash, activo)
                           VALUES (?, ?, ?, 1)");
    $stmt->execute([$nombre, $email, $hash]);

    http_response_code(201);
    echo json_encode(['mensaje' => 'Usuario registrado correctamente']);

} catch (Exception $e) {

    http_response_code(500);

    // 🔥 AHORA SÍ VERÁS EL ERROR REAL
    echo json_encode([
        'mensaje' => 'Error en el servidor',
        'detalle' => $e->getMessage()
    ]);
}