# Portafolio Web Profesional Autoadministrable

> Proyecto desarrollado para la Evaluación N°3 — Diseño y Desarrollo Web + IA  
> TECLAB UCT — Turno Diurno S-2 Informática

**🔗 Proyecto en producción:** `https://teclab.uct.cl/~usuario/portafolio/`

---

## Descripción

Portafolio web dinámico y autoadministrable que permite presentar información profesional de manera moderna y responsive. Incluye un panel de administración protegido por autenticación para gestionar todo el contenido.

## Tecnologías Utilizadas

| Capa | Tecnología |
|------|-----------|
| Frontend | HTML5, CSS3, Bootstrap 5.3.8, JavaScript ES6+ |
| Backend | PHP 8+, PDO |
| Base de datos | MySQL / MariaDB |
| Comunicación | AJAX + JSON |
| Íconos | Bootstrap Icons 1.11 |
| Carousel | Swiper.js 11 |
| Control versiones | Git / GitHub |

## Estructura del Proyecto

```
portafolio/
├── index.php              # Página pública principal
├── login.php              # Inicio de sesión
├── logout.php             # Cierre de sesión
├── install.php            # Setup inicial (eliminar tras usar)
├── bd.sql                 # Script completo de base de datos
├── config/
│   └── database.php       # Conexión PDO a MySQL
├── admin/
│   └── index.php          # Dashboard administrativo
├── api/
│   ├── contacto.php       # POST: guardar mensaje de contacto
│   ├── biografia.php      # GET/POST: CRUD biografía
│   ├── habilidades.php    # GET/POST/PUT/DELETE: CRUD habilidades
│   ├── tecnologias.php    # GET/POST/PUT/DELETE: CRUD tecnologías
│   └── proyectos.php      # GET/POST/PUT/DELETE: CRUD proyectos
└── assets/
    ├── css/style.css      # Estilos personalizados
    ├── js/
    │   ├── main.js        # JS del portafolio público
    │   └── admin.js       # JS del panel administrativo
    └── img/
        └── topo-pattern.svg  # Patrón topográfico de fondo
```

## Instalación

### 1. Importar base de datos

```bash
mysql -u root -p < bd.sql
```

O desde phpMyAdmin: importar el archivo `bd.sql`.

### 2. Configurar conexión

Editar `config/database.php` con tus credenciales:

```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'portafolio_db');
define('DB_USER', 'tu_usuario');
define('DB_PASS', 'tu_contraseña');
```

### 3. Crear usuario administrador

Acceder a `install.php` desde el navegador y crear el usuario admin. **Eliminar este archivo después de usarlo.**

### 4. Acceder

- **Portafolio público:** `index.php`
- **Panel admin:** `admin/index.php` (requiere login en `login.php`)
- **Credenciales por defecto:** las definidas en install.php

## Funcionalidades

### Portafolio Público
- ✅ Navbar responsive con navegación por anclas
- ✅ Sección Biografía con avatar y descripción profesional
- ✅ Sección Habilidades con carrusel Swiper (8 tarjetas)
- ✅ Sección Tecnologías con barras de progreso animadas y estrellas
- ✅ Sección Proyectos en grilla 2×2 con tags y links
- ✅ Formulario de Contacto con envío AJAX
- ✅ Footer con links rápidos e íconos sociales
- ✅ Animaciones fade-in al hacer scroll

### Panel Administrativo
- ✅ Login con autenticación segura (bcrypt)
- ✅ Dashboard con estadísticas de contenido
- ✅ CRUD Biografía (editar nombre, cargo, descripción, email, foto)
- ✅ CRUD Habilidades (agregar, editar, eliminar con íconos)
- ✅ CRUD Tecnologías (porcentaje con slider, nivel, orden)
- ✅ CRUD Proyectos (título, descripción, imagen, URLs, tags)
- ✅ Operaciones via AJAX sin recarga completa

## Uso de Inteligencia Artificial

Este proyecto fue desarrollado con apoyo de **Claude (Anthropic)** como herramienta de IA.

### Herramientas IA Utilizadas
- **Claude (Anthropic)** — Generación de código, estructura y lógica del sistema

### Prompts Utilizados (ejemplos)
1. *"Planifica la implementación de un portafolio web profesional autoadministrable con PHP, MySQL, Bootstrap 5.3.8 y AJAX siguiendo este diseño Figma..."*
2. *"Implementa el sistema completo con dashboard administrativo con tabs para CRUD de biografía, habilidades, tecnologías y proyectos"*
3. *"Diseña el CSS para replicar el tema oscuro con patrón topográfico del wireframe, usando variables CSS y animaciones de scroll"*

### Resultados Generados
- Estructura completa del proyecto (15+ archivos)
- Sistema de autenticación con bcrypt
- APIs REST en PHP con PDO y prepared statements
- CSS con variables, animaciones y patrón SVG topográfico
- JavaScript con AJAX, Swiper.js y IntersectionObserver

### Ajustes Realizados
- Adaptación de nombres y datos al contexto del estudiante
- Ajuste del diseño visual para mayor fidelidad al wireframe
- Configuración de credenciales de base de datos del servidor TECLAB

### Reflexión Crítica
La IA aceleró significativamente el desarrollo estructural y repetitivo. Sin embargo, fue necesario revisar la lógica de autenticación, ajustar el diseño CSS para mayor fidelidad al wireframe, y adaptar la configuración al servidor específico. La IA es una herramienta de apoyo que requiere criterio técnico del desarrollador para producir código de calidad.

## Seguridad
- Contraseñas hasheadas con `password_hash()` (bcrypt)
- Prepared statements en todas las consultas SQL (previene SQL injection)
- Validación de sesión en todas las rutas administrativas
- `htmlspecialchars()` en todas las salidas HTML (previene XSS)
- Validación de email con `filter_var()`

## Commits de Desarrollo

Ver historial completo en: [github.com/zikness/portafolio](https://github.com/zikness/portafolio)
