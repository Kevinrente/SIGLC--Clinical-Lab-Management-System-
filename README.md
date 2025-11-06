# 🔬 SIGLC: Sistema de Gestión Integral de Laboratorio y Clínica

Este proyecto es una aplicación web de nivel profesional desarrollada con Laravel 12 y PostgreSQL, diseñada para la gestión integral de un centro que combina consultas médicas especializadas y servicios de laboratorio clínico.

El sistema garantiza la **confidencialidad médica** mediante un estricto control de acceso basado en roles (RBAC) y la **integridad de los resultados de laboratorio** mediante el almacenamiento seguro de archivos.

## ✨ Características Clave del SIGLC

* **RBAC (Control de Acceso Basado en Roles):** Roles definidos como **Administrador**, **Doctor**, **Técnico de Laboratorio** y **Recepción**, con permisos para ver solo la información relevante a su puesto.
* **Gestión de Citas:** Agendamiento de citas con validación de unicidad para evitar la doble reserva de un Doctor a la misma hora.
* **Flujo Clínico Completo:** Permite al Doctor iniciar la **Consulta** desde la Cita, registrar el **Diagnóstico** y **Solicitar Exámenes**.
* **Laboratorio Seguro:** Carga de resultados clínicos en formato **PDF** con almacenamiento privado (`storage/app/`) y verificación de integridad (Hash).
* **Confidencialidad:** La descarga de resultados está protegida por RBAC (`lectura.historial`).
* **Dashboard Operacional:** Muestra KPIs clave como Citas Pendientes para hoy y Órdenes de Laboratorio pendientes.

---

## 🛠️ Requisitos del Sistema

* PHP >= 8.2
* Composer
* Node.js & npm
* **PostgreSQL** (configurado y activo)
* Extensión PHP `pdo_pgsql` instalada.

---

## 🚀 Guía de Instalación y Setup

Sigue estos pasos para configurar y ejecutar el proyecto:

### 1. Clonar, Instalar Dependencias y Configurar Entorno

```bash
# 1. Clonar el proyecto
git clone [ADJUNTA EL LINK DEL REPOSITORIO AQUÍ] siglc
cd siglc

# 2. Instalar dependencias
composer install
npm install

# 3. Compilar assets y generar clave de app
npm run dev 
cp .env.example .env
php artisan key:generate

### 2. Configuración de PostgreSQL

    Abre el archivo .env y configura los parámetros de conexión:
    Fragmento de código

    DB_CONNECTION=pgsql
    DB_HOST=127.0.0.1
    DB_PORT=5432
    DB_DATABASE=siglc_db
    DB_USERNAME=postgres
    DB_PASSWORD=tu_contraseña_segura

    Crea la base de datos siglc_db en tu servidor PostgreSQL si no existe.

### 3. Reconstrucción de la Base de Datos y Seeders (CRÍTICO)

Este comando elimina las tablas, las recrea y puebla los roles, permisos, usuarios de prueba y un Doctor inicial:
Bash

php artisan migrate:fresh --seed

### 4. Ejecución del Servidor

Bash

php artisan serve

El sistema estará disponible en http://127.0.0.1:8000/.

🔑 Cuentas de Prueba (RBAC)

Utiliza estas credenciales para probar los flujos de trabajo y la seguridad del sistema:
Rol	Email	Contraseña	Permisos Principales
Administrador	admin@siglc.com	password	TOTAL (Gestiona Doctores, ve todo).
Doctor	doctor@siglc.com	password	Crea Consultas, Agenda, Descarga Historial.
Recepción	recepcion@siglc.com	password	Gestiona Pacientes y Agenda de Citas.

🛡️ Prueba de Flujo Clínico y Seguridad (RBAC)

    Prueba de Roles: Inicia sesión como Recepción. Verifica que puedes crear Pacientes y Citas, pero NO puedes ver el módulo Doctores ni Laboratorio.

    Prueba de Cita: Inicia sesión como Doctor. Ve a la Agenda (/citas) y utiliza el botón para iniciar la consulta de una cita pendiente.

    Prueba de Solicitud: En la Consulta, registra el diagnóstico y selecciona un Examen. El sistema debe crear una Orden de Examen con estado "Solicitado".

    Prueba de Laboratorio: Inicia sesión como Admin o Técnico. Ve a Laboratorio (/laboratorio). Sube un archivo PDF para esa orden.

    Prueba de Descarga Segura: Verifica que el Doctor pueda descargar el resultado (PDF) de forma segura desde el historial del paciente, lo cual confirma la protección de la ruta_resultado_pdf.