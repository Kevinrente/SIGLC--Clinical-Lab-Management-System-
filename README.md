Sistema de Gestión Integral Clínica (SIGLC)

Sistema de gestión de clínica médica desarrollado con Laravel, PostgreSQL, y Tailwind CSS. Diseñado para manejar la agenda de citas, el registro médico (consultas), y el flujo de órdenes de laboratorio con control de acceso basado en roles (RBAC).

🚀 1. Requisitos del Sistema

    PHP >= 8.2

    PostgreSQL (Configurado en el puerto 5432)

    Composer

    Node.js & npm (Para compilar assets de Tailwind/Breeze)

🛠️ 2. Guía de Instalación Rápida

Sigue estos pasos para poner el proyecto en funcionamiento en tu entorno local (Fedora/Linux):

2.1 Clonar el Repositorio e Instalar Dependencias

Bash

# 1. Clonar el repositorio
git clone https://docs.github.com/es/repositories/creating-and-managing-repositories/quickstart-for-repositories siglc

# 2. Entrar al directorio
cd siglc

# 3. Instalar dependencias de Composer
composer install

# 4. Instalar dependencias de Node.js y compilar assets
npm install
npm run dev

2.2 Configuración de la Base de Datos (PostgreSQL)

    Crear la Base de Datos: Accede a la consola de PostgreSQL y crea la base de datos siglc_db (o el nombre que uses):
    SQL

sudo -i -u postgres
createdb siglc_db
exit

Configurar .env: Duplica el archivo .env.example a .env y actualiza las credenciales de PostgreSQL:
Bash

DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=siglc_db
DB_USERNAME=postgres
DB_PASSWORD=tu_contraseña_segura

Ejecutar Migraciones y Seeds: Esto creará todas las tablas y poblará los roles, permisos y usuarios de prueba.
Bash

    php artisan migrate:fresh --seed

2.3 Iniciar la Aplicación

Ejecuta el servidor de desarrollo de Laravel:
Bash

php artisan serve

Accede a la aplicación en: http://127.0.0.1:8000

🔑 3. Usuarios de Prueba y Credenciales (RBAC)

El seeder MedicalRolesAndPermissionsSeeder ha creado las siguientes cuentas con la contraseña password para probar el flujo de trabajo:
Rol	Email de Acceso	Permisos Clave	Propósito
Admin	admin@siglc.com	gestion.administracion, gestion.laboratorio (Total)	Gestión de Usuarios, Configuración y Supervisión.
Doctor	doctor@siglc.com	gestion.citas, gestion.consultas	Agenda, Registrar Notas Médicas, y Generar Órdenes de Examen.
Laboratorio	laboratorio@siglc.com	gestion.laboratorio	Procesar órdenes, subir resultados, y gestionar el módulo de Laboratorio.
Recepción	recepcion@siglc.com	gestion.citas, gestion.pacientes	Agendar, editar y cancelar citas, registrar pacientes.

🧬 4. Arquitectura y Flujos de Trabajo Clave

El sistema se enfoca en tres flujos principales:

4.1 Flujo de Agenda y Citas

    Ruta principal: /citas

    Seguridad: Controlada por CitaController::middleware() (gestion.citas).

    Lógica: La agenda se filtra automáticamente para el Doctor logueado y permite el filtro por fecha/doctor para Recepción/Admin.

4.2 Flujo de Consulta y Órdenes de Examen

Este flujo es crucial e inicia cuando la cita es Completada.
Paso	Usuario	Acción/Ruta	Lógica de Integración
1. Iniciar Consulta	Doctor	Clic en "Gestionar Consulta".	El sistema verifica el estado de la cita y el rol del usuario.
2. Generar Orden	Doctor	Clic en "Generar Orden de Examen" (/citas/{cita}/ordenes/create).	Crea un registro en orden_examens con estado Solicitado, vinculado a la cita.
3. Procesar Resultado	Laboratorio	Accede a la orden, cambia el estado a Finalizado.	Sube el archivo de resultado de forma segura (disco privado) y registra el hash de integridad.
4. Descarga	Doctor/Paciente	Clic en "PDF" en la vista de detalle de la cita.	LaboratorioController::downloadResultado verifica la autenticación antes de servir el archivo privado.

4.3 Arquitectura de Seguridad (RBAC)

    Paquete: Spatie/Laravel-Permission.

    Implementación: Los middlewares (permission:X) se aplican estáticamente en los controladores (Controller::middleware()), asegurando que solo los roles con el permiso explícito puedan acceder a las funciones (ej., solo Laboratorio puede acceder a gestion.laboratorio).

Nota: La tabla orden_examens fue ajustada para usar un campo de texto (examenes_solicitados) en lugar de una llave foránea simple, para manejar múltiples solicitudes de examen por orden.