@extends('layouts.app')

@section('title', 'Мой профиль')

@section('content')
<div class="container mx-auto px-4 py-8">

    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">
            Добро пожаловать, {{ $user->name ?? 'кто-то' }}!
        </h1>
        <p class="text-gray-600">Ваш личный кабинет на платформе Добро47Jobs</p>
    </div>


    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white p-6 rounded-lg shadow-sm">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mr-4">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Пройдено тестов</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $stats['completed_tests'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-sm">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mr-4">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Завершено</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $stats['finished_tests'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-sm">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mr-4">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Проверено</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $stats['verified_tests'] }}</p>
                </div>
            </div>
        </div>
    </div>


    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">


        <div class="bg-white rounded-lg shadow-sm">
            <div class="p-6 pb-3 border-gray-200">
                <h2 class="text-xl font-bold text-gray-800">Недавние тесты</h2>
            </div>
            <div class="p-6">
                @if($recentTests->isEmpty())
                    <div class="text-center py-8">
                        <p class="text-gray-500 mb-1">Вы пока не прошли ни одного теста.</p>
                        <a href="#available-tests" class="text-blue-600 hover:text-blue-700 font-medium">
                            Начать тестирование
                        </a>
                    </div>
                @else
                    <div class="space-y-4">
                        @foreach($recentTests as $test)
                            <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                                <div>
                                    <h3 class="font-medium text-gray-800">{{ $test->test_title }}</h3>
                                    <p class="text-sm text-gray-500">{{ $test->completed_at->format('d.m.Y') }}</p>
                                    @if($test->score)
                                        <p class="text-sm text-blue-600">Результат: {{ $test->score }}%</p>
                                    @endif
                                </div>
                                <span class="px-3 py-1 text-xs rounded-full
                                    {{ $test->status === 'verified' ? 'bg-purple-100 text-purple-800' : 'bg-green-100 text-green-800' }}">
                                    {{ $test->status === 'verified' ? 'Проверено' : 'Завершен' }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm" id="available-tests">
            <div class="p-6 pb-3 border-gray-200">
                <h2 class="text-xl font-bold text-gray-800">Доступные тесты</h2>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    @foreach($availableTests as $test)
                        <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg hover:border-blue-300 transition-colors">
                            <div>
                                <h3 class="font-medium text-gray-800">{{ $test['title'] }}</h3>
                                <p class="text-sm text-gray-500">{{ $test['category'] }}</p>
                            </div>
                            <a href="{{ $test['url'] }}"
                               class="px-4 py-2 bg-[#DEF2FF] hover:bg-[#3379A7] text-[#3379A7] hover:text-white text-sm font-bold rounded-lg transition-colors">
                                Пройти
                            </a>
                        </div>
                    @endforeach
                </div>

                <div class="mt-6">
                    <a href="#" class="text-blue-600 hover:text-blue-700 font-medium inline-flex items-center">
                        Посмотреть все тесты
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </div>


    <div class="mt-8">
        <div class="bg-white rounded-lg shadow-sm">
            <div class="p-6 pb-3 border-gray-200">
                <h2 class="text-xl font-bold text-gray-800">Рекомендуемые вакансии</h2>
            </div>
            <div class="p-6 pt-0">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @foreach($recommendedJobs as $job)
                        <div class="border border-gray-200 rounded-lg p-4 hover:border-green-300 transition-colors">
                            <h3 class="font-medium text-gray-800 mb-2">{{ $job['title'] }}</h3>
                            <p class="text-sm text-gray-600 mb-1">{{ $job['company'] }}</p>
                            <p class="text-sm font-medium text-green-600 mb-3">{{ $job['salary'] }}</p>
                            <a href="{{ $job['url'] }}"
                               class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                                Подробнее
                            </a>
                        </div>
                    @endforeach
                </div>

                <div class="mt-6">
                    <a href="#" class="text-blue-600 hover:text-blue-700 font-medium inline-flex items-center">
                        Посмотреть все вакансии
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
