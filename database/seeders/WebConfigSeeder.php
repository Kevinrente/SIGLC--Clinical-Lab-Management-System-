<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WebConfigSeeder extends Seeder
{
    public function run()
    {
        $configs = [
            // SECCIÓN HERO
            ['key' => 'hero_titulo', 'value' => 'Cuidado Experto. Diagnóstico Preciso.', 'label' => 'Título Principal', 'type' => 'text'],
            ['key' => 'hero_descripcion', 'value' => 'Tu salud es nuestra prioridad. Laboratorio clínico de vanguardia y médicos especialistas en un solo lugar.', 'label' => 'Descripción Principal', 'type' => 'textarea'],
            
            // SECCIÓN SERVICIOS DESTACADOS
            ['key' => 'card_1_titulo', 'value' => 'Laboratorio 24 Horas', 'label' => 'Título Tarjeta 1', 'type' => 'text'],
            ['key' => 'card_1_desc', 'value' => 'Servicio de emergencia continua para análisis urgentes y toma de muestras.', 'label' => 'Desc. Tarjeta 1', 'type' => 'textarea'],
            
            ['key' => 'card_2_titulo', 'value' => 'Especialidades Certificadas', 'label' => 'Título Tarjeta 2', 'type' => 'text'],
            ['key' => 'card_2_desc', 'value' => 'Doctores internos y consultorio para la atención de patologías complejas.', 'label' => 'Desc. Tarjeta 2', 'type' => 'textarea'],
            
            ['key' => 'card_3_titulo', 'value' => 'Resultados Digitales', 'label' => 'Título Tarjeta 3', 'type' => 'text'],
            ['key' => 'card_3_desc', 'value' => 'Accede a tus resultados clínicos e historial médico de forma segura en línea.', 'label' => 'Desc. Tarjeta 3', 'type' => 'textarea'],

            // CONTACTO Y FOOTER
            ['key' => 'ubicacion', 'value' => 'Av. del Pacífico y Gran Colombia', 'label' => 'Dirección Física', 'type' => 'text'],
            ['key' => 'telefono_emergencia', 'value' => '(555) 555-5555', 'label' => 'Teléfono Emergencia', 'type' => 'text'],
            ['key' => 'email_contacto', 'value' => 'info@siglc.com', 'label' => 'Email de Contacto', 'type' => 'text'],
            ['key' => 'horario_atencion', 'value' => 'L-V 7:00 AM - 7:00 PM', 'label' => 'Horario', 'type' => 'text'],
        ];

        DB::table('web_configs')->insert($configs);
    }
}