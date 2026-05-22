-- ============================================================
-- Portafolio Web Profesional Autoadministrable
-- Base de Datos MySQL
-- ============================================================

CREATE DATABASE IF NOT EXISTS caguilera_db2
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE caguilera_db2;

-- ============================================================
-- TABLA: usuarios
-- ============================================================
CREATE TABLE IF NOT EXISTS usuarios (
    id           INT AUTO_INCREMENT PRIMARY KEY,
    username     VARCHAR(50)  NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    created_at   TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ============================================================
-- TABLA: biografia
-- ============================================================
CREATE TABLE IF NOT EXISTS biografia (
    id              INT AUTO_INCREMENT PRIMARY KEY,
    nombre          VARCHAR(100) NOT NULL DEFAULT 'Christopher Aguilera',
    cargo           VARCHAR(100) DEFAULT 'Estudiante de Tecnico en Informática',
    descripcion     TEXT,
    descripcion_extra TEXT,
    foto            VARCHAR(255) DEFAULT NULL,
    email           VARCHAR(150) DEFAULT 'c.danielaguilera29@gmail.com',
    updated_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ============================================================
-- TABLA: habilidades
-- ============================================================
CREATE TABLE IF NOT EXISTS habilidades (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    nombre      VARCHAR(100) NOT NULL,
    icono       VARCHAR(100) DEFAULT 'bi bi-code-slash',
    descripcion VARCHAR(255),
    orden       INT DEFAULT 0
) ENGINE=InnoDB;

-- ============================================================
-- TABLA: tecnologias
-- ============================================================
CREATE TABLE IF NOT EXISTS tecnologias (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    nombre      VARCHAR(100) NOT NULL,
    porcentaje  INT DEFAULT 0,
    nivel       VARCHAR(50)  DEFAULT 'Intermedio',
    orden       INT DEFAULT 0,
    CONSTRAINT chk_porcentaje CHECK (porcentaje >= 0 AND porcentaje <= 100)
) ENGINE=InnoDB;

-- ============================================================
-- TABLA: proyectos
-- ============================================================
CREATE TABLE IF NOT EXISTS proyectos (
    id                  INT AUTO_INCREMENT PRIMARY KEY,
    titulo              VARCHAR(150) NOT NULL,
    descripcion         TEXT,
    imagen              VARCHAR(255) DEFAULT NULL,
    url_demo            VARCHAR(255),
    url_github          VARCHAR(255),
    tecnologias_usadas  VARCHAR(255),
    orden               INT DEFAULT 0,
    created_at          TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ============================================================
-- TABLA: contacto
-- ============================================================
CREATE TABLE IF NOT EXISTS contacto (
    id      INT AUTO_INCREMENT PRIMARY KEY,
    nombre  VARCHAR(100) NOT NULL,
    correo  VARCHAR(150) NOT NULL,
    asunto  VARCHAR(200),
    mensaje TEXT,
    fecha   TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ============================================================
-- DATOS INICIALES
-- ============================================================

INSERT INTO biografia (nombre, cargo, descripcion, descripcion_extra, email) VALUES (
    'Christopher Aguilera',
    'Estudiante de Tecnico en Informática',
    'Estudiante de técnico en informática con interés en el desarrollo web y la resolución de problemas. Apasionado por aprender nuevas tecnologías y mejorar continuamente mis habilidades.',
    'Me enfoco en escribir código limpio y mantenible para proyectos escalables. Trabajo con tecnologías web modernas para construir aplicaciones robustas y de alto rendimiento. Comprometido con la innovación y siempre explorando nuevas tecnologías en cada proyecto.',
    'contacto@ejemplo.com'
);

INSERT INTO habilidades (nombre, icono, descripcion, orden) VALUES
('HTML',       'bi bi-filetype-html', 'Estructura semántica web',         1),
('CSS',        'bi bi-filetype-css',  'Estilos y diseño visual',           2),
('JavaScript', 'bi bi-filetype-js',   'Programación del cliente',          3),
('PHP',        'bi bi-filetype-php',  'Desarrollo backend',                4),
('MySQL',      'bi bi-database',      'Gestión de bases de datos',         5),
('Bootstrap',  'bi bi-bootstrap',     'Framework CSS responsivo',          6),
('GitHub',     'bi bi-github',        'Control de versiones',              7),
('IA Aplicada','bi bi-robot',         'Herramientas de inteligencia artificial', 8);

INSERT INTO tecnologias (nombre, porcentaje, nivel, orden) VALUES
('HTML',         95, 'Experto',      1),
('CSS/SCSS',     88, 'Experto',      2),
('JavaScript',   82, 'Avanzado',     3),
('React',        72, 'Avanzado',     4),
('PHP',          80, 'Avanzado',     5),
('MySQL',        78, 'Avanzado',     6),
('Bootstrap',    92, 'Experto',      7),
('Python',       60, 'Básico',       8),
('Node.js',      65, 'Intermedio',   9),
('TailWind CSS', 45, 'Básico',      10);

INSERT INTO proyectos (titulo, descripcion, tecnologias_usadas, url_demo, url_github, orden) VALUES
('Sistema de Gestión Empresarial',
 'Sistema web completo para gestión de inventario, ventas y reportes empresariales. Incluye módulo de usuarios, roles y permisos.',
 'PHP,MySQL,Bootstrap,JavaScript', '#', '#', 1),
('E-commerce Responsivo',
 'Tienda en línea completa con carrito de compras, pasarela de pago y panel de administración. Diseño 100% responsive.',
 'PHP,MySQL,Bootstrap,AJAX', '#', '#', 2),
('Blog Personal con CMS',
 'Sistema de blog autoadministrable con editor de texto, categorías, etiquetas y sistema de comentarios.',
 'PHP,MySQL,AJAX,TailWind', '#', '#', 3),
('Dashboard Analytics',
 'Panel de visualización de datos con gráficos interactivos, reportes en tiempo real y exportación de datos.',
 'JavaScript,Chart.js,PHP,API', '#', '#', 4);

-- NOTA: Ejecutar install.php para crear el usuario administrador
