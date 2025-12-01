<?php

namespace App\Mail;

use App\Models\Consulta;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Queue\SerializesModels;
use Barryvdh\DomPDF\Facade\Pdf; // Importante para generar el PDF en memoria

class RecetaMedicaMail extends Mailable
{
    use Queueable, SerializesModels;

    public $consulta;

    public function __construct(Consulta $consulta)
    {
        $this->consulta = $consulta;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Su Receta Médica - SIGLC',
        );
    }

    public function content(): Content
    {
        // Usaremos una vista simple para el cuerpo del correo
        return new Content(
            view: 'emails.receta_cuerpo', 
        );
    }

    public function attachments(): array
    {
        // 1. Generamos el PDF en memoria (sin guardarlo en disco para no llenar el servidor)
        $pdf = Pdf::loadView('pdf.receta_medica', ['consulta' => $this->consulta]);

        // 2. Lo adjuntamos al correo
        return [
            Attachment::fromData(fn () => $pdf->output(), 'Receta_' . $this->consulta->id . '.pdf')
                ->withMime('application/pdf'),
        ];
    }
}