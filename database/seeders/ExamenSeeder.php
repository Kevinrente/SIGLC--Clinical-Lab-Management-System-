<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Examen;

class ExamenSeeder extends Seeder
{
    public function run(): void
    {
        $examenes = [
            // HEMATOLOGÍA
            ['categoria' => 'Hematología', 'nombre' => 'Biometría Hemática'],
            ['categoria' => 'Hematología', 'nombre' => 'Hematología'], // Si se refiere a un panel general
            ['categoria' => 'Hematología', 'nombre' => 'Hematocrito'],
            ['categoria' => 'Hematología', 'nombre' => 'Leucocitos - Fórmula'],
            ['categoria' => 'Hematología', 'nombre' => 'Eritrosedimentación'],
            ['categoria' => 'Hematología', 'nombre' => 'Hematozoario'],
            ['categoria' => 'Hematología', 'nombre' => 'Plaquetas'],
            ['categoria' => 'Hematología', 'nombre' => 'Grupo y Factor Rh'],

            // HEMOSTÁSICOS
            ['categoria' => 'Hemostásicos', 'nombre' => 'T. Protrombina'],
            ['categoria' => 'Hemostásicos', 'nombre' => 'T.P. Tromboplastina'],
            ['categoria' => 'Hemostásicos', 'nombre' => 'Tiempo de sangría'],
            ['categoria' => 'Hemostásicos', 'nombre' => 'Tiempo de coagulación'],

            // ELECTROLITOS
            ['categoria' => 'Electrolitos', 'nombre' => 'Sodio'],
            ['categoria' => 'Electrolitos', 'nombre' => 'Potasio'],
            ['categoria' => 'Electrolitos', 'nombre' => 'Cloro'],
            ['categoria' => 'Electrolitos', 'nombre' => 'Litio'],
            ['categoria' => 'Electrolitos', 'nombre' => 'Calcio'],

            // BIOQUÍMICOS
            ['categoria' => 'Bioquímicos', 'nombre' => 'Glucosa ayunas'],
            ['categoria' => 'Bioquímicos', 'nombre' => 'Glucosa 2h'], // Opcional: Postprandial
            ['categoria' => 'Bioquímicos', 'nombre' => 'Hemoglobina Glicosilada'],
            ['categoria' => 'Bioquímicos', 'nombre' => 'Urea'],
            ['categoria' => 'Bioquímicos', 'nombre' => 'Creatinina'],
            ['categoria' => 'Bioquímicos', 'nombre' => 'Ácido Úrico'],
            ['categoria' => 'Bioquímicos', 'nombre' => 'Colesterol Total'],
            ['categoria' => 'Bioquímicos', 'nombre' => 'Triglicéridos'],
            ['categoria' => 'Bioquímicos', 'nombre' => 'HDL Colesterol'],
            ['categoria' => 'Bioquímicos', 'nombre' => 'LDL Colesterol'],
            ['categoria' => 'Bioquímicos', 'nombre' => 'Bilirrubinas y Fracciones'],
            ['categoria' => 'Bioquímicos', 'nombre' => 'Proteínas y Fracciones'],

            // ENZIMAS
            ['categoria' => 'Enzimas', 'nombre' => 'TGO - TGP'],
            ['categoria' => 'Enzimas', 'nombre' => 'GGT'],
            ['categoria' => 'Enzimas', 'nombre' => 'Amilasa'],
            ['categoria' => 'Enzimas', 'nombre' => 'Lipasa'],
            ['categoria' => 'Enzimas', 'nombre' => 'Fosfatasa Alcalina'],
            ['categoria' => 'Enzimas', 'nombre' => 'F. Ácida Prostática'],
            ['categoria' => 'Enzimas', 'nombre' => 'CK-MB'],
            ['categoria' => 'Enzimas', 'nombre' => 'CPK Total'],
            ['categoria' => 'Enzimas', 'nombre' => 'Troponina'],

            // SEROLÓGICOS
            ['categoria' => 'Serológicos', 'nombre' => 'Reacción de Widal'],
            ['categoria' => 'Serológicos', 'nombre' => 'V.D.R.L'],
            ['categoria' => 'Serológicos', 'nombre' => 'ASTO'],
            ['categoria' => 'Serológicos', 'nombre' => 'PCR (Proteína C Reactiva)'], // Aclarado
            ['categoria' => 'Serológicos', 'nombre' => 'Factor Reumatoideo'],

            // INMUNOLÓGICOS
            ['categoria' => 'Inmunológicos', 'nombre' => 'Hepatitis A (HAV Total)'],
            ['categoria' => 'Inmunológicos', 'nombre' => 'Hepatitis B (HBsAg)'],
            ['categoria' => 'Inmunológicos', 'nombre' => 'Hepatitis C (HCV Total)'],
            ['categoria' => 'Inmunológicos', 'nombre' => 'Herpes I (IgG-IgM)'],
            ['categoria' => 'Inmunológicos', 'nombre' => 'Herpes II (IgG-IgM)'],
            ['categoria' => 'Inmunológicos', 'nombre' => 'Rubeola (IgG-IgM)'],
            ['categoria' => 'Inmunológicos', 'nombre' => 'Toxoplasma (IgG-IgM)'],
            ['categoria' => 'Inmunológicos', 'nombre' => 'Citomegalovirus (IgG-IgM)'],
            ['categoria' => 'Inmunológicos', 'nombre' => 'HIV 1-2'],
            ['categoria' => 'Inmunológicos', 'nombre' => 'TORCH IgM'],
            ['categoria' => 'Inmunológicos', 'nombre' => 'TORCH (IgG-IgM)'], // Panel completo
            ['categoria' => 'Inmunológicos', 'nombre' => 'Helicobacter Pylori Anticuerpos'],
            ['categoria' => 'Inmunológicos', 'nombre' => 'Dengue'],

            // HORMONALES
            ['categoria' => 'Hormonales', 'nombre' => 'T3 Total'],
            ['categoria' => 'Hormonales', 'nombre' => 'FT3 Libre'],
            ['categoria' => 'Hormonales', 'nombre' => 'T4 Total'],
            ['categoria' => 'Hormonales', 'nombre' => 'FT4 Libre'],
            ['categoria' => 'Hormonales', 'nombre' => 'TSH'],
            ['categoria' => 'Hormonales', 'nombre' => 'LH (Hormona Luteinizante)'], // Aclarado
            ['categoria' => 'Hormonales', 'nombre' => 'FSH (Folículo Estimulante)'], // Aclarado
            ['categoria' => 'Hormonales', 'nombre' => 'Prolactina'],
            ['categoria' => 'Hormonales', 'nombre' => 'Estradiol'],
            ['categoria' => 'Hormonales', 'nombre' => 'Progesterona'],
            ['categoria' => 'Hormonales', 'nombre' => 'Testosterona'],
            ['categoria' => 'Hormonales', 'nombre' => 'HCG Cuantitativa (Embarazo)'],

            // MARCADORES TUMORALES
            ['categoria' => 'Marcadores Tumorales', 'nombre' => 'PSA Total'],
            ['categoria' => 'Marcadores Tumorales', 'nombre' => 'PSA Libre'],
            ['categoria' => 'Marcadores Tumorales', 'nombre' => 'AFP Alfafetoproteína'],
            ['categoria' => 'Marcadores Tumorales', 'nombre' => 'CEA Carcinoembrión'],
            ['categoria' => 'Marcadores Tumorales', 'nombre' => 'CA 125 (CA Ovárico)'],
            ['categoria' => 'Marcadores Tumorales', 'nombre' => 'CA 15-3 (CA Mamario)'], // Corrección común
            ['categoria' => 'Marcadores Tumorales', 'nombre' => 'CA 19-9 (CA Gástrico/Pancreático)'],
            ['categoria' => 'Marcadores Tumorales', 'nombre' => 'CA 12-4 (CA Gástrico)'], // Menos común, verificado
            ['categoria' => 'Marcadores Tumorales', 'nombre' => 'Tiroglobulina'],

            // MICROBIOLOGÍA
            ['categoria' => 'Microbiología', 'nombre' => 'BAAR-ESPUTO'],
            ['categoria' => 'Microbiología', 'nombre' => 'Coloración de Gram'],
            ['categoria' => 'Microbiología', 'nombre' => 'Sec. Vaginal Fresco'],
            ['categoria' => 'Microbiología', 'nombre' => 'Exudado Faríngeo'],
            ['categoria' => 'Microbiología', 'nombre' => 'Cultivo Secreción Vaginal'],
            ['categoria' => 'Microbiología', 'nombre' => 'Cultivo Secreción'], // Genérico
            ['categoria' => 'Microbiología', 'nombre' => 'Cultivo Lesión Cutánea'],

            // ORINA
            ['categoria' => 'Orina', 'nombre' => 'EMO (Examen Elemental y Microscópico)'],
            ['categoria' => 'Orina', 'nombre' => 'HCG en Orina (Cualitativa)'],
            ['categoria' => 'Orina', 'nombre' => 'Fresco y Gram'],
            ['categoria' => 'Orina', 'nombre' => 'Cultivo y Antibiograma (Urocultivo)'],
            ['categoria' => 'Orina', 'nombre' => 'Proteinuria de 24 horas'],
            ['categoria' => 'Orina', 'nombre' => 'Depuración de Creatinina'],
            ['categoria' => 'Orina', 'nombre' => 'Microalbuminuria'],

            // HECES
            ['categoria' => 'Heces', 'nombre' => 'Coproparasitario'],
            ['categoria' => 'Heces', 'nombre' => 'Seriado 3 días'], // Copro seriado
            ['categoria' => 'Heces', 'nombre' => 'Rotavirus'],
            ['categoria' => 'Heces', 'nombre' => 'Sangre Oculta'],
            ['categoria' => 'Heces', 'nombre' => 'Polimorfonucleares'],
            ['categoria' => 'Heces', 'nombre' => 'Helicobacter Pylori (Heces)'],
            ['categoria' => 'Heces', 'nombre' => 'Cultivo y Antibiograma (Coprocultivo)'],

            // LÍQUIDO SEMINAL
            ['categoria' => 'Líquido Seminal', 'nombre' => 'Espermatograma'],
            ['categoria' => 'Líquido Seminal', 'nombre' => 'Fresco y Gram'],
            ['categoria' => 'Líquido Seminal', 'nombre' => 'Cultivo y Antibiograma'],

            // OTROS
            // Puedes agregar aquí cualquier otro examen específico si es necesario.
        ];

        // Insertar o Actualizar para evitar duplicados
        foreach ($examenes as $examen) {
            Examen::firstOrCreate(['nombre' => $examen['nombre']], $examen);
        }
    }
}