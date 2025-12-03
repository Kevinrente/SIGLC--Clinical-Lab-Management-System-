{{-- Fondo blanco (bg-white) y borde derecho (border-r) --}}
<div :class="sidebarOpen ? 'translate-x-0 ease-out' : '-translate-x-full ease-in'" 
     class="fixed z-30 inset-y-0 left-0 w-64 transition duration-300 transform bg-white border-r border-gray-200 overflow-y-auto lg:translate-x-0 lg:static lg:inset-0 shadow-lg lg:shadow-none">
    
    {{-- Cabecera del Sidebar con Logo y Botón Cerrar (Solo móvil) --}}
    <div class="flex items-center justify-between mt-6 px-6">
        <div class="flex items-center gap-2">
            <x-application-logo class="block h-8 w-auto fill-current text-indigo-600" />
            <span class="text-gray-800 text-xl font-bold tracking-wide">SIGLC</span>
        </div>
        
        {{-- Botón X para cerrar en móvil --}}
        <button @click="sidebarOpen = false" class="text-gray-500 hover:text-gray-800 lg:hidden focus:outline-none">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    {{-- Navegación --}}
    <nav class="mt-8 px-4 space-y-2 pb-4">
        
        {{-- DASHBOARD --}}
        @if(!Auth::user()->paciente)
            <x-nav-link-sidebar :href="route('dashboard')" :active="request()->routeIs('dashboard')" icon="home">
                Dashboard
            </x-nav-link-sidebar>
        @endif

        {{-- PACIENTE --}}
        @if(Auth::user()->paciente)
            <p class="px-4 text-xs font-bold text-gray-400 uppercase tracking-wider mt-6 mb-2">Paciente</p>
            <x-nav-link-sidebar :href="route('pacientes.portal')" :active="request()->routeIs('pacientes.portal')" icon="document-text">
                Mis Resultados
            </x-nav-link-sidebar>
            <x-nav-link-sidebar :href="route('citas.calendario')" :active="request()->routeIs('citas.calendario')" icon="calendar">
                Reservar Cita
            </x-nav-link-sidebar>
        @endif

        {{-- GESTIÓN MÉDICA --}}
        @if(Auth::user()->doctor || Auth::user()->can('gestion.administracion'))
            <p class="px-4 text-xs font-bold text-gray-400 uppercase tracking-wider mt-6 mb-2">Gestión Médica</p>
            <x-nav-link-sidebar :href="route('citas.calendario')" :active="request()->routeIs('citas.calendario')" icon="calendar">
                Agenda
            </x-nav-link-sidebar>
            <x-nav-link-sidebar :href="route('citas.index')" :active="request()->routeIs('citas.index')" icon="clock">
                Lista de Citas
            </x-nav-link-sidebar>
            <x-nav-link-sidebar :href="route('consultas.index')" :active="request()->routeIs('consultas.*')" icon="clipboard-list">
                Consultas
            </x-nav-link-sidebar>
        @endif

        {{-- PERSONAS --}}
        @if(Auth::user()->doctor || Auth::user()->can('gestion.administracion') || Auth::user()->can('gestion.laboratorio'))
            <p class="px-4 text-xs font-bold text-gray-400 uppercase tracking-wider mt-6 mb-2">Personas</p>
            <x-nav-link-sidebar :href="route('pacientes.index')" :active="request()->routeIs('pacientes.*')" icon="users">
                Pacientes
            </x-nav-link-sidebar>
        @endif

        @if(Auth::user()->can('gestion.administracion'))
            <x-nav-link-sidebar :href="route('doctors.index')" :active="request()->routeIs('doctors.*')" icon="user-group">
                Doctores
            </x-nav-link-sidebar>
        @endif

        {{-- LABORATORIO --}}
        @if(Auth::user()->can('gestion.laboratorio') || Auth::user()->can('gestion.administracion'))
            <p class="px-4 text-xs font-bold text-gray-400 uppercase tracking-wider mt-6 mb-2">Laboratorio</p>
            <x-nav-link-sidebar :href="route('laboratorio.index')" :active="request()->routeIs('laboratorio.*')" icon="beaker">
                Gestión Órdenes
            </x-nav-link-sidebar>
            <x-nav-link-sidebar :href="route('examenes.index')" :active="request()->routeIs('examenes.*')" icon="book-open">
                Catálogo
            </x-nav-link-sidebar>
            <x-nav-link-sidebar :href="route('inventario.index')" :active="request()->routeIs('inventario.*')" icon="cube">
                Inventario
            </x-nav-link-sidebar>
        @endif

        {{-- ADMINISTRACIÓN --}}
        @if(Auth::user()->can('gestion.administracion') || Auth::user()->can('gestion.laboratorio'))
            <p class="px-4 text-xs font-bold text-gray-400 uppercase tracking-wider mt-6 mb-2">Administración</p>
            <x-nav-link-sidebar :href="route('caja.index')" :active="request()->routeIs('caja.*')" icon="cash">
                Control Caja
            </x-nav-link-sidebar>
        @endif

        @if(Auth::user()->can('gestion.administracion'))
            <x-nav-link-sidebar :href="route('reportes.index')" :active="request()->routeIs('reportes.*')" icon="chart-bar">
                Reportes
            </x-nav-link-sidebar>
            <x-nav-link-sidebar :href="route('web.edit')" :active="request()->routeIs('web.*')" icon="globe-alt">
                Sitio Web
            </x-nav-link-sidebar>
        @endif

        {{-- Botón de Salir (Opcional en Sidebar) --}}
        <div class="pt-6 mt-6 border-t border-gray-200">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="flex items-center px-4 py-2 w-full text-gray-600 hover:bg-red-50 hover:text-red-600 rounded-lg transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    <span class="font-medium">Cerrar Sesión</span>
                </button>
            </form>
        </div>

    </nav>
</div>