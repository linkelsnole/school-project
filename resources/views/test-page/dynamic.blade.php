@extends('layouts.app')

@section('title', $test->title)

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">

        <div class="mb-6">
            <a href="javascript:history.back()"
               class="inline-flex items-center text-gray-600 hover:text-gray-800 font-medium transition-all">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Назад
            </a>
        </div>


        <div class="bg-white rounded-lg shadow-lg p-8 mb-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-4">{{ $test->title }}</h1>
            @if($test->description)
                <p class="text-gray-600 text-lg mb-6">{{ $test->description }}</p>
            @endif

            <div class="bg-gray-50 rounded-lg p-4 mb-6">
                <h3 class="font-semibold text-gray-800 mb-2">Информация о тесте:</h3>
                <div class="text-gray-700">
                    <div>Количество вопросов: {{ $test->questions->count() }}</div>
                    @if($test->categories->count() > 0)
                        <div>Категории: {{ $test->categories->pluck('title')->join(', ') }}</div>
                    @endif
                </div>
            </div>
        </div>

        @if($test->questions->count() === 0)
            <div class="bg-white rounded-lg shadow-lg p-12 text-center">
                <div class="text-6xl mb-4">🚧</div>
                <h3 class="text-xl font-bold text-gray-800 mb-2">Тест в разработке</h3>
                <p class="text-gray-600">К этому тесту еще не добавлены вопросы. Попробуйте позже.</p>
                <a href="javascript:history.back()"
                   class="mt-4 inline-block bg-gray-900 hover:bg-gray-800 text-white px-6 py-3 rounded-lg font-medium transition-all">
                    Назад
                </a>
            </div>
        @else
            <!-- Форма с данными студента -->
            <div class="bg-white rounded-lg shadow-lg p-8 mb-8">
                <h2 class="text-xl font-bold text-gray-800 mb-6">Данные для прохождения теста</h2>

                <form id="studentDataForm" class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="fio" class="block text-sm font-medium text-gray-700 mb-2">
                            ФИО *
                        </label>
                        <input type="text"
                               id="fio"
                               name="fio"
                               required
                               placeholder="Иванов Иван Иванович"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-gray-400">
                    </div>

                    <div>
                        <label for="date_of_birth" class="block text-sm font-medium text-gray-700 mb-2">
                            Дата рождения *
                        </label>
                        <input type="text"
                               id="date_of_birth"
                               name="date_of_birth"
                               required
                               placeholder="ДД.ММ.ГГГГ"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-gray-400"
                               readonly>
                    </div>
                </form>

                <button type="button"
                        id="startTestBtn"
                        class="w-full bg-gray-900 hover:bg-gray-800 text-white py-3 px-6 rounded-lg font-medium transition-all">
                    Начать тест
                </button>
            </div>

            <!-- Форма теста (скрыта изначально) -->
            <div id="testContainer" class="hidden">
                <form id="testForm" class="bg-white rounded-lg shadow-lg p-8">
                    @csrf
                    <input type="hidden" name="test_code" value="{{ $test->code }}">
                    <input type="hidden" name="test_title" value="{{ $test->title }}">
                    <input type="hidden" id="studentFio" name="fio">
                    <input type="hidden" id="studentDateOfBirth" name="date_of_birth">

                    @if($test->categories->count() > 0)
                        {{-- Показываем вопросы по категориям --}}
                        @foreach($test->categories as $categoryIndex => $category)
                            <div class="mb-8">
                                <h3 class="text-xl font-bold text-gray-800 mb-6">{{ $category->title }}</h3>

                                @foreach($category->questions as $questionIndex => $question)
                                    <div class="mb-6 p-6 border border-gray-200 rounded-lg question-container">
                                        <h4 class="font-semibold text-gray-800 mb-4 question-title">
                                            {{ $question->order_index }}. {{ $question->question_text }}
                                        </h4>

                                        @if($question->question_type === 'radio' && $question->options->count() > 0)
                                            <div class="space-y-2">
                                                @foreach($question->options as $option)
                                                    <div class="custom-radio-container" onclick="selectRadioOption(this, '{{ $question->id }}', '{{ $option->option_value }}')">
                                                        <div class="custom-radio"></div>
                                                        <input type="radio"
                                                               name="answers[{{ $question->id }}]"
                                                               value="{{ $option->option_value }}"
                                                               {{ $question->is_required ? 'required' : '' }}>
                                                        <span class="custom-radio-label">{{ $option->option_text }}</span>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @elseif($question->question_type === 'checkbox' && $question->options->count() > 0)
                                            <div class="space-y-2">
                                                @foreach($question->options as $option)
                                                    <div class="custom-checkbox-container" onclick="toggleCheckboxOption(this, '{{ $question->id }}', '{{ $option->option_value }}')">
                                                        <div class="custom-checkbox"></div>
                                                        <input type="checkbox"
                                                               name="answers[{{ $question->id }}][]"
                                                               value="{{ $option->option_value }}">
                                                        <span class="custom-checkbox-label">{{ $option->option_text }}</span>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @elseif($question->question_type === 'scale' && $question->options->count() > 0)
                                            <div class="flex flex-col space-y-4">
                                                <div class="flex justify-between items-center text-sm text-gray-600">
                                                    <span>1 - Совсем не согласен</span>
                                                    <span>5 - Полностью согласен</span>
                                                </div>
                                                <div class="flex justify-center space-x-4">
                                                    @foreach($question->options as $option)
                                                        <div class="custom-scale-container text-center" onclick="selectRadioOption(this, '{{ $question->id }}', '{{ $option->option_value }}')">
                                                            <div class="custom-scale-option">{{ $option->option_text }}</div>
                                                            <input type="radio"
                                                                   name="answers[{{ $question->id }}]"
                                                                   value="{{ $option->option_value }}"
                                                                   {{ $question->is_required ? 'required' : '' }}>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @elseif($question->question_type === 'text')
                                            <textarea name="answers[{{ $question->id }}]"
                                                      rows="3"
                                                      {{ $question->is_required ? 'required' : '' }}
                                                      placeholder="Введите ваш ответ..."
                                                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-gray-400 text-base"></textarea>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @endforeach
                    @else
                        {{-- Показываем вопросы без категорий --}}
                        @foreach($test->questions as $question)
                            <div class="mb-6 p-6 border border-gray-200 rounded-lg question-container">
                                <h4 class="font-semibold text-gray-800 mb-4 question-title">
                                    {{ $question->order_index }}. {{ $question->question_text }}
                                </h4>

                                @if($question->question_type === 'radio' && $question->options->count() > 0)
                                    <div class="space-y-2">
                                        @foreach($question->options as $option)
                                            <div class="custom-radio-container" onclick="selectRadioOption(this, '{{ $question->id }}', '{{ $option->option_value }}')">
                                                <div class="custom-radio"></div>
                                                <input type="radio"
                                                       name="answers[{{ $question->id }}]"
                                                       value="{{ $option->option_value }}"
                                                       {{ $question->is_required ? 'required' : '' }}>
                                                <span class="custom-radio-label">{{ $option->option_text }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                @elseif($question->question_type === 'checkbox' && $question->options->count() > 0)
                                    <div class="space-y-2">
                                        @foreach($question->options as $option)
                                            <div class="custom-checkbox-container" onclick="toggleCheckboxOption(this, '{{ $question->id }}', '{{ $option->option_value }}')">
                                                <div class="custom-checkbox"></div>
                                                <input type="checkbox"
                                                       name="answers[{{ $question->id }}][]"
                                                       value="{{ $option->option_value }}">
                                                <span class="custom-checkbox-label">{{ $option->option_text }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                @elseif($question->question_type === 'scale' && $question->options->count() > 0)
                                    <div class="flex flex-col space-y-4">
                                        <div class="flex justify-between items-center text-sm text-gray-600">
                                            <span>1 - Совсем не согласен</span>
                                            <span>5 - Полностью согласен</span>
                                        </div>
                                        <div class="flex justify-center space-x-4">
                                            @foreach($question->options as $option)
                                                <div class="custom-scale-container text-center" onclick="selectRadioOption(this, '{{ $question->id }}', '{{ $option->option_value }}')">
                                                    <div class="custom-scale-option">{{ $option->option_text }}</div>
                                                    <input type="radio"
                                                           name="answers[{{ $question->id }}]"
                                                           value="{{ $option->option_value }}"
                                                           {{ $question->is_required ? 'required' : '' }}>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @elseif($question->question_type === 'text')
                                    <textarea name="answers[{{ $question->id }}]"
                                              rows="3"
                                              {{ $question->is_required ? 'required' : '' }}
                                              placeholder="Введите ваш ответ..."
                                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-gray-400 text-base"></textarea>
                                @endif
                            </div>
                        @endforeach
                    @endif

                    <div class="mt-8 text-center">
                        <button type="submit"
                                class="bg-gray-900 hover:bg-gray-800 text-white py-3 px-8 rounded-lg font-medium transition-all">
                            Завершить тест
                        </button>
                    </div>
                </form>
            </div>
        @endif
    </div>
</div>

<!-- Модальное окно -->
<div id="customModal" class="fixed inset-0 bg-black/85 hidden z-50 flex items-center justify-center p-4 transition-all duration-300 ease-out opacity-0">
    <div id="customModalContent" class="bg-white shadow-lg rounded-lg max-w-md w-full transform scale-95 transition-all duration-300 ease-out">
        <div class="p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 id="customModalTitle" class="text-lg font-semibold text-gray-900">Уведомление</h3>
                <button type="button" id="customModalClose" class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-md text-sm font-medium ring-offset-white transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-gray-400 focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-gray-200 bg-white hover:bg-gray-50 hover:text-gray-900 h-8 w-8">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div id="customModalMessage" class="text-sm text-gray-600 leading-relaxed mb-6">
                Сообщение
            </div>
            <div class="flex gap-3 justify-end">
                <button type="button" id="customModalOk" class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-md text-sm font-medium ring-offset-white transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-gray-400 focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-gray-900 text-white hover:bg-gray-800 h-10 px-4 py-2">
                    OK
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

/* Заголовок календаря */
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

.flatpickr-monthDropdown-months,
.flatpickr-current-month select.cur-month {
    background: white !important;
    border: 1px solid #d1d5db !important;
    border-radius: 6px !important;
    color: #111827 !important;
    font-weight: 600 !important;
    font-size: 16px !important;
    padding: 6px 12px !important;
    cursor: pointer !important;
    appearance: none !important;
    -webkit-appearance: none !important;
    -moz-appearance: none !important;
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e") !important;
    background-position: right 8px center !important;
    background-repeat: no-repeat !important;
    background-size: 16px 16px !important;
    padding-right: 32px !important;
    min-width: 100px !important;
    margin: 0 !important;
}

.flatpickr-monthDropdown-months:hover,
.flatpickr-current-month select.cur-month:hover {
    border-color: #9ca3af !important;
    background-color: #f9fafb !important;
}

.flatpickr-monthDropdown-months:focus,
.flatpickr-current-month select.cur-month:focus {
    border-color: #3b82f6 !important;
    outline: none !important;
    box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.2) !important;
}

.flatpickr-current-month .numInputWrapper {
    width: 70px !important;
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
    width: 70px !important;
    height: 32px !important;
    line-height: 1 !important;
    box-sizing: border-box !important;
}

.flatpickr-current-month input.cur-year:hover {
    border-color: #9ca3af !important;
    background-color: #f9fafb !important;
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

/* Стили для вопросов и вариантов ответов */
.question-container {
    font-size: 16px;
}

.question-title {
    font-size: 18px !important;
    line-height: 1.4;
    margin-bottom: 20px !important;
}

.answer-option {
    font-size: 16px !important;
    line-height: 1.5;
    margin-bottom: 12px !important;
}

/* Красивые радиобаттоны в стиле shadcn UI */
.custom-radio-container {
    position: relative;
    display: flex;
    align-items: flex-start;
    cursor: pointer;
    padding: 12px;
    border-radius: 8px;
    transition: all 0.2s ease;
    border: 1px solid transparent;
}

.custom-radio-container:hover {
    background-color: #f8f9fa;
    border-color: #e5e7eb;
}

.custom-radio-container.selected {
    background-color: #f8f9fa;
    border-color: #111827;
}

.custom-radio {
    position: relative;
    width: 20px;
    height: 20px;
    border: 2px solid #d1d5db;
    border-radius: 50%;
    margin-right: 12px;
    margin-top: 2px;
    flex-shrink: 0;
    transition: all 0.2s ease;
    background: white;
}

.custom-radio::after {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%) scale(0);
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: #111827;
    transition: transform 0.2s ease;
}

.custom-radio-container:hover .custom-radio {
    border-color: #9ca3af;
}

.custom-radio-container.selected .custom-radio {
    border-color: #111827;
    background: white;
}

.custom-radio-container.selected .custom-radio::after {
    transform: translate(-50%, -50%) scale(1);
}

.custom-radio-label {
    flex: 1;
    font-size: 16px;
    line-height: 1.5;
    color: #374151;
    user-select: none;
}

/* Скрываем оригинальные радиобаттоны */
.custom-radio-container input[type="radio"] {
    position: absolute;
    opacity: 0;
    cursor: pointer;
    height: 0;
    width: 0;
}

/* Стили для чекбоксов */
.custom-checkbox-container {
    position: relative;
    display: flex;
    align-items: flex-start;
    cursor: pointer;
    padding: 12px;
    border-radius: 8px;
    transition: all 0.2s ease;
    border: 1px solid transparent;
    margin-bottom: 12px;
}

.custom-checkbox-container:hover {
    background-color: #f8f9fa;
    border-color: #e5e7eb;
}

.custom-checkbox-container.selected {
    background-color: #f8f9fa;
    border-color: #111827;
}

.custom-checkbox {
    position: relative;
    width: 20px;
    height: 20px;
    border: 2px solid #d1d5db;
    border-radius: 4px;
    margin-right: 12px;
    margin-top: 2px;
    flex-shrink: 0;
    transition: all 0.2s ease;
    background: white;
}

.custom-checkbox::after {
    content: '';
    position: absolute;
    top: 2px;
    left: 6px;
    width: 6px;
    height: 10px;
    border: solid white;
    border-width: 0 2px 2px 0;
    transform: rotate(45deg) scale(0);
    transition: transform 0.2s ease;
}

.custom-checkbox-container:hover .custom-checkbox {
    border-color: #9ca3af;
}

.custom-checkbox-container.selected .custom-checkbox {
    border-color: #111827;
    background: #111827;
}

.custom-checkbox-container.selected .custom-checkbox::after {
    transform: rotate(45deg) scale(1);
}

.custom-checkbox-label {
    flex: 1;
    font-size: 16px;
    line-height: 1.5;
    color: #374151;
    user-select: none;
}

/* Скрываем оригинальные чекбоксы */
.custom-checkbox-container input[type="checkbox"] {
    position: absolute;
    opacity: 0;
    cursor: pointer;
    height: 0;
    width: 0;
}

/* Стили для scale опций */
.scale-option {
    flex-direction: column;
    align-items: center;
    padding: 16px 12px;
    min-width: 60px;
    text-align: center;
}

.scale-option .custom-radio {
    margin: 0 0 8px 0;
}

.scale-option .custom-radio-label {
    font-weight: 600;
    font-size: 14px;
    margin: 0;
}

/* Стили для новых элементов шкалы */
.custom-scale-container {
    cursor: pointer;
    transition: all 0.2s ease;
}

.custom-scale-option {
    width: 48px;
    height: 48px;
    border: 2px solid #d1d5db;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 18px;
    color: #6b7280;
    background: white;
    transition: all 0.2s ease;
    margin-bottom: 8px;
}

.custom-scale-container:hover .custom-scale-option {
    border-color: #9ca3af;
    background: #f9fafb;
}

.custom-scale-container.selected .custom-scale-option {
    border-color: #111827;
    background: #111827;
    color: white;
}

/* Скрываем оригинальные радиобаттоны в шкале */
.custom-scale-container input[type="radio"] {
    position: absolute;
    opacity: 0;
    cursor: pointer;
    height: 0;
    width: 0;
}
</style>

<script src="https://cdn.jsdelivr.net/npm/flatpickr@4.6.13/dist/flatpickr.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr@4.6.13/dist/l10n/ru.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {

    flatpickr("#date_of_birth", {
        locale: "ru",
        dateFormat: "Y-m-d",
        allowInput: true,
        altInput: true,
        altFormat: "d.m.Y",
        maxDate: "today"
    });


    const modal = document.getElementById('customModal');
    const closeBtn = document.getElementById('customModalClose');
    const okBtn = document.getElementById('customModalOk');


    closeBtn.addEventListener('click', hideCustomModal);


    okBtn.addEventListener('click', hideCustomModal);


    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            hideCustomModal();
        }
    });


    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
            hideCustomModal();
        }
    });

    const startTestBtn = document.getElementById('startTestBtn');
    const testContainer = document.getElementById('testContainer');
    const studentDataForm = document.getElementById('studentDataForm');

    if (startTestBtn) {
        startTestBtn.addEventListener('click', function() {
            const fio = document.getElementById('fio').value.trim();
            const dateOfBirth = document.getElementById('date_of_birth').value;

            if (!fio || !dateOfBirth) {
                showCustomModal('Ошибка', 'Пожалуйста, заполните все обязательные поля');
                return;
            }


            document.getElementById('studentFio').value = fio;
            document.getElementById('studentDateOfBirth').value = dateOfBirth;


            studentDataForm.parentElement.style.display = 'none';
            testContainer.classList.remove('hidden');


            testContainer.scrollIntoView({ behavior: 'smooth' });
        });
    }

    const testForm = document.getElementById('testForm');
    if (testForm) {
        testForm.addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);

            fetch('/api/v1/results', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showCustomModal('Успех', 'Тест успешно завершен! Спасибо за участие.', function() {
                        window.location.href = '{{ route("tests.index") }}';
                    });
                } else {
                    showCustomModal('Ошибка', 'Ошибка при сохранении результатов: ' + (data.message || 'Неизвестная ошибка'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showCustomModal('Ошибка', 'Произошла ошибка при отправке теста');
            });
        });
    }
});


