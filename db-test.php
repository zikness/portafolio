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

echo '<div class="container" style="max-width:780px">';
echo '<h4 class="mb-3">🔍 Diagnóstico de conexión MySQL</h4>';

echo '<table class="table table-dark table-bordered mb-4">';
echo '<tr><th>Parámetro</th><th>Valor</th></tr>';
echo '<tr><td>DB_HOST</td><td>' . DB_HOST . '</td></tr>';
echo '<tr><td>DB_NAME</td><td>' . DB_NAME . '</td></tr>';
echo '<tr><td>DB_USER</td><td>' . DB_USER . '</td></tr>';
echo '<tr><td>DB_PASS</td><td>' . str_repeat('*', strlen(DB_PASS)) . ' (' . strlen(DB_PASS) . ' chars)</td></tr>';
echo '</table>';

// Step 1: Test credentials WITHOUT specifying database
echo '<h5>Paso 1 — Validar usuario/contraseña (sin seleccionar BD):</h5>';
try {
    $dsn0 = 'mysql:host=' . DB_HOST . ';charset=utf8mb4';
    $pdo0 = new PDO($dsn0, DB_USER, DB_PASS, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    echo '<div class="alert alert-success">✅ Usuario <strong>' . DB_USER . '</strong> y contraseña son <strong>correctos</strong>.</div>';

    // Step 2: List accessible databases
    echo '<h5>Paso 2 — Bases de datos disponibles para este usuario:</h5>';
    $dbs = $pdo0->query("SHOW DATABASES")->fetchAll(PDO::FETCH_COLUMN);
    echo '<ul class="list-group mb-4">';
    $found = false;
    foreach ($dbs as $dbname) {
        $active = ($dbname === DB_NAME) ? ' list-group-item-success' : ' list-group-item-dark';
        $marker = ($dbname === DB_NAME) ? ' ✅ <strong>(esta es la configurada)</strong>' : '';
        echo '<li class="list-group-item' . $active . '">' . htmlspecialchars($dbname) . $marker . '</li>';
        if ($dbname === DB_NAME) $found = true;
    }
    echo '</ul>';

    if (!$found) {
        echo '<div class="alert alert-warning">⚠️ La base de datos <strong>' . DB_NAME . '</strong> <strong>NO existe</strong>.<br>';
        echo 'Debes crearla en phpMyAdmin: clic en <strong>"Nueva"</strong>, nombre <code>' . DB_NAME . '</code>, cotejamiento <code>utf8mb4_unicode_ci</code>, luego importa <code>bd.sql</code>.</div>';

        echo '<div class="alert alert-info"><strong>Bases de datos que SÍ puedes usar:</strong><ul class="mt-2 mb-0">';
        foreach ($dbs as $dbname) {
            if (!in_array($dbname, ['information_schema', 'performance_schema', 'mysql', 'sys'])) {
                echo '<li><code>' . htmlspecialchars($dbname) . '</code> — cambia DB_NAME a este valor en config/database.php</li>';
            }
        }
        echo '</ul></div>';
    }

} catch (PDOException $e) {
    $msg = $e->getMessage();
    echo '<div class="alert alert-danger">';
    echo '<strong>❌ Credenciales inválidas o MySQL rechaza la conexión:</strong><br>';
    echo '<code>' . htmlspecialchars($msg) . '</code>';
    echo '</div>';

    echo '<div class="alert alert-warning"><strong>Posibles causas:</strong><ul class="mt-2 mb-0">';
    if (str_contains($msg, 'Access denied')) {
        echo '<li>La contraseña de MySQL para el usuario <strong>' . DB_USER . '</strong> puede ser diferente a la de phpMyAdmin.</li>';
        echo '<li>En TECLAB, prueba con el usuario <strong>caguilera2025</strong> (igual al FTP) en lugar de <strong>caguilera</strong>.</li>';
        echo '<li>Ve a phpMyAdmin → tu usuario → pestaña "Cambiar contraseña" para ver o resetear la contraseña MySQL.</li>';
    }
    echo '</ul></div>';

    // Try with caguilera2025 as fallback hint
    echo '<div class="alert alert-info mt-3"><strong>💡 Sugerencia para TECLAB:</strong><br>';
    echo 'El usuario MySQL en servidores universitarios suele ser el mismo que el usuario del sistema (<code>caguilera2025</code>). ';
    echo 'Si tu phpMyAdmin te pide <code>caguilera2025</code> como usuario para entrar, actualiza DB_USER en <code>config/database.php</code> a <code>caguilera2025</code>.</div>';
}

// Step 3: Full connection test (with DB)
echo '<h5 class="mt-4">Paso 3 — Conexión completa con BD <code>' . DB_NAME . '</code>:</h5>';
try {
    $db = getDB();
    echo '<div class="alert alert-success">✅ Conexión completa exitosa a <strong>' . DB_NAME . '</strong></div>';

    $tables = $db->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
    echo '<h6>Tablas encontradas:</h6><ul class="list-group mb-3">';
    foreach ($tables as $t) {
        echo '<li class="list-group-item list-group-item-dark">' . htmlspecialchars($t) . '</li>';
    }
    if (empty($tables)) {
        echo '<li class="list-group-item list-group-item-warning">⚠️ Sin tablas. Importa <code>bd.sql</code> en phpMyAdmin.</li>';
    }
    echo '</ul>';

    try {
        $u = $db->query("SELECT username FROM usuarios LIMIT 5")->fetchAll();
        echo '<h6>Usuarios en BD:</h6><ul class="list-group mb-3">';
        if (empty($u)) {
            echo '<li class="list-group-item list-group-item-warning">⚠️ Sin usuarios. Importa bd.sql.</li>';
        }
        foreach ($u as $row) {
            echo '<li class="list-group-item list-group-item-dark">' . htmlspecialchars($row['username']) . '</li>';
        }
        echo '</ul>';
    } catch (Exception $e2) {
        echo '<div class="alert alert-warning">⚠️ Tabla <code>usuarios</code> no existe. Importa bd.sql.</div>';
    }

} catch (PDOException $e) {
    echo '<div class="alert alert-danger"><code>' . htmlspecialchars($e->getMessage()) . '</code></div>';
}

echo '<div class="alert alert-warning mt-4">⚠️ <strong>Elimina este archivo</strong> (db-test.php) después de diagnosticar.</div>';
echo '</div></body></html>';
