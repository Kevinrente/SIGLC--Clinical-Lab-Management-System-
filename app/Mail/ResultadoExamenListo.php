<?php

namespace App\Mail;

use App\Models\OrdenExamen; // <--- Importante: Importar el Modelo
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Attachment; // <--- Importante: Importar Adjuntos
use Illuminate\Queue\SerializesModels;

class ResultadoExamenListo extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * La orden que contiene la info del paciente y la ruta del PDF.
     * Al ser 'public', Laravel la pasa automáticamente a la vista.
     */
    public $orden;

    /**
     * Create a new message instance.
     */
    public function __construct(OrdenExamen $orden)
    {
        $this->orden = $orden;
    }

    /**
     * Asunto del Correo
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Resultados de Laboratorio Disponibles - SIGLC',
        );
    }

    /**
     * Definición de la Vista (El HTML del correo)
     */
    public function content(): Content
    {
        return new Content(
            // Asegúrate de que este archivo exista en resources/views/emails/resultado_listo.blade.php
            view: 'emails.resultado_listo', 
        );
    }

    /**
     * Adjuntar el PDF
     */
    public function attachments(): array
    {
        // Si la ruta es nula, no adjuntamos nada para evitar error
        if (!$this->orden->ruta_resultado_pdf) {
            return [];
        }

        return [
            Attachment::fromStorage($this->orden->ruta_resultado_pdf)
                ->as('Resultado_Laboratorio_' . $this->orden->id . '.pdf')
                ->withMime('application/pdf'),
        ];
    }
}