function selectRadioOption(container, questionId, value) {

    const allRadioContainers = document.querySelectorAll(`[onclick*="${questionId}"]`);
    allRadioContainers.forEach(c => {
        if (c.classList.contains('custom-radio-container') || c.classList.contains('custom-scale-container')) {
            c.classList.remove('selected');
        }
    });


    container.classList.add('selected');


    const input = container.querySelector('input[type="radio"]');
    if (input) {
        input.checked = true;
    }
}

function toggleCheckboxOption(container, questionId, value) {
    const input = container.querySelector('input[type="checkbox"]');

    if (input) {
        input.checked = !input.checked;

        if (input.checked) {
            container.classList.add('selected');
        } else {
            container.classList.remove('selected');
        }
    }
}


function showCustomModal(title, message, callback = null) {
    const modal = document.getElementById('customModal');
    const modalContent = document.getElementById('customModalContent');
    const modalTitle = document.getElementById('customModalTitle');
    const modalMessage = document.getElementById('customModalMessage');

    modalTitle.textContent = title;
    modalMessage.textContent = message;


    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';


    setTimeout(() => {
        modal.classList.remove('opacity-0');
        modalContent.classList.remove('scale-95');
        modalContent.classList.add('scale-100');
    }, 10);


    modal._callback = callback;
}

function hideCustomModal() {
    const modal = document.getElementById('customModal');
    const modalContent = document.getElementById('customModalContent');


    modal.classList.add('opacity-0');
    modalContent.classList.remove('scale-100');
    modalContent.classList.add('scale-95');

    setTimeout(() => {
        modal.classList.add('hidden');
        document.body.style.overflow = '';


        if (modal._callback && typeof modal._callback === 'function') {
            modal._callback();
            modal._callback = null;
        }
    }, 300);
}
</script>
@endsection
