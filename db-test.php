<?php
/*
 * db-test.php — Diagnóstico de conexión MySQL
 * ELIMINAR ESTE ARCHIVO DESPUÉS DE USARLO
 */
require_once __DIR__ . '/config/database.php';

echo '<!DOCTYPE html><html lang="es"><head>
<meta charset="UTF-8">
<title>Diagnóstico BD</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"/>
</head><body class="bg-dark text-light p-4">';

echo '<div class="container" style="max-width:700px">';
echo '<h4 class="mb-3">🔍 Diagnóstico de conexión MySQL</h4>';
echo '<table class="table table-dark table-bordered mb-4">';
echo '<tr><th>Parámetro</th><th>Valor</th></tr>';
echo '<tr><td>DB_HOST</td><td>' . DB_HOST . '</td></tr>';
echo '<tr><td>DB_NAME</td><td>' . DB_NAME . '</td></tr>';
echo '<tr><td>DB_USER</td><td>' . DB_USER . '</td></tr>';
echo '<tr><td>DB_PASS</td><td>' . str_repeat('*', strlen(DB_PASS)) . ' (' . strlen(DB_PASS) . ' caracteres)</td></tr>';
echo '</table>';

echo '<h5>Resultado de conexión:</h5>';
try {
    $db = getDB();
    echo '<div class="alert alert-success">✅ Conexión exitosa a <strong>' . DB_NAME . '</strong></div>';

    // Check tables
    $tables = $db->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
    echo '<h6>Tablas encontradas:</h6><ul class="list-group mb-3">';
    foreach ($tables as $t) {
        echo '<li class="list-group-item list-group-item-dark">' . htmlspecialchars($t) . '</li>';
    }
    if (empty($tables)) {
        echo '<li class="list-group-item list-group-item-warning">⚠️ No hay tablas. Importa bd.sql en phpMyAdmin.</li>';
    }
    echo '</ul>';

    // Check admin user
    try {
        $u = $db->query("SELECT username FROM usuarios LIMIT 5")->fetchAll();
        echo '<h6>Usuarios en BD:</h6><ul class="list-group mb-3">';
        if (empty($u)) {
            echo '<li class="list-group-item list-group-item-warning">⚠️ Sin usuarios. Ejecuta install.php o importa bd.sql.</li>';
        }
        foreach ($u as $row) {
            echo '<li class="list-group-item list-group-item-dark">' . htmlspecialchars($row['username']) . '</li>';
        }
        echo '</ul>';
    } catch (Exception $e2) {
        echo '<div class="alert alert-warning">⚠️ Tabla usuarios no existe. Importa bd.sql.</div>';
    }

} catch (PDOException $e) {
    echo '<div class="alert alert-danger">';
    echo '<strong>❌ Error de conexión:</strong><br>';
    echo '<code>' . htmlspecialchars($e->getMessage()) . '</code>';
    echo '</div>';

    echo '<div class="alert alert-info mt-3">';
    echo '<strong>Posibles causas y soluciones:</strong><ul class="mb-0 mt-2">';

    $msg = $e->getMessage();
    if (str_contains($msg, 'Access denied')) {
        echo '<li>Usuario <strong>' . DB_USER . '</strong> o contraseña incorrectos</li>';
        echo '<li>Ve a phpMyAdmin → Privilegios → Verifica el usuario y su contraseña</li>';
        echo '<li>El usuario de MySQL en TECLAB puede ser diferente al de sistema (ej: <strong>caguilera2025</strong> en lugar de <strong>caguilera</strong>)</li>';
    } elseif (str_contains($msg, 'Unknown database') || str_contains($msg, "doesn't exist")) {
        echo '<li>La base de datos <strong>' . DB_NAME . '</strong> no existe</li>';
        echo '<li>Crea la base de datos en phpMyAdmin y luego importa bd.sql</li>';
    } elseif (str_contains($msg, 'Connection refused') || str_contains($msg, 'No such file')) {
        echo '<li>MySQL no está corriendo en <strong>' . DB_HOST . '</strong></li>';
        echo '<li>Contacta al soporte de TECLAB</li>';
    } else {
        echo '<li>Revisa todos los parámetros en config/database.php</li>';
    }
    echo '</ul></div>';
}

echo '<div class="alert alert-warning mt-4">⚠️ <strong>Elimina este archivo</strong> (db-test.php) después de diagnosticar.</div>';
echo '</div></body></html>';
