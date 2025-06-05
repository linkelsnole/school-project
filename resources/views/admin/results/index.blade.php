@extends('layouts.app')

@section('title', 'Результаты тестов')

@section('content')
<div class="min-h-screen bg-gray-50/50">




    <main class="max-w-7xl mx-auto px-6 py-8">

        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-2xl font-semibold text-gray-900">Результаты тестов</h1>
                <p class="text-sm text-gray-600 mt-1">Управление результатами психологических тестов</p>
            </div>
            <form action="{{ route('logout') }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-red-200 text-red-700 hover:bg-red-50 rounded-md text-sm font-medium transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                    </svg>
                    Выйти
                </button>
            </form>
        </div>


        <div class="rounded-lg border border-gray-200 bg-white shadow-sm mb-8">
            <div class="p-6">
                <form method="GET" action="{{ route('admin.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6" id="searchForm">
                    <div class="space-y-2">
                        <label for="search" class="text-sm font-medium text-gray-700">
                            Поиск по ФИО
                        </label>
                        <input type="text"
                               id="search"
                               name="search"
                               value="{{ request('search') }}"
                               placeholder="Введите ФИО"
                               class="flex h-10 w-full rounded-md border border-gray-200 bg-white px-3 py-2 text-sm ring-offset-white file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-gray-500 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-gray-400 focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50">
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-medium text-gray-700">Тест</label>
                        <div class="relative">
                            <div id="testSelectDropdown"
                                 class="flex h-10 w-full items-center justify-between rounded-md border border-gray-200 bg-white px-3 py-2 text-sm ring-offset-white cursor-pointer hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-offset-2 transition-colors"
                                 onclick="toggleTestDropdown()">
                                <span id="selectedTestText">
                                    @if(request('test_code'))
                                        @foreach($testCodes as $title => $code)
                                            @if($code === request('test_code'))
                                                {{ $title }}
                                                @break
                                            @endif
                                        @endforeach
                                    @else
                                        Все тесты
                                    @endif
                                </span>
                                <svg class="w-4 h-4 text-gray-400 transition-transform duration-200" id="testDropdownIcon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </div>

                            <div id="testDropdownOptions" class="absolute z-10 w-full mt-1 bg-white border border-gray-300 rounded-md shadow-lg hidden">
                                <div class="py-1 max-h-60 overflow-y-auto">
                                    <div class="px-3 py-2 hover:bg-gray-100 hover:text-gray-900 cursor-pointer transition-colors duration-200 {{ !request('test_code') ? 'bg-blue-50 text-blue-700' : 'text-gray-700' }}"
                                         onclick="selectTestOption('', 'Все тесты')">
                                        Все тесты
                                    </div>
                                    @foreach($testCodes as $title => $code)
                                    <div class="px-3 py-2 hover:bg-gray-100 hover:text-gray-900 cursor-pointer transition-colors duration-200 {{ request('test_code') === $code ? 'bg-blue-50 text-blue-700' : 'text-gray-700' }}"
                                         onclick="selectTestOption('{{ $code }}', '{{ $title }}')">
                                        {{ $title }}
                                    </div>
                                    @endforeach
                                </div>
                            </div>

                            <input type="hidden" name="test_code" id="testCodeInput" value="{{ request('test_code') }}">
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label for="date_from" class="text-sm font-medium text-gray-700">
                            Дата от
                        </label>
                        <input type="text"
                               id="date_from"
                               name="date_from"
                               value="{{ request('date_from') }}"
                               placeholder="Выберите дату"
                               class="flex h-10 w-full rounded-md border border-gray-200 bg-white px-3 py-2 text-sm ring-offset-white file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-gray-500 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-gray-400 focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                               readonly>
                    </div>

                    <div class="space-y-2">
                        <label for="date_to" class="text-sm font-medium text-gray-700">
                            Дата до
                        </label>
                        <div class="flex gap-2">
                            <input type="text"
                                   id="date_to"
                                   name="date_to"
                                   value="{{ request('date_to') }}"
                                   placeholder="Выберите дату"
                                   class="flex h-10 w-full rounded-md border border-gray-200 bg-white px-3 py-2 text-sm ring-offset-white file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-gray-500 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-gray-400 focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                                   readonly>
                            <button type="submit"
                                    class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-md text-sm font-medium ring-offset-white transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-gray-400 focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-gray-900 text-white hover:bg-gray-800 h-10 px-6">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                                Найти
                            </button>
                        </div>
                    </div>
                </form>

                <div class="flex gap-3">
                    <a href="{{ route('admin.export', request()->all()) }}"
                       class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-lg text-sm font-medium ring-offset-white transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-gray-400 focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-gray-200 bg-gray-900 hover:bg-gray-800 text-white h-10 px-4 py-2">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Экспорт в CSV
                    </a>
                    <a href="{{ route('admin.tests.index') }}"
                       class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-lg text-sm font-medium ring-offset-white transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-gray-400 focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-gray-200 bg-white hover:bg-gray-50 hover:text-gray-900 h-10 px-4 py-2">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        Настроить тесты
                    </a>
                </div>
            </div>
        </div>


        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="rounded-lg border border-gray-200 bg-white shadow-sm">
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 mb-2">Всего результатов</p>
                            <p class="text-3xl font-bold text-gray-900">{{ $results->total() }}</p>
                            <p class="text-xs text-gray-500 mt-1">за все время</p>
                        </div>
                        <div class="h-12 w-12 bg-gray-100 rounded-lg flex items-center justify-center">
                            <svg class="h-6 w-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <div class="rounded-lg border border-gray-200 bg-white shadow-sm">
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 mb-2">За сегодня</p>
                            <p class="text-3xl font-bold text-gray-900">{{ $results->where('created_at', '>=', today())->count() }}</p>
                            <p class="text-xs text-gray-500 mt-1">новых тестов</p>
                        </div>
                        <div class="h-12 w-12 bg-gray-100 rounded-lg flex items-center justify-center">
                            <svg class="h-6 w-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <div class="rounded-lg border border-gray-200 bg-white shadow-sm">
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 mb-2">Уникальных учеников</p>
                            <p class="text-3xl font-bold text-gray-900">{{ $results->pluck('student_id')->unique()->count() }}</p>
                            <p class="text-xs text-gray-500 mt-1">активных пользователей</p>
                        </div>
                        <div class="h-12 w-12 bg-gray-100 rounded-lg flex items-center justify-center">
                            <img src="{{ asset('icons/school.svg') }}" alt="Уникальных учеников" class="h-6 w-6 text-gray-600">
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="rounded-lg border border-gray-200 bg-white shadow-sm">
            <div class="p-0">
                <div class="border-b border-gray-100">
                    <div class="w-full overflow-auto">
                        <table class="w-full caption-bottom text-sm">
                            <thead class="[&_tr]:border-b">
                                <tr class="border-b border-gray-100 transition-colors hover:bg-gray-50/50 data-[state=selected]:bg-gray-50 bg-gray-50/50">
                                    <th class="h-12 px-4 text-left align-middle font-semibold text-gray-700 [&:has([role=checkbox])]:pr-0 py-4 px-6 text-xs uppercase tracking-wider">
                                        Ученик
                                    </th>
                                    <th class="h-12 px-4 text-left align-middle font-semibold text-gray-700 [&:has([role=checkbox])]:pr-0 py-4 px-6 text-xs uppercase tracking-wider">
                                        Тест
                                    </th>
                                    <th class="h-12 px-4 text-left align-middle font-semibold text-gray-700 [&:has([role=checkbox])]:pr-0 py-4 px-6 text-xs uppercase tracking-wider">
                                        Дата прохождения
                                    </th>
                                    <th class="h-12 px-4 text-center align-middle font-semibold text-gray-700 [&:has([role=checkbox])]:pr-0 pl-1 py-4 px-6 text-xs uppercase tracking-wider">
                                        Статус
                                    </th>
                                    <th class="h-12 px-4 text-center align-middle font-semibold text-gray-700 [&:has([role=checkbox])]:pr-0 py-4 px-6 text-xs uppercase tracking-wider">
                                        Действия
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="[&_tr:last-child]:border-0">
                                @forelse($results as $result)
                                    <tr class="border-b border-gray-100 transition-colors hover:bg-gray-50/50 data-[state=selected]:bg-gray-50">
                                        <td class="p-4 align-middle [&:has([role=checkbox])]:pr-0 px-6 py-4 whitespace-nowrap">
                                            <div>
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ $result->student->fio }}
                                                </div>
                                                <div class="text-sm text-gray-500">
                                                    {{ $result->student->date_of_birth->format('d.m.Y') }}
                                                </div>
                                            </div>
                                        </td>
                                        <td class="p-4 align-middle [&:has([role=checkbox])]:pr-0 px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $result->test_title }}
                                        </td>
                                        <td class="p-4 align-middle [&:has([role=checkbox])]:pr-0 pl-14 px-6 py-4 whitespace-nowrap text-sm text-gray-900 ">
                                            {{ $result->created_at->format('d.m.Y') }}
                                        </td>
                                        <td class="p-4 align-middle [&:has([role=checkbox])]:pr-0 pl-0 px-6 py-4 whitespace-nowrap text-center">
                                            <span class="inline-flex items-center rounded-full px-2 py-1 text-xs font-medium bg-green-100 text-green-800">
                                                Выполнено
                                            </span>
                                        </td>
                                        <td class="p-4 align-middle [&:has([role=checkbox])]:pr-0 px-6 py-4 whitespace-nowrap text-sm font-medium text-center">
                                            <div class="flex items-center justify-between">
                                                <a href="{{ route('admin.results.show', $result->id) }}"
                                                   class="text-gray-600 hover:text-gray-900 transition-colors">
                                                    Подробнее
                                                </a>
                                                <button type="button"
                                                        class="delete-btn inline-flex items-center justify-center w-8 h-8 rounded-md border border-gray-200 text-black hover:bg-gray-50 hover:text-gray-700 transition-colors mr-2"
                                                        data-result-id="{{ $result->id }}"
                                                        data-student-name="{{ $result->student->fio }}"
                                                        title="Удалить результат">
                                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                                        <path d="M5.755,20.283,4,8H20L18.245,20.283A2,2,0,0,1,16.265,22H7.735A2,2,0,0,1,5.755,20.283ZM21,4H16V3a1,1,0,0,0-1-1H9A1,1,0,0,0,8,3V4H3A1,1,0,0,0,3,6H21a1,1,0,0,0,0-2Z"/>
                                                    </svg>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="p-4 align-middle [&:has([role=checkbox])]:pr-0 text-center py-16">
                                            <div class="h-16 w-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                                </svg>
                                            </div>
                                            <h3 class="text-lg font-medium text-gray-900 mb-2">Результаты не найдены</h3>
                                            <p class="text-gray-500 text-sm">Попробуйте изменить параметры поиска или добавить новые тесты</p>
                                            @if(request()->anyFilled(['search', 'test_code', 'date_from', 'date_to']))
                                                <div class="mt-4">
                                                    <a href="{{ route('admin.index') }}" class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                                                        Сбросить фильтры
                                                    </a>
                                                </div>
                                            @endif
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>


                @if($results->hasPages())
                    <div class="flex items-center justify-between px-6 py-4">
                        {{ $results->withQueryString()->links() }}
                    </div>
                @endif
            </div>
        </div>
    </main>
