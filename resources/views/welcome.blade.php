<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clínica LAMBDA - Laboratorio y Especialidades Médicas</title>
    <!-- Carga de Tailwind CSS (CDN) --><script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>

    <!-- Configuración de color personalizada para Tailwind --><script>
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

    <!-- Header and Navigation --><header class="bg-white shadow-custom sticky top-0 z-50">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-4 flex justify-between items-center">
            
            <!-- Logo/Name --><a href="#inicio" class="text-2xl font-extrabold text-primary hover:text-secondary transition duration-300 flex items-center space-x-1">
                <svg class="w-7 h-7 text-secondary" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm5 11h-4v4h-2v-4H7v-2h4V7h2v4h4v2z"/>
                </svg>
                <span class="tracking-wide">SIGLC</span>
            </a>

            <!-- Desktop Menu --><nav class="hidden md:flex space-x-8 font-medium items-center">
                <a href="#laboratorio" class="text-gray-600 hover:text-primary transition duration-300">Laboratorio</a>
                <a href="#especialidades" class="text-gray-600 hover:text-primary transition duration-300">Especialidades</a>
                <a href="#contacto" class="text-gray-600 hover:text-primary transition duration-300">Contacto</a>
                
                <!-- Botones de Autenticación --><a href="/login" class="bg-primary text-white hover:bg-secondary font-semibold py-2 px-4 rounded-lg transition duration-300 shadow-md">
                    Iniciar Sesión
                </a>
                <a href="/register" class="bg-secondary text-white hover:bg-primary font-semibold py-2 px-4 rounded-lg transition duration-300 shadow-md">
                    Registrarse
                </a>
            </nav>

            <!-- Mobile Menu Button (Hamburger) --><button id="mobile-menu-button" class="md:hidden text-gray-600 focus:outline-none p-2 rounded-md hover:bg-gray-100 transition duration-300">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>
        </div>

        <!-- Mobile Menu (Hidden by default) --><nav id="mobile-menu" class="hidden md:hidden bg-white border-t border-gray-100 py-2">
            <a href="#laboratorio" class="block px-4 py-2 text-gray-600 hover:bg-blue-cloud hover:text-white transition duration-300">Laboratorio</a>
            <a href="#especialidades" class="block px-4 py-2 text-gray-600 hover:bg-blue-cloud hover:text-white transition duration-300">Especialidades</a>
            <a href="#contacto" class="block px-4 py-2 text-gray-600 hover:bg-blue-cloud hover:text-white transition duration-300">Contacto</a>
            <div class="p-4 space-y-2 border-t mt-2">
                <a href="/login" class="block bg-primary text-white hover:bg-secondary transition duration-300 font-semibold rounded-lg text-center py-2">Iniciar Sesión</a>
                <a href="/register" class="block bg-secondary text-white hover:bg-primary transition duration-300 font-semibold rounded-lg text-center py-2">Registrarse</a>
            </div>
        </nav>
    </header>

    <!-- 1. Hero Section (Inicio) --><section id="inicio" class="bg-primary text-white py-24 md:py-40">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <div class="max-w-5xl mx-auto">
                <h1 class="text-4xl md:text-6xl font-extrabold mb-4 leading-tight">
                    Cuidado Experto. Diagnóstico Preciso.
                </h1>
                <p class="text-xl md:text-2xl font-light mb-8 opacity-90">
                    Tu salud es nuestra prioridad. Laboratorio clínico de vanguardia y médicos especialistas en un solo lugar.
                </p>
                <a href="#cita" class="bg-secondary text-white hover:bg-white hover:text-secondary font-bold py-3 px-10 rounded-full shadow-lg transition duration-300 transform hover:scale-105 inline-block border-2 border-secondary">
                    Agenda tu Examen o Cita
                </a>
            </div>
        </div>
    </section>

    <!-- 2. Services Highlights --><section class="py-16 bg-white">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                
                <div class="text-center p-6 rounded-xl shadow-custom border border-gray-100">
                    <span class="text-5xl block mb-4 text-secondary">🔬</span>
                    <h3 class="text-xl font-bold text-primary mb-2">Laboratorio 24 Horas</h3>
                    <p class="text-gray-600">Servicio de emergencia continua para análisis urgentes y toma de muestras.</p>
                </div>
                
                <div class="text-center p-6 rounded-xl shadow-custom border border-gray-100">
                    <span class="text-5xl block mb-4 text-secondary">🩺</span>
                    <h3 class="text-xl font-bold text-primary mb-2">Especialidades Certificadas</h3>
                    <p class="text-gray-600">Doctores internos y consultorio para la atención de patologías complejas.</p>
                </div>

                <div class="text-center p-6 rounded-xl shadow-custom border border-gray-100">
                    <span class="text-5xl block mb-4 text-secondary">💻</span>
                    <h3 class="text-xl font-bold text-primary mb-2">Resultados Digitales</h3>
                    <p class="text-gray-600">Accede a tus resultados clínicos e historial médico de forma segura en línea.</p>
                </div>
                
            </div>
        </div>
    </section>

    <!-- 3. Laboratory Services Section (Detailed List) --><section id="laboratorio" class="py-16 md:py-24 bg-gray-bg">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl md:text-4xl font-bold text-center mb-4 text-primary">
                Catálogo Completo de Exámenes
            </h2>
            <p class="text-center max-w-3xl mx-auto text-lg text-gray-600 mb-12">
                Realizamos un amplio rango de análisis utilizando tecnología avanzada para garantizar resultados exactos y oportunos.
            </p>

            <div class="bg-white p-6 md:p-10 rounded-xl shadow-xl border border-gray-200">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
                    
                    {{-- COLUMNA 1: HEMATOLOGÍA Y QUÍMICA --}}
                    <div>
                        <h3 class="text-2xl font-bold text-secondary mb-4 border-b pb-2">Hematología, Química y Enzimas</h3>
                        
                        <!-- Hematología --><div class="bg-gray-50 p-4 rounded-lg border border-gray-200 mt-4">
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

                    {{-- COLUMNA 2: INMUNOLOGÍA, HORMONAS Y MICROBIOLOGÍA --}}
                    <div>
                        <h3 class="text-2xl font-bold text-secondary mb-4 border-b pb-2">Inmunología, Hormonas y Varios</h3>
                        
                        <!-- Inmunológicos y Serológicos --><div class="bg-gray-50 p-4 rounded-lg border border-gray-200 mt-4">
                            <h4 class="font-semibold text-primary mb-2">INMUNOLÓGICOS Y VIRALES</h4>
                            <ul class="list-disc list-inside space-y-1 text-sm text-gray-600 grid grid-cols-2">
                                <li>Hepatitis B (HBsAg) / Hepatitis C (HCV)</li>
                                <li>HIV 1-2</li>
                                <li>Toxoplasmosis / Rubeola (IgG-IgM)</li>
                                <li>ASTO / PCR</li>
                            </ul>
                            <h4 class="font-semibold text-primary mb-2 mt-4">HORMONALES</h4>
                            <ul class="list-disc list-inside space-y-1 text-sm text-gray-600 grid grid-cols-2">
                                <li>T3 Total / T4 Total / TSH</li>
                                <li>FSH / LH / Prolactina</li>
                                <li>hCG Cuantitativa (Embarazo)</li>
                                <li>Testosterona</li>
                            </ul>
                        </div>

                        <!-- Microbiología --><div class="bg-gray-50 p-4 rounded-lg border border-gray-200 mt-6">
                            <h4 class="font-semibold text-primary mb-2">MICROBIOLOGÍA / URINÁLISIS</h4>
                            <ul class="list-disc list-inside space-y-1 text-sm text-gray-600 grid grid-cols-2">
                                <li>Examen de Orina / Urocultivo</li>
                                <li>Frotis Papanicolau (PAP)</li>
                                <li>Cultivo Secreción Vaginal / Uretral</li>
                                <li>Coprocultivo / Parasitoscópicos</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 4. Specialties Section --><section id="especialidades" class="py-16 md:py-24 bg-white">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl md:text-4xl font-bold text-center mb-12 text-primary">
                Nuestras Especialidades Médicas
            </h2>

            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-6">
                
                {{-- Tarjetas de Especialidad --}}
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
                
                @foreach ($specialties as $s)
                <div class="bg-gray-50 p-6 rounded-xl shadow-md text-center hover:shadow-lg transition duration-300 border border-gray-200 flex flex-col items-center justify-center min-h-[120px]">
                    <span class="text-4xl block mb-2" style="color: {{ $s['color'] }}" role="img" aria-label="{{ $s['name'] }}">
                        {{ $s['icon'] }}
                    </span>
                    <p class="text-base font-semibold text-gray-800">{{ $s['name'] }}</p>
                </div>
                @endforeach
            </div>
            
            <!-- CTA Block --><div id="cita" class="mt-16 text-center bg-blue-cloud bg-opacity-10 border border-blue-cloud p-8 rounded-xl max-w-4xl mx-auto shadow-inner">
                <h3 class="text-2xl font-semibold text-primary mb-4">¡Reserva tu cita con un especialista!</h3>
                <p class="text-lg text-primary mb-6">Contamos con médicos expertos para cada una de estas áreas.</p>
                <a href="/register" class="bg-secondary text-white font-bold py-3 px-8 rounded-full shadow-lg hover:bg-primary transition duration-300 transform hover:scale-105 inline-block">
                    Crear Cuenta y Agendar Ahora
                </a>
            </div>
        </div>
    </section>

    <!-- 5. Contact and Footer --><footer id="contacto" class="bg-primary text-white py-10">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 text-center md:flex md:justify-between md:items-start">
            
            <div class="md:w-1/3 mb-6 md:mb-0 text-left">
                <h4 class="text-2xl font-extrabold mb-3 text-secondary">LAMBDA SIGLC</h4>
                <p class="text-sm">Tu centro de diagnóstico y atención médica de confianza.</p>
            </div>
            
            <div class="md:w-1/3 mb-6 md:mb-0 text-left md:text-center">
                <h4 class="text-xl font-bold mb-3">Ubicación</h4>
                <p class="text-sm">Av. del Pacífico y Gran Colombia</p>
                <p class="text-sm font-light mt-2">Horario: L-V 7:00 AM - 7:00 PM</p>
            </div>

            <div class="md:w-1/3 text-left md:text-right">
                <h4 class="text-xl font-bold mb-3">Contacto Rápido</h4>
                <p class="text-sm">Emergencia 24H: (555) 555-5555</p>
                <a href="mailto:info@siglc.com" class="text-blue-cloud hover:text-white text-sm">info@siglc.com</a>
            </div>
        </div>
        <p class="text-center text-xs mt-8 opacity-70">&copy; 2024 LAMBDA. Todos los derechos reservados.</p>
    </footer>

    <!-- JavaScript para el menú móvil y el formulario de confirmación --><script>
        document.addEventListener('DOMContentLoaded', function() {
            const mobileMenuButton = document.getElementById('mobile-menu-button');
            const mobileMenu = document.getElementById('mobile-menu');
            
            // Función para alternar el menú móvil
            mobileMenuButton.addEventListener('click', function() {
                mobileMenu.classList.toggle('hidden');
            });
            
            // Función para cerrar el menú al hacer clic en un enlace
            const mobileLinks = mobileMenu.querySelectorAll('a');
            mobileLinks.forEach(link => {
                link.addEventListener('click', function() {
                    mobileMenu.classList.add('hidden');
                });
            });
        });
        
        // Función placeholder para el formulario de cita (Redirección a login/registro)
        function handleAppointmentForm(event) {
            event.preventDefault();
            // Redirigir a la página de registro/login para agendar la cita.
            window.location.href = '/register'; 
        }

        // Asignar la función al formulario de cita (si existe un formulario similar al de la referencia)
        const appointmentForm = document.getElementById('appointment-form');
        if (appointmentForm) {
            appointmentForm.addEventListener('submit', handleAppointmentForm);
        }

    </script>

</body>
</html>