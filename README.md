# SIGLC - Sistema de Gestión Integral Clínica con IA 🏥🧠

Sistema web Full-Stack desarrollado en **Laravel 12** para la administración inteligente de centros médicos y laboratorios clínicos. 

**Versión 2.0:** Ahora potenciado con **Inteligencia Artificial (Groq / Llama 3 & Whisper)** para automatizar diagnósticos, transcripciones y atención al paciente.

---

## ✨ NUEVO: Módulos de Inteligencia Artificial (AI)

El sistema integra la API de **Groq Cloud** para ofrecer funcionalidades de vanguardia:

* **🎙️ Dictado de Voz a Texto (Whisper):** Los doctores pueden dictar la anamnesis y síntomas directamente en la historia clínica usando el micrófono.
* **📸 Escáner de Órdenes (Visión OCR):** El personal de recepción puede tomar una foto a una orden médica física y la IA marcará automáticamente los exámenes correspondientes en el sistema.
* **🔬 Interpretación de Resultados:** Al cargar valores de laboratorio, la IA genera automáticamente una conclusión técnica/patológica para el informe final del doctor.
* **🤖 Asistente Virtual "Dr. IA":** Chatbot en el portal del paciente que responde dudas sobre:
    * Requisitos de exámenes (Ayuno, muestras, etc.) basado en el catálogo real.
    * Uso de medicamentos recetados (Lee el historial clínico del paciente).
    * Explicación sencilla de resultados de laboratorio.

---

## 🚀 Características Principales

* **📅 Agenda Médica:** Calendario interactivo con gestión de estados y validación de cruce de horarios.
* **🩺 Historia Clínica Electrónica:** Registro de consultas, diagnósticos CIE-10 y Receta Médica Dinámica.
* **🧪 Laboratorio Completo:** Flujo de trabajo desde la toma de muestra hasta la validación, con control de **Inventario de Reactivos** automático.
* **💰 Caja y Facturación:** Control de sesiones de caja, gastos y cobros polimórficos (Consultas/Exámenes).
* **👤 Portal del Paciente:** Autogestión de citas, descarga de resultados (PDF) y Chatbot de asistencia.
* **📧 Notificaciones:** Envío automático de credenciales, recetas y resultados por correo.

---

## 🛠️ Tecnologías

* **Backend:** Laravel 12, PHP 8.2+
* **Base de Datos:** PostgreSQL
* **Frontend:** Blade, Tailwind CSS, Alpine.js
* **IA & LLM:** Groq API (Modelos: Llama-3-70b, Llama-Vision, Whisper-v3)
* **PDF:** DomPDF

---

## ⚙️ Guía de Instalación

### 1. Clonar e Instalar
```bash
git clone [https://github.com/Kevinrente/SIGLC--Clinical-Lab-Management-System-](https://github.com/Kevinrente/SIGLC--Clinical-Lab-Management-System-) siglc
cd siglc
composer install
npm install && npm run dev

2. Configuración de Entorno (.env)
Duplica el archivo .env.example, renómbralo a .env y configura:

Base de Datos:
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=siglc_db
DB_USERNAME=postgres
DB_PASSWORD=tu_password

Inteligencia Artificial (Groq): Obtén tu API Key gratuita en Groq Cloud Console.
GROQ_API_KEY=gsk_tu_api_key_aqui
GROQ_MODEL=llama-3.1-8b-instant

Correo (SMTP):
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=tucorreo@gmail.com
MAIL_PASSWORD=tu_app_password
MAIL_ENCRYPTION=tls

3. Migraciones y Seeds
Crea las tablas, roles, usuarios base y el catálogo de exámenes:
php artisan migrate:fresh --seed
# Cargar actualizaciones de la base de datos médica
php artisan db:seed --class=UpdateExamenesSeeder

4. Iniciar Servidor
php artisan serve

Accede a: http://127.0.0.1:8000
Rol,Email,Contraseña,Funciones
Admin,admin@siglc.com,password,"Dashboard, Usuarios, Reportes, Configuración."
Doctor,doctor@siglc.com,password,"Agenda, Historia Clínica (Voz), Recetas."
Laboratorio,laboratorio@siglc.com,password,"Procesar muestras, OCR de órdenes, Inventario."
Paciente,kevin@siglc.com,password,"Portal, Chatbot IA, Descarga PDF."

📘 Flujos de Trabajo con IA
🩺 1. Atención Médica (Doctor)
El doctor inicia una consulta desde la agenda.

Uso de IA: Presiona el botón "Dictar" 🎙️ y narra los síntomas. El sistema transcribe el audio a texto.

Genera la receta y finaliza. El paciente recibe el PDF por correo.

🧪 2. Laboratorio (Recepción y Proceso)
Recepción: Si llega una orden física, usa el botón "Escanear Orden (IA)" 📸 para digitalizarla sin teclear.

Proceso: Ingresa los resultados numéricos.

Análisis: Presiona "Generar Resumen con IA" 🪄. El sistema redacta una interpretación patológica automática.

Al guardar, se descuenta el stock del inventario y se genera el PDF.

👤 3. Portal del Paciente
El paciente ingresa a ver sus resultados.

Puede presionar "Ver Conclusión" para una explicación simple.

Chatbot: Puede abrir el chat flotante y preguntar "¿Para qué sirve el medicamento que me mandaron?" o "¿Debo ir en ayunas para el examen de Glucosa?". La IA responde usando los datos reales del sistema.