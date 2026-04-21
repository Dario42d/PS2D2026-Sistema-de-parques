<?php
// login.php

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

require 'conexion.php';

$input = json_decode(file_get_contents('php://input'), true);

$email = $input['email'] ?? '';
$password = $input['password'] ?? '';

if (!$email || !$password) {
    http_response_code(400);
    echo json_encode(['mensaje' => 'Email y contraseña requeridos']);
    exit;
}

try {

    $stmt = $pdo->prepare("SELECT id, password_hash, activo FROM usuarios WHERE email = ?");
    $stmt->execute([$email]);

    $user = $stmt->fetch();

    $ok = false;

    if ($user && $user['activo'] == 1 && password_verify($password, $user['password_hash'])) {
        $ok = true;
    }

    // Guardar intento login
    $stmt = $pdo->prepare("INSERT INTO intentos_login (email, exito) VALUES (?, ?)");
    $stmt->execute([$email, $ok ? 1 : 0]);

    if ($ok) {

        $token = bin2hex(random_bytes(32));
        $exp = date('Y-m-d H:i:s', strtotime('+8 hours'));

        $stmt = $pdo->prepare("INSERT INTO sesiones (usuario_id, token, fecha_expiracion)
                               VALUES (?, ?, ?)");
        $stmt->execute([$user['id'], $token, $exp]);

        echo json_encode([
            'mensaje' => 'Acceso permitido',
            'token' => $token
        ]);

    } else {
        http_response_code(401);
        echo json_encode(['mensaje' => 'Credenciales incorrectas']);
    }

} catch (Exception $e) {

    http_response_code(500);
    echo json_encode([
        'mensaje' => 'Error servidor',
        'detalle' => $e->getMessage()
    ]);
}
?>