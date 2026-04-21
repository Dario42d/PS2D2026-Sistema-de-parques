<?php
// Configuración para que devuelva JSON y permita conexión desde el Frontend (CORS)
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *'); 
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

require 'conexion.php';

// 1. Recibir email y password (vienen en formato JSON desde el frontend)
$input = json_decode(file_get_contents('php://input'), true);
$email = $input['email'] ?? '';
$password = $input['password'] ?? '';

// Validar que no lleguen vacíos
if (empty($email) || empty($password)) {
    http_response_code(400);
    echo json_encode(['mensaje' => 'Email y contraseña son requeridos']);
    exit;
}

try {
    // 2. Buscar usuario en MySQL
    $stmt = $pdo->prepare('SELECT id, password_hash, activo FROM usuarios WHERE email = ?');
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    $loginExitoso = false;

    // 3. Comparar contraseña (bcrypt) y verificar que el usuario esté activo
    // password_verify es la función nativa de PHP para leer hashes de bcrypt
    if ($user && $user['activo'] == 1 && password_verify($password, $user['password_hash'])) {
        $loginExitoso = true;
    }

    // 4. Registrar el intento en intentos_login
    $stmtIntento = $pdo->prepare('INSERT INTO intentos_login (email, exito) VALUES (?, ?)');
    $stmtIntento->execute([$email, $loginExitoso ? 1 : 0]);

    // 5. Retornar respuesta (ok / error)
    if ($loginExitoso) {
        
        // Crear un token seguro y definir expiración (8 horas)
        $token = bin2hex(random_bytes(32)); 
        $fechaExpiracion = date('Y-m-d H:i:s', strtotime('+8 hours'));

        // Guardar el token en la tabla sesiones
        $stmtSesion = $pdo->prepare('INSERT INTO sesiones (usuario_id, token, fecha_expiracion) VALUES (?, ?, ?)');
        $stmtSesion->execute([$user['id'], $token, $fechaExpiracion]);

        // Retornar 200 OK -> acceso permitido
        http_response_code(200);
        echo json_encode([
            'mensaje' => 'Acceso permitido',
            'token' => $token
        ]);
    } else {
        // Retornar 401 Unauthorized -> acceso denegado
        http_response_code(401);
        echo json_encode(['mensaje' => 'Credenciales incorrectas o usuario inactivo']);
    }

} catch (Exception $e) {
    // Error interno del servidor
    http_response_code(500);
    echo json_encode(['mensaje' => 'Error en el servidor', 'detalle' => $e->getMessage()]);
}
?>