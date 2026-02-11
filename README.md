# BlocApp - Bloc de Notas Boutique (Laravel + Vue + Inertia)

Este proyecto es una aplicación web de gestión personal moderna y minimalista, desarrollada con **Laravel 10**, **Vue.js 3**, **Inertia.js** y **Tailwind CSS**. Se enfoca en una experiencia de usuario fluida con una estética "Boutique White".

## ✨ Características Recientes

### 📅 Módulo de Eventos (NUEVO)
- Se ha implementado un completo gestor de **Eventos** con soporte para:
    - Categorización (Reunión, Personal, Trabajo, Importante).
    - Seguimiento de ubicación y descripciones detalladas.
    - Interfaz de tarjetas moderna con soporte para Modo Oscuro.
    - Seguridad integrada mediante `Policies` para proteger la privacidad del usuario.

### 🎨 Estética "Boutique White"
- Interfaz limpia con fondos blancos suaves, bordes muy redondeados (`rounded-[2rem]`), sombras sutiles y tipografía optimizada para lectura prolongada.
- Navegación **Sidebar fija** en escritorio para un flujo de trabajo profesional y cabecera colapsable moderna en móviles.

### 🌗 Modo Oscuro Nativo
- Sistema de temas (Claro/Oscuro) integrado con persistencia en el navegador (`localStorage`).
- Transiciones suaves entre temas y soporte completo en todos los módulos (Dashboard, Notas, Calendario, Eventos, Ajustes).
- Inyección temprana de script en `app.blade.php` para evitar el parpadeo blanco al cargar la página.

### ⚙️ Preferencias y Navegación
- **Menú Principal**: El Dashboard ha sido renombrado a "Menú Principal" para una navegación más intuitiva.
* **Ajustes Dinámicos**: Configuración funcional del título del sitio que se refleja automáticamente en toda la interfaz.
- **Gestión de Usuario**: Acceso al perfil y cierre de sesión centralizado en el módulo de Ajustes.

## 📦 Estado del Proyecto y Tecnologías

### Arquitectura
- **Backend**: Laravel 10 con SQLite (persistencia garantizada y cero configuración externa).
- **Frontend**: Vue.js 3 (Composition API) + Inertia.js (SPA feel).
- **Estilos**: Tailwind CSS 3 con componentes reutilizables (`card-white`, `btn-primary`, `input-friendly`).

### Módulos Operativos
1.  **Menú Principal**: Resumen visual con estadísticas dinámicas.
2.  **Notas Rápidas**: CRUD con "stickers" de colores y opción de fijar notas.
3.  **Calendario de Tareas**: Vista mensual para gestión de fechas límite.
4.  **Eventos**: Agenda personal con categorización y ubicaciones.
5.  **Ajustes**: Centro de control para apariencia, datos del sitio y cuenta de usuario.

## 🚀 Instalación y Uso

1.  **Clonar y configurar**:
    ```bash
    composer install
    npm install
    cp .env.example .env
    php artisan key:generate
    ```
2.  **Base de Datos**:
    ```bash
    # Asegúrate de tener database/database.sqlite creado
    php artisan migrate
    ```
3.  **Ejecución**:
    - Backend: `php artisan serve`
    - Frontend: `npm run dev` o `npm run build`

---
*Desarrollado con ❤️ para una organización personal sin distracciones.*
