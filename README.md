# Sistema de Trazabilidad de Servicios

Este es un sistema de gestión y trazabilidad de órdenes de servicio desarrollado con Laravel.

## Funcionalidades

- Gestión de órdenes de servicio
- Recepción de servicios
- Seguimiento de inventario (Kardex)
- Generación de informes
- Panel de control con estadísticas

## Estructura del Proyecto

El proyecto está organizado en dos partes:

1. **Directorio raíz**: Contiene el sistema de trazabilidad de servicios completo
2. **Subdirectorio "sistema-trazabilidad"**: Contiene una nueva implementación del sistema

## Requisitos

- PHP 8.1+
- Composer
- Node.js y npm
- Base de datos (MySQL, PostgreSQL, SQLite)

## Instalación

1. Clonar el repositorio
2. Instalar dependencias de PHP: `composer install`
3. Instalar dependencias de JavaScript: `npm install`
4. Configurar el archivo .env
5. Generar clave de aplicación: `php artisan key:generate`
6. Ejecutar migraciones: `php artisan migrate`
7. Ejecutar seeders: `php artisan db:seed`
8. Compilar assets: `npm run dev`
9. Iniciar servidor: `php artisan serve`