</div>


<div id="deleteModal" class="fixed inset-0 bg-black/50 hidden z-50 flex items-center justify-center p-4 transition-all duration-300 ease-out opacity-0">
    <div id="deleteModalContent" class="bg-white shadow-lg rounded-lg max-w-md w-full transform scale-95 transition-all duration-300 ease-out">
        <div class="p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Подтверждение удаления</h3>
                <button type="button" id="deleteModalClose" class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-md text-sm font-medium ring-offset-white transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-gray-400 focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-gray-200 bg-white hover:bg-gray-50 hover:text-gray-900 h-8 w-8">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div class="mb-6">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mr-4">
                        <svg class="w-6 h-6 text-gray-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.464 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-700 leading-relaxed font-medium">
                            Вы действительно хотите удалить результат теста для ученика <strong class="text-gray-900" id="deleteStudentName"></strong>?
                        </p>
                        <p class="text-xs text-gray-500 mt-2">
                            Это действие необратимо!
                        </p>
                    </div>
                </div>
            </div>
            <div class="flex gap-3 justify-end">
                <button type="button" id="deleteCancel" class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-md text-sm font-medium ring-offset-white transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-gray-400 focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-gray-200 bg-white hover:bg-gray-50 hover:text-gray-900 h-10 px-4 py-2">
                    Отмена
                </button>
                <button type="button" id="deleteConfirm" class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-md text-sm font-medium ring-offset-white transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-gray-400 focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-gray-900 text-white hover:bg-gray-800 h-10 px-4 py-2">
                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M5.755,20.283,4,8H20L18.245,20.283A2,2,0,0,1,16.265,22H7.735A2,2,0,0,1,5.755,20.283ZM21,4H16V3a1,1,0,0,0-1-1H9A1,1,0,0,0,8,3V4H3A1,1,0,0,0,3,6H21a1,1,0,0,0,0-2Z"/>
                    </svg>
                    Удалить
                </button>
            </div>
        </div>
    </div>
