<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'SIGLC') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* Fondo muy claro y suave para un ambiente clínico */
        .bg-medical {
            background-color: #f7f9fc; 
        }
        /* Color primario: Azul profesional (para marca y botones de login) */
        .text-brand {
            color: #1a4d95; 
        }
        .bg-brand {
            background-color: #1a4d95; 
        }
        .shadow-clinical {
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        }
        /* Estilo para los botones de autenticación en la cabecera */
        .btn-login {
            background-color: #1a4d95;
            color: white;
        }
        .btn-register {
            border: 1px solid #ccc;
            background-color: #ffffff;
            color: #4a5568;
        }
    </style>
</head>
<body class="antialiased bg-medical">
    <div class="relative min-h-screen flex flex-col items-center justify-center pt-0 sm:pt-0">
        
        <div class="fixed top-0 left-0 right-0 flex justify-end p-6 bg-white border-b border-gray-200 z-10">
            @auth
                <a href="{{ url('/dashboard') }}" class="font-semibold text-gray-700 hover:text-gray-900 transition duration-150">
                    Dashboard
                </a>
            @else
                <a href="{{ route('login') }}" class="font-semibold btn-login py-2 px-4 rounded-lg shadow-md hover:bg-opacity-90 transition duration-150">
                    Iniciar Sesión
                </a>

                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="ml-4 font-semibold btn-register py-2 px-4 rounded-lg hover:bg-gray-100 transition duration-150">
                        Registrarse
                    </a>
                @endif
            @endauth
        </div>
        
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 mt-20">
            <div class="flex flex-col items-center justify-center p-12 bg-white rounded-xl shadow-clinical">
                
                <svg class="w-20 h-20 mb-4 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.5v15m7.5-7.5h-15M12 4.5v15M19.5 12h-15"></path>
                </svg>

                <h1 class="text-5xl font-extrabold text-brand mb-2 tracking-tight">
                    SIGLC
                </h1>
                <p class="text-xl font-light text-gray-700 mb-6">
                    Gestión Integral de Laboratorio y Consulta
                </p>

                <div class="text-center max-w-lg">
                    <p class="text-lg text-gray-600 leading-relaxed mb-4">
                        La plataforma centralizada para la **gestión de citas médicas**, **trazabilidad de muestras** y **acceso seguro a resultados clínicos**. 
                    </p>
                    <p class="text-sm text-red-500 font-semibold mb-6">
                        Cumplimiento de Confidencialidad: Su información médica está protegida y auditada.
                    </p>
                </div>
                
                

                <div class="mt-12 pt-4 border-t border-gray-100 w-full text-center text-xs text-gray-400">
                    Desarrollado con Laravel & PostgreSQL. Confiabilidad Clínica y Tecnológica.
                </div>

            </div>
        </div>
        
    </div>
</body>
</html>