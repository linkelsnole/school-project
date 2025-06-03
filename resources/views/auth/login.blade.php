@extends('layouts.app')

@section('title', 'Вход в систему')

@section('content')
<div class="bg-gradient-to-r from-[#C8E4FC] to-[#FFC2C2]">
    <div class="container mx-auto px-4 py-16">
        <div class="text-center">
            <h1 class="text-4xl font-bold text-white mb-4">Вход в систему</h1>
            <p class="text-xl text-white/90">Войдите для сохранения ваших ответов</p>
        </div>
    </div>
</div>

<div class="container mx-auto px-4 py-8">
    <div class="max-w-md mx-auto bg-white rounded-[8px] shadow-lg p-8">
        <form method="POST" action="{{ route('login.post') }}" class="space-y-6">
            @csrf

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                <input type="email"
                       name="email"
                       id="email"
                       value="{{ old('email') }}"
                       class="w-full p-3 border border-gray-300 rounded-[8px] focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                       required>
                @error('email')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Пароль</label>
                <input type="password"
                       name="password"
                       id="password"
                       class="w-full p-3 border border-gray-300 rounded-[8px] focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                       required>
                @error('password')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit"
                    class="w-full bg-black hover:bg-gray-800 text-white px-6 py-3 rounded-[8px] font-medium transition-all">
                Войти
            </button>
        </form>

        <div class="mt-6 text-center">
            <a href="{{ route('notebook.index') }}" class="text-purple-600 hover:text-purple-700 font-medium">
                ← Назад к тетради
            </a>
        </div>
    </div>
</div>
@endsection