</div>


<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

<style>
/* Простые и надежные стили для flatpickr в стиле shadcn UI */
.flatpickr-calendar {
    background: white !important;
    border: 1px solid #e5e7eb !important;
    border-radius: 8px !important;
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05) !important;
    font-family: inherit !important;
    padding: 16px !important;
    z-index: 1001 !important;
    width: 340px !important;
}

.flatpickr-calendar.open {
    z-index: 1001 !important;
}


.flatpickr-months {
    position: relative !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    margin-bottom: 16px !important;
}

.flatpickr-month {
    background: transparent !important;
    color: #111827 !important;
    fill: #111827 !important;
    height: 40px !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    flex: 1 !important;
}

.flatpickr-current-month {
    font-size: 16px !important;
    font-weight: 600 !important;
    color: #111827 !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    gap: 8px !important;
    position: static !important;
    width: auto !important;
    padding: 0 !important;
    border: none !important;
    background: transparent !important;
}

.flatpickr-current-month .cur-month {
    font-weight: 600 !important;
    color: #111827 !important;
    margin: 0 !important;
    padding: 0 !important;
}

.flatpickr-current-month .numInputWrapper {
    width: 60px !important;
    margin-left: 8px !important;
}

.flatpickr-current-month input.cur-year {
    background: white !important;
    border: 1px solid #d1d5db !important;
    border-radius: 6px !important;
    color: #111827 !important;
    font-weight: 600 !important;
    font-size: 16px !important;
    padding: 6px 8px !important;
    text-align: center !important;
    width: 60px !important;
    height: 32px !important;
    line-height: 1 !important;
    box-sizing: border-box !important;
}

