<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'SIGLC') }}</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-gray-100">
        
        {{-- CONTENEDOR PRINCIPAL --}}
        <div x-data="{ sidebarOpen: false }" class="flex h-screen bg-gray-100 font-roboto">
            
            {{-- FONDO OSCURO (Backdrop) SOLO PARA MÓVIL --}}
            <div :class="sidebarOpen ? 'block' : 'hidden'" @click="sidebarOpen = false" class="fixed inset-0 z-20 transition-opacity bg-black opacity-50 lg:hidden"></div>

            {{-- 1. SIDEBAR --}}
            @include('layouts.sidebar')

            {{-- 2. CONTENIDO --}}
            <div class="flex-1 flex flex-col overflow-hidden">
                
                <header class="flex justify-between items-center py-4 px-6 bg-white border-b border-gray-200">
                    <div class="flex items-center">
                        {{-- Botón Hamburguesa (Abre el sidebar) --}}
                        <button @click="sidebarOpen = true" class="text-gray-500 focus:outline-none lg:hidden mr-4">
                            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M4 6H20M4 12H20M4 18H11" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </button>

                        {{-- Título --}}
                        <div class="text-xl font-semibold text-gray-800">
                            @if (isset($header)) {{ $header }} @endif
                        </div>
                    </div>

                    {{-- Perfil --}}
                    <div class="flex items-center">
                        <span class="text-sm text-gray-600 mr-2 hidden md:block">{{ Auth::user()->name }}</span>
                        <div class="h-8 w-8 rounded-full bg-indigo-100 border border-indigo-300 flex items-center justify-center text-indigo-700 font-bold overflow-hidden">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </div>
                    </div>
                </header>

                <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50 p-6">
                    {{ $slot }}
                </main>
            </div>
        </div>
    </body>
</html>