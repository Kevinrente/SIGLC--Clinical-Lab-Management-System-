<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Examen;

class UpdateExamenesSeeder extends Seeder
{
    public function run(): void
    {
        // LISTA MAESTRA DE VALORES DE REFERENCIA
        // Aquí defines todos los datos técnicos de una sola vez
        $datos = [
            // QUÍMICA SANGUÍNEA
            ['nombre' => 'Creatinina',      'unidades' => 'mg/dL', 'ref' => '0.7 - 1.3 (H) / 0.6 - 1.1 (M)'],
            ['nombre' => 'Ácido Úrico',     'unidades' => 'mg/dL', 'ref' => '3.5 - 7.2 (H) / 2.6 - 6.0 (M)'],
            ['nombre' => 'Glucosa 2h',      'unidades' => 'mg/dL', 'ref' => '< 140'],
            ['nombre' => 'Glucosa ayunas',  'unidades' => 'mg/dL', 'ref' => '70 - 100'],
            ['nombre' => 'Colesterol Total','unidades' => 'mg/dL', 'ref' => '< 200'],
            ['nombre' => 'Triglicéridos',   'unidades' => 'mg/dL', 'ref' => '< 150'],
            ['nombre' => 'Amilasa',         'unidades' => 'U/L',   'ref' => '28 - 100'],
            
            // HEMATOLOGÍA / COAGULACIÓN
            ['nombre' => 'T. Protrombina',  'unidades' => 'segundos', 'ref' => '11 - 13.5'],
            ['nombre' => 'T. Tromboplastina','unidades' => 'segundos', 'ref' => '25 - 35'],
            ['nombre' => 'Leucocitos - Fórmula', 'unidades' => 'mm3', 'ref' => '4,500 - 11,000'],
            
            // HORMONAS
            ['nombre' => 'Progesterona',    'unidades' => 'ng/mL', 'ref' => 'Varía según fase ciclo'],
            ['nombre' => 'TSH',             'unidades' => 'uUI/mL', 'ref' => '0.4 - 4.0'],
            ['nombre' => 'T3 Total',        'unidades' => 'ng/dL',  'ref' => '80 - 200'],
            
            // OTROS
            ['nombre' => 'Depuración de Creatinina', 'unidades' => 'mL/min', 'ref' => '90 - 130'],
        ];

        foreach ($datos as $item) {
            // Buscamos el examen por nombre (usamos 'like' para ser flexibles)
            $examen = Examen::where('nombre', 'like', '%' . $item['nombre'] . '%')->first();

            if ($examen) {
                $examen->update([
                    'unidades' => $item['unidades'],
                    'valor_referencia' => $item['ref']
                ]);
                $this->command->info("✅ Actualizado: " . $item['nombre']);
            } else {
                $this->command->warn("⚠️ No encontrado: " . $item['nombre']);
            }
        }
    }
}