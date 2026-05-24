# Portafolio Web Profesional Autoadministrable

> **Evaluación N°3 — Diseño y Desarrollo Web + IA**  
> TECLAB UCT — Turno Diurno S-2 Informática  
> Alumno: Christopher Daniel Aguilera

---

## Enlaces del Proyecto

| Recurso | URL |
|---------|-----|
| **Proyecto en producción** | `https://teclab.uct.cl/~caguilera2025/portafolio/` |
| **Wireframe Figma (referencia de diseño)** | [Ver diseño en Figma](https://www.figma.com/make/PhPtPxqfjOuLOAfKVB7YwX/Generar-archivo-solicitado?t=4tS8bglulCJJWN2u-1) |
| **Repositorio GitHub** | [github.com/zikness/portafolio](https://github.com/zikness/portafolio) |

---

## Descripción

Portafolio web dinámico y autoadministrable que permite presentar información profesional de manera moderna y responsive. Incluye un panel de administración protegido por autenticación para gestionar todo el contenido sin necesidad de editar código.

El diseño visual está basado en el wireframe Figma indicado arriba: tema oscuro con fondo de mármol abstracto, navbar centrada con logo, carrusel de habilidades con navegación, barras de progreso animadas para tecnologías, grilla 2×2 de proyectos con overlay interactivo, y formulario de contacto con envío AJAX.

---

## Tecnologías Utilizadas

| Capa | Tecnología |
|------|-----------|
| Frontend | HTML5, CSS3, Bootstrap 5.3.8, JavaScript ES6+ |
| Backend | PHP 8+, PDO |
| Base de datos | MySQL / MariaDB |
| Comunicación | AJAX + Fetch API + JSON |
| Íconos | Bootstrap Icons 1.11.3 |
| Carrusel | Swiper.js 11 |
| Control de versiones | Git / GitHub |

---

## Estructura del Proyecto

```
portafolio/
├── index.php              # Página pública principal
├── login.php              # Inicio de sesión admin
├── logout.php             # Cierre de sesión
├── bd.sql                 # Script completo de base de datos
├── CAMBIOS.md             # Registro de cambios y uso de IA
├── config/
│   └── database.php       # Conexión PDO (singleton)
├── admin/
│   └── index.php          # Dashboard administrativo con tabs CRUD
├── api/
│   ├── contacto.php       # POST: guardar mensaje + enviar email
│   ├── biografia.php      # GET/POST: CRUD biografía
│   ├── habilidades.php    # POST + _method override: CRUD habilidades
│   ├── tecnologias.php    # POST + _method override: CRUD tecnologías
│   └── proyectos.php      # POST + _method override: CRUD proyectos
└── assets/
    ├── css/style.css      # Estilos personalizados con variables CSS
    ├── js/
    │   ├── main.js        # JS del portafolio (Swiper, observers, AJAX)
    │   └── admin.js       # JS del panel CRUD via fetch()
    └── img/
        ├── abstract-marble.jpg   # Fondo textura mármol
        └── onimask-white.png     # Logo navbar
```

---

## Instalación en servidor TECLAB

### 1. Importar base de datos

Desde phpMyAdmin: seleccionar `caguilera_db2` → pestaña **Importar** → subir `bd.sql`.

### 2. Configurar conexión

`config/database.php` ya tiene las credenciales del servidor TECLAB configuradas:

```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'caguilera_db2');
define('DB_USER', 'caguilera');
define('DB_PASS', 'CgX91mQp');
```

### 3. Subir archivos via FTP

Usar CoreFTP con usuario `caguilera2025` y subir todo el contenido a la carpeta `portafolio/` dentro del directorio público.

### 4. Acceder

| Sección | URL |
|---------|-----|
| Portafolio público | `https://teclab.uct.cl/~caguilera2025/portafolio/index.php` |
| Panel administrativo | `https://teclab.uct.cl/~caguilera2025/portafolio/admin/index.php` |
| Credenciales admin | Usuario: `admin` / Contraseña: `admin` |

---

## Funcionalidades Implementadas

### Portafolio Público (`index.php`)

- ✅ Navbar responsive con navegación por anclas y logo centrado
- ✅ Sección **Biografía**: avatar, nombre, cargo, descripción, botones CTA
- ✅ Sección **Habilidades**: carrusel Swiper.js con botones prev/next y paginación
- ✅ Sección **Tecnologías**: barras de progreso animadas con IntersectionObserver + estrellas
- ✅ Sección **Proyectos**: grilla 2×2 con overlay de botones al hover (Demo / Código)
- ✅ Sección **Contacto**: formulario con envío AJAX sin recarga de página
- ✅ Footer con links rápidos, email y redes sociales
- ✅ Animaciones fade-in al hacer scroll (IntersectionObserver)
- ✅ Fondo de mármol con capa de gradiente oscuro superpuesta

### Panel Administrativo (`admin/index.php`)

- ✅ Login con autenticación segura (bcrypt via `password_verify`)
- ✅ Guard de sesión en todas las rutas protegidas
- ✅ Dashboard con contadores de contenido por sección
- ✅ **CRUD Biografía**: nombre, cargo, descripción, email, foto
- ✅ **CRUD Habilidades**: nombre, ícono, descripción, orden
- ✅ **CRUD Tecnologías**: nombre, porcentaje (slider), nivel, orden
- ✅ **CRUD Proyectos**: título, descripción, imagen, URL demo, URL GitHub, tags
- ✅ Todas las operaciones via AJAX (modales Bootstrap, sin recarga)
- ✅ Method Override (POST + `_method`) para compatibilidad con Apache de TECLAB

---

## Base de Datos

### Tablas

| Tabla | Descripción |
|-------|-------------|
| `usuarios` | Administradores con contraseña bcrypt |
| `biografia` | Información personal del portafolio (1 registro) |
| `habilidades` | Tarjetas del carrusel |
| `tecnologias` | Barras de progreso con nivel y porcentaje |
| `proyectos` | Grilla de proyectos con URLs y tags |
| `contacto` | Mensajes recibidos desde el formulario |

---

## Uso de Inteligencia Artificial

Este proyecto fue desarrollado con apoyo de **Claude Code (Anthropic)** como herramienta de IA.

### Wireframe de referencia

El diseño se basó en el siguiente wireframe Figma:  
🔗 **[https://www.figma.com/make/PhPtPxqfjOuLOAfKVB7YwX/Generar-archivo-solicitado](https://www.figma.com/make/PhPtPxqfjOuLOAfKVB7YwX/Generar-archivo-solicitado?t=4tS8bglulCJJWN2u-1)**

### Herramienta IA Utilizada

- **Claude Code (Anthropic)** — CLI de IA para programación. Generación de código, diagnóstico de errores, explicación de conceptos y resolución de problemas específicos del servidor TECLAB.

### Prompts Utilizados (ejemplos)

1. *"Planifica la implementación de un portafolio web profesional autoadministrable con PHP, MySQL, Bootstrap 5.3.8 y AJAX siguiendo este diseño Figma..."*
2. *"Implementa el sistema completo con dashboard administrativo con tabs para CRUD de biografía, habilidades, tecnologías y proyectos"*
3. *"Diseña el CSS para replicar el tema oscuro con fondo de mármol del wireframe, usando variables CSS y animaciones de scroll"*
4. *"Al intentar guardar desde el admin me da 403 Forbidden en las peticiones PUT y DELETE"*
5. *"El formulario de contacto dice que se envió pero no llega ningún correo"*

### Resultados Generados con IA

- Estructura completa del proyecto (15+ archivos)
- Sistema de autenticación con bcrypt
- APIs REST en PHP con PDO y prepared statements
- CSS con variables, animaciones y fondo de mármol
- JavaScript con AJAX, Swiper.js e IntersectionObserver
- Diagnóstico y solución de errores específicos del servidor TECLAB

### Ajustes Realizados por el Estudiante

- Subida manual de imágenes (abstract-marble.jpg, onimask-white.png) vía FTP
- Configuración de credenciales de base de datos en phpMyAdmin TECLAB
- Corrección manual de contraseñas en tabla `usuarios` (hashes bcrypt via phpMyAdmin)
- Revisión y validación de cada componente generado
- Adaptación del contenido al contexto personal del estudiante

### Reflexión Crítica

La IA aceleró significativamente el desarrollo estructural y repetitivo. Sin embargo, fue necesario:
- Diagnosticar problemas específicos del servidor universitario (bloqueo de métodos HTTP PUT/DELETE, configuración de sesiones)
- Corregir errores generados por la IA (CSS con pseudo-elementos que no funcionaban, rutas de archivos incorrectas)
- Validar y comprender cada bloque de código antes de desplegarlo
- Tomar decisiones de diseño propias al comparar el resultado con el wireframe Figma

**La IA es una herramienta de apoyo que requiere criterio técnico del desarrollador para producir código de calidad.**

---

## Seguridad Implementada

| Medida | Implementación |
|--------|---------------|
| Contraseñas | `password_hash()` bcrypt (costo 12) |
| SQL Injection | Prepared statements PDO en todas las consultas |
| XSS | `htmlspecialchars()` en todas las salidas HTML |
| Autenticación | Validación de sesión `$_SESSION['admin_logged']` en cada ruta admin |
| Session fixation | `session_regenerate_id(true)` al iniciar sesión |
| Email | `filter_var($correo, FILTER_VALIDATE_EMAIL)` antes de procesar |

---

## Registro de Cambios

Ver historial detallado de cambios en: [`CAMBIOS.md`](CAMBIOS.md)
