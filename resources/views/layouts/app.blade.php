<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">


    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">

    <title>@yield('title', 'Рабочая тетрадь') - Молодёжь и дети</title>


    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Kumbh+Sans:wght@100..900&family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        .accent-teal { color: #14b8a6; }
        .bg-accent-teal { background-color: #14b8a6; }
    </style>
</head>
<body class="bg-black min-h-screen flex flex-col" style="overscroll-behavior: none;">
    <header class="bg-black text-white">
        <div class="container mx-auto px-4 py-4">
            <div class="flex items-center justify-between relative">
                <nav class="flex space-x-6 text-xl">
                    <a href="{{ route('notebook.index') }}" class="hover:text-[#CCE2F8] hover:scale-105 transition-all">Методическая тетрадь</a>
                </nav>

                <div class="absolute left-1/2 transform -translate-x-1/2">
                    <div class="bg-white px-6 py-2 rounded">
                        <span class="text-black text-xl font-semibold">МОЛОДЁЖЬ И ДЕТИ</span>
                    </div>
                </div>

                <div class="flex items-center gap-10">
                    <div class="flex flex-col items-center">
                        <div class="flex items-center space-x-4">
                            <div class="flex space-x-4">
                                <a href="https://vk.com/dobrovoleclo" target="_blank" class="text-base hover:text-[#FCC5C6] hover:scale-105 transition-all">VK</a>
                                <a href="#" class="text-base hover:text-[#FCC5C6] hover:scale-105 transition-all">Telegram</a>
                                <a href="#" class="text-base hover:text-[#FCC5C6] hover:scale-105 transition-all">YouTube</a>
                            </div>
                        </div>
                    </div>
                    <div class="relative" id="profile-dropdown">
                            @auth
                                <button class="w-10 h-10 bg-gray-800 hover:bg-gray-700 rounded-full flex items-center justify-center transition-all border-2 border-transparent hover:border-accent-teal" id="profile-button">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                </button>

                                <div class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border hidden z-50" id="dropdown-menu">
                                    <div class="py-1">
                                        <div class="px-4 py-2 text-sm text-gray-500 border-b border-gray-100">
                                            {{ Auth::user()->name ?? 'кто-то' }}
                                        </div>
                                        <a href="{{ route('profile') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                            </svg>
                                            Мой профиль
                                        </a>
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit" class="flex items-center w-full px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                                </svg>
                                                Выйти
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @else
                                <a href="{{ route('login') }}" class="w-10 h-10 bg-accent-teal hover:bg-teal-800 rounded-full flex items-center justify-center transition-all" title="Войти в аккаунт">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                                    </svg>
                                </a>
                            @endauth
                        </div>

                </div>

            </div>
        </div>
    </header>

    <main class="flex-1 bg-gray-50">
        @yield('content')
    </main>

    <footer class="bg-black text-white">
        <div class="container mx-auto px-4 py-8">
            <div class="text-center text-sm">
                <p>&copy; 2024 Национальный проект «Молодёжь и дети». Все права защищены.</p>
            </div>
        </div>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const profileButton = document.getElementById('profile-button');
            const dropdownMenu = document.getElementById('dropdown-menu');

            if (profileButton && dropdownMenu) {
                profileButton.addEventListener('click', function(e) {
                    e.stopPropagation();
                    const isHidden = dropdownMenu.classList.contains('hidden');

                    if (isHidden) {
                        dropdownMenu.classList.remove('hidden');
                    } else {
                        dropdownMenu.classList.add('hidden');
                    }
                });

                document.addEventListener('click', function() {
                    dropdownMenu.classList.add('hidden');
                });

                dropdownMenu.addEventListener('click', function(e) {
                    e.stopPropagation();
                });
            }
        });
    </script>


    <script src="//unpkg.com/alpinejs" defer></script>
</body>
</html>
