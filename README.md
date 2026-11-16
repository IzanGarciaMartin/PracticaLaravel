# Taskora

**Taskora** es una aplicación web de gestión de proyectos desarrollada con Laravel. Permite crear y organizar proyectos, gestionar participantes y colaborar con otros usuarios desde una interfaz intuitiva y adaptable a distintos dispositivos.

## Características principales

- **Autenticación:** registro, inicio de sesión y recuperación de contraseña.
- **Gestión de proyectos:** creación, consulta, edición y eliminación de proyectos.
- **Colaboración:** incorporación y gestión de participantes en los proyectos.
- **Roles y permisos:** diferenciación entre propietarios, administradores y miembros.
- **Gestión de cuentas:** actualización del perfil y contraseña, además de eliminación segura de la cuenta.
- **Transferencia de propiedad:** gestión de la propiedad de los proyectos cuando se elimina una cuenta.
- **Dashboard:** resumen de la actividad del usuario, estadísticas y proyectos recientes.
- **Diseño responsive:** interfaz adaptable a escritorio, tablet y dispositivos móviles.

## Tecnologías utilizadas

| Tecnología | Uso |
|---|---|
| Laravel 13 | Framework backend |
| PHP 8.5 | Lenguaje de programación |
| MySQL 8.4 | Base de datos |
| Laravel Sail | Entorno de desarrollo con Docker |
| Blade | Motor de plantillas |
| Tailwind CSS | Estilos e interfaz |
| JavaScript | Interactividad |
| Vite | Compilación de recursos frontend |

## Requisitos previos

Antes de instalar el proyecto, es necesario disponer de:

- Git.
- Docker y Docker Compose.
- Composer.
- Node.js y npm.

PHP no necesita estar instalado directamente en el sistema si se ejecutan los comandos de Laravel mediante Sail.

## Instalación y configuración

### 1. Clonar el repositorio

```bash
git clone URL_DEL_REPOSITORIO
cd taskora
```

### 2. Instalar las dependencias

```bash
composer install
npm install
```

### 3. Configurar el entorno

Crear el archivo `.env` a partir del ejemplo:

```bash
cp .env.example .env
```

Configurar los datos de conexión a la base de datos en el archivo `.env`. Para utilizar MySQL mediante Sail, la configuración debe apuntar al servicio de Docker:

```env
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=sail
DB_PASSWORD=password
```

Los valores deben coincidir con la configuración del entorno Docker del proyecto.

### 4. Iniciar Docker

```bash
./vendor/bin/sail up -d
```

### 5. Generar la clave de aplicación

```bash
./vendor/bin/sail artisan key:generate
```

### 6. Ejecutar las migraciones

```bash
./vendor/bin/sail artisan migrate
```

### 7. Compilar los recursos frontend

```bash
npm run build
```

### 8. Acceder a la aplicación

Con los contenedores en funcionamiento, abrir en el navegador:

[http://localhost](http://localhost)

Desde la aplicación se puede crear una cuenta mediante el formulario de registro.

## Ejecución de tests

Para ejecutar las pruebas automatizadas del proyecto:

```bash
./vendor/bin/sail artisan test
```

La suite de pruebas verifica funcionalidades relacionadas con:

- Autenticación y registro.
- Gestión del perfil.
- Gestión de proyectos.
- Gestión de participantes y permisos.
- Eliminación de cuentas y transferencia de propiedad.

## Estructura general

```text
taskora/
├── app/
│   ├── Http/
│   │   └── Controllers/
│   └── Models/
├── database/
│   ├── migrations/
│   └── seeders/
├── resources/
│   ├── css/
│   ├── js/
│   └── views/
├── routes/
│   ├── web.php
│   └── auth.php
├── tests/
│   ├── Feature/
│   └── Unit/
├── public/
├── docker-compose.yml
└── README.md
```

## Uso de inteligencia artificial

Durante el desarrollo se ha utilizado inteligencia artificial como herramienta de apoyo para la revisión de código, resolución de dudas técnicas, depuración de errores y orientación durante determinadas fases de implementación.

El desarrollo, integración y comprobación de las funcionalidades se ha realizado sobre el propio proyecto.

## Autoría

Proyecto desarrollado como parte de las actividades formativas del ciclo de **Desarrollo de Aplicaciones Web (DAW)**.