<?php

namespace App\Services;

use App\Models\OrdenExamen;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AIService
{
    protected $apiKey;
    protected $baseUrl = 'https://api.groq.com/openai/v1/chat/completions';
    protected $model;

    public function __construct()
    {
        $this->apiKey = config('services.groq.api_key');
        $this->model = config('services.groq.model', 'llama-3.1-8b-instant');
    }

    /**
     * LABORATORIO: Botón "Generar Resumen"
     */
    public function generarConclusionTecnica(array $resultados, $datosPaciente)
    {
        if (empty($this->apiKey)) return "Error: API Key no configurada.";

        $textoResultados = "";
        foreach ($resultados as $res) {
            $textoResultados .= "- {$res['examen']}: {$res['valor']} (Ref: {$res['referencia']})\n";
        }

        $prompt = "Actúa como Patólogo Clínico. Analiza:\nPaciente: {$datosPaciente}\nResultados:\n{$textoResultados}\n" .
                  "Genera una conclusión técnica breve y profesional. Menciona patologías. Máximo 3 líneas.";

        return $this->conectarGroq($prompt, 'Experto en análisis clínico.');
    }

    /**
     * LABORATORIO: Análisis automático (Update)
     */
    public function analizarResultados(OrdenExamen $orden)
    {
        if (empty($this->apiKey)) return null;

        $datosPaciente = "{$orden->paciente->sexo}, Edad: " . \Carbon\Carbon::parse($orden->paciente->fecha_nacimiento)->age;
        $textoResultados = "";
        foreach ($orden->examenes as $examen) {
            $pivot = json_decode($examen->pivot->resultado, true);
            $val = is_array($pivot) ? implode(', ', $pivot) : $examen->pivot->resultado;
            $textoResultados .= "- {$examen->nombre}: $val\n";
        }

        $prompt = "Analiza resultados:\n$datosPaciente\n$textoResultados\nGenera resumen clínico corto.";
        return $this->conectarGroq($prompt, 'Bioanalista experto.');
    }

    /**
     * RECEPCIÓN: Leer Orden Médica (OCR)
     */
    public function leerOrdenMedica($imagePath)
    {
        if (empty($this->apiKey)) return null;
        
        try {
            $data = file_get_contents($imagePath);
            $base64 = base64_encode($data);
            $mime = mime_content_type($imagePath);
            $dataUrl = "data:{$mime};base64,{$base64}";
            $nombres = \App\Models\Examen::pluck('nombre')->implode(', ');

            $response = Http::withoutVerifying()
                ->withHeaders(['Authorization' => 'Bearer ' . $this->apiKey])
                ->post($this->baseUrl, [
                    'model' => 'llama-3.2-11b-vision-preview',
                    'messages' => [
                        [
                            'role' => 'user',
                            'content' => [
                                ['type' => 'text', 'text' => "Identifica exámenes. Catálogo: [$nombres]. JSON: {\"examenes\": []}."],
                                ['type' => 'image_url', 'image_url' => ['url' => $dataUrl]]
                            ]
                        ]
                    ],
                    'temperature' => 0.1,
                    'response_format' => ['type' => 'json_object']
                ]);

            return $response->successful() ? $response->json()['choices'][0]['message']['content'] : null;
        } catch (\Exception $e) { return null; }
    }

    /**
     * DOCTOR: Analizar Síntomas (FALTABA ESTE)
     */
    public function analizarSintomas($sintomas)
    {
        if (empty($this->apiKey)) return "Servicio no disponible.";
        $prompt = "Soy doctor. Paciente presenta: $sintomas. Sugiere 3 diagnósticos y exámenes de laboratorio.";
        return $this->conectarGroq($prompt, 'Experto medicina interna.');
    }

    /**
     * DOCTOR: Transcribir Audio (FALTABA ESTE)
     */
    public function transcribirAudio($archivoAudio)
    {
        if (empty($this->apiKey)) return null;
        try {
            $response = Http::withoutVerifying()
                ->withToken($this->apiKey)
                ->attach('file', file_get_contents($archivoAudio), 'audio.webm')
                ->post('https://api.groq.com/openai/v1/audio/transcriptions', [
                    'model' => 'whisper-large-v3',
                    'language' => 'es',
                    'response_format' => 'json'
                ]);
            return $response->successful() ? $response->json()['text'] : null;
        } catch (\Exception $e) { return null; }
    }

    /**
     * Privado: Conexión Genérica
     */
    private function conectarGroq($prompt, $systemRole)
    {
        try {
            $response = Http::withoutVerifying()
                ->withToken($this->apiKey)
                ->post($this->baseUrl, [
                    'model' => $this->model,
                    'messages' => [
                        ['role' => 'system', 'content' => $systemRole],
                        ['role' => 'user', 'content' => $prompt],
                    ],
                    'temperature' => 0.5,
                    'max_tokens' => 400,
                ]);

            return $response->successful() ? ($response->json()['choices'][0]['message']['content'] ?? 'Sin respuesta') : 'Error API';
        } catch (\Exception $e) {
            return 'Error conexión';
        }
    }

    /**
     * CHATBOT PACIENTE: Responde dudas sobre exámenes y medicamentos
     */
    public function chatMedico($pregunta, $contextoPaciente, $catalogoExamenes)
    {
        if (empty($this->apiKey)) return "El sistema de chat no está disponible.";

        // Formateamos el catálogo de exámenes (Nombre y Requisitos)
        // Esto es crucial para que la IA sepa TUS reglas de ayuno, no las de internet.
        $textoCatalogo = "CATÁLOGO Y REQUISITOS DEL LABORATORIO:\n";
        foreach ($catalogoExamenes as $examen) {
            $textoCatalogo .= "- {$examen['nombre']}: {$examen['requisitos']}\n";
        }

        $prompt = "Eres 'Dr. IA', el asistente virtual del Laboratorio SIGLC. Tu tono es profesional, empático y claro.\n\n" .
                  "INFORMACIÓN DEL PACIENTE:\n" .
                  "$contextoPaciente\n\n" .
                  "$textoCatalogo\n\n" .
                  "PREGUNTA DEL PACIENTE: \"$pregunta\"\n\n" .
                  "INSTRUCCIONES:\n" .
                  "1. Si pregunta por un examen, usa la información del CATÁLOGO para decirle los requisitos exactos (ayuno, muestras, etc).\n" .
                  "2. Si pregunta por medicamentos, revisa su HISTORIAL clínico arriba y explica para qué sirve lo que le recetaron.\n" .
                  "3. Si es una pregunta general de salud, responde brevemente pero recomienda visitar al médico.\n" .
                  "4. Respuesta corta (máximo 50 palabras).";

        return $this->conectarGroq($prompt, 'Eres un asistente médico útil y seguro.');
    }
}