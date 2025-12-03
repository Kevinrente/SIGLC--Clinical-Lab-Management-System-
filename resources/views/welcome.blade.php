<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clínica LAMBDA - Laboratorio y Especialidades Médicas</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'primary': '#194B8F',    // Azul Oscuro
                        'secondary': '#5D9C45',  // Verde (Acento/Éxito)
                        'blue-cloud': '#43A4D9', // Azul Claro (Fondo)
                        'gray-bg': '#f7f9fb',    // Fondo general limpio
                    },
                    boxShadow: {
                        'custom': '0 10px 15px -3px rgba(0, 0, 0, 0.08), 0 4px 6px -2px rgba(0, 0, 0, 0.05)',
                    }
                }
            }
        }
    </script>
</head>
<body class="text-gray-800 bg-gray-bg min-h-screen">

    <header class="bg-white shadow-custom sticky top-0 z-50">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-4 flex justify-between items-center">
            
            <a href="#inicio" class="text-2xl font-extrabold text-primary hover:text-secondary transition duration-300 flex items-center space-x-1">
                <svg class="w-7 h-7 text-secondary" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm5 11h-4v4h-2v-4H7v-2h4V7h2v4h4v2z"/>
                </svg>
                <span class="tracking-wide">SIGLC</span>
            </a>

            <nav class="hidden md:flex space-x-8 font-medium items-center">
                <a href="#laboratorio" class="text-gray-600 hover:text-primary transition duration-300">Laboratorio</a>
                <a href="#especialidades" class="text-gray-600 hover:text-primary transition duration-300">Especialidades</a>
                <a href="#contacto" class="text-gray-600 hover:text-primary transition duration-300">Contacto</a>
                
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}" class="bg-secondary text-white hover:bg-primary font-semibold py-2 px-4 rounded-lg transition duration-300 shadow-md">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="bg-primary text-white hover:bg-secondary font-semibold py-2 px-4 rounded-lg transition duration-300 shadow-md">Iniciar Sesión</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="bg-secondary text-white hover:bg-primary font-semibold py-2 px-4 rounded-lg transition duration-300 shadow-md">Registrarse</a>
                        @endif
                    @endauth
                @endif
            </nav>

            <button id="mobile-menu-button" class="md:hidden text-gray-600 focus:outline-none p-2 rounded-md hover:bg-gray-100 transition duration-300">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>
        </div>

        <nav id="mobile-menu" class="hidden md:hidden bg-white border-t border-gray-100 py-2">
            <a href="#laboratorio" class="block px-4 py-2 text-gray-600 hover:bg-blue-cloud hover:text-white transition duration-300">Laboratorio</a>
            <a href="#especialidades" class="block px-4 py-2 text-gray-600 hover:bg-blue-cloud hover:text-white transition duration-300">Especialidades</a>
            <a href="#contacto" class="block px-4 py-2 text-gray-600 hover:bg-blue-cloud hover:text-white transition duration-300">Contacto</a>
            <div class="p-4 space-y-2 border-t mt-2">
                @auth
                    <a href="{{ url('/dashboard') }}" class="block bg-secondary text-white hover:bg-primary transition duration-300 font-semibold rounded-lg text-center py-2">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="block bg-primary text-white hover:bg-secondary transition duration-300 font-semibold rounded-lg text-center py-2">Iniciar Sesión</a>
                    <a href="{{ route('register') }}" class="block bg-secondary text-white hover:bg-primary transition duration-300 font-semibold rounded-lg text-center py-2">Registrarse</a>
                @endauth
            </div>
        </nav>
    </header>

    <section id="inicio" class="bg-primary text-white py-24 md:py-40">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <div class="max-w-5xl mx-auto">
                {{-- TÍTULO DINÁMICO --}}
                <h1 class="text-4xl md:text-6xl font-extrabold mb-4 leading-tight">
                    {{ $web['hero_titulo'] ?? 'Cuidado Experto. Diagnóstico Preciso.' }}
                </h1>
                
                {{-- DESCRIPCIÓN DINÁMICA --}}
                <p class="text-xl md:text-2xl font-light mb-8 opacity-90">
                    {{ $web['hero_descripcion'] ?? 'Tu salud es nuestra prioridad. Laboratorio clínico de vanguardia y médicos especialistas en un solo lugar.' }}
                </p>
                
                <a href="#cita" class="bg-secondary text-white hover:bg-white hover:text-secondary font-bold py-3 px-10 rounded-full shadow-lg transition duration-300 transform hover:scale-105 inline-block border-2 border-secondary">
                    Agenda tu Examen o Cita
                </a>
            </div>
        </div>
    </section>

    <section class="py-16 bg-white">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                
                {{-- TARJETA 1 --}}
                <div class="text-center p-6 rounded-xl shadow-custom border border-gray-100">
                    <span class="text-5xl block mb-4 text-secondary">🔬</span>
                    <h3 class="text-xl font-bold text-primary mb-2">
                        {{ $web['card_1_titulo'] ?? 'Laboratorio 24 Horas' }}
                    </h3>
                    <p class="text-gray-600">
                        {{ $web['card_1_desc'] ?? 'Servicio de emergencia continua para análisis urgentes y toma de muestras.' }}
                    </p>
                </div>
                
                {{-- TARJETA 2 --}}
                <div class="text-center p-6 rounded-xl shadow-custom border border-gray-100">
                    <span class="text-5xl block mb-4 text-secondary">🩺</span>
                    <h3 class="text-xl font-bold text-primary mb-2">
                        {{ $web['card_2_titulo'] ?? 'Especialidades Certificadas' }}
                    </h3>
                    <p class="text-gray-600">
                        {{ $web['card_2_desc'] ?? 'Doctores internos y consultorio para la atención de patologías complejas.' }}
                    </p>
                </div>

                {{-- TARJETA 3 --}}
                <div class="text-center p-6 rounded-xl shadow-custom border border-gray-100">
                    <span class="text-5xl block mb-4 text-secondary">💻</span>
                    <h3 class="text-xl font-bold text-primary mb-2">
                        {{ $web['card_3_titulo'] ?? 'Resultados Digitales' }}
                    </h3>
                    <p class="text-gray-600">
                        {{ $web['card_3_desc'] ?? 'Accede a tus resultados clínicos e historial médico de forma segura en línea.' }}
                    </p>
                </div>
                
            </div>
        </div>
    </section>

    <section id="laboratorio" class="py-16 md:py-24 bg-gray-bg">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl md:text-4xl font-bold text-center mb-4 text-primary">
                Catálogo Completo de Exámenes
            </h2>
            <p class="text-center max-w-3xl mx-auto text-lg text-gray-600 mb-12">
                Realizamos un amplio rango de análisis utilizando tecnología avanzada para garantizar resultados exactos y oportunos.
            </p>

            <div class="bg-white p-6 md:p-10 rounded-xl shadow-xl border border-gray-200">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
                    {{-- COLUMNA 1 --}}
                    <div>
                        <h3 class="text-2xl font-bold text-secondary mb-4 border-b pb-2">Hematología, Química y Enzimas</h3>
                        <div class="bg-gray-50 p-4 rounded-lg border border-gray-200 mt-4">
                            <h4 class="font-semibold text-primary mb-2">HEMATOLOGÍA</h4>
                            <ul class="list-disc list-inside space-y-1 text-sm text-gray-600 grid grid-cols-2">
                                <li>Biometría Hemática</li>
                                <li>Reticulocitos</li>
                                <li>Plaquetas</li>
                                <li>Grupo y Factor Rh</li>
                            </ul>
                            <h4 class="font-semibold text-primary mb-2 mt-4">QUÍMICA / ENZIMAS</h4>
                            <ul class="list-disc list-inside space-y-1 text-sm text-gray-600 grid grid-cols-2">
                                <li>Glucosa ayunas / Glucosa 2h</li>
                                <li>Hemoglobina Glicosilada</li>
                                <li>Urea / Creatinina</li>
                                <li>Colesterol Total / Triglicéridos</li>
                                <li>TGO - TGP / GGT</li>
                                <li>Amilasa / Lipasa</li>
                            </ul>
                        </div>
                    </div>

                    {{-- COLUMNA 2 --}}
                    <div>
                        <h3 class="text-2xl font-bold text-secondary mb-4 border-b pb-2">Inmunología, Hormonas y Varios</h3>
                        <div class="bg-gray-50 p-4 rounded-lg border border-gray-200 mt-4">
                            <h4 class="font-semibold text-primary mb-2">INMUNOLÓGICOS Y VIRALES</h4>
                            <ul class="list-disc list-inside space-y-1 text-sm text-gray-600 grid grid-cols-2">
                                <li>Hepatitis B (HBsAg) / Hepatitis C (HCV)</li>
                                <li>HIV 1-2</li>
                                <li>Toxoplasmosis / Rubeola</li>
                                <li>ASTO / PCR</li>
                            </ul>
                            <h4 class="font-semibold text-primary mb-2 mt-4">HORMONALES</h4>
                            <ul class="list-disc list-inside space-y-1 text-sm text-gray-600 grid grid-cols-2">
                                <li>T3 Total / T4 Total / TSH</li>
                                <li>FSH / LH / Prolactina</li>
                                <li>hCG Cuantitativa</li>
                                <li>Testosterona</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="especialidades" class="py-16 md:py-24 bg-white">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl md:text-4xl font-bold text-center mb-12 text-primary">
                Nuestras Especialidades Médicas
            </h2>

            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-6">
                @php
                    $specialties = [
                        ['name' => 'Medicina Interna', 'icon' => '🩺', 'color' => 'secondary'],
                        ['name' => 'Ginecología/Obstetricia', 'icon' => '🤰', 'color' => 'blue-cloud'],
                        ['name' => 'Pediatría', 'icon' => '👶', 'color' => 'secondary'],
                        ['name' => 'Ecografía', 'icon' => '🖥️', 'color' => 'blue-cloud'],
                        ['name' => 'Endocrinología', 'icon' => '🧬', 'color' => 'primary'],
                        ['name' => 'Traumatología', 'icon' => '🦴', 'color' => 'secondary'],
                        ['name' => 'Cardiología', 'icon' => '❤️', 'color' => 'primary'],
                        ['name' => 'Nefrología', 'icon' => '🚽', 'color' => 'blue-cloud'],
                    ];
                @endphp
                
                @foreach ($especialidades as $s)
                <div class="bg-gray-50 p-6 rounded-xl shadow-md text-center hover:shadow-lg transition duration-300 border border-gray-200 flex flex-col items-center justify-center min-h-[120px]">
                    {{-- Usamos clases de Tailwind dinámicas para el color --}}
                    <span class="text-4xl block mb-2 {{ $s->color == 'primary' ? 'text-primary' : ($s->color == 'secondary' ? 'text-secondary' : 'text-blue-cloud') }}">
                        {{ $s->icono }}
                    </span>
                    <p class="text-base font-semibold text-gray-800">{{ $s->nombre }}</p>
                </div>
                @endforeach
            </div>
            
            <div id="cita" class="mt-16 text-center bg-blue-cloud bg-opacity-10 border border-blue-cloud p-8 rounded-xl max-w-4xl mx-auto shadow-inner">
                <h3 class="text-2xl font-semibold text-primary mb-4">¡Reserva tu cita con un especialista!</h3>
                <p class="text-lg text-primary mb-6">Contamos con médicos expertos para cada una de estas áreas.</p>
                <a href="{{ route('register') }}" class="bg-secondary text-white font-bold py-3 px-8 rounded-full shadow-lg hover:bg-primary transition duration-300 transform hover:scale-105 inline-block">
                    Crear Cuenta y Agendar Ahora
                </a>
            </div>
        </div>
    </section>

    <footer id="contacto" class="bg-primary text-white py-10">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 text-center md:flex md:justify-between md:items-start">
            
            <div class="md:w-1/3 mb-6 md:mb-0 text-left">
                <h4 class="text-2xl font-extrabold mb-3 text-secondary">LAMBDA SIGLC</h4>
                <p class="text-sm">Tu centro de diagnóstico y atención médica de confianza.</p>
            </div>
            
            <div class="md:w-1/3 mb-6 md:mb-0 text-left md:text-center">
                <h4 class="text-xl font-bold mb-3">Ubicación</h4>
                <p class="text-sm">{{ $web['ubicacion'] ?? 'Av. del Pacífico y Gran Colombia' }}</p>
                <p class="text-sm font-light mt-2">Horario: {{ $web['horario_atencion'] ?? 'L-V 7:00 AM - 7:00 PM' }}</p>
            </div>

            <div class="md:w-1/3 text-left md:text-right">
                <h4 class="text-xl font-bold mb-3">Contacto Rápido</h4>
                <p class="text-sm">Emergencia 24H: {{ $web['telefono_emergencia'] ?? '(555) 555-5555' }}</p>
                <a href="mailto:{{ $web['email_contacto'] ?? 'info@siglc.com' }}" class="text-blue-cloud hover:text-white text-sm">
                    {{ $web['email_contacto'] ?? 'info@siglc.com' }}
                </a>
            </div>
        </div>
        <p class="text-center text-xs mt-8 opacity-70">&copy; {{ date('Y') }} DESARROLLADO POR ING.KEVIN RENTERIA</p>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const mobileMenuButton = document.getElementById('mobile-menu-button');
            const mobileMenu = document.getElementById('mobile-menu');
            
            mobileMenuButton.addEventListener('click', function() {
                mobileMenu.classList.toggle('hidden');
            });
            
            const mobileLinks = mobileMenu.querySelectorAll('a');
            mobileLinks.forEach(link => {
                link.addEventListener('click', function() {
                    mobileMenu.classList.add('hidden');
                });
            });
        });
    </script>

</body>
</html>