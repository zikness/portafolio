<?php
session_start();
if (!isset($_SESSION['admin_logged'])) {
    header('Location: ../login.php');
    exit;
}

require_once __DIR__ . '/../config/database.php';

$db = getDB();

$counts = [
    'biografia'   => $db->query("SELECT COUNT(*) FROM biografia")->fetchColumn(),
    'habilidades' => $db->query("SELECT COUNT(*) FROM habilidades")->fetchColumn(),
    'tecnologias' => $db->query("SELECT COUNT(*) FROM tecnologias")->fetchColumn(),
    'proyectos'   => $db->query("SELECT COUNT(*) FROM proyectos")->fetchColumn(),
];

$bio   = $db->query("SELECT * FROM biografia LIMIT 1")->fetch();
$habs  = $db->query("SELECT * FROM habilidades ORDER BY orden ASC")->fetchAll();
$techs = $db->query("SELECT * FROM tecnologias ORDER BY orden ASC")->fetchAll();
$projs = $db->query("SELECT * FROM proyectos ORDER BY orden ASC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Panel de Administración</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"/>
    <link rel="stylesheet" href="../assets/css/style.css"/>
</head>
<body class="admin-body">

<!-- ============================================================
     ADMIN NAVBAR
     ============================================================ -->
<nav class="admin-navbar">
    <div class="d-flex align-items-center gap-3">
        <i class="bi bi-bar-chart-line brand" style="font-size:1.5rem;"></i>
        <div class="brand">
            <h5>Panel de Administración</h5>
            <small>Gestiona tu portafolio</small>
        </div>
    </div>
    <a href="../logout.php" class="btn-logout">
        <i class="bi bi-box-arrow-right"></i> Cerrar Sesión
    </a>
</nav>

<div class="container py-4">

    <!-- ---- Stat cards ---- -->
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="stat-card">
                <i class="bi bi-person stat-icon"></i>
                <div class="stat-number"><?= $counts['biografia'] ?></div>
                <div class="stat-label">Biografía</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card">
                <i class="bi bi-wrench stat-icon"></i>
                <div class="stat-number"><?= $counts['habilidades'] ?></div>
                <div class="stat-label">Habilidades</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card">
                <i class="bi bi-code-slash stat-icon"></i>
                <div class="stat-number"><?= $counts['tecnologias'] ?></div>
                <div class="stat-label">Tecnologías</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card">
                <i class="bi bi-folder2 stat-icon"></i>
                <div class="stat-number"><?= $counts['proyectos'] ?></div>
                <div class="stat-label">Proyectos</div>
            </div>
        </div>
    </div>

    <!-- ---- Tabs ---- -->
    <div class="admin-tabs-wrapper">
        <ul class="nav admin-tabs" role="tablist">
            <li class="nav-item">
                <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tab-bio" type="button">
                    <i class="bi bi-person"></i> Biografía
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-hab" type="button">
                    <i class="bi bi-tools"></i> Habilidades
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-tec" type="button">
                    <i class="bi bi-code-slash"></i> Tecnologías
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-pro" type="button">
                    <i class="bi bi-folder2"></i> Proyectos
                </button>
            </li>
        </ul>
    </div>

    <!-- ---- Tab content ---- -->
    <div class="tab-content">

        <!-- BIOGRAFíA -->
        <div class="tab-pane fade show active" id="tab-bio">
            <div class="admin-content-panel">
                <div class="panel-header">
                    <h5>Gestionar Biografía</h5>
                    <button class="btn-add-item" onclick="openBioModal()">
                        <i class="bi bi-pencil-square"></i> Editar
                    </button>
                </div>
                <?php if ($bio): ?>
                <div class="p-3 border rounded" style="background:var(--light-alt);">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <small class="text-muted d-block">Nombre</small>
                            <strong><?= htmlspecialchars($bio['nombre']) ?></strong>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted d-block">Cargo</small>
                            <strong><?= htmlspecialchars($bio['cargo']) ?></strong>
                        </div>
                        <div class="col-12">
                            <small class="text-muted d-block">Descripción</small>
                            <span><?= htmlspecialchars($bio['descripcion'] ?? '') ?></span>
                        </div>
                        <div class="col-12">
                            <small class="text-muted d-block">Descripción Extra</small>
                            <span><?= htmlspecialchars($bio['descripcion_extra'] ?? '') ?></span>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted d-block">Email</small>
                            <span><?= htmlspecialchars($bio['email'] ?? '') ?></span>
                        </div>
                    </div>
                </div>
                <?php else: ?>
                <div class="p-3 border rounded text-muted" style="background:var(--light-alt);">
                    Aquí podrás editar tu información personal, foto de perfil y descripción profesional.
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- HABILIDADES -->
        <div class="tab-pane fade" id="tab-hab">
            <div class="admin-content-panel">
                <div class="panel-header">
                    <h5>Gestionar Habilidades</h5>
                    <button class="btn-add-item" onclick="openHabModal()">
                        <i class="bi bi-plus-lg"></i> Agregar Habilidad
                    </button>
                </div>
                <div id="habList">
                    <?php foreach ($habs as $h): ?>
                    <div class="crud-list-item" id="hab-<?= $h['id'] ?>">
                        <div>
                            <i class="<?= htmlspecialchars($h['icono']) ?> me-2" style="color:#6c757d;"></i>
                            <span class="item-name"><?= htmlspecialchars($h['nombre']) ?></span>
                        </div>
                        <div class="item-actions">
                            <button class="btn-edit" onclick="editHab(<?= $h['id'] ?>,<?= htmlspecialchars(json_encode($h), ENT_QUOTES) ?>)" title="Editar">
                                <i class="bi bi-pencil-square"></i>
                            </button>
                            <button class="btn-delete" onclick="deleteItem('habilidades',<?= $h['id'] ?>,'hab-<?= $h['id'] ?>')" title="Eliminar">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <!-- TECNOLOGÍAS -->
        <div class="tab-pane fade" id="tab-tec">
            <div class="admin-content-panel">
                <div class="panel-header">
                    <h5>Gestionar Tecnologías</h5>
                    <button class="btn-add-item" onclick="openTecModal()">
                        <i class="bi bi-plus-lg"></i> Agregar Tecnología
                    </button>
                </div>
                <div id="tecList">
                    <?php foreach ($techs as $t): ?>
                    <div class="crud-list-item" id="tec-<?= $t['id'] ?>">
                        <div class="d-flex align-items-center gap-3 flex-grow-1 me-3">
                            <span class="item-name"><?= htmlspecialchars($t['nombre']) ?></span>
                            <span class="badge bg-secondary"><?= htmlspecialchars($t['nivel']) ?></span>
                            <div style="flex:1;max-width:200px;">
                                <div style="background:#e1e4e8;border-radius:3px;height:6px;">
                                    <div style="background:#1a1f2e;height:6px;border-radius:3px;width:<?= $t['porcentaje'] ?>%"></div>
                                </div>
                            </div>
                            <small class="text-muted"><?= $t['porcentaje'] ?>%</small>
                        </div>
                        <div class="item-actions">
                            <button class="btn-edit" onclick="editTec(<?= $t['id'] ?>,<?= htmlspecialchars(json_encode($t), ENT_QUOTES) ?>)" title="Editar">
                                <i class="bi bi-pencil-square"></i>
                            </button>
                            <button class="btn-delete" onclick="deleteItem('tecnologias',<?= $t['id'] ?>,'tec-<?= $t['id'] ?>')" title="Eliminar">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <!-- PROYECTOS -->
        <div class="tab-pane fade" id="tab-pro">
            <div class="admin-content-panel">
                <div class="panel-header">
                    <h5>Gestionar Proyectos</h5>
                    <button class="btn-add-item" onclick="openProModal()">
                        <i class="bi bi-plus-lg"></i> Agregar Proyecto
                    </button>
                </div>
                <div id="proList">
                    <?php foreach ($projs as $p): ?>
                    <div class="crud-list-item" id="pro-<?= $p['id'] ?>">
                        <div>
                            <i class="bi bi-folder2 me-2" style="color:#6c757d;"></i>
                            <span class="item-name"><?= htmlspecialchars($p['titulo']) ?></span>
                            <small class="text-muted ms-2"><?= htmlspecialchars($p['tecnologias_usadas'] ?? '') ?></small>
                        </div>
                        <div class="item-actions">
                            <button class="btn-edit" onclick="editPro(<?= $p['id'] ?>,<?= htmlspecialchars(json_encode($p), ENT_QUOTES) ?>)" title="Editar">
                                <i class="bi bi-pencil-square"></i>
                            </button>
                            <button class="btn-delete" onclick="deleteItem('proyectos',<?= $p['id'] ?>,'pro-<?= $p['id'] ?>')" title="Eliminar">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

    </div><!-- /tab-content -->

    <div class="text-center mt-3">
        <a href="../index.php" style="font-size:0.82rem;color:#6c757d;text-decoration:none;">
            <i class="bi bi-eye me-1"></i>Ver portafolio público
        </a>
    </div>
</div>

<!-- ==============================================================
     MODALS
     ============================================================== -->

<!-- Modal Biografía -->
<div class="modal fade" id="bioModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-person me-2"></i>Editar Biografía</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="bioAlert"></div>
                <form id="bioForm">
                    <input type="hidden" name="id" id="bioId"/>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Nombre completo</label>
                            <input type="text" name="nombre" id="bioNombre" class="form-control" required/>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Cargo / Título</label>
                            <input type="text" name="cargo" id="bioCargo" class="form-control"/>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Descripción</label>
                            <textarea name="descripcion" id="bioDesc" class="form-control" rows="3"></textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Descripción Extra</label>
                            <textarea name="descripcion_extra" id="bioDescExtra" class="form-control" rows="3"></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email de contacto</label>
                            <input type="email" name="email" id="bioEmail" class="form-control"/>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">URL Foto (opcional)</label>
                            <input type="text" name="foto" id="bioFoto" class="form-control" placeholder="URL de imagen"/>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button class="btn btn-dark" onclick="saveBio()"><i class="bi bi-save me-1"></i>Guardar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Habilidad -->
<div class="modal fade" id="habModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="habModalTitle"><i class="bi bi-tools me-2"></i>Habilidad</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="habAlert"></div>
                <form id="habForm">
                    <input type="hidden" name="id" id="habId"/>
                    <div class="mb-3">
                        <label class="form-label">Nombre</label>
                        <input type="text" name="nombre" id="habNombre" class="form-control" required/>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Ícono Bootstrap (ej: bi bi-filetype-html)</label>
                        <input type="text" name="icono" id="habIcono" class="form-control" placeholder="bi bi-code-slash"/>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Descripción</label>
                        <input type="text" name="descripcion" id="habDesc" class="form-control"/>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Orden</label>
                        <input type="number" name="orden" id="habOrden" class="form-control" min="0" value="0"/>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button class="btn btn-dark" onclick="saveHab()"><i class="bi bi-save me-1"></i>Guardar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tecnología -->
<div class="modal fade" id="tecModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tecModalTitle"><i class="bi bi-code-slash me-2"></i>Tecnología</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="tecAlert"></div>
                <form id="tecForm">
                    <input type="hidden" name="id" id="tecId"/>
                    <div class="mb-3">
                        <label class="form-label">Nombre</label>
                        <input type="text" name="nombre" id="tecNombre" class="form-control" required/>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Porcentaje (0-100)</label>
                        <input type="range" name="porcentaje" id="tecPorcentaje" class="form-range" min="0" max="100" value="50"
                               oninput="document.getElementById('tecPctLabel').textContent=this.value+'%'"/>
                        <div class="text-end"><small id="tecPctLabel">50%</small></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nivel</label>
                        <select name="nivel" id="tecNivel" class="form-select">
                            <option value="Básico">Básico</option>
                            <option value="Intermedio">Intermedio</option>
                            <option value="Avanzado">Avanzado</option>
                            <option value="Experto">Experto</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Orden</label>
                        <input type="number" name="orden" id="tecOrden" class="form-control" min="0" value="0"/>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button class="btn btn-dark" onclick="saveTec()"><i class="bi bi-save me-1"></i>Guardar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Proyecto -->
<div class="modal fade" id="proModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="proModalTitle"><i class="bi bi-folder2 me-2"></i>Proyecto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="proAlert"></div>
                <form id="proForm">
                    <input type="hidden" name="id" id="proId"/>
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Título</label>
                            <input type="text" name="titulo" id="proTitulo" class="form-control" required/>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Descripción</label>
                            <textarea name="descripcion" id="proDesc" class="form-control" rows="3"></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">URL Demo</label>
                            <input type="text" name="url_demo" id="proDemo" class="form-control" placeholder="https://..."/>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">URL GitHub</label>
                            <input type="text" name="url_github" id="proGithub" class="form-control" placeholder="https://github.com/..."/>
                        </div>
                        <div class="col-md-8">
                            <label class="form-label">Tecnologías (separadas por coma)</label>
                            <input type="text" name="tecnologias_usadas" id="proTechs" class="form-control" placeholder="PHP,MySQL,Bootstrap"/>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Orden</label>
                            <input type="number" name="orden" id="proOrden" class="form-control" min="0" value="0"/>
                        </div>
                        <div class="col-12">
                            <label class="form-label">URL Imagen (opcional)</label>
                            <input type="text" name="imagen" id="proImagen" class="form-control" placeholder="URL de imagen del proyecto"/>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button class="btn btn-dark" onclick="savePro()"><i class="bi bi-save me-1"></i>Guardar</button>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
<!-- Admin JS -->
<script src="../assets/js/admin.js?v=3"></script>
</body>
</html>
