# Sistema de Trazabilidad

## Acerca del Proyecto

Sistema de Trazabilidad es una aplicación web desarrollada con Laravel para gestionar y dar seguimiento a servicios. Esta plataforma permite registrar, monitorear y analizar el ciclo de vida completo de los servicios ofrecidos.

## Características

- Gestión de usuarios y roles
- Registro y seguimiento de servicios
- Generación de reportes
- Notificaciones y alertas
- Panel de administración intuitivo
- API RESTful para integración con otros sistemas

## Requisitos

- PHP >= 8.1
- Composer
- Base de datos (MySQL, PostgreSQL, SQLite)
- Node.js y NPM (para compilar assets)

## Instalación

1. Clonar el repositorio
2. Ejecutar `composer install`
3. Copiar `.env.example` a `.env` y configurar la base de datos
4. Ejecutar `php artisan key:generate`
5. Ejecutar `php artisan migrate`
6. Ejecutar `npm install && npm run dev`
7. Ejecutar `php artisan serve`

## Licencia

Este proyecto está licenciado bajo la [Licencia MIT](https://opensource.org/licenses/MIT).
