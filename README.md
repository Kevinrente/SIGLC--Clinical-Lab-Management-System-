# SIGLC - Sistema de Gestión Integral Clínica 🏥

Sistema web Full-Stack desarrollado en **Laravel 12** para la administración completa de un centro médico y laboratorio clínico. Diseñado para optimizar el flujo de trabajo entre doctores, laboratoristas, caja y pacientes.

---

## 🚀 Características Principales

* **📅 Agenda Médica Inteligente:** Calendario visual interactivo (FullCalendar) con validación de horarios y gestión de estados.
* **🩺 Historia Clínica Electrónica:** Registro de consultas con diagnósticos (CIE-10) y **Receta Médica Dinámica**.
* **🧪 Laboratorio Avanzado:** Flujo de órdenes, carga de resultados con valores de referencia y generación automática de PDFs.
* **💰 Caja y Facturación:** Módulo de cobro polimórfico (cobra Consultas y Exámenes por separado) con descuentos y recibos.
* **👤 Portal del Paciente:** Autogestión de citas y descarga de resultados/recetas desde casa.
* **📧 Notificaciones:** Envío automático de resultados y recetas por correo electrónico.

---

## 🛠️ 1. Requisitos del Sistema

* PHP >= 8.2
* PostgreSQL (Puerto 5432)
* Composer
* Node.js & npm (Para compilar assets)

---

## ⚙️ 2. Guía de Instalación

Sigue estos pasos para levantar el proyecto en tu entorno local:

### 2.1 Clonar e Instalar
```bash
# 1. Clonar el repositorio
git clone [https://github.com/Kevinrente/SIGLC--Clinical-Lab-Management-System-](https://github.com/Kevinrente/SIGLC--Clinical-Lab-Management-System-) siglc

# 2. Entrar al directorio
cd siglc

# 3. Instalar dependencias Backend
composer install

# 4. Instalar dependencias Frontend
npm install && npm run dev
2.2 Configuración de Base de DatosCrea una base de datos en PostgreSQL llamada siglc_db.Duplica el archivo .env.example a .env y configura tus credenciales:Fragmento de códigoDB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=siglc_db
DB_USERNAME=postgres
DB_PASSWORD=tu_contraseña
2.3 Configuración de Correo (Vital para notificaciones)Para que el sistema envíe los PDFs, configura un servidor SMTP (como Gmail App Password o Mailtrap) en el .env:Fragmento de códigoMAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=465
MAIL_USERNAME=tucorreo@gmail.com
MAIL_PASSWORD="tu_contraseña_de_aplicacion"
MAIL_ENCRYPTION=ssl
MAIL_FROM_ADDRESS="no-reply@siglc.com"
2.4 Migraciones y Datos de PruebaEste comando crea las tablas, roles y carga el catálogo de exámenes con precios y valores de referencia:Bashphp artisan migrate:fresh --seed
# IMPORTANTE: Cargar valores de referencia médicos
php artisan db:seed --class=UpdateExamenesSeeder
2.5 Iniciar ServidorBashphp artisan serve
Accede a: http://127.0.0.1:8000🔑 3. Credenciales de Acceso (Roles)RolEmailContraseñaFunciones PrincipalesAdminadmin@siglc.compasswordControl total, Dashboard Gerencial, Reportes.Doctordoctor@siglc.compasswordAgenda, Atender Consultas, Recetar, Ordenar Exámenes.Laboratoriolaboratorio@siglc.compasswordProcesar muestras, Cargar resultados, Órdenes directas.Pacientekevin@siglc.compasswordReservar citas, Descargar PDFs (Resultados/Recetas).📘 4. Manual de Uso y Flujos de Trabajo📅 Flujo 1: Agenda y Citas MédicasReservar:Paciente: Ingresa a "Reservar Cita", ve los huecos disponibles (en blanco) y hace clic para agendar.Secretaria/Doctor: Puede ver la agenda completa. Al hacer clic en un espacio, puede seleccionar al paciente y marcar la cita como "Confirmada" inmediatamente.Validación: El sistema impide automáticamente que se reserven dos citas a la misma hora con el mismo doctor.🩺 Flujo 2: Atención Médica (Consulta)El Doctor va a "Lista de Citas" y pulsa "Atender" (Botón Verde).Llena la Historia Clínica: Motivo, Exploración Física y Diagnósticos.Receta Dinámica: Usa el botón "Agregar Medicamento" para crear la receta línea por línea.Finalización:Opción A (Solo Consulta): Guarda y finaliza. El paciente recibe su receta por correo.Opción B (Con Exámenes): Clic en "Guardar y Generar Orden". Esto guarda la consulta y redirige inmediatamente al módulo de laboratorio.💰 Flujo 3: Caja y FacturaciónEl sistema maneja cobros separados para Consultas y Laboratorio.Ir al menú "Consultas & Caja" o "Laboratorio".Buscar el registro con el ícono de Billete Verde 💵 (Pendiente de pago).Ingresar método de pago (Efectivo/Transferencia) y aplicar descuentos si aplica.Al confirmar, el estado cambia a "PAGADO" y se descarga un Recibo PDF.🧪 Flujo 4: Gestión de LaboratorioRecepción:Desde Cita: La orden llega automática del doctor.Directa (Walk-in): El laboratorista usa "Pacientes -> Orden Rápida" para pacientes sin cita médica.Procesamiento: Clic en "Gestionar". Se ingresan los valores numéricos de los exámenes.Entrega: Al finalizar, el sistema genera el Informe de Resultados (PDF) y lo envía automáticamente al correo del paciente.👤 Flujo 5: Portal del PacienteEl paciente inicia sesión y accede a un panel privado donde puede:Ver sus próximas citas.Descargar Recetas Médicas históricas.Descargar Resultados de Laboratorio apenas estén listos.🛡️ Arquitectura de SeguridadEl sistema utiliza Spatie/Laravel-Permission para proteger las rutas.Middleware role:admin para configuración global.Middleware permission:gestion.consultas para historias clínicas.Políticas de privacidad en el calendario (los pacientes no ven nombres de otros pacientes).