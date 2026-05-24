# Registro de Cambios — Portafolio Web Profesional Autoadministrable

> **Proyecto:** Evaluación N°3 — Diseño y Desarrollo Web + IA  
> **TECLAB UCT** — Turno Diurno S-2 Informática  
> **Herramienta IA utilizada:** Claude Code (Anthropic)

---

## ¿Por qué utilicé Claude Code?

Claude Code es un asistente de IA especializado en programación que opera directamente desde la línea de comandos. Lo utilicé como herramienta de apoyo en este proyecto por las siguientes razones:

1. **Velocidad de desarrollo:** Generó la estructura base del proyecto (15+ archivos) en tiempo reducido, permitiendo concentrarme en la personalización y comprensión del código.
2. **Resolución de errores complejos:** Ayudó a diagnosticar problemas específicos del servidor TECLAB que no están documentados públicamente (bloqueo de métodos HTTP, configuración de sesiones PHP, credenciales MySQL).
3. **Conocimiento técnico especializado:** Explicó el porqué de cada decisión técnica (por ejemplo, por qué usar PDO en lugar de `mysqli`, o por qué bcrypt para contraseñas).
4. **Iteración rápida:** Permitió ajustar el diseño visual iterativamente a partir de capturas del wireframe Figma sin tener que reescribir todo el CSS manualmente.
5. **Aprendizaje activo:** Al pedir explicaciones de cada parte del código, el proceso de desarrollo se convirtió también en un proceso de aprendizaje.

> **Reflexión crítica:** La IA no reemplaza el criterio técnico del desarrollador. Fue necesario revisar cada solución propuesta, entender su lógica, y adaptarla al contexto específico del servidor TECLAB. La IA cometió errores (ej: CSS con pseudo-elementos que no funcionaban, rutas de archivos incorrectas) que requirieron intervención manual.

---

## Historial de Cambios

### Versión inicial — Estructura completa del proyecto

**Archivos creados:**
- `index.php` — Página pública principal con todas las secciones
- `login.php` — Sistema de autenticación
- `logout.php` — Cierre de sesión
- `install.php` — Instalador inicial (eliminado tras uso)
- `bd.sql` — Script completo de base de datos
- `config/database.php` — Conexión PDO con patrón singleton
- `admin/index.php` — Dashboard administrativo con tabs Bootstrap
- `api/biografia.php` — CRUD de biografía
- `api/habilidades.php` — CRUD de habilidades
- `api/tecnologias.php` — CRUD de tecnologías
- `api/proyectos.php` — CRUD de proyectos
- `api/contacto.php` — Endpoint de formulario de contacto
- `assets/css/style.css` — Estilos personalizados completos
- `assets/js/main.js` — JavaScript del portafolio público
- `assets/js/admin.js` — JavaScript del panel administrativo

**Decisiones de diseño iniciales:**
- Tema oscuro con variables CSS en `:root`
- Bootstrap 5.3.8 como framework base
- Swiper.js 11 para el carrusel de habilidades
- AJAX + JSON para todas las operaciones del admin

---

### Cambio 1 — Fondo de mármol (abstract-marble.jpg)

**Problema:** El fondo original era un patrón SVG topográfico generado con CSS. No coincidía con el diseño Figma del wireframe original.

**Solución aplicada:** Se cambió a una imagen JPG real (`abstract-marble.jpg`) cargada por el usuario. Se implementó un sistema de doble capa en CSS usando `background-image` con dos valores:

```css
.topo-bg {
    background-image:
        linear-gradient(rgba(13,17,23,0.72), rgba(13,17,23,0.72)),
        url('../img/abstract-marble.jpg');
}
```

**Por qué este enfoque:** El intento original usaba `::before` con `position: absolute` para crear la capa oscura encima, pero generaba problemas de z-index en distintos navegadores. La doble capa en `background-image` es más confiable y no requiere pseudo-elementos.

---

### Cambio 2 — Logo Onimask en la navbar

**Problema:** La navbar mostraba el nombre en texto. El diseño Figma requería el logo blanco de Onimask centrado.

**Solución aplicada:**
- Se reemplazó el texto por `<img src="assets/img/onimask-white.png"/>` en la posición central de la navbar
- Se agregó `onerror` como fallback por si la imagen no carga en el servidor

```php
<a class="navbar-brand mx-3" href="#biografia">
    <img src="assets/img/onimask-white.png" alt="<?= $nombre ?>"
         onerror="this.src='https://ui-avatars.com/api/?...'"/>
</a>
```

---

### Cambio 3 — Tarjetas de proyectos con overlay

**Problema:** Las tarjetas de proyectos eran simples cards sin interactividad visual.

**Solución aplicada:** Se rediseñaron con un área de imagen oscura que, al pasar el mouse, muestra botones "Demo" y "Código" superpuestos con efecto de opacidad:

```css
.project-img-overlay {
    position: absolute;
    inset: 0;
    opacity: 0;
    transition: opacity 0.3s ease;
}
.project-card:hover .project-img-overlay { opacity: 1; }
```

**Adicionalmente:** Se detectó que el CSS del overlay nunca existió en el archivo — se creó desde cero en este cambio.

---

### Cambio 4 — Diagnóstico y corrección de errores MySQL

**Problema:** Al intentar iniciar sesión con `admin/admin`, el sistema mostraba "Error de conexión a la base de datos".

**Investigación:** Se creó el archivo `db-test.php` con diagnóstico en dos pasos:
1. Conectar **sin seleccionar base de datos** (verifica solo usuario/contraseña)
2. Listar las bases de datos disponibles para el usuario

**Causa encontrada:** Las contraseñas en la tabla `usuarios` estaban guardadas como **texto plano** (`admin`, `CgX91mQp#`) en lugar de hashes bcrypt. La función `password_verify()` en PHP siempre falla con texto plano.