.flatpickr-current-month input.cur-year:focus {
    border-color: #3b82f6 !important;
    outline: none !important;
    box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.2) !important;
}

/* Навигационные кнопки */
.flatpickr-prev-month,
.flatpickr-next-month {
    position: absolute !important;
    top: 0 !important;
    height: 40px !important;
    width: 40px !important;
    padding: 0 !important;
    margin: 0 !important;
    border: none !important;
    background: transparent !important;
    color: #6b7280 !important;
    cursor: pointer !important;
    border-radius: 6px !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    transition: all 0.2s !important;
}

.flatpickr-prev-month:hover,
.flatpickr-next-month:hover {
    background: #f3f4f6 !important;
    color: #374151 !important;
}

.flatpickr-prev-month {
    left: 0 !important;
}

.flatpickr-next-month {
    right: 0 !important;
}

.flatpickr-prev-month svg,
.flatpickr-next-month svg {
    width: 16px !important;
    height: 16px !important;
}

/* Дни недели */
.flatpickr-weekdays {
    background: transparent !important;
    border: none !important;
    margin: 0 0 8px 0 !important;
}

.flatpickr-weekday {
    background: transparent !important;
    color: #6b7280 !important;
    font-size: 12px !important;
    font-weight: 500 !important;
    text-align: center !important;
    text-transform: uppercase !important;
    letter-spacing: 0.05em !important;
    padding: 8px 0 !important;
    margin: 0 !important;
    width: 14.28% !important;
    display: inline-block !important;
}

