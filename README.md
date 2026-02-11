# Bloc de Notas Personal (Laravel + Vue + Inertia)

Este proyecto es una aplicación web de un bloc de notas personal organizado, desarrollado con Laravel 10, Vue.js 3, Inertia.js y Tailwind CSS.

## Estado Actual del Proyecto

### 1. Configuración Inicial del Entorno
- Se ha creado el proyecto Laravel utilizando la versión ^10.0.
- Se ha configurado el entorno de desarrollo para utilizar **SQLite** como base de datos, eliminando la dependencia de MySQL y simplificando la configuración local.
- Se ha creado el archivo de base de datos vacío `database/database.sqlite`.

### 2. Instalación de Dependencias
- Se ha instalado **Laravel Breeze** (`laravel/breeze`) para proporcionar la autenticación y el andamiaje inicial con Vue.js y Inertia.
- Las dependencias de Node.js (Vite, Vue, Tailwind) están listas para ser instaladas y compiladas.

## Módulos Desarrollados

El sistema cuenta con los siguientes módulos operativos:

1.  **Menú Principal (Dashboard)**: Visualización de estadísticas generales (Total de notas, Notas destacadas, Tareas pendientes y completadas).
2.  **Notas Rápidas**: CRUD completo para gestionar notas personales. Permite crear, editar, eliminar y fijar notas destacadas, con selección de color de fondo.
3.  **Calendario de Tareas**: Interface para organizar tareas con fechas, permitiendo marcarlas como completadas.
4.  **Configuraciones del Sistema**: Formulario para ajustar preferencias de la aplicación (como título del sitio, tema, etc.).

## Próximos Pasos

El proyecto está configurado y funcional. Para probarlo:

1.  Asegúrate de ejecutar las migraciones si no lo has hecho: `php artisan migrate`.
2.  Inicia el servidor backend: `php artisan serve`.
3.  Inicia el servidor frontend (Vite): `npm run dev`.
4.  Accede a `http://localhost:8000`.

¡La aplicación está lista para usar!
