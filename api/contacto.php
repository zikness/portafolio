<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../config/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Método no permitido.']);
    exit;
}

$nombre  = trim($_POST['nombre']  ?? '');
$correo  = trim($_POST['correo']  ?? '');
$asunto  = trim($_POST['asunto']  ?? '');
$mensaje = trim($_POST['mensaje'] ?? '');

if ($nombre === '' || $correo === '' || $mensaje === '') {
    echo json_encode(['success' => false, 'message' => 'Nombre, correo y mensaje son obligatorios.']);
    exit;
}

if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'El correo electrónico no es válido.']);
    exit;
}

try {
    $db   = getDB();
    $stmt = $db->prepare(
        "INSERT INTO contacto (nombre, correo, asunto, mensaje) VALUES (?, ?, ?, ?)"
    );
    $stmt->execute([
        htmlspecialchars($nombre, ENT_QUOTES),
        $correo,
        htmlspecialchars($asunto, ENT_QUOTES),
        htmlspecialchars($mensaje, ENT_QUOTES),
    ]);

    // Send email notification
    $to      = 'c.danielaguilera29@gmail.com';
    $subject = '=?UTF-8?B?' . base64_encode('Nuevo contacto: ' . ($asunto ?: 'Sin asunto')) . '?=';
    $body    = "Has recibido un nuevo mensaje desde tu portafolio web.\n\n"
             . "Nombre:  {$nombre}\n"
             . "Correo:  {$correo}\n"
             . "Asunto:  " . ($asunto ?: 'Sin asunto') . "\n\n"
             . "Mensaje:\n{$mensaje}\n\n"
             . "---\nResponde directamente a: {$correo}";
    $headers  = "From: portafolio@teclab.uct.cl\r\n"
              . "Reply-To: {$correo}\r\n"
              . "Content-Type: text/plain; charset=UTF-8\r\n"
              . "X-Mailer: PHP/" . PHP_VERSION;
    @mail($to, $subject, $body, $headers);

    echo json_encode(['success' => true, 'message' => '¡Mensaje enviado correctamente! Te responderé pronto.']);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Error al guardar el mensaje.']);
}
