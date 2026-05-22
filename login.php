<?php
session_start();
if (isset($_SESSION['admin_logged'])) {
    header('Location: admin/index.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($username === '' || $password === '') {
        $error = 'Completa todos los campos.';
    } else {
        try {
            require_once __DIR__ . '/config/database.php';
            $db   = getDB();
            $stmt = $db->prepare("SELECT id, password_hash FROM usuarios WHERE username = ? LIMIT 1");
            $stmt->execute([$username]);
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['password_hash'])) {
                session_regenerate_id(true);
                $_SESSION['admin_logged'] = true;
                $_SESSION['admin_id']     = $user['id'];
                $_SESSION['admin_user']   = $username;
                header('Location: admin/index.php');
                exit;
            } else {
                $error = 'Credenciales incorrectas. Usuario o contraseña inválidos.';
            }
        } catch (PDOException $e) {
            $msg = $e->getMessage();
            if (str_contains($msg, 'Access denied') || str_contains($msg, 'No such host')) {
                $error = 'No se puede conectar a la base de datos. Verifica las credenciales en config/database.php.';
            } elseif (str_contains($msg, "doesn't exist") || str_contains($msg, 'Unknown database')) {
                $error = 'La base de datos no existe. Importa bd.sql en phpMyAdmin del servidor.';
            } elseif (str_contains($msg, "Table") && str_contains($msg, "doesn't exist")) {
                $error = 'Las tablas no existen. Importa bd.sql en phpMyAdmin del servidor.';
            } else {
                $error = 'Error de base de datos: ' . htmlspecialchars($msg);
            }
        } catch (Exception $e) {
            $error = 'Error inesperado: ' . htmlspecialchars($e->getMessage());
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Iniciar Sesión — Admin</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"/>
    <link rel="stylesheet" href="assets/css/style.css"/>
</head>
<body class="topo-bg">

<div class="login-page">
    <div class="login-card">
        <!-- Header -->
        <div class="text-center mb-4">
            <i class="bi bi-shield-lock" style="font-size:2.5rem;color:var(--text-muted);"></i>
            <h4 class="mt-2">Panel de Administración</h4>
            <p class="login-sub">Ingresa tus credenciales para continuar</p>
        </div>

        <?php if ($error): ?>
            <div class="alert-custom alert-error">
                <i class="bi bi-exclamation-circle me-1"></i><?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <form method="POST" novalidate>
            <div class="mb-3">
                <label class="form-label">Usuario</label>
                <input type="text" name="username" class="form-control"
                       placeholder="Nombre de usuario"
                       value="<?= htmlspecialchars($_POST['username'] ?? '') ?>"
                       required autofocus/>
            </div>
            <div class="mb-3">
                <label class="form-label">Contraseña</label>
                <input type="password" name="password" class="form-control"
                       placeholder="••••••••" required/>
            </div>
            <button type="submit" class="btn-login-submit">
                <i class="bi bi-box-arrow-in-right me-1"></i> Ingresar
            </button>
        </form>

        <div class="login-back">
            <a href="index.php"><i class="bi bi-arrow-left me-1"></i>Volver al portafolio</a>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
