# HelpDesk - Sistema de Gestión de Tickets

Sistema completo de gestión de tickets con estadísticas, control de usuarios y gestión de soporte.

## Requisitos

- PHP 7.4+
- MySQL
- Composer
- Git
- Cuenta en Heroku

## Pasos para desplegar en Heroku

### 1. Instalar Heroku CLI
Descarga desde: https://devcenter.heroku.com/articles/heroku-cli

### 2. Crear cuenta en Heroku
Ve a: https://www.heroku.com/

### 3. Login en Heroku
```bash
heroku login
```

### 4. Crear base de datos MySQL en Heroku
```bash
heroku addons:create cleardb:ignite -a nombre-de-tu-app
```

Obtén la URL de la base de datos:
```bash
heroku config:get CLEARDB_DATABASE_URL -a nombre-de-tu-app
```

### 5. Clonar el repositorio (si aún no lo has hecho)
```bash
git clone https://github.com/tu-usuario/tu-repositorio.git
cd tu-repositorio
```

### 6. Inicializar repositorio Git (si es necesario)
```bash
git init
git add .
git commit -m "Inicial commit"
```

### 7. Crear aplicación en Heroku
```bash
heroku create nombre-de-tu-app
```

### 8. Configurar variables de entorno
```bash
heroku config:set HEROKU=true -a nombre-de-tu-app
```

### 9. Desplegar la aplicación
```bash
git push heroku main
```

O si tu rama principal es master:
```bash
git push heroku master
```

### 10. Importar base de datos
Obtén la URL de conexión de ClearDB:
```bash
heroku config -a nombre-de-tu-app
```

Busca `CLEARDB_DATABASE_URL` y usa esa información para importar la base de datos.

Puedes usar phpMyAdmin o importar directamente con mysql:
```bash
mysql -h host -u usuario -p base_datos < dump.sql
```

### 11. Ver logs de la aplicación
```bash
heroku logs --tail -a nombre-de-tu-app
```

## Estructura del proyecto

```
HelpDesk/
├── config/          # Configuración de conexión a BD
├── controller/      # Controladores
├── models/          # Modelos de datos
├── view/            # Vistas
├── public/          # Archivos CSS, JS, imágenes
├── Procfile         # Configuración para Heroku
├── composer.json    # Dependencias PHP
└── .gitignore       # Archivos a ignorar en git
```

## Funcionalidades

- ✅ Registro y login de usuarios
- ✅ Creación de tickets
- ✅ Consulta de tickets
- ✅ Gestión de usuarios (admin)
- ✅ Estadísticas detalladas
- ✅ Control de soporte
- ✅ Gráficos y reportes

## Credenciales de prueba

Usuario Regular:
- Email: usuario@test.com
- Contraseña: 123456

Personal de Soporte:
- Email: soporte@test.com
- Contraseña: 123456

## Solución de problemas

### Error: "No puedo conectar a la BD"
- Verifica que la base de datos está creada en Heroku
- Comprueba las variables de entorno: `heroku config`

### Error: "Timeout en Heroku"
- La aplicación puede tardar más en iniciar. Espera 1-2 minutos.
- Verifica los logs: `heroku logs --tail`

### Error: "404 Not Found"
- Verifica que los archivos están en el directorio correcto
- Comprueba que el Procfile está configurado correctamente

## Contacto

Para más información, contacta al desarrollador.
