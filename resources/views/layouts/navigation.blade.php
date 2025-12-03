<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                    </a>
                </div>

                <div class="hidden space-x-4 sm:-my-px sm:ml-4 sm:flex overflow-x-auto whitespace-nowrap scrollbar-hide">
                    
                    {{-- 1. DASHBOARD --}}
                    @if(!Auth::user()->paciente)
                        <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                            {{ __('Dashboard') }}
                        </x-nav-link>
                    @endif

                    {{-- 2. PACIENTE --}}
                    @if(Auth::user()->paciente)
                        <x-nav-link :href="route('pacientes.portal')" :active="request()->routeIs('pacientes.portal')">
                            {{ __('Mis Resultados') }}
                        </x-nav-link>
                        <x-nav-link :href="route('citas.calendario')" :active="request()->routeIs('citas.calendario')">
                            {{ __('Reservar Cita') }}
                        </x-nav-link>
                    @endif

                    {{-- 3. AGENDA (Doctores + Admin) --}}
                    @if(Auth::user()->doctor || Auth::user()->can('gestion.administracion'))
                        <x-nav-link :href="route('citas.calendario')" :active="request()->routeIs('citas.calendario')">
                            {{ __('Agenda') }}
                        </x-nav-link>
                        <x-nav-link :href="route('citas.index')" :active="request()->routeIs('citas.index')">
                            {{ __('Lista Citas') }}
                        </x-nav-link>
                    @endif

                    {{-- 4. PACIENTES --}}
                    @if(Auth::user()->doctor || Auth::user()->can('gestion.administracion') || Auth::user()->can('gestion.laboratorio'))
                        <x-nav-link :href="route('pacientes.index')" :active="request()->routeIs('pacientes.*')">
                            {{ __('Pacientes') }}
                        </x-nav-link>
                    @endif

                    {{-- 5. DOCTORES (SOLO ADMIN) --}}
                    @if(Auth::user()->can('gestion.administracion'))
                        <x-nav-link :href="route('doctors.index')" :active="request()->routeIs('doctors.*')">
                            {{ __('Doctores') }}
                        </x-nav-link>
                    @endif

                    {{-- 6. CONSULTAS --}}
                    @if(Auth::user()->doctor || Auth::user()->can('gestion.administracion'))
                        <x-nav-link :href="route('consultas.index')" :active="request()->routeIs('consultas.*')">
                            {{ __('Consultas') }}
                        </x-nav-link>
                    @endif

                    {{-- 7. LABORATORIO Y GESTIÓN --}}
                    @if(Auth::user()->can('gestion.laboratorio') || Auth::user()->can('gestion.administracion'))
                        <x-nav-link :href="route('laboratorio.index')" :active="request()->routeIs('laboratorio.*')">
                            {{ __('Laboratorio') }}
                        </x-nav-link>
                        <x-nav-link :href="route('examenes.index')" :active="request()->routeIs('examenes.*')">
                            {{ __('Catálogo') }}
                        </x-nav-link>
                        
                        <x-nav-link :href="route('inventario.index')" :active="request()->routeIs('inventario.*')">
                            {{ __('Inventario') }}
                        </x-nav-link>
                    @endif

                    {{-- 8. CAJA Y REPORTES --}}
                    @if(Auth::user()->can('gestion.administracion') || Auth::user()->can('gestion.laboratorio'))
                        <x-nav-link :href="route('caja.index')" :active="request()->routeIs('caja.*')">
                            {{ __('Control Caja') }}
                        </x-nav-link>
                    @endif

                    @if(Auth::user()->can('gestion.administracion'))
                        <x-nav-link :href="route('reportes.index')" :active="request()->routeIs('reportes.*')">
                            {{ __('Reportes') }}
                        </x-nav-link>

                        {{-- 9. CONFIGURACIÓN WEB (CMS) - NUEVO --}}
                        <x-nav-link :href="route('web.edit')" :active="request()->routeIs('web.*')">
                            {{ __('Sitio Web') }}
                        </x-nav-link>
                    @endif

                </div>
            </div>

            {{-- DATOS USUARIO / LOGOUT --}}
            <div class="hidden sm:flex sm:items-center sm:ml-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            <div>
                                {{ Auth::user()->name }}
                                @if(Auth::user()->can('gestion.administracion')) <span class="text-xs text-red-600 font-bold">(Admin)</span> @endif
                                @if(Auth::user()->doctor) <span class="text-xs text-indigo-600">(Dr)</span> @endif
                            </div>

                            <div class="ml-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>
                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">{{ __('Perfil') }}</x-dropdown-link>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">{{ __('Cerrar Sesión') }}</x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            {{-- MENÚ MÓVIL (HAMBURGUESA) --}}
            <div class="-mr-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    {{-- MENÚ MÓVIL (DESPLEGABLE) --}}
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            
            @if(!Auth::user()->paciente)
                <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                    {{ __('Dashboard') }}
                </x-responsive-nav-link>
            @endif

            @if(Auth::user()->paciente)
                <x-responsive-nav-link :href="route('pacientes.portal')" :active="request()->routeIs('pacientes.portal')">
                    {{ __('Mis Resultados') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('citas.calendario')" :active="request()->routeIs('citas.calendario')">
                    {{ __('Reservar Cita') }}
                </x-responsive-nav-link>
            @endif

            @if(Auth::user()->doctor || Auth::user()->can('gestion.administracion'))
                <x-responsive-nav-link :href="route('citas.calendario')" :active="request()->routeIs('citas.calendario')">
                    {{ __('Agenda') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('citas.index')" :active="request()->routeIs('citas.index')">
                    {{ __('Lista Citas') }}
                </x-responsive-nav-link>
            @endif

            @if(Auth::user()->doctor || Auth::user()->can('gestion.administracion') || Auth::user()->can('gestion.laboratorio'))
                <x-responsive-nav-link :href="route('pacientes.index')" :active="request()->routeIs('pacientes.*')">
                    {{ __('Pacientes') }}
                </x-responsive-nav-link>
            @endif

            @if(Auth::user()->can('gestion.administracion'))
                <x-responsive-nav-link :href="route('doctors.index')" :active="request()->routeIs('doctors.*')">
                    {{ __('Doctores') }}
                </x-responsive-nav-link>
            @endif

            @if(Auth::user()->doctor || Auth::user()->can('gestion.administracion'))
                <x-responsive-nav-link :href="route('consultas.index')" :active="request()->routeIs('consultas.*')">
                    {{ __('Consultas') }}
                </x-responsive-nav-link>
            @endif

            @if(Auth::user()->can('gestion.laboratorio') || Auth::user()->can('gestion.administracion'))
                <x-responsive-nav-link :href="route('laboratorio.index')" :active="request()->routeIs('laboratorio.*')">
                    {{ __('Laboratorio') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('examenes.index')" :active="request()->routeIs('examenes.*')">
                    {{ __('Catálogo') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('inventario.index')" :active="request()->routeIs('inventario.*')">
                    {{ __('Inventario') }}
                </x-responsive-nav-link>
            @endif

            @if(Auth::user()->can('gestion.administracion') || Auth::user()->can('gestion.laboratorio'))
                <x-responsive-nav-link :href="route('caja.index')" :active="request()->routeIs('caja.*')">
                    {{ __('Control Caja') }}
                </x-responsive-nav-link>
            @endif

            @if(Auth::user()->can('gestion.administracion'))
                <x-responsive-nav-link :href="route('reportes.index')" :active="request()->routeIs('reportes.*')">
                    {{ __('Reportes') }}
                </x-responsive-nav-link>
                
                {{-- MÓVIL: CONFIGURACIÓN WEB --}}
                <x-responsive-nav-link :href="route('web.edit')" :active="request()->routeIs('web.*')">
                    {{ __('Sitio Web') }}
                </x-responsive-nav-link>
            @endif
        </div>

        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>
            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">{{ __('Perfil') }}</x-responsive-nav-link>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">{{ __('Cerrar Sesión') }}</x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>