/* Дни */
.flatpickr-days {
    width: 100% !important;
}

.flatpickr-day {
    background: transparent !important;
    border: 1px solid transparent !important;
    border-radius: 6px !important;
    color: #374151 !important;
    cursor: pointer !important;
    font-weight: 400 !important;
    font-size: 14px !important;
    height: 40px !important;
    line-height: 40px !important;
    margin: 1px !important;
    padding: 0 !important;
    text-align: center !important;
    width: 40px !important;
    display: inline-block !important;
    vertical-align: top !important;
    box-sizing: border-box !important;
    transition: all 0.2s !important;
}

.flatpickr-day:hover {
    background: #f3f4f6 !important;
    border-color: #f3f4f6 !important;
}

.flatpickr-day.today {
    border-color: #3b82f6 !important;
    color: #3b82f6 !important;
    font-weight: 600 !important;
}

.flatpickr-day.selected,
.flatpickr-day.selected:hover {
    background: #111827 !important;
    border-color: #111827 !important;
    color: white !important;
    font-weight: 600 !important;
}

.flatpickr-day.prevMonthDay,
.flatpickr-day.nextMonthDay {
    color: #d1d5db !important;
}

.flatpickr-day.flatpickr-disabled {
    color: #d1d5db !important;
    cursor: default !important;
}

.flatpickr-day.flatpickr-disabled:hover {
    background: transparent !important;
    border-color: transparent !important;
}

/* Контейнеры */
.flatpickr-innerContainer {
    display: block !important;
    padding: 0 !important;
    width: 100% !important;
    overflow: visible !important;
}

.flatpickr-rContainer {
    display: inline-block !important;
    padding: 0 !important;
    width: 100% !important;
}

/* Убираем спиннеры для года */
.flatpickr-current-month input[type="number"]::-webkit-outer-spin-button,
.flatpickr-current-month input[type="number"]::-webkit-inner-spin-button {
    -webkit-appearance: none !important;
    margin: 0 !important;
}

.flatpickr-current-month input[type="number"] {
    -moz-appearance: textfield !important;
}

/* Мобильная версия */
@media (max-width: 768px) {
    .flatpickr-calendar {
        width: 300px !important;
        padding: 12px !important;
    }
}
</style>

<script src="https://cdn.jsdelivr.net/npm/flatpickr@4.6.13/dist/flatpickr.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr@4.6.13/dist/l10n/ru.js"></script>

<script>
function toggleTestDropdown() {
    const dropdown = document.getElementById('testDropdownOptions');
    const icon = document.getElementById('testDropdownIcon');

    if (dropdown.classList.contains('hidden')) {
        dropdown.classList.remove('hidden');
        icon.style.transform = 'rotate(180deg)';
    } else {
        dropdown.classList.add('hidden');
        icon.style.transform = 'rotate(0deg)';
    }
}

function selectTestOption(value, text) {
    document.getElementById('selectedTestText').textContent = text;
    document.getElementById('testCodeInput').value = value;
    document.getElementById('testDropdownOptions').classList.add('hidden');
    document.getElementById('testDropdownIcon').style.transform = 'rotate(0deg)';
}


