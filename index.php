<?php
require_once __DIR__ . '/config/database.php';

try {
    $db = getDB();
    $bio   = $db->query("SELECT * FROM biografia LIMIT 1")->fetch();
    $habs  = $db->query("SELECT * FROM habilidades ORDER BY orden ASC")->fetchAll();
    $techs = $db->query("SELECT * FROM tecnologias ORDER BY orden ASC")->fetchAll();
    $projs = $db->query("SELECT * FROM proyectos ORDER BY orden ASC")->fetchAll();
} catch (Exception $e) {
    $bio   = ['nombre'=>'Christopher Aguilera','cargo'=>'Estudiante de Tecnico en Informática','descripcion'=>'Bienvenido a mi portafolio.','descripcion_extra'=>'','email'=>'c.danielaguilera29@gmail.com','foto'=>null];
    $habs  = [];
    $techs = [];
    $projs = [];
}

$nombre = htmlspecialchars($bio['nombre'] ?? 'Christopher Aguilera');
$cargo  = htmlspecialchars($bio['cargo']  ?? 'Estudiante de Tecnico en Informática');
$desc1  = htmlspecialchars($bio['descripcion'] ?? '');
$desc2  = htmlspecialchars($bio['descripcion_extra'] ?? '');
$email  = htmlspecialchars($bio['email'] ?? 'c.danielaguilera29@gmail.com');

