# HelpDesk - Sistema de Gestión de Tickets

![Project Status](https://img.shields.io/badge/status-active-brightgreen)
![PHP Version](https://img.shields.io/badge/php-%3E%3D7.0-blue)
![License](https://img.shields.io/badge/license-MIT-green)

## 📋 Descripción

**HelpDesk** es un sistema web de gestión de tickets de soporte técnico desarrollado en **PHP** con arquitectura **MVC**. Complementa un chatbot principal proporcionando un sistema completo de soporte al cliente, permitiendo a los usuarios crear, consultar y dar seguimiento a tickets de soporte, mientras que el personal de soporte puede asignar, resolver y gestionar tickets.

El sistema está diseñado como un proyecto de complemento educativo que demuestra conceptos de desarrollo web moderno, seguridad, autenticación y gestión de datos.

---

## ✨ Características Principales

### 👥 Sistema de Autenticación
- Autenticación de usuarios con correo electrónico
- Control de roles (Usuario, Personal de Soporte, SuperAdmin)
- Validación de sesiones
- Página de error 404 personalizada con redirección automática

### 🎫 Gestión de Tickets
- **Crear tickets**: Los usuarios pueden crear nuevos tickets de soporte
- **Consultar tickets**: Vista de todos los tickets del usuario
- **Detalles de ticket**: Información completa con historial de comentarios
- **Asignación automática**: Round-Robin entre personal de soporte
- **Estados**: Abierto, En Progreso, Cerrado
- **Prioridades**: Baja, Media, Alta, Urgente
- **Adjuntos**: Carga de archivos con los tickets

### 📊 Estadísticas
- Dashboard con métricas de tickets
- Visualización de datos por categoría
- Gráficos de rendimiento (solo para rol Soporte y Admin)

### 👨‍💼 Administración de Usuarios
- CRUD de usuarios (Solo SuperAdmin)
- Gestión de roles
- Cambio de contraseña
- Estados activos/inactivos

### 🏷️ Categorías
- Gestión de categorías de tickets
- Clasificación de problemas

---

## 🔧 Requisitos Previos

- **PHP** >= 7.0
- **MySQL/MariaDB** >= 5.7
- **Apache** con módulo `mod_rewrite` habilitado
- **XAMPP** (incluye todo lo anterior)

---

## 📦 Instalación

### 1. Descargar el Proyecto

```bash
git clone https://github.com/kokeeee/Helpdesk.git
cd HelpDesk
```

### 2. Configurar Base de Datos

#### Crear la base de datos:
```sql
CREATE DATABASE heldesk CHARACTER SET utf8 COLLATE utf8_general_ci;
USE heldesk;
```

#### Importar esquema SQL:
```bash
mysql -u root -p heldesk < database/schema.sql
```

#### O crear manualmente las tablas:

```sql
-- Tabla de usuarios
CREATE TABLE tm_usuario (
  usu_id INT PRIMARY KEY AUTO_INCREMENT,
  nombre VARCHAR(100) NOT NULL,
  apellido VARCHAR(100) NOT NULL,
  correo VARCHAR(255) UNIQUE NOT NULL,
  contrasenia VARCHAR(255) NOT NULL,
  rol_id INT NOT NULL,
  fecha_crea DATETIME DEFAULT CURRENT_TIMESTAMP,
  fecha_modifi DATETIME,
  fecha_elim DATETIME,
  estado INT DEFAULT 1,
  FOREIGN KEY (rol_id) REFERENCES tm_rol(rol_id)
);

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

#### Credenciales de Prueba:
```
Usuario Regular:
  Correo: usuario@example.com
  Contraseña: 123456
  Rol: Usuario

Personal Soporte:
  Correo: soporte@example.com
  Contraseña: 123456
  Rol: Soporte

Administrador:
  Correo: admin@example.com
  Contraseña: 123456
  Rol: SuperAdmin
```

---

## 📁 Estructura del Proyecto

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

---

## 🔐 Sistema de Roles y Permisos

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

---

## 🚀 Uso del Sistema

### Para Usuarios Regulares

1. **Iniciar Sesión**: Acceder con correo y contraseña
2. **Crear Ticket**: 
   - Click en "Nuevo Ticket"
   - Seleccionar categoría
   - Escribir asunto y descripción
   - Adjuntar archivos si es necesario
   - Enviar
3. **Consultar Tickets**: Ver estado de todos sus tickets
4. **Ver Detalles**: Acceder a comentarios y historial
5. **Cambiar Contraseña**: En perfil de usuario

### Para Personal de Soporte

1. Todas las funciones de usuario +
2. **Ver todos los tickets**: Dashboard general
3. **Asignar tickets**: A otros miembros del equipo
4. **Agregar comentarios**: Dar seguimiento a tickets
5. **Resolver tickets**: Cambiar estado a resuelto
6. **Ver Estadísticas**: Métricas de desempeño

### Para Administrador

1. Todas las funciones de soporte +
2. **Gestionar usuarios**: Crear, editar, eliminar
3. **Gestionar categorías**: Crear y actualizar categorías
4. **Gestionar roles**: Control total del sistema

---

## 🔒 Seguridad

⚠️ **NOTA IMPORTANTE PARA PRODUCCIÓN:**

Este proyecto es educativo. Antes de usar en producción:

- [ ] Implementar **hashing de contraseñas** (`password_hash()`)
- [ ] Agregar **validación CSRF**
- [ ] Implementar **sanitización de inputs** (prevenir SQL injection)
- [ ] Usar **HTTPS** (configurar `FORCE_HTTPS=true` en `.env`)
- [ ] Cambiar **claves de sesión** en `.env`
- [ ] Configurar **rate limiting** para login
- [ ] Implementar **logging de auditoría**
- [ ] Restricción de acceso a archivos sensibles

---

## 📝 Cambios Recientes

Ver [AUTENTICACION_404.md](./AUTENTICACION_404.md) para documentación técnica de cambios recientes.

### Últimas mejoras:
- ✅ Sistema de autenticación con validación de roles
- ✅ Página de error 404 personalizada
- ✅ Gestión de tickets con asignación automática
- ✅ Dashboard de estadísticas
- ✅ Gestión de usuarios y categorías

---

## 🐛 Troubleshooting

### Error: "¡Error BD!"

**Problema**: No se conecta a la base de datos
```
Solución:
1. Verificar que MySQL esté corriendo
2. Revisar credenciales en config/conexion.php
3. Confirmar que la base de datos 'heldesk' existe
```

### Error 404 en todas las páginas

**Problema**: Rutas no funcionan
```
Solución:
1. Habilitar mod_rewrite en Apache
2. Verificar archivo .htaccess en view/
3. Confirmar que la URL base es correcta
```

### No se pueden subir archivos

**Problema**: Error al cargar adjuntos
```
Solución:
1. Crear directorio public/uploads/tickets/
2. Verificar permisos de escritura (755)
3. Revisar límite de carga en php.ini
```

---

## 📚 Tecnologías Utilizadas

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

---

## 🤝 Contribuciones

Este es un proyecto educativo. Las sugerencias y mejoras son bienvenidas.

---

## 📄 Licencia

Este proyecto está bajo la licencia MIT. Ver archivo `LICENSE` para más detalles.

---

## 👨‍💻 Autor

**Proyecto Universitario - HelpDesk**
- Complemento del chatbot principal
- Desarrollado como proyecto de apoyo técnico
- Demostrativo de conceptos MVC en PHP

---

## 📞 Soporte

Para preguntas o problemas, contactar al administrador del sistema.

---

## ✅ Checklist para Producción

- [ ] Implementar hashing de contraseñas
- [ ] Configurar HTTPS
- [ ] Implementar validación CSRF
- [ ] Agregar sanitización de inputs
- [ ] Configurar rate limiting
- [ ] Implementar logging
- [ ] Realizar pruebas de seguridad
- [ ] Configurar backups automáticos
- [ ] Documentar procesos de administración
- [ ] Capacitar al equipo de soporte

---

**Última actualización**: 1 de diciembre de 2025