document.addEventListener('click', function(event) {
    const dropdown = document.getElementById('testSelectDropdown');
    const options = document.getElementById('testDropdownOptions');

    if (dropdown && !dropdown.contains(event.target) && !options.contains(event.target)) {
        options.classList.add('hidden');
        document.getElementById('testDropdownIcon').style.transform = 'rotate(0deg)';
    }
});


document.addEventListener('DOMContentLoaded', function() {

    flatpickr("#date_from", {
        locale: "ru",
        dateFormat: "Y-m-d",
        allowInput: true,
        altInput: true,
        altFormat: "d.m.Y",
        maxDate: "today"
    });


    flatpickr("#date_to", {
        locale: "ru",
        dateFormat: "Y-m-d",
        allowInput: true,
        altInput: true,
        altFormat: "d.m.Y",
        maxDate: "today"
    });


    let deleteResultId = null;


    function showDeleteModal(resultId, studentName) {
        deleteResultId = resultId;
        const modal = document.getElementById('deleteModal');
        const modalContent = document.getElementById('deleteModalContent');
        const studentNameEl = document.getElementById('deleteStudentName');

        studentNameEl.textContent = studentName;

        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';

        setTimeout(() => {
            modal.classList.remove('opacity-0');
            modalContent.classList.remove('scale-95');
            modalContent.classList.add('scale-100');
        }, 10);
    }

    function hideDeleteModal() {
        const modal = document.getElementById('deleteModal');
        const modalContent = document.getElementById('deleteModalContent');

        modal.classList.add('opacity-0');
        modalContent.classList.remove('scale-100');
        modalContent.classList.add('scale-95');

        setTimeout(() => {
            modal.classList.add('hidden');
            document.body.style.overflow = '';
            deleteResultId = null;
        }, 300);
    }


    document.querySelectorAll('.delete-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const resultId = this.getAttribute('data-result-id');
            const studentName = this.getAttribute('data-student-name');
            showDeleteModal(resultId, studentName);
        });
    });


    document.getElementById('deleteModalClose').addEventListener('click', hideDeleteModal);
    document.getElementById('deleteCancel').addEventListener('click', hideDeleteModal);


    document.getElementById('deleteConfirm').addEventListener('click', function() {
        if (!deleteResultId) return;

        this.disabled = true;
        this.innerHTML = '<svg class="w-4 h-4 mr-2 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>Удаление...';


        fetch(`/admin/results/${deleteResultId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                hideDeleteModal();

                window.location.reload();
            } else {
                alert('Ошибка при удалении: ' + (data.message || 'Неизвестная ошибка'));
                this.disabled = false;
                this.innerHTML = '<svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 24 24"><path d="M5.755,20.283,4,8H20L18.245,20.283A2,2,0,0,1,16.265,22H7.735A2,2,0,0,1,5.755,20.283ZM21,4H16V3a1,1,0,0,0-1-1H9A1,1,0,0,0,8,3V4H3A1,1,0,0,0,3,6H21a1,1,0,0,0,0-2Z"/></svg>Удалить';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Произошла ошибка при удалении');
            this.disabled = false;
            this.innerHTML = '<svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 24 24"><path d="M5.755,20.283,4,8H20L18.245,20.283A2,2,0,0,1,16.265,22H7.735A2,2,0,0,1,5.755,20.283ZM21,4H16V3a1,1,0,0,0-1-1H9A1,1,0,0,0,8,3V4H3A1,1,0,0,0,3,6H21a1,1,0,0,0,0-2Z"/></svg>Удалить';
        });
    });


    document.getElementById('deleteModal').addEventListener('click', function(e) {
        if (e.target === this) {
            hideDeleteModal();
        }
    });


    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const deleteModal = document.getElementById('deleteModal');
            if (!deleteModal.classList.contains('hidden')) {
                hideDeleteModal();
            }
        }
    });
});
</script>
@endsection
