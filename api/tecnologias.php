<?php
header('Content-Type: application/json; charset=utf-8');
session_start();

if (!isset($_SESSION['admin_logged'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'No autorizado.']);
    exit;
}

require_once __DIR__ . '/../config/database.php';

$method = $_SERVER['REQUEST_METHOD'];
$body   = json_decode(file_get_contents('php://input'), true) ?? [];
$db     = getDB();

if ($method === 'GET') {
    $rows = $db->query("SELECT * FROM tecnologias ORDER BY orden ASC")->fetchAll();
    echo json_encode(['success' => true, 'data' => $rows]);
    exit;
}

if ($method === 'POST') {
    $nombre = trim($body['nombre'] ?? '');
    if ($nombre === '') { echo json_encode(['success'=>false,'message'=>'Nombre obligatorio.']); exit; }

    $pct = max(0, min(100, (int)($body['porcentaje'] ?? 0)));
    $stmt = $db->prepare("INSERT INTO tecnologias (nombre,porcentaje,nivel,orden) VALUES (?,?,?,?)");
    $stmt->execute([$nombre, $pct, trim($body['nivel'] ?? 'Intermedio'), (int)($body['orden'] ?? 0)]);
    echo json_encode(['success' => true, 'message' => 'Tecnología agregada.', 'id' => $db->lastInsertId()]);
    exit;
}

if ($method === 'PUT') {
    $id = (int)($body['id'] ?? 0);
    if (!$id) { echo json_encode(['success'=>false,'message'=>'ID inválido.']); exit; }

    $pct  = max(0, min(100, (int)($body['porcentaje'] ?? 0)));
    $stmt = $db->prepare("UPDATE tecnologias SET nombre=?,porcentaje=?,nivel=?,orden=? WHERE id=?");
    $stmt->execute([
        trim($body['nombre'] ?? ''),
        $pct,
        trim($body['nivel']  ?? 'Intermedio'),
        (int)($body['orden'] ?? 0),
        $id,
    ]);
    echo json_encode(['success' => true, 'message' => 'Tecnología actualizada.']);
    exit;
}

if ($method === 'DELETE') {
    $id = (int)($body['id'] ?? 0);
    if (!$id) { echo json_encode(['success'=>false,'message'=>'ID inválido.']); exit; }

    $db->prepare("DELETE FROM tecnologias WHERE id=?")->execute([$id]);
    echo json_encode(['success' => true, 'message' => 'Tecnología eliminada.']);
    exit;
}

http_response_code(405);
echo json_encode(['success' => false, 'message' => 'Método no permitido.']);
