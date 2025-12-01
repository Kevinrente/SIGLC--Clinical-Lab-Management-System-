<!DOCTYPE html>
<html>
<head>
    <title>Resultado #{{ $orden->id }}</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; color: #333; }
        .header { width: 100%; border-bottom: 2px solid #0056b3; margin-bottom: 20px; padding-bottom: 10px; }
        .logo { width: 150px; float: left; }
        .company-info { text-align: right; float: right; }
        .patient-info { width: 100%; margin-bottom: 20px; background: #f9f9f9; padding: 10px; }
        .table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        .table th { background: #0056b3; color: white; text-align: left; padding: 8px; }
        .table td { border-bottom: 1px solid #ddd; padding: 6px; }
        .footer { position: fixed; bottom: 0; width: 100%; text-align: center; font-size: 10px; color: #777; border-top: 1px solid #ddd; padding-top: 10px; }
        .page-break { page-break-after: always; }
    </style>
</head>
<body>
    {{-- Encabezado --}}
    <div class="header">
        <div class="company-info">
            <h2>LABORATORIO CLÍNICO SIGLC</h2>
            <p>Dir: Av. Principal y Calle 5ta<br>Tel: 0991234567<br>Email: resultados@siglc.com</p>
        </div>
        <div style="clear: both;"></div>
    </div>

    {{-- Datos del Paciente --}}
    <div class="patient-info">
        <table width="100%">
            <tr>
                <td><strong>Paciente:</strong> {{ $orden->paciente->nombre }} {{ $orden->paciente->apellido }}</td>
                <td><strong>Fecha:</strong> {{ $orden->created_at->format('d/m/Y') }}</td>
            </tr>
            <tr>
                <td><strong>Doctor:</strong> {{ $orden->doctor->usuario->name ?? 'Particular' }}</td>
                <td><strong>Orden No:</strong> {{ str_pad($orden->id, 6, '0', STR_PAD_LEFT) }}</td>
            </tr>
        </table>
    </div>

    {{-- Resultados --}}
    <h3 style="color: #0056b3; text-transform: uppercase;">Informe de Resultados</h3>
    
    <table class="table">
        <thead>
            <tr>
                <th>Examen / Parámetro</th>
                <th>Resultado</th>
                <th>Unidad</th>
                <th>Referencia</th>
            </tr>
        </thead>
        <tbody>
            @foreach($orden->examenes as $examen)
                {{-- Encabezado del Examen --}}
                <tr style="background-color: #eef2f7;">
                    <td colspan="4"><strong>{{ $examen->nombre }}</strong></td>
                </tr>

                {{-- Lógica para mostrar resultados --}}
                @php 
                    $resultadoDB = json_decode($examen->pivot->resultado, true); 
                @endphp

                @if(!empty($examen->campos_dinamicos))
                    {{-- Caso Complejo (Orina, Perfil Lipídico) --}}
                    @foreach($examen->campos_dinamicos as $campo)
                        <tr>
                            <td style="padding-left: 20px;"> - {{ $campo }}</td>
                            <td><strong>{{ $resultadoDB[$campo] ?? '-' }}</strong></td>
                            <td>{{ $examen->unidades ?? '' }}</td>
                            <td>{{ $examen->valor_referencia ?? '' }}</td>
                        </tr>
                    @endforeach
                @else
                    {{-- Caso Simple (Glucosa) --}}
                    <tr>
                        <td style="padding-left: 20px;">Resultado</td>
                        <td><strong>{{ $resultadoDB['valor'] ?? '-' }}</strong></td>
                        <td>{{ $examen->unidades }}</td>
                        <td>{{ $examen->valor_referencia }}</td>
                    </tr>
                @endif
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Este documento es un informe confidencial. Generado automáticamente por SIGLC.
    </div>
</body>
</html>