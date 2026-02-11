# BlocApp - Bloc de Notas Boutique (Laravel + Vue + Inertia)

Este proyecto es una aplicación web de gestión personal moderna y minimalista, desarrollada con **Laravel 10**, **Vue.js 3**, **Inertia.js** y **Tailwind CSS**. Se enfoca en una experiencia de usuario fluida con una estética "Boutique White".

## ✨ Características Recientes (Actualización UI/UX)

### 🎨 Estética "Boutique White"
- Interfaz limpia con fondos blancos suaves, bordes muy redondeados (`rounded-[2rem]`), sombras sutiles y tipografía optimizada para lectura prolongada.
- Navegación **Sidebar fija** en escritorio para un flujo de trabajo profesional y cabecera colapsable moderna en móviles.

### 🌗 Modo Oscuro Nativo
- Sistema de temas (Claro/Oscuro) integrado con persistencia en el navegador (`localStorage`).
- Transiciones suaves entre temas y soporte completo en todos los módulos (Dashboard, Notas, Calendario, Ajustes).
- Inyección temprana de script para evitar el parpadeo blanco al cargar la página.

### ⚙️ Centro de Configuración Unificado
- Se ha rediseñado el módulo de **Ajustes** para actuar como centro de control.
- **Gestión de Usuario**: El acceso al perfil y cierre de sesión se han migrado del panel lateral al módulo de Ajustes para una interfaz más despejada.
- **Preferencias de Apariencia**: Interruptor visual para cambiar el modo de color.

## 📦 Estado del Proyecto y Tecnologías

### Arquitectura
- **Backend**: Laravel 10 con SQLite (cero configuración de base de datos externa).
- **Frontend**: Vue.js 3 (Composition API) + Inertia.js (SPA feel).
- **Estilos**: Tailwind CSS 3 con configuraciones personalizadas para el modo oscuro y componentes reutilizables (`card-white`, `btn-primary`).

### Módulos Operativos
1.  **Dashboard**: Resumen visual con estadísticas dinámicas y diseño adaptativo.
2.  **Notas Rápidas**: CRUD con "stickers" de colores, opción de destacar (Pin) y visualización en cuadrícula.
3.  **Calendario de Tareas**: Vista mensual intuitiva para gestionar fechas límite y estados de tareas.
4.  **Ajustes**: Gestión de metadatos del sitio, cuenta de usuario y personalización estética.

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
    php artisan migrate --seed
    ```
3.  **Ejecución**:
    - Backend: `php artisan serve`
    - Frontend: `npm run dev` o `npm run build`

---
*Desarrollado con ❤️ para una organización personal sin distracciones.*
