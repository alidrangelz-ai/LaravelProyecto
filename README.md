# BlocApp 

##  Características 

### Módulo de Eventos 
- Se ha implementado un completo gestor de **Eventos** con soporte para:
    - Categorización (Reunión, Personal, Trabajo, Importante).
    - Seguimiento de ubicación y descripciones detalladas.
    - Interfaz de tarjetas moderna con soporte para Modo Oscuro.
    - Seguridad integrada mediante `Policies` para proteger la privacidad del usuario.

### Estética
- Interfaz limpia con fondos blancos suaves, bordes muy redondeados (`rounded-[2rem]`), sombras sutiles y tipografía optimizada para lectura prolongada.
- Navegación **Sidebar fija** en escritorio para un flujo de trabajo profesional y cabecera colapsable moderna en móviles.

### Agenda Personal & Prioridades 
- **Integración Dual**: Unificación de Eventos y Tareas en un solo módulo de "Agenda Personal" con navegación por pestañas.
- **Sistema de Prioridades (🔥)**: Marcado de elementos críticos que se visualizan automáticamente en una "Hot List" destacada en el Menú Principal.
- **Filtrado por Rango de Fechas**: Panel avanzado para segmentar eventos y tareas por fechas específicas, optimizando la planificación temporal.
- **Edición Avanzada**: Flujo mejorado para la gestión de tareas existentes y cambio de estados.

### Decoración Estacional Dinámica
- El Calendario ahora reacciona al mes actual con **Temas Estacionales** (Invierno, Primavera, Verano, Otoño).
- Fondos animados con iconos estacionales flotantes en baja opacidad para una experiencia inmersiva.
- Micro-animaciones y gradientes de color que cambian suavemente al navegar entre meses.

### Modo Oscuro Nativo
- Sistema de temas (Claro/Oscuro) integrado con persistencia en el navegador (`localStorage`).
- Soporte completo en todos los nuevos módulos y componentes visuales.

### Preferencias y Navegación
- **Navegación Optimizada**: Se ha reordenado el menú principal para priorizar los **Eventos** sobre el **Calendario**, facilitando el acceso a la agenda inmediata.
- **Menú Principal**: El Dashboard funciona como centro de control con acceso a la "Hot List" de prioridades.
* **Ajustes Dinámicos**: Configuración funcional del título del sitio que se refleja automáticamente en toda la interfaz.
- **Gestión de Usuario**: Acceso al perfil y cierre de sesión centralizado en el módulo de Ajustes.

### Atajos de Productividad 
- **Creación Rápida desde Calendario**: Al hacer clic en cualquier día del calendario, ahora puedes añadir **Tareas** o **Eventos** instantáneamente a través de atajos inteligentes que pre-completan la fecha seleccionada.
- **Integración Total**: Los elementos creados desde el calendario se sincronizan automáticamente con sus respectivos módulos de gestión.

## Estado del Proyecto y Tecnologías

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


