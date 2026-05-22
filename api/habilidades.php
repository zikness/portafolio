<?php
header('Content-Type: application/json; charset=utf-8');
session_start();

if (!isset($_SESSION['admin_logged'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'No autorizado.']);
    exit;
}

require_once __DIR__ . '/../config/database.php';

$body   = json_decode(file_get_contents('php://input'), true) ?? [];
$method = !empty($body['_method']) ? strtoupper($body['_method']) : $_SERVER['REQUEST_METHOD'];
$db     = getDB();

if ($method === 'GET') {
    $rows = $db->query("SELECT * FROM habilidades ORDER BY orden ASC")->fetchAll();
    echo json_encode(['success' => true, 'data' => $rows]);
    exit;
}

if ($method === 'POST') {
    $nombre = trim($body['nombre'] ?? '');
    if ($nombre === '') { echo json_encode(['success'=>false,'message'=>'Nombre obligatorio.']); exit; }

    $stmt = $db->prepare("INSERT INTO habilidades (nombre,icono,descripcion,orden) VALUES (?,?,?,?)");
    $stmt->execute([
        $nombre,
        trim($body['icono'] ?? 'bi bi-code-slash'),
        trim($body['descripcion'] ?? ''),
        (int)($body['orden'] ?? 0),
    ]);
    echo json_encode(['success' => true, 'message' => 'Habilidad agregada.', 'id' => $db->lastInsertId()]);
    exit;
}

if ($method === 'PUT') {
    $id = (int)($body['id'] ?? 0);
    if (!$id) { echo json_encode(['success'=>false,'message'=>'ID inválido.']); exit; }

    $stmt = $db->prepare("UPDATE habilidades SET nombre=?,icono=?,descripcion=?,orden=? WHERE id=?");
    $stmt->execute([
        trim($body['nombre']      ?? ''),
        trim($body['icono']       ?? 'bi bi-code-slash'),
        trim($body['descripcion'] ?? ''),
        (int)($body['orden']      ?? 0),
        $id,
    ]);
    echo json_encode(['success' => true, 'message' => 'Habilidad actualizada.']);
    exit;
}

if ($method === 'DELETE') {
    $id = (int)($body['id'] ?? 0);
    if (!$id) { echo json_encode(['success'=>false,'message'=>'ID inválido.']); exit; }

    $db->prepare("DELETE FROM habilidades WHERE id=?")->execute([$id]);
    echo json_encode(['success' => true, 'message' => 'Habilidad eliminada.']);
    exit;
}

http_response_code(405);
echo json_encode(['success' => false, 'message' => 'Método no permitido.']);
