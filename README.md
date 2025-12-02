HelpDesk 

![Project Status](https://img.shields.io/badge/status-active-brightgreen)
![PHP Version](https://img.shields.io/badge/php-%3E%3D7.0-blue)
![License](https://img.shields.io/badge/license-MIT-green)


-- Tabla de roles
CREATE TABLE tm_rol (
  rol_id INT PRIMARY KEY AUTO_INCREMENT,
  rol_nom VARCHAR(50) NOT NULL,
  rol_descripcion TEXT
);

-- Tabla de categorías
CREATE TABLE tm_categoria (
  cat_id INT PRIMARY KEY AUTO_INCREMENT,
  cat_nom VARCHAR(100) NOT NULL,
  cat_descripcion TEXT,
  estado INT DEFAULT 1
);

-- Tabla de tickets
CREATE TABLE tm_ticket (
  tick_id INT PRIMARY KEY AUTO_INCREMENT,
  usu_id INT NOT NULL,
  cat_id INT NOT NULL,
  tick_asunto VARCHAR(255) NOT NULL,
  tick_descrip LONGTEXT NOT NULL,
  tick_prioridad VARCHAR(50) DEFAULT 'Media',
  tick_estado VARCHAR(50) DEFAULT 'Abierto',
  usu_asig INT,
  fecha_crea DATETIME DEFAULT CURRENT_TIMESTAMP,
  fecha_cierre DATETIME,
  est INT DEFAULT 1,
  FOREIGN KEY (usu_id) REFERENCES tm_usuario(usu_id),
  FOREIGN KEY (cat_id) REFERENCES tm_categoria(cat_id),
  FOREIGN KEY (usu_asig) REFERENCES tm_usuario(usu_id)
);

-- Tabla de detalles de tickets
CREATE TABLE td_ticketdetalle (
  tdet_id INT PRIMARY KEY AUTO_INCREMENT,
  tick_id INT NOT NULL,
  usu_id INT NOT NULL,
  tdet_descripcion LONGTEXT NOT NULL,
  fecha_crea DATETIME DEFAULT CURRENT_TIMESTAMP,
  est INT DEFAULT 1,
  FOREIGN KEY (tick_id) REFERENCES tm_ticket(tick_id),
  FOREIGN KEY (usu_id) REFERENCES tm_usuario(usu_id)
);

-- Tabla de archivos adjuntos
CREATE TABLE tm_archivo_ticket (
  arch_id INT PRIMARY KEY AUTO_INCREMENT,
  tick_id INT NOT NULL,
  arch_nombre VARCHAR(255) NOT NULL,
  arch_ruta VARCHAR(500) NOT NULL,
  fecha_carga DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (tick_id) REFERENCES tm_ticket(tick_id)
);

-- Insertar roles
INSERT INTO tm_rol VALUES 
(1, 'Usuario', 'Usuario regular'),
(2, 'Soporte', 'Personal de soporte técnico'),
(3, 'SuperAdmin', 'Administrador del sistema');

-- Insertar usuarios de prueba
INSERT INTO tm_usuario (nombre, apellido, correo, contrasenia, rol_id, estado) VALUES 
('Usuario', 'Demo', 'usuario@example.com', '123456', 1, 1),
('Soporte', 'Técnico', 'soporte@example.com', '123456', 2, 1),
('Admin', 'Sistema', 'admin@example.com', '123456', 3, 1);
```

### 3. Configurar Archivos

#### Editar `config/conexion.php`:
```php
// Actualizar credenciales de base de datos
$dbh = new PDO(
    "mysql:host=localhost;dbname=heldesk",
    "root",
    "" // Contraseña de MySQL
);
```

#### Configurar `.env`:
```env
BD_HOST=localhost
BD_NOMBRE=heldesk
BD_USUARIO=root
BD_CLAVE=
FORCE_HTTPS=false
SESSION_TIMEOUT=3600
```

### 4. Permisos de Directorios

```bash
# Windows (PowerShell)
mkdir public/uploads/tickets -Force
```

```bash
# Linux/Mac
mkdir -p public/uploads/tickets
chmod 755 public/uploads/tickets
```

### 5. Acceso a la Aplicación

```
URL: http://localhost/HelpDesk/view/
```


##  Estructura del Proyecto

```
HelpDesk/
├── config/
│   ├── conexion.php          # Conexión a base de datos
│   └── check_auth.php        # Funciones de autenticación
│
├── controller/
│   ├── ticket.php            # Controlador de tickets
│   ├── usuario.php           # Controlador de usuarios
│   ├── categoria.php         # Controlador de categorías
│   └── estadistica.php       # Controlador de estadísticas
│
├── models/
│   ├── Ticket.php            # Modelo de tickets
│   ├── Usuario.php           # Modelo de usuarios
│   ├── Categoria.php         # Modelo de categorías
│   └── Estadistica.php       # Modelo de estadísticas
│
├── view/
│   ├── index.php             # Página de login
│   ├── error404.php          # Página de error 404
│   ├── home/                 # Dashboard del usuario
│   ├── NuevoTicket/          # Crear nuevo ticket
│   ├── ConsultarTicket/      # Listar tickets
│   ├── DetalleTicket/        # Detalle de ticket
│   ├── Estadisticas/         # Dashboard de estadísticas
│   ├── MntUsuario/           # Gestión de usuarios (Admin)
│   ├── CambiarContrasenia/   # Cambio de contraseña
│   ├── MainHead/             # Componente de head
│   ├── MainHeader/           # Componente de header
│   ├── MainNav/              # Componente de navegación
│   ├── MainJs/               # Scripts globales
│   └── Logout/               # Cerrar sesión
│
├── public/
│   ├── css/                  # Estilos CSS
│   │   ├── lib/              # Librerías CSS
│   │   └── *.css             # Hojas de estilo personalizadas
│   │
│   ├── js/                   # Scripts JavaScript
│   │   ├── lib/              # Librerías JS
│   │   ├── app.js            # Script de aplicación
│   │   └── plugins.js        # Plugins
│   │
│   ├── img/                  # Imágenes
│   ├── fonts/                # Fuentes
│   └── uploads/
│       └── tickets/          # Archivos adjuntos de tickets
│
├── docs/
│   └── Mockups Helpdesk.bmpr # Wireframes del proyecto
│
├── .env                      # Variables de entorno
├── .gitignore               # Configuración de Git
├── README.md                # Este archivo
└── AUTENTICACION_404.md     # Documentación técnica
```


## Sistema de Roles y Permisos

| Funcionalidad | Usuario | Soporte | SuperAdmin |
|---|---|---|---|
| Crear ticket | ✅ | ✅ | ✅ |
| Ver propios tickets | ✅ | ✅ | ✅ |
| Consultar todos los tickets | ❌ | ✅ | ✅ |
| Asignar tickets | ❌ | ✅ | ✅ |
| Resolver tickets | ❌ | ✅ | ✅ |
| Ver estadísticas | ❌ | ✅ | ✅ |
| Gestionar usuarios | ❌ | ❌ | ✅ |
| Gestionar categorías | ❌ | ❌ | ✅ |
| Cambiar contraseña | ✅ | ✅ | ✅ |


### Últimas mejoras:
-  Sistema de autenticación con validación de roles
-  Página de error 404 personalizada
-  Dashboard de estadísticas
-  Gestión de usuarios y categorías

## Tecnologías Utilizadas

- **Backend**: PHP 7.0+
- **Base de datos**: MySQL/MariaDB
- **Frontend**: HTML5, CSS3, JavaScript
- **Librerías**:
  - Bootstrap 5.3
  - jQuery
  - DataTables
  - Summernote (editor de texto)
  - SweetAlert (alertas)
  - Full Calendar (calendario)
  - Chart.js / C3.js (gráficos)