**Solución:** Se generaron hashes bcrypt correctos y se proporcionó el SQL para ejecutar en phpMyAdmin:

```sql
UPDATE usuarios 
SET password_hash = '$2y$12$x8QFF4ZQ6m/tt2g51/os...' 
WHERE username = 'admin';
```

---

### Cambio 5 — Botones de navegación en el carrusel de Habilidades

**Problema:** El carrusel de Habilidades no tenía botones de navegación visibles. Además, con `loop: true` activado, Swiper clonaba las primeras slides y las mostraba al final del carrusel (HTML y CSS duplicados).

**Solución aplicada:**
- Eliminado `loop: true` del Swiper
- Agregado wrapper `.skill-swiper-wrap` con `position: relative`
- Botones `<` y `>` posicionados absolutamente a los costados
- `slidesPerGroup` sincronizado con `slidesPerView` por breakpoint

```javascript
new Swiper('.skillSwiper', {
    loop: false,
    navigation: { nextEl: '.skill-nav-next', prevEl: '.skill-nav-prev' },
    breakpoints: {
        1024: { slidesPerView: 4, slidesPerGroup: 4 },
    },
});
```

**Problema adicional (segunda iteración):** Los botones aparecían desalineados porque la propiedad `flex` del wrapper era sobrescrita por Swiper.js. Se cambió a `position: absolute` con `top: calc(50% - 18px)` que es más confiable.

---

### Cambio 6 — Links de proyectos no funcionales

**Problema:** Los botones "Demo" y "Repositorio" en las tarjetas de proyectos tenían `href="#"` como valor por defecto, lo que hacía que al hacer clic la página saltara al inicio.

**Solución:** Se cambió el fallback de `'#'` a `'javascript:void(0)'` y se agrega clase `link-disabled` cuando no hay URL real:

```php
$demoHref = $hasDemo ? htmlspecialchars($p['url_demo']) : 'javascript:void(0)';
```

---

### Cambio 7 — Email de contacto actualizado

**Problema:** El email mostrado en el footer y en la sección de contacto era el placeholder `contacto@ejemplo.com`.

**Solución:**
- Actualizado en `bd.sql` (datos semilla)
- El email real `c.danielaguilera29@gmail.com` ya estaba en el fallback de PHP, pero la BD tenía el valor placeholder

**SQL para actualizar en servidor:**
```sql
UPDATE biografia SET email = 'c.danielaguilera29@gmail.com' WHERE id = 1;
```

---

### Cambio 8 — Envío de correo desde el formulario de contacto

**Problema:** El formulario de contacto guardaba los mensajes en la BD (`tabla contacto`) pero no enviaba ningún correo al dueño del portafolio.

**Solución:** Se agregó `mail()` en `api/contacto.php` con cabeceras correctas:

```php
$headers  = "From: portafolio@teclab.uct.cl\r\n"
          . "Reply-To: {$correo}\r\n"
          . "Content-Type: text/plain; charset=UTF-8\r\n";
@mail('c.danielaguilera29@gmail.com', $subject, $body, $headers);
```

**Nota técnica:** Se usa `@mail()` (con `@`) para suprimir errores si el servidor no tiene sendmail configurado, sin interrumpir el guardado en BD.

---

### Cambio 9 — CRUD del admin panel no funcionaba (403 Forbidden)

**Problema:** Al intentar editar o eliminar elementos desde el panel de administración, el sistema mostraba "Error de conexión". La consola del navegador reveló **HTTP 403 Forbidden** en solicitudes PUT y DELETE.

**Causa:** El servidor Apache de TECLAB bloquea los métodos HTTP `PUT` y `DELETE` por configuración de seguridad del hosting universitario. Solo permite `GET` y `POST`.

**Solución — Method Override pattern:** Se cambió toda la comunicación a `POST` con un campo `_method` en el cuerpo JSON:

**admin.js (JavaScript):**
```javascript
async function apiCall(endpoint, method, data) {
    const res = await fetch(API + endpoint, {
        method: 'POST',  // Siempre POST
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ ...data, _method: method }), // _method: 'PUT' o 'DELETE'
    });
    return res.json();
}
```

**PHP APIs (habilidades.php, tecnologias.php, proyectos.php):**
```php
$body   = json_decode(file_get_contents('php://input'), true) ?? [];
$method = !empty($body['_method']) ? strtoupper($body['_method']) : $_SERVER['REQUEST_METHOD'];
```

**Caché del navegador:** Se agregó `?v=3` al script tag de `admin.js` para forzar que el navegador descargue la versión actualizada:
```html
<script src="../assets/js/admin.js?v=3"></script>
```

---

## Resumen de archivos modificados

| Archivo | Cambios realizados |
|---------|-------------------|
| `config/database.php` | Credenciales de BD del servidor TECLAB |
| `bd.sql` | Email corregido en datos semilla |
| `index.php` | Logo navbar, overlay proyectos, links proyectos, wrapper carrusel |
| `login.php` | Manejo específico de errores PDO |
| `admin/index.php` | Cache bust en script admin.js |
| `api/contacto.php` | Agregado envío de correo con mail() |
| `api/habilidades.php` | Method override POST/_method |
| `api/tecnologias.php` | Method override POST/_method |
| `api/proyectos.php` | Method override POST/_method |
| `assets/css/style.css` | Fondo mármol, logo navbar, overlay proyectos, botones carrusel |
| `assets/js/main.js` | Swiper sin loop, navigation config con botones personalizados |
| `assets/js/admin.js` | apiCall siempre POST con _method |
| `db-test.php` | Archivo de diagnóstico (eliminar del servidor) |
