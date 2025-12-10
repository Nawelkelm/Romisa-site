# 📚 DOCUMENTACIÓN TÉCNICA - SISTEMAS ROMISA

## Índice
1. [Sistema de Administración de Usuarios](#1-sistema-de-administración-de-usuarios)
2. [Sistema de Administración de Noticias](#2-sistema-de-administración-de-noticias)
3. [Gestor de PDF](#3-gestor-de-pdf)
4. [Catálogo Público](#4-catálogo-público)
5. [Arquitectura de Seguridad](#5-arquitectura-de-seguridad)
6. [Base de Datos](#6-base-de-datos)
7. [Estructura de Archivos](#7-estructura-de-archivos)

---

## 1. Sistema de Administración de Usuarios

### 1.1 Descripción General
Sistema CRUD completo para gestionar usuarios que pueden acceder a los paneles de administración (noticias, PDFs).

### 1.2 Archivos del Sistema

| Archivo | Función |
|---------|---------|
| `usuarios.html` | Interfaz de gestión de usuarios (panel admin) |
| `login.php` | Procesamiento de login con seguridad avanzada |
| `logout.php` | Cierre de sesión seguro |
| `add_user.php` | Agregar nuevos usuarios |
| `get_users.php` | Listar todos los usuarios |
| `get_user.php` | Obtener datos de un usuario específico |
| `update_user.php` | Actualizar datos de usuario |
| `delete_user.php` | Eliminar usuario |
| `auth_middleware.php` | Middleware de autenticación y autorización |

### 1.3 Funcionalidades

#### Login (`login.php`)
- **Rate Limiting**: Máximo 5 intentos fallidos, bloqueo de 15 minutos por IP
- **Protección CSRF**: Token de seguridad opcional
- **Sesiones seguras**: Cookies HttpOnly, SameSite=Strict
- **Timeout de sesión**: 30 minutos de inactividad
- **Logging**: Registro de intentos exitosos y fallidos
- **Encriptación**: Contraseñas hasheadas con `password_hash()` (bcrypt)

#### Roles de Usuario
| Rol | Permisos |
|-----|----------|
| **Admin** (`is_admin = 1`) | CRUD usuarios, eliminar noticias permanentemente, gestión total |
| **Editor** (`is_admin = 0`) | Crear/editar noticias, subir PDFs, ver catálogos |

### 1.4 API Endpoints

```
POST /login.php
├── action: "login"     → Iniciar sesión
├── action: "logout"    → Cerrar sesión
└── action: "check"     → Verificar estado de sesión

POST /add_user.php      → Crear usuario (requiere admin)
GET  /get_users.php     → Listar usuarios (requiere login)
GET  /get_user.php?id=X → Obtener usuario específico
POST /update_user.php   → Actualizar usuario (requiere admin)
POST /delete_user.php   → Eliminar usuario (requiere admin)
```

### 1.5 Ejemplo de Petición Login

```javascript
// Petición de login
fetch('login.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({
        action: 'login',
        username: 'usuario',
        password: 'contraseña',
        csrf_token: 'token_opcional'
    })
});

// Respuesta exitosa
{
    "success": true,
    "message": "Inicio de sesión exitoso",
    "user": {
        "username": "admin",
        "nombre": "Administrador",
        "is_admin": true
    }
}
```

---

## 2. Sistema de Administración de Noticias

### 2.1 Descripción General
Sistema completo para crear, editar, publicar y eliminar noticias del sitio web. Incluye editor WYSIWYG, galería de imágenes y sistema de borradores.

### 2.2 Archivos del Sistema

| Archivo | Función |
|---------|---------|
| `admin-noticias.html` | Panel de administración de noticias |
| `api_noticias.php` | API REST para todas las operaciones |
| `upload_imagen_noticia.php` | Subida de imágenes para noticias |
| `novedades.html` | Página pública de listado de noticias |
| `noticia.html` | Página de detalle de noticia individual |

### 2.3 Funcionalidades

- **Editor TinyMCE**: Editor visual para contenido enriquecido
- **Galería de imágenes**: Hasta 4 imágenes por noticia (principal + 3 galería)
- **Sistema de estados**: Activo/Inactivo (borrador)
- **Contador de vistas**: Tracking automático de visualizaciones
- **Fechas de publicación**: Control de cuándo se publica

### 2.4 API Endpoints

```
GET /api_noticias.php?action=listar
    ├── limite=5           → Limitar cantidad
    └── activas=true       → Solo noticias publicadas

GET /api_noticias.php?action=obtener&id=X
    → Obtiene noticia completa (incrementa vistas)

POST /api_noticias.php
    ├── action: "crear"      → Nueva noticia (requiere editor)
    ├── action: "actualizar" → Editar noticia (requiere editor)
    ├── action: "eliminar"   → Eliminar permanente (requiere admin)
    └── action: "toggle"     → Activar/desactivar (requiere editor)
```

### 2.5 Estructura de Noticia

```json
{
    "id": 1,
    "titulo": "Título de la noticia",
    "resumen": "Resumen corto para listados",
    "contenido": "<p>Contenido HTML completo</p>",
    "imagen": "assets/img/noticias/imagen-principal.jpg",
    "imagen_galeria_1": "assets/img/noticias/galeria1.jpg",
    "imagen_galeria_2": "assets/img/noticias/galeria2.jpg",
    "imagen_galeria_3": "assets/img/noticias/galeria3.jpg",
    "fecha_publicacion": "2025-12-09",
    "autor": "Admin",
    "activo": 1,
    "vistas": 150
}
```

### 2.6 Permisos por Acción

| Acción | Permiso Requerido |
|--------|-------------------|
| Listar (públicas) | Ninguno |
| Listar (todas) | Editor |
| Crear | Editor |
| Editar | Editor |
| Activar/Desactivar | Editor |
| Eliminar permanente | Admin |

---

## 3. Gestor de PDF

### 3.1 Descripción General
Sistema para administrar catálogos y fichas técnicas en formato PDF. Permite subir, visualizar y eliminar archivos organizados en carpetas.

### 3.2 Archivos del Sistema

| Archivo | Función |
|---------|---------|
| `gestor.html` | Panel de administración de PDFs |
| `upload_pdf.php` | Subida de archivos PDF |
| `get_pdfs.php` | Listar PDFs existentes |
| `get_pdf_files.php` | Obtener archivos de carpeta específica |
| `delete_pdf.php` | Eliminar PDF |

### 3.3 Estructura de Carpetas

```
assets/files/
├── catalogos/        → Catálogos de productos
│   ├── ATLAS.pdf
│   ├── BOVENAU.pdf
│   └── ...
└── info-tecnica/     → Fichas técnicas
    ├── Acoples.pdf
    ├── PENETRIT.pdf
    └── ...
```

### 3.4 API Endpoints

```
GET  /get_pdfs.php          → Lista todos los PDFs (ambas carpetas)
POST /upload_pdf.php        → Subir nuevo PDF
     ├── pdfFile: [archivo]
     └── folder: "catalogos" | "info-tecnica"
POST /delete_pdf.php        → Eliminar PDF
     └── filePath: "assets/files/catalogos/archivo.pdf"
```

### 3.5 Validaciones de Seguridad

- **Autenticación**: Requiere sesión activa
- **Tipo de archivo**: Solo permite `.pdf`
- **Rutas permitidas**: Solo `assets/files/catalogos/` y `assets/files/info-tecnica/`
- **Verificación de ruta real**: Usa `realpath()` para prevenir path traversal (retorna ruta absoluta y verifica que esté dentro de las rutas permitidas)

### 3.6 Ejemplo de Subida

```javascript
const formData = new FormData();
formData.append('pdfFile', archivoSeleccionado);
formData.append('folder', 'catalogos');

fetch('upload_pdf.php', {
    method: 'POST',
    body: formData
});

// Respuesta exitosa
{
    "success": true,
    "message": "Archivo subido exitosamente",
    "file": "nuevo-catalogo.pdf"
}
```

---

## 4. Catálogo Público

### 4.1 Descripción General
Página pública donde los clientes pueden visualizar y descargar catálogos y fichas técnicas sin necesidad de login.

### 4.2 Archivo Principal
- `Catalogos.html` - Interfaz de visualización pública

### 4.3 Funcionalidades

- **Visualización dual**: Catálogos e Info Técnica en secciones separadas
- **Selector de vista**: Ver ambas secciones o una a la vez
- **Visor PDF integrado**: Visualización en modal sin salir de la página
- **Descarga directa**: Botón para descargar cada PDF
- **Miniaturas automáticas**: Generadas con PDF.js
- **Responsive**: Adaptado para móviles

### 4.4 Tecnologías Utilizadas

- **PDF.js v2.16.105**: Renderizado de PDFs en navegador
- **Bootstrap 5**: Grid y componentes UI
- **Visor modal**: Iframe para visualización completa

### 4.5 Carga de PDFs Públicos

```javascript
// La página carga los PDFs desde el servidor
fetch('get_pdf_files.php?folder=catalogos')
    .then(response => response.json())
    .then(data => {
        // Renderizar miniaturas
    });
```

---

## 5. Arquitectura de Seguridad

### 5.1 Middleware de Autenticación (`auth_middleware.php`)

#### Funciones Principales

| Función | Descripción |
|---------|-------------|
| `iniciarSesionSegura()` | Inicia sesión con cookies seguras |
| `estaAutenticado()` | Verifica si hay sesión válida |
| `esAdmin()` | Verifica rol de administrador |
| `esEditor()` | Verifica permisos de edición |
| `cerrarSesion()` | Destruye la sesión de forma segura |
| `requerirAutenticacion()` | Middleware para proteger endpoints |

#### Configuración de Seguridad

```php
define('SESSION_TIMEOUT', 1800);    // 30 minutos
define('MAX_LOGIN_ATTEMPTS', 5);    // Intentos máximos
define('LOCKOUT_TIME', 900);        // 15 minutos de bloqueo
```

### 5.2 Rate Limiting

- **Tabla**: `login_attempts`
- **Campos**: `ip_address`, `username`, `success`, `attempt_time`
- **Lógica**: 5 intentos fallidos = bloqueo 15 minutos por IP

### 5.3 Protección CSRF

- **Archivo**: `csrf_protection.php`
- **Token**: Generado por sesión, validado en POST sensibles
- **Opcional**: Se activa si el archivo existe

### 5.4 Encriptación de Datos Sensibles

```php
// config.php - Datos cifrados con AES-256-CBC
define('ENCRYPTION_KEY', 'clave-secreta-de-encripcion');

function data_encrypt($data) {
    $key = ENCRYPTION_KEY;
    return openssl_encrypt($data, 'AES-256-CBC', $key, 0, 
        substr(hash('sha256', $key), 0, 16));
}
```

---

## 6. Base de Datos

### 6.1 Conexión

```php
// connect.php
$host = 'localhost';
$dbname = 'romisa';
$username = 'root';
$password = '';

$pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", 
    $username, $password);
```

### 6.2 Tablas Principales

#### Tabla `usuarios`
```sql
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    nombre VARCHAR(100),
    is_admin TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

#### Tabla `noticias`
```sql
CREATE TABLE noticias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(255) NOT NULL,
    resumen TEXT,
    contenido LONGTEXT,
    imagen VARCHAR(255),
    imagen_galeria_1 VARCHAR(255),
    imagen_galeria_2 VARCHAR(255),
    imagen_galeria_3 VARCHAR(255),
    fecha_publicacion DATE,
    autor VARCHAR(100),
    activo TINYINT(1) DEFAULT 1,
    vistas INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

#### Tabla `login_attempts`
```sql
CREATE TABLE login_attempts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ip_address VARCHAR(45) NOT NULL,
    username VARCHAR(50),
    success TINYINT(1) DEFAULT 0,
    attempt_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_ip_time (ip_address, attempt_time)
);
```

#### Tabla `activity_log`
```sql
CREATE TABLE activity_log (
    id INT AUTO_INCREMENT PRIMARY KEY,
    action VARCHAR(50) NOT NULL,
    entity_type VARCHAR(50),
    entity_id INT,
    user_id INT,
    ip_address VARCHAR(45),
    details TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

---

## 7. Estructura de Archivos

```
romisa-site/
│
├── 📁 ADMINISTRACIÓN
│   ├── usuarios.html          # Panel gestión usuarios
│   ├── admin-noticias.html    # Panel gestión noticias
│   ├── gestor.html            # Panel gestión PDFs
│   │
│   ├── 📁 API Usuarios
│   │   ├── login.php
│   │   ├── logout.php
│   │   ├── add_user.php
│   │   ├── get_users.php
│   │   ├── get_user.php
│   │   ├── update_user.php
│   │   └── delete_user.php
│   │
│   ├── 📁 API Noticias
│   │   ├── api_noticias.php
│   │   └── upload_imagen_noticia.php
│   │
│   └── 📁 API PDFs
│       ├── upload_pdf.php
│       ├── get_pdfs.php
│       ├── get_pdf_files.php
│       └── delete_pdf.php
│
├── 📁 SEGURIDAD
│   ├── auth_middleware.php    # Middleware autenticación
│   ├── csrf_protection.php    # Protección CSRF
│   ├── session_validator.php  # Validador de sesión
│   └── config.php             # Configuración cifrada
│
├── 📁 PÚBLICO
│   ├── Catalogos.html         # Catálogos públicos
│   ├── novedades.html         # Listado noticias
│   └── noticia.html           # Detalle noticia
│
├── 📁 assets/
│   ├── files/
│   │   ├── catalogos/         # PDFs catálogos
│   │   └── info-tecnica/      # PDFs fichas técnicas
│   ├── img/
│   │   └── noticias/          # Imágenes de noticias
│   └── css/
│       ├── custom.css
│       ├── noticias.css
│       └── login.css
│
└── connect.php                # Conexión BD
```

---

## 📝 Notas de Mantenimiento

### Agregar Nuevo Usuario Admin
```sql
INSERT INTO usuarios (username, password, nombre, is_admin) 
VALUES ('nuevo_admin', '$2y$10$HASH_GENERADO', 'Nombre Admin', 1);
```

### Resetear Bloqueo de IP
```sql
DELETE FROM login_attempts WHERE ip_address = 'IP_BLOQUEADA';
```

### Backup de PDFs
```bash
# Copiar carpeta de archivos
cp -r assets/files/ /backup/files_$(date +%Y%m%d)/
```

---

## 🔧 Requisitos del Sistema

| Componente | Versión Mínima |
|------------|----------------|
| PHP | 7.4+ |
| MySQL | 5.7+ |
| Apache | 2.4+ |
| Extensiones PHP | PDO, OpenSSL, JSON |

---

## 📞 Soporte

- **Desarrollador**: Nawelkelm
- **Repositorio**: github.com/Nawelkelm/Romisa-site
- **Branch**: Actualizacion-con-gestor-de-pdf-y-login

---

*Documentación generada el 9 de Diciembre de 2025*
