<?php
header('Content-Type: application/json; charset=utf-8');
session_start();

require_once __DIR__ . '/../config/database.php';

$method = $_SERVER['REQUEST_METHOD'];

/* GET is public (index.php uses it for admin modal pre-fill) */
if ($method === 'GET') {
    try {
        $bio = getDB()->query("SELECT * FROM biografia LIMIT 1")->fetch();
        echo json_encode(['success' => true, 'data' => $bio]);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'data' => null]);
    }
    exit;
}

/* All other methods require auth */
if (!isset($_SESSION['admin_logged'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'No autorizado.']);
    exit;
}

$body = json_decode(file_get_contents('php://input'), true) ?? [];

if ($method === 'POST') {
    $nombre  = trim($body['nombre']            ?? '');
    $cargo   = trim($body['cargo']             ?? '');
    $desc    = trim($body['descripcion']       ?? '');
    $desc2   = trim($body['descripcion_extra'] ?? '');
    $email   = trim($body['email']             ?? '');
    $foto    = trim($body['foto']              ?? '');
    $id      = (int)($body['id']              ?? 0);

    if ($nombre === '') {
        echo json_encode(['success' => false, 'message' => 'El nombre es obligatorio.']);
        exit;
    }

    try {
        $db = getDB();
        if ($id > 0) {
            $stmt = $db->prepare("UPDATE biografia SET nombre=?,cargo=?,descripcion=?,descripcion_extra=?,email=?,foto=? WHERE id=?");
            $stmt->execute([$nombre,$cargo,$desc,$desc2,$email,$foto ?: null,$id]);
        } else {
            $count = $db->query("SELECT COUNT(*) FROM biografia")->fetchColumn();
            if ($count > 0) {
                $stmt = $db->prepare("UPDATE biografia SET nombre=?,cargo=?,descripcion=?,descripcion_extra=?,email=?,foto=? LIMIT 1");
                $stmt->execute([$nombre,$cargo,$desc,$desc2,$email,$foto ?: null]);
            } else {
                $stmt = $db->prepare("INSERT INTO biografia (nombre,cargo,descripcion,descripcion_extra,email,foto) VALUES (?,?,?,?,?,?)");
                $stmt->execute([$nombre,$cargo,$desc,$desc2,$email,$foto ?: null]);
            }
        }
        echo json_encode(['success' => true, 'message' => 'Biografía actualizada correctamente.']);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Error al guardar.']);
    }
    exit;
}

http_response_code(405);
echo json_encode(['success' => false, 'message' => 'Método no permitido.']);