function nivelBadge(string $nivel): string {
    $map = ['experto'=>'experto','avanzado'=>'avanzado','intermedio'=>'intermedio','básico'=>'basico','basico'=>'basico'];
    return $map[strtolower($nivel)] ?? 'intermedio';
}
function starsHTML(int $pct): string {
    $stars = round($pct / 20);
    $html = '';
    for ($i = 1; $i <= 5; $i++) {
        $html .= $i <= $stars
            ? '<i class="bi bi-star-fill"></i>'
            : '<i class="bi bi-star"></i>';
    }
    return $html;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title><?= $nombre ?> — Portafolio</title>
    <!-- Bootstrap 5.3.8 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"/>
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"/>
    <!-- Swiper -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/style.css"/>
</head>
<body>

<!-- ============================================================
     NAVBAR
     ============================================================ -->
<nav id="mainNav">
    <div class="container d-flex align-items-center justify-content-between">
        <!-- Left links -->
        <ul class="nav nav-left">
            <li class="nav-item"><a class="nav-link active" href="#biografia">Inicio</a></li>
            <li class="nav-item"><a class="nav-link" href="#habilidades">Habilidades</a></li>
        </ul>
        <!-- Center brand — logo onimask -->
        <a class="navbar-brand mx-3" href="#biografia">
            <img src="assets/img/onimask-white.png" alt="<?= $nombre ?>" onerror="this.src='https://ui-avatars.com/api/?name=<?= urlencode($nombre) ?>&background=161b22&color=e6edf3&size=48&rounded=true&bold=true'"/>
        </a>
        <!-- Right links -->
        <ul class="nav nav-right">
            <li class="nav-item"><a class="nav-link" href="#contacto">Contactos</a></li>
            <li class="nav-item ms-2">
                <a class="btn-login" href="login.php">Ingresar</a>
            </li>
        </ul>
        <!-- Mobile toggle -->
        <button class="navbar-toggler d-md-none" type="button" style="background:transparent;border:1px solid #21262d;color:#e6edf3;padding:.3rem .7rem;border-radius:6px;" data-bs-toggle="collapse" data-bs-target="#mobileMenu">
            <i class="bi bi-list"></i>
        </button>
    </div>
    <!-- Mobile menu -->
    <div class="collapse" id="mobileMenu">
        <div class="container py-2">
            <ul class="nav flex-column gap-1">
                <li><a class="nav-link" href="#biografia">Inicio</a></li>
                <li><a class="nav-link" href="#habilidades">Habilidades</a></li>
                <li><a class="nav-link" href="#tecnologias">Tecnologías</a></li>
                <li><a class="nav-link" href="#proyectos">Proyectos</a></li>
                <li><a class="nav-link" href="#contacto">Contacto</a></li>
                <li><a class="nav-link" href="login.php">Ingresar</a></li>
            </ul>
        </div>
    </div>
</nav>

<!-- ============================================================
     HERO / BIOGRAPHY
     ============================================================ -->
<section id="biografia" class="topo-bg">
    <div class="container">
        <div class="row align-items-center g-5">
            <!-- Avatar -->
            <div class="col-md-5 avatar-container fade-in-up">
                <div class="avatar-circle">
                    <?php if (!empty($bio['foto'])): ?>
                        <img src="<?= htmlspecialchars($bio['foto']) ?>" alt="<?= $nombre ?>"/>
                    <?php else: ?>
                        <i class="bi bi-person avatar-icon"></i>
                    <?php endif; ?>
                </div>
            </div>
            <!-- Text -->
            <div class="col-md-7 hero-content fade-in-up">
                <div class="hero-name-wrapper">
                    <span class="hero-divider"></span>
                    <h1 class="hero-name"><?= $nombre ?></h1>
                </div>
                <p class="hero-title"><?= $cargo ?></p>
                <?php if ($desc1): ?>
                    <p class="hero-desc"><?= $desc1 ?></p>
                <?php endif; ?>
                <?php if ($desc2): ?>
                    <p class="hero-desc"><?= $desc2 ?></p>
                <?php endif; ?>
                <div class="d-flex gap-3 flex-wrap mt-4">
                    <a href="#proyectos" class="btn-hero-primary">Ver Proyectos</a>
                    <a href="#contacto"  class="btn-hero-outline">Colaborar / Contactar</a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ============================================================
     HABILIDADES Y HERRAMIENTAS
     ============================================================ -->
<section id="habilidades">
    <div class="container">
        <div class="text-center fade-in-up">
            <i class="bi bi-globe section-icon light"></i>
            <h2 class="section-title light">Habilidades y Herramientas</h2>
            <div class="section-divider light mx-auto"></div>
            <p class="section-subtitle light mt-2">Tecnologías que domino y aplico en mis proyectos</p>
        </div>

        <?php if ($habs): ?>
        <div class="skill-swiper-wrap fade-in-up">
            <div class="swiper skillSwiper">
                <div class="swiper-wrapper">
                    <?php foreach ($habs as $h): ?>
                    <div class="swiper-slide">
                        <div class="skill-card">
                            <i class="<?= htmlspecialchars($h['icono']) ?> skill-icon"></i>
                            <h6><?= htmlspecialchars($h['nombre']) ?></h6>
                            <p><?= htmlspecialchars($h['descripcion'] ?? '') ?></p>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <div class="swiper-pagination mt-3"></div>
            </div>
            <button class="skill-nav-btn skill-nav-prev" aria-label="Anterior"><i class="bi bi-chevron-left"></i></button>
            <button class="skill-nav-btn skill-nav-next" aria-label="Siguiente"><i class="bi bi-chevron-right"></i></button>
        </div>
        <?php else: ?>
        <div class="row g-3">
            <?php
            $defaults = [
                ['HTML','bi bi-filetype-html','Estructura semántica'],
                ['CSS','bi bi-filetype-css','Estilos y diseño'],
                ['JavaScript','bi bi-filetype-js','Programación cliente'],
                ['PHP','bi bi-filetype-php','Desarrollo backend'],
                ['MySQL','bi bi-database','Bases de datos'],
                ['Bootstrap','bi bi-bootstrap','Framework CSS'],
                ['GitHub','bi bi-github','Control versiones'],
                ['IA Aplicada','bi bi-robot','Inteligencia artificial'],
            ];
            foreach ($defaults as [$n,$ic,$d]):
            ?>
            <div class="col-lg-3 col-md-6 col-6">
                <div class="skill-card">
                    <i class="<?= $ic ?> skill-icon"></i>
                    <h6><?= $n ?></h6>
                    <p><?= $d ?></p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</section>

<!-- ============================================================
     TECNOLOGÍAS DOMINADAS
     ============================================================ -->
<section id="tecnologias" class="topo-bg">
    <div class="container">
        <div class="text-center fade-in-up">
            <i class="bi bi-code-slash section-icon dark"></i>
            <h2 class="section-title dark">Tecnologías Dominadas</h2>
            <div class="section-divider dark mx-auto"></div>
            <p class="section-subtitle dark mt-2">Nivel de experticia en diferentes herramientas de desarrollo</p>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-8 fade-in-up">
                <?php foreach ($techs as $t):
                    $pct   = (int)$t['porcentaje'];
                    $nivel = $t['nivel'];
                    $badge = nivelBadge($nivel);
                ?>
                <div class="tech-item">
                    <div class="tech-header">
                        <span class="tech-name"><?= htmlspecialchars($t['nombre']) ?></span>
                        <span class="tech-badge <?= $badge ?>"><?= htmlspecialchars($nivel) ?></span>
                    </div>
                    <div class="tech-bar-wrapper">
                        <div class="tech-progress">
                            <div class="tech-fill" data-width="<?= $pct ?>"></div>
                        </div>
                        <span class="tech-percent"><?= $pct ?>%</span>
                    </div>
                    <div class="tech-stars">
                        <?= starsHTML($pct) ?>
                    </div>
                </div>
                <?php endforeach; ?>

                <div class="tech-legend">
                    <span><span class="legend-dot" style="background:#6e7681"></span> Básico (0-40%)</span>
                    <span><span class="legend-dot" style="background:#9e6a03"></span> Intermedio (50%)</span>
                    <span><span class="legend-dot" style="background:#238636"></span> Avanzado (60%)</span>
                    <span><span class="legend-dot" style="background:#1f6feb"></span> Experto (80%+)</span>
                    <span><i class="bi bi-star-fill" style="color:#ffa657"></i><i class="bi bi-star-fill" style="color:#ffa657"></i><i class="bi bi-star-fill" style="color:#ffa657"></i><i class="bi bi-star-fill" style="color:#ffa657"></i><i class="bi bi-star-fill" style="color:#ffa657"></i> Experto 100%</span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ============================================================
     PROYECTOS REALIZADOS
     ============================================================ -->
<section id="proyectos">
    <div class="container">
        <div class="text-center fade-in-up">
            <i class="bi bi-braces section-icon light"></i>
            <h2 class="section-title light">Proyectos Realizados</h2>
            <div class="section-divider light mx-auto"></div>
            <p class="section-subtitle light mt-2">Selección de proyectos que demuestran mis habilidades web y creatividad</p>
        </div>

        <div class="row g-4 fade-in-up">
            <?php foreach ($projs as $p):
                $tags     = array_filter(array_map('trim', explode(',', $p['tecnologias_usadas'] ?? '')));
                $hasDemo  = !empty($p['url_demo'])   && $p['url_demo']   !== '#';
                $hasGit   = !empty($p['url_github']) && $p['url_github'] !== '#';
                $demoHref = $hasDemo ? htmlspecialchars($p['url_demo'])   : 'javascript:void(0)';
                $gitHref  = $hasGit  ? htmlspecialchars($p['url_github']) : 'javascript:void(0)';
            ?>
            <div class="col-md-6">
                <div class="project-card">
                    <!-- Image area with overlay buttons -->
                    <div class="project-img">
                        <?php if (!empty($p['imagen'])): ?>
                            <img src="<?= htmlspecialchars($p['imagen']) ?>" alt="<?= htmlspecialchars($p['titulo']) ?>"/>
                        <?php endif; ?>
                        <div class="project-img-overlay">
                            <a href="<?= $demoHref ?>" <?= $hasDemo ? 'target="_blank"' : '' ?> class="btn-overlay-demo<?= !$hasDemo ? ' link-disabled' : '' ?>">
                                <i class="bi bi-box-arrow-up-right"></i> Demo
                            </a>
                            <a href="<?= $gitHref ?>" <?= $hasGit ? 'target="_blank"' : '' ?> class="btn-overlay-code<?= !$hasGit ? ' link-disabled' : '' ?>">
                                <i class="bi bi-github"></i> Código
                            </a>
                        </div>
                    </div>
                    <!-- Card body -->
                    <div class="project-body">
                        <h5><?= htmlspecialchars($p['titulo']) ?></h5>
                        <p><?= htmlspecialchars($p['descripcion'] ?? '') ?></p>
                        <div class="project-tags">
                            <?php foreach ($tags as $tag): ?>
                                <span class="project-tag"><?= htmlspecialchars($tag) ?></span>
                            <?php endforeach; ?>
                        </div>
                        <div class="project-links">
                            <a href="<?= $demoHref ?>" <?= $hasDemo ? 'target="_blank"' : '' ?> class="btn-project-link<?= !$hasDemo ? ' link-disabled' : '' ?>">
                                <i class="bi bi-box-arrow-up-right"></i> Ver demo
                            </a>
                            <a href="<?= $gitHref ?>" <?= $hasGit ? 'target="_blank"' : '' ?> class="btn-project-link<?= !$hasGit ? ' link-disabled' : '' ?>">
                                <i class="bi bi-github"></i> Repositorio
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ============================================================
     CONTACTO
     ============================================================ -->
<section id="contacto" class="topo-bg">
    <div class="container">
        <!-- Auth shortcuts -->
        <div class="contact-auth-btns">
            <a href="login.php" class="btn-contact-tab">Iniciar sesión</a>
        </div>

        <div class="text-center fade-in-up">
            <i class="bi bi-envelope section-icon dark"></i>
            <h2 class="section-title dark">Contacto</h2>
            <div class="section-divider dark mx-auto"></div>
            <p class="section-subtitle dark mt-2">¿Tienes un proyecto en mente? Conversemos y busquemos la mejor solución</p>
        </div>

        <div class="contact-form-wrapper fade-in-up">
            <!-- Alert container -->
            <div id="contactAlert"></div>

            <form id="contactForm" novalidate>
                <div class="form-group-icon">
                    <label class="form-label"><i class="bi bi-person"></i> Nombre a elegir</label>
                    <input type="text" class="form-control" name="nombre" placeholder="Tu apodo" required/>
                </div>
                <div class="form-group-icon">
                    <label class="form-label"><i class="bi bi-envelope"></i> Correo electrónico</label>
                    <input type="email" class="form-control" name="correo" placeholder="Tu dirección" required/>
                </div>
                <div class="form-group-icon">
                    <label class="form-label"><i class="bi bi-card-text"></i> Asunto</label>
                    <input type="text" class="form-control" name="asunto" placeholder="Asunto de contacto"/>
                </div>
                <div class="form-group-icon">
                    <label class="form-label"><i class="bi bi-chat-left-text"></i> Mensaje</label>
                    <textarea class="form-control" name="mensaje" rows="5" placeholder="Escribe tu mensaje" required></textarea>
                </div>
                <button type="submit" class="btn-send" id="btnSend">
                    Enviar mensaje <i class="bi bi-send"></i>
                </button>
            </form>

            <p class="contact-alt-text">
                También puedes contactarme directamente a:
                <a href="mailto:<?= $email ?>"><?= $email ?></a>
            </p>
        </div>
    </div>
</section>

<!-- ============================================================
     FOOTER
     ============================================================ -->
<footer id="footer">
    <div class="container">
        <div class="row g-4">
            <!-- Brand -->
            <div class="col-md-4">
                <div class="footer-brand">
                    <h5><?= $nombre ?></h5>
                    <p>Desarrollador web comprometido con crear soluciones digitales modernas, funcionales y accesibles.</p>
                </div>
            </div>
            <!-- Quick Links -->
            <div class="col-md-4">
                <p class="footer-title">Enlace rápido</p>
                <ul class="footer-links">
                    <li><a href="#biografia">Inicio</a></li>
                    <li><a href="#habilidades">Habilidades</a></li>
                    <li><a href="#tecnologias">Tecnologías</a></li>
                    <li><a href="#proyectos">Proyectos</a></li>
                    <li><a href="#contacto">Contacto</a></li>
                </ul>
            </div>
            <!-- Contact Info -->
            <div class="col-md-4">
                <p class="footer-title">Conectemos</p>
                <div class="footer-contact-item">
                    <i class="bi bi-envelope"></i>
                    <a href="mailto:<?= $email ?>" style="color:var(--text-muted);text-decoration:none;"><?= $email ?></a>
                </div>
                <div class="footer-social">
                    <a href="#" title="GitHub"><i class="bi bi-github"></i></a>
                    <a href="#" title="LinkedIn"><i class="bi bi-linkedin"></i></a>
                    <a href="#" title="Twitter/X"><i class="bi bi-twitter-x"></i></a>
                </div>
            </div>
        </div>
        <div class="footer-copy">
            &copy; <?= date('Y') ?> <?= $nombre ?> &mdash; Todos los derechos reservados.
        </div>
    </div>
</footer>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
<!-- Swiper JS -->
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<!-- Custom JS -->
<script src="assets/js/main.js"></script>
</body>
</html>
