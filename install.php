<?php
/*
 * install.php — Crea el usuario administrador inicial.
 * EJECUTAR UNA SOLA VEZ, luego eliminar este archivo.
 */
require_once __DIR__ . '/config/database.php';

$message = '';
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? 'admin');
    $password = trim($_POST['password'] ?? '');
    $confirm  = trim($_POST['confirm']  ?? '');

    if ($username === '' || $password === '') {
        $message = 'Completa todos los campos.';
    } elseif (strlen($password) < 6) {
        $message = 'La contraseña debe tener al menos 6 caracteres.';
    } elseif ($password !== $confirm) {
        $message = 'Las contraseñas no coinciden.';
    } else {
        try {
            $db   = getDB();
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $db->prepare("INSERT INTO usuarios (username, password_hash) VALUES (?, ?)
                                  ON DUPLICATE KEY UPDATE password_hash = VALUES(password_hash)");
            $stmt->execute([$username, $hash]);
            $message = "Usuario '$username' creado / actualizado correctamente. ¡Elimina este archivo!";
            $success = true;
        } catch (Exception $e) {
            $message = 'Error: ' . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8"/>
    <title>Instalación — Portafolio</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"/>
    <link rel="stylesheet" href="assets/css/style.css"/>
</head>
<body class="topo-bg">
<div class="login-page">
    <div class="login-card">
        <h4 class="text-center mb-1">Instalación</h4>
        <p class="login-sub text-center">Crea el usuario administrador</p>

        <?php if ($message): ?>
            <div class="alert-custom <?= $success ? 'alert-success' : 'alert-error' ?> mb-3">
                <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>

        <?php if (!$success): ?>
        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Usuario</label>
                <input type="text" name="username" class="form-control" value="admin" required/>
            </div>
            <div class="mb-3">
                <label class="form-label">Contraseña</label>
                <input type="password" name="password" class="form-control" placeholder="Mínimo 6 caracteres" required/>
            </div>
            <div class="mb-3">
                <label class="form-label">Confirmar contraseña</label>
                <input type="password" name="confirm" class="form-control" required/>
            </div>
            <button type="submit" class="btn-login-submit">Crear administrador</button>
        </form>
        <?php else: ?>
            <div class="text-center mt-3">
                <a href="login.php" style="color:var(--text-primary)">Ir al login &rarr;</a>
            </div>
        <?php endif; ?>
    </div>
</div>
</body>
</html>
