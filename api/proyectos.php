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
    $rows = $db->query("SELECT * FROM proyectos ORDER BY orden ASC")->fetchAll();
    echo json_encode(['success' => true, 'data' => $rows]);
    exit;
}

if ($method === 'POST') {
    $titulo = trim($body['titulo'] ?? '');
    if ($titulo === '') { echo json_encode(['success'=>false,'message'=>'Título obligatorio.']); exit; }

    $stmt = $db->prepare(
        "INSERT INTO proyectos (titulo,descripcion,imagen,url_demo,url_github,tecnologias_usadas,orden)
         VALUES (?,?,?,?,?,?,?)"
    );
    $stmt->execute([
        $titulo,
        trim($body['descripcion']        ?? ''),
        trim($body['imagen']             ?? '') ?: null,
        trim($body['url_demo']           ?? ''),
        trim($body['url_github']         ?? ''),
        trim($body['tecnologias_usadas'] ?? ''),
        (int)($body['orden']             ?? 0),
    ]);
    echo json_encode(['success' => true, 'message' => 'Proyecto agregado.', 'id' => $db->lastInsertId()]);
    exit;
}

if ($method === 'PUT') {
    $id = (int)($body['id'] ?? 0);
    if (!$id) { echo json_encode(['success'=>false,'message'=>'ID inválido.']); exit; }

    $stmt = $db->prepare(
        "UPDATE proyectos SET titulo=?,descripcion=?,imagen=?,url_demo=?,url_github=?,tecnologias_usadas=?,orden=?
         WHERE id=?"
    );
    $stmt->execute([
        trim($body['titulo']             ?? ''),
        trim($body['descripcion']        ?? ''),
        trim($body['imagen']             ?? '') ?: null,
        trim($body['url_demo']           ?? ''),
        trim($body['url_github']         ?? ''),
        trim($body['tecnologias_usadas'] ?? ''),
        (int)($body['orden']             ?? 0),
        $id,
    ]);
    echo json_encode(['success' => true, 'message' => 'Proyecto actualizado.']);
    exit;
}

if ($method === 'DELETE') {
    $id = (int)($body['id'] ?? 0);
    if (!$id) { echo json_encode(['success'=>false,'message'=>'ID inválido.']); exit; }

    $db->prepare("DELETE FROM proyectos WHERE id=?")->execute([$id]);
    echo json_encode(['success' => true, 'message' => 'Proyecto eliminado.']);
    exit;
}

http_response_code(405);
echo json_encode(['success' => false, 'message' => 'Método no permitido.']);
