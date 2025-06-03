@extends('layouts.app')

@section('title', 'Профтесты')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="text-center max-w-2xl mx-auto">
        <h1 class="text-3xl font-bold text-gray-800 mb-6">Система профессиональных тестов</h1>

        <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-8">
            <h2 class="text-xl font-semibold text-blue-800 mb-3">Для школьников</h2>
            <p class="text-blue-700 mb-4">
                Пройдите психологические тесты в электронной тетради
            </p>
            <a href="{{ route('tests.index') }}"
               class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-xl font-semibold transition-all inline-block">
                Открыть электронную тетрадь
            </a>
        </div>

        <div class="bg-purple-50 border border-purple-200 rounded-lg p-6">
            <h2 class="text-xl font-semibold text-purple-800 mb-3">Для консультантов</h2>
            <p class="text-purple-700 mb-4">
                Войдите в админ-панель для просмотра результатов тестов
            </p>
            <a href="{{ route('login') }}"
               class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-3 rounded-xl font-semibold transition-all inline-block">
                Войти в админ-панель
            </a>
        </div>
    </div>
</div>
@endsection
