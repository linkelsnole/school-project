@extends('layouts.app')

@section('title', 'Редактирование теста: ' . $test->title)

@section('content')
<style>

    body.modal-open {
        overflow: hidden;
        position: fixed;
        width: 100%;
        height: 100%;
    }

    .modal-scroll {
        max-height: 85vh;
        overflow-y: auto;
    }

    .modal-header-sticky {
        position: sticky;
        top: 0;
        background: white;
        z-index: 10;
        border-bottom: 1px solid #e5e7eb;
    }


    .modal-scroll::-webkit-scrollbar {
        width: 8px;
    }

    .modal-scroll::-webkit-scrollbar-track {
        background: #f1f1f1;
    }

    .modal-scroll::-webkit-scrollbar-thumb {
        background: #c1c1c1;
        border-radius: 4px;
    }

    .modal-scroll::-webkit-scrollbar-thumb:hover {
        background: #a1a1a1;
    }
</style>

<div class="max-w-6xl mx-auto px-4 py-8">

    <div class="mb-8">
        <div class="flex justify-between items-start mb-4">
            <div>
                <h1 class="text-2xl font-semibold text-gray-900">{{ $test->title }}</h1>
                <p class="text-sm text-gray-500 mt-1">Код: {{ $test->code }}</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('admin.tests.index') }}"
                   class="px-4 py-2 text-sm border border-gray-300 rounded text-gray-700 hover:bg-gray-50 transition-colors">
                    ← Назад к списку
                </a>
                @if($test->status === 'active')
                <a href="/tests/{{ $test->code }}" target="_blank"
                   style="background-color: #101828"
                   onmouseover="this.style.backgroundColor='#1f2937'"
                   onmouseout="this.style.backgroundColor='#101828'"
                   class="px-4 py-2 pt-2.25 text-sm text-white rounded transition-colors">
                    Просмотр теста
                </a>
                @endif
            </div>
        </div>

        @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded text-sm mb-6">
            {{ session('success') }}
        </div>
        @endif
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        <div class="lg:col-span-1">
            <div class="bg-white border border-gray-200 rounded-lg">
                <div class="px-6 pt-4  border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Настройки теста</h3>
                </div>
                <div class="p-6">
                    <form action="{{ route('admin.tests.update', $test) }}" method="POST" class="space-y-4">
                        @csrf
                        @method('PUT')


                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Название</label>
                            <input type="text" name="title" value="{{ $test->title }}" required
                                   class="w-full px-3 py-2 text-sm border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>


                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Код (URL)</label>
                            <input type="text" name="code" value="{{ $test->code }}" readonly
                                   class="w-full px-3 py-2 text-sm border border-gray-300 rounded bg-gray-50 text-gray-600 cursor-not-allowed">
                        </div>


                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Описание</label>
                            <textarea name="description" rows="3"
                                      class="w-full px-3 py-2 text-sm border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ $test->description }}</textarea>
                        </div>


                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Статус</label>
                            <select name="status" required
                                    class="w-full px-3 py-2 text-sm border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="active" {{ $test->status === 'active' ? 'selected' : '' }}>Активный</option>
                                <option value="inactive" {{ $test->status === 'inactive' ? 'selected' : '' }}>Неактивный</option>
                            </select>
                        </div>


                        <div class="pt-3 border-t border-gray-100">
                            <button type="submit"
                                    style="background-color: #101828"
                                    onmouseover="this.style.backgroundColor='#1f2937'"
                                    onmouseout="this.style.backgroundColor='#101828'"
                                    class="w-full px-4 py-2 text-sm text-white rounded transition-colors">
                                Сохранить настройки
                            </button>
                        </div>
                    </form>


                    <div class="pt-4 mt-4 border-t border-gray-100">
                        <form action="{{ route('admin.tests.destroy', $test) }}" method="POST"
                              onsubmit="return confirm('Удалить тест? Все вопросы и результаты будут потеряны.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="w-full px-4 py-2 text-sm bg-red-600 text-white rounded hover:bg-red-700 transition-colors">
                                Удалить тест
                            </button>
                        </form>
                    </div>
                </div>
            </div>


            <div class="bg-white border border-gray-200 rounded-lg mt-6">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Статистика</h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 gap-4">
                        <div class="text-center py-4 bg-gray-50 rounded">
                            <div class="text-2xl font-semibold text-gray-900">{{ $test->questions->count() }}</div>
                            <div class="text-sm text-gray-500">Вопросов</div>
                        </div>
                    </div>

                    @if($test->description)
                    <div class="mt-4 pt-4 border-t border-gray-100">
                        <h4 class="font-medium text-gray-900 mb-2">Описание</h4>
                        <p class="text-sm text-gray-600">{{ $test->description }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>


        <div class="lg:col-span-2">
            <div class="bg-white border border-gray-200 rounded-lg">
                <div class="px-6 pt-4 border-gray-200 flex justify-between items-center">
                    <h3 class="text-lg font-medium text-gray-900">Управление вопросами</h3>
                    <div class="flex gap-3">
                        <button onclick="openBulkQuestionBuilder()"
                                style="background-color: #16a34a"
                                onmouseover="this.style.backgroundColor='#15803d'"
                                onmouseout="this.style.backgroundColor='#16a34a'"
                                class="px-4 py-2 text-sm text-white rounded transition-colors">
                            + Несколько вопросов
                        </button>
                        <button onclick="openQuestionBuilder()"
                                style="background-color: #101828"
                                onmouseover="this.style.backgroundColor='#1f2937'"
                                onmouseout="this.style.backgroundColor='#101828'"
                                class="px-4 py-2 text-sm text-white rounded transition-colors">
                            + Создать вопрос
                        </button>
                    </div>
                </div>

                <div class="p-6">
                    @if($test->questions->count() > 0)
                        <div class="space-y-6" id="questionsList">
                            @foreach($test->questions as $question)
                            <div class="bg-white border border-gray-200 rounded-lg p-5 shadow-sm hover:shadow-md transition-shadow" data-question-id="{{ $question->id }}">

                                <div class="flex justify-between items-start mb-4">
                                    <div class="flex-1">
                                        <div class="flex items-center mb-3">
                                            <span class="bg-blue-100 text-blue-800 px-3 py-1  text-sm font-medium">
                                                Вопрос {{ $loop->iteration }}
                                            </span>
                                            <span class="text-sm text-gray-500 bg-gray-100 px-3 py-1">
                                                {{
                                                    $question->question_type === 'radio' ? 'Один вариант' :
                                                    ($question->question_type === 'checkbox' ? 'Множественный выбор' :
                                                    ($question->question_type === 'scale' ? 'Шкала 1-5' : 'Текстовый ответ'))
                                                }}
                                            </span>
                                        </div>


                                        <div class="question-text-container">
                                            <h4 class="font-medium text-gray-900 cursor-pointer hover:bg-blue-50 hover:border-blue-200 hover:rounded-md p-3 -m-3 transition-all border border-transparent edit-question-text"
                                                data-question-id="{{ $question->id }}">
                                                {{ $question->question_text }}
                                            </h4>
                                            <div class="hidden question-edit-form" id="edit-question-{{ $question->id }}">
                                                <form class="save-question-text-form" data-question-id="{{ $question->id }}">
                                                    @csrf
                                                    <textarea class="w-full px-3 py-2 border border-blue-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 mb-3"
                                                              rows="2" required>{{ $question->question_text }}</textarea>
                                                    <div class="flex gap-2">
                                                        <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-md text-sm hover:bg-green-700 transition-colors">
                                                            Сохранить
                                                        </button>
                                                        <button type="button" class="cancel-question-edit px-4 py-2 bg-gray-500 text-white rounded-md text-sm hover:bg-gray-600 transition-colors"
                                                                data-question-id="{{ $question->id }}">
                                                            Отмена
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="flex gap-2 ml-4">
                                        <button class="edit-question-btn flex items-center gap-2 text-gray-600 hover:text-gray-700 text-sm bg-gray-50 px-3 py-2 rounded-md border border-gray-200 hover:bg-gray-100 transition-colors"
                                                data-question-id="{{ $question->id }}">
                                            <img src="{{ asset('icons/settingsIcon.svg') }}" alt="Settings" class="w-4 h-4">

                                        </button>
                                        <form action="{{ route('admin.questions.destroy', $question) }}" method="POST" class="inline"
                                              onsubmit="return confirm('Удалить вопрос? Это действие необратимо.')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="flex items-center gap-2 text-red-600 hover:text-red-700 text-sm bg-red-50 px-3 py-2 rounded-md border border-red-200 hover:bg-red-100 transition-colors">
                                                <img src="{{ asset('icons/delete.svg') }}" alt="Delete" class="w-4 h-4">

                                            </button>
                                        </form>
                                    </div>
                                </div>


                                @if($question->question_type !== 'text' && $question->options && $question->options->count() > 0)
                                <div class="bg-gray-50 rounded-lg  p-4">
                                    <h5 class="text-sm font-medium text-gray-700 mb-3">Варианты ответов:</h5>
                                    @if($question->question_type === 'scale')

                                        <div class="flex flex-wrap gap-2">
                                            @foreach($question->options as $option)
                                            <div class="flex items-center gap-2 bg-white px-3 py-2 rounded-md border border-gray-200">
                                                <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded text-xs font-medium">{{ $option->option_text }}</span>
                                                <span class="text-sm text-gray-700">{{ $option->option_text }}</span>
                                            </div>
                                            @endforeach
                                        </div>
                                    @else

                                        <div class="space-y-2">
                                            @foreach($question->options as $option)
                                            <div class="flex items-center justify-between bg-white px-3 py-2 rounded-md border border-gray-200">
                                                <div class="flex items-center gap-3">
                                                    @if($question->question_type === 'radio')
                                                        <div class="w-4 h-4 border-2 border-gray-400 rounded-full"></div>
                                                    @elseif($question->question_type === 'checkbox')
                                                        <div class="w-4 h-4 border-2 border-gray-400 rounded"></div>
                                                    @endif
                                                    <span class="text-sm text-gray-700">{{ $option->option_text }}</span>
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                                @elseif($question->question_type !== 'text')
                                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3">
                                        <p class="text-sm text-yellow-700">Нет вариантов ответов. Нажмите "Настроить" чтобы добавить.</p>
                                    </div>
                                @endif
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-16">
                            <img src="{{ asset('icons/wrench.svg') }}" alt="Empty Test" class="w-20 h-20 mx-auto mb-6">
                            <h3 class="text-xl font-medium text-gray-900 mb-3">Конструктор пуст</h3>
                            <p class="text-gray-500 mb-8 max-w-md mx-auto">Создайте первый вопрос и настройте варианты ответов для вашего теста</p>
                            <button onclick="openQuestionBuilder()"
                                    style="background-color: #101828"
                                    onmouseover="this.style.backgroundColor='#1f2937'"
                                    onmouseout="this.style.backgroundColor='#101828'"
                                    class="px-8 py-3 text-white rounded-lg font-medium hover:shadow-md transition-all">
                                  Создать первый вопрос
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div id="questionBuilderModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-lg w-full max-w-3xl mx-4 modal-scroll" style="width: 600px; max-width: 95vw;">
            <div class="p-6 border-b border-gray-200 modal-header-sticky">
                <h3 id="modalTitle" class="text-xl font-medium text-gray-900">Конструктор вопросов</h3>
                <p class="text-sm text-gray-500 mt-1">Создайте вопрос и настройте варианты ответов</p>
            </div>

            <div class="p-6">
                <form id="questionBuilderForm" method="POST">
                    @csrf
                    <input type="hidden" id="questionId" name="question_id">
                    <input type="hidden" id="formMethod" name="_method">
                    <div class="space-y-6">

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Текст вопроса *</label>
                            <textarea id="questionTextInput" name="question_text" required rows="3" placeholder="Введите текст вопроса..."
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"></textarea>
                        </div>


                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Тип вопроса *</label>
                            <div class="relative">
                                <div id="questionTypeDropdown"
                                     class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-white cursor-pointer focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                     onclick="toggleDropdown()">
                                    <div class="flex justify-between items-center">
                                        <span id="selectedType">Один вариант ответа</span>
                                        <svg class="w-5 h-5 text-gray-400 transition-transform duration-200" id="dropdownIcon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </div>
                                </div>

                                <div id="dropdownOptions" class="absolute z-10 w-full mt-1 bg-white border border-gray-300 rounded-lg shadow-lg hidden">
                                    <div class="py-2">
                                        <div class="px-4 py-3 hover:bg-blue-50 cursor-pointer transition-colors duration-150"
                                             onclick="selectOption('radio', 'Один вариант ответа')">
                                            <div class="font-medium text-gray-900">Один вариант ответа</div>
                                            <div class="text-sm text-gray-500">Radio buttons - выбор одного варианта</div>
                                        </div>
                                        <div class="px-4 py-3 hover:bg-blue-50 cursor-pointer transition-colors duration-150"
                                             onclick="selectOption('checkbox', 'Множественный выбор')">
                                            <div class="font-medium text-gray-900">Множественный выбор</div>
                                            <div class="text-sm text-gray-500">Checkbox - выбор нескольких вариантов</div>
                                        </div>
                                        <div class="px-4 py-3 hover:bg-blue-50 cursor-pointer transition-colors duration-150"
                                             onclick="selectOption('scale', 'Шкала оценки')">
                                            <div class="font-medium text-gray-900">Шкала оценки</div>
                                            <div class="text-sm text-gray-500">Числовая шкала от 1 до 5</div>
                                        </div>
                                        <div class="px-4 py-3 hover:bg-blue-50 cursor-pointer transition-colors duration-150"
                                             onclick="selectOption('text', 'Текстовый ответ')">
                                            <div class="font-medium text-gray-900">Текстовый ответ</div>
                                            <div class="text-sm text-gray-500">Свободный ввод текста</div>
                                        </div>
                                    </div>
                                </div>

                                <input type="hidden" name="question_type" id="questionTypeInput" value="radio" required>
                            </div>
                        </div>

                        <div id="optionsSection" class="bg-gray-50 rounded-lg p-4">
                            <div class="flex justify-between items-center mb-4">
                                <h4 class="font-medium text-gray-900">Варианты ответов</h4>
                                <button type="button" onclick="addOption()"
                                        class="inline-flex items-center gap-2 px-4 py-2 bg-gray-900 text-white text-sm font-medium rounded-lg hover:bg-gray-800 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                    Добавить вариант
                                </button>
                            </div>

                            <div id="optionsList" class="space-y-3">
                            </div>
                        </div>


                        <div class="flex gap-3 pt-4 border-t border-gray-200">
                            <button type="submit" id="submitButton"
                                    style="background-color: #101828"
                                    onmouseover="this.style.backgroundColor='#1f2937'"
                                    onmouseout="this.style.backgroundColor='#101828'"
                                    class="flex-1 px-6 py-3 text-white rounded-lg font-medium transition-colors">
                                  Создать вопрос
                            </button>
                            <button type="button" onclick="closeQuestionBuilder()"
                                    class="flex-1 px-6 py-3 border-2 border-gray-300 text-gray-700 rounded-lg font-medium hover:bg-gray-50 transition-colors">
                                Отмена
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <div id="bulkQuestionBuilderModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-lg w-full max-w-4xl mx-4 modal-scroll">
            <div class="p-6 pb-3  modal-header-sticky">
                <h3 class="text-xl font-medium text-gray-900">Создание нескольких вопросов</h3>

            </div>

            <div class="p-6" x-data="questionBuilder()">
                <form :action="`{{ route('admin.tests.bulk-add-questions', $test) }}`" method="POST">
                    @csrf

                    <template x-for="(question, qIndex) in questions" :key="qIndex">
                        <div class="border border-gray-200 rounded-lg p-6 mb-6 bg-gray-50">
                            <div class="flex justify-between items-center mb-4">
                                <span class="font-semibold text-gray-900">Вопрос № <span x-text="qIndex + 1"></span></span>
                                <button type="button" @click="removeQuestion(qIndex)"
                                        class="text-red-600 hover:text-red-700 font-bold text-xl px-2 py-1 hover:bg-red-50 rounded transition-colors">×</button>
                            </div>

                            <div class="space-y-4">

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Текст вопроса *</label>
                                    <textarea
                                        x-model="question.text"
                                        :name="`questions[${qIndex}][text]`"
                                        class="w-full border border-gray-300 rounded-lg p-3 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        rows="2"
                                        placeholder="Введите текст вопроса"></textarea>
                                </div>


                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Тип вопроса *</label>
                                    <div class="relative">
                                        <div :id="`questionTypeDropdown-${qIndex}`"
                                             class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-white cursor-pointer focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                             @click="toggleQuestionDropdown(qIndex)">
                                            <div class="flex justify-between items-center">
                                                <span x-text="getTypeLabel(question.type)"></span>
                                                <svg class="w-5 h-5 text-gray-400 transition-transform duration-200"
                                                     :class="question.dropdownOpen ? 'rotate-180' : ''"
                                                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                                </svg>
                                            </div>
                                        </div>

                                        <div x-show="question.dropdownOpen"
                                             @click.away="question.dropdownOpen = false"
                                             x-transition:enter="transition ease-out duration-100"
                                             x-transition:enter-start="transform opacity-0 scale-95"
                                             x-transition:enter-end="transform opacity-100 scale-100"
                                             x-transition:leave="transition ease-in duration-75"
                                             x-transition:leave-start="transform opacity-100 scale-100"
                                             x-transition:leave-end="transform opacity-0 scale-95"
                                             class="absolute z-10 w-full mt-1 bg-white border border-gray-300 rounded-lg shadow-lg">
                                            <div class="py-2">
                                                <div class="px-4 py-3 hover:bg-blue-50 cursor-pointer transition-colors duration-150"
                                                     @click="selectQuestionType(qIndex, 'radio')">
                                                    <div class="font-medium text-gray-900">Один вариант ответа</div>
                                                    <div class="text-sm text-gray-500">Radio buttons - выбор одного варианта</div>
                                                </div>
                                                <div class="px-4 py-3 hover:bg-blue-50 cursor-pointer transition-colors duration-150"
                                                     @click="selectQuestionType(qIndex, 'checkbox')">
                                                    <div class="font-medium text-gray-900">Множественный выбор</div>
                                                    <div class="text-sm text-gray-500">Checkbox - выбор нескольких вариантов</div>
                                                </div>
                                                <div class="px-4 py-3 hover:bg-blue-50 cursor-pointer transition-colors duration-150"
                                                     @click="selectQuestionType(qIndex, 'scale')">
                                                    <div class="font-medium text-gray-900">Шкала оценки</div>
                                                    <div class="text-sm text-gray-500">Числовая шкала от 1 до 5</div>
                                                </div>
                                                <div class="px-4 py-3 hover:bg-blue-50 cursor-pointer transition-colors duration-150"
                                                     @click="selectQuestionType(qIndex, 'text')">
                                                    <div class="font-medium text-gray-900">Текстовый ответ</div>
                                                    <div class="text-sm text-gray-500">Свободный ввод текста</div>
                                                </div>
                                            </div>
                                        </div>

                                        <input type="hidden" :name="`questions[${qIndex}][type]`" x-model="question.type">
                                    </div>
                                </div>


                                <div x-show="question.type !== 'text'">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Варианты ответов</label>
                                    <div class="space-y-3">
                                        <template x-for="(answer, aIndex) in question.answers" :key="aIndex">
                                            <div class="flex items-center gap-3 bg-gray-50 border border-gray-200 rounded-lg p-4">
                                                <input
                                                    x-model="answer.text"
                                                    :name="`questions[${qIndex}][answers][${aIndex}][text]`"
                                                    type="text"
                                                    placeholder="Текст варианта..."
                                                    :readonly="question.type === 'scale'"
                                                    :class="question.type === 'scale' ? 'bg-transparent cursor-not-allowed' : 'bg-transparent'"
                                                    class="flex-1 border-none outline-none text-gray-900 placeholder-gray-500" />

                                                <input
                                                    x-model="answer.weight"
                                                    :name="`questions[${qIndex}][answers][${aIndex}][weight]`"
                                                    type="hidden"
                                                    :value="question.type === 'scale' ? answer.text : answer.weight" />

                                                <button
                                                    x-show="question.type !== 'scale' && question.answers.length > 2"
                                                    type="button"
                                                    @click="removeAnswer(qIndex, aIndex)"
                                                    class="p-2 text-gray-600 hover:text-gray-900 transition-colors">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
                                                </button>
                                            </div>
                                        </template>

                                        <button
                                            x-show="question.type !== 'scale'"
                                            type="button"
                                            @click="addAnswer(qIndex)"
                                            class="inline-flex items-center gap-2 px-4 py-2 bg-gray-900 text-white text-sm font-medium rounded-lg hover:bg-gray-800 transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                            </svg>
                                            Добавить вариант
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>

                    <div class="flex justify-between items-center pt-4 border-t border-gray-200">
                        <button
                            type="button"
                            @click="addQuestion()"
                            class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">+ Добавить новый вопрос</button>

                        <div class="flex gap-3">
                            <button type="submit"
                                    style="background-color: #101828"
                                    onmouseover="this.style.backgroundColor='#1f2937'"
                                    onmouseout="this.style.backgroundColor='#101828'"
                                    class="px-6 py-3 text-white rounded-lg font-medium transition-colors">
                                Сохранить все вопросы
                            </button>
                            <button type="button" onclick="closeBulkQuestionBuilder()"
                                    class="px-6 py-3 border-2 border-gray-300 text-gray-700 rounded-lg font-medium hover:bg-gray-50 transition-colors">
                                Отмена
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
let currentQuestionData = null;


document.addEventListener('DOMContentLoaded', function() {

    document.querySelectorAll('.edit-question-text').forEach(element => {
        element.addEventListener('click', function() {
            const questionId = this.dataset.questionId;
            editQuestionText(questionId);
        });
    });


    document.querySelectorAll('.edit-question-btn').forEach(element => {
        element.addEventListener('click', function() {
            const questionId = this.dataset.questionId;
            editQuestion(questionId);
        });
    });


    document.querySelectorAll('.save-question-text-form').forEach(form => {
        form.addEventListener('submit', function(event) {
            event.preventDefault();
            const questionId = this.dataset.questionId;
            saveQuestionText(event, questionId);
        });
    });


    document.querySelectorAll('.cancel-question-edit').forEach(element => {
        element.addEventListener('click', function() {
            const questionId = this.dataset.questionId;
            cancelQuestionEdit(questionId);
        });
    });
});

function openQuestionBuilder() {
    currentQuestionData = null;
    document.getElementById('questionBuilderModal').classList.remove('hidden');
    document.getElementById('questionBuilderModal').classList.add('flex');


    document.body.classList.add('modal-open');


    document.getElementById('modalTitle').textContent = 'Конструктор вопросов';
    document.getElementById('submitButton').textContent = 'Создать вопрос';
    document.getElementById('questionBuilderForm').action = '{{ route("admin.tests.add-question", $test) }}';
    document.getElementById('formMethod').value = '';
    document.getElementById('questionId').value = '';


    document.getElementById('questionBuilderForm').reset();
    document.getElementById('questionTextInput').value = '';
    document.getElementById('selectedType').textContent = 'Один вариант ответа';
    document.getElementById('questionTypeInput').value = 'radio';

    toggleOptionsSection('radio');
}

function editQuestion(questionId) {

    fetch(`/admin/questions/${questionId}/data`)
        .then(response => response.json())
        .then(data => {
            currentQuestionData = data;

            document.getElementById('questionBuilderModal').classList.remove('hidden');
            document.getElementById('questionBuilderModal').classList.add('flex');


            document.body.classList.add('modal-open');


            document.getElementById('modalTitle').textContent = 'Редактирование вопроса';
            document.getElementById('submitButton').textContent = 'Сохранить изменения';
            document.getElementById('questionBuilderForm').action = `/admin/questions/${questionId}`;
            document.getElementById('formMethod').value = 'PUT';
            document.getElementById('questionId').value = questionId;


            document.getElementById('questionTextInput').value = data.question_text;


            const typeLabels = {
                'radio': 'Один вариант ответа',
                'checkbox': 'Множественный выбор',
                'scale': 'Шкала оценки',
                'text': 'Текстовый ответ'
            };

            document.getElementById('selectedType').textContent = typeLabels[data.question_type];
            document.getElementById('questionTypeInput').value = data.question_type;


            toggleOptionsSection(data.question_type);
            if (data.options && data.options.length > 0) {
                const optionsList = document.getElementById('optionsList');
                optionsList.innerHTML = '';

                data.options.forEach((option, index) => {
                    if (data.question_type === 'scale') {
                        addScaleOption(parseInt(option.option_text));
                    } else {
                        addOption(option.option_text);
                    }
                });
            }
        })
        .catch(error => {
            console.error('Ошибка загрузки данных вопроса:', error);
            alert('Ошибка загрузки данных вопроса');
        });
}

function closeQuestionBuilder() {
    document.getElementById('questionBuilderModal').classList.add('hidden');
    document.getElementById('questionBuilderModal').classList.remove('flex');


    document.body.classList.remove('modal-open');


    document.getElementById('questionBuilderForm').reset();
    document.getElementById('optionsList').innerHTML = '';
    document.getElementById('dropdownOptions').classList.add('hidden');

    currentQuestionData = null;
}

function toggleDropdown() {
    const dropdown = document.getElementById('dropdownOptions');
    const icon = document.getElementById('dropdownIcon');

    if (dropdown.classList.contains('hidden')) {
        dropdown.classList.remove('hidden');
        icon.style.transform = 'rotate(180deg)';
    } else {
        dropdown.classList.add('hidden');
        icon.style.transform = 'rotate(0deg)';
    }
}

function selectOption(value, text) {
    document.getElementById('selectedType').textContent = text;
    document.getElementById('questionTypeInput').value = value;
    document.getElementById('dropdownOptions').classList.add('hidden');
    document.getElementById('dropdownIcon').style.transform = 'rotate(0deg)';

    toggleOptionsSection(value);
}


document.addEventListener('click', function(event) {
    const dropdown = document.getElementById('questionTypeDropdown');
    const options = document.getElementById('dropdownOptions');

    if (dropdown && !dropdown.contains(event.target) && !options.contains(event.target)) {
        options.classList.add('hidden');
        document.getElementById('dropdownIcon').style.transform = 'rotate(0deg)';
    }
});

function toggleOptionsSection(questionType) {
    const optionsSection = document.getElementById('optionsSection');
    const optionsList = document.getElementById('optionsList');


    if (!currentQuestionData) {
        optionsList.innerHTML = '';
    }

    if (questionType === 'text') {
        optionsSection.style.display = 'none';
    } else {
        optionsSection.style.display = 'block';


        if (!currentQuestionData) {
            if (questionType === 'scale') {
                for (let i = 1; i <= 5; i++) {
                    addScaleOption(i);
                }
            } else {
                addOption('Да');
                addOption('Нет');
            }
        }
    }
}

function addOption(defaultText = '') {
    const optionsList = document.getElementById('optionsList');
    const optionIndex = optionsList.children.length;

    const optionHtml = `
        <div class="flex items-center gap-3 option-item bg-gray-50 border border-gray-200 rounded-lg p-2">
            <input type="text" name="options[${optionIndex}][text]" value="${defaultText}" placeholder="Текст варианта..."
                   class="flex-1 bg-transparent border-none outline-none text-gray-900 placeholder-gray-500">
            <button type="button" onclick="removeOption(this)"
                    class="p-2 text-gray-600 hover:text-gray-900 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                </svg>
            </button>
        </div>
    `;

    optionsList.insertAdjacentHTML('beforeend', optionHtml);
}

function addScaleOption(value) {
    const optionsList = document.getElementById('optionsList');

    const optionHtml = `
        <div class="flex gap-3 items-center option-item">
            <input type="text" name="options[${value-1}][text]" value="${value}" readonly
                   class="w-16 px-3 py-2 border border-gray-300 rounded bg-gray-100 text-center">
            <span class="flex-1 text-sm text-gray-600">Балл ${value}</span>
        </div>
    `;

    optionsList.insertAdjacentHTML('beforeend', optionHtml);
}

function removeOption(button) {
    button.closest('.option-item').remove();
}


function editQuestionText(questionId) {
    const container = document.querySelector(`[data-question-id="${questionId}"] .question-text-container`);
    const textDisplay = container.querySelector('h4');
    const editForm = container.querySelector('.question-edit-form');

    textDisplay.style.display = 'none';
    editForm.classList.remove('hidden');
    editForm.querySelector('textarea').focus();
}

function cancelQuestionEdit(questionId) {
    const container = document.querySelector(`[data-question-id="${questionId}"] .question-text-container`);
    const textDisplay = container.querySelector('h4');
    const editForm = container.querySelector('.question-edit-form');

    textDisplay.style.display = 'block';
    editForm.classList.add('hidden');
}

async function saveQuestionText(event, questionId) {
    event.preventDefault();
    const form = event.target;
    const textarea = form.querySelector('textarea');
    const newText = textarea.value.trim();

    if (!newText) return;

    try {
        const response = await fetch(`/admin/questions/${questionId}/update-text`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ question_text: newText })
        });

        if (response.ok) {
            const container = document.querySelector(`[data-question-id="${questionId}"] .question-text-container`);
            const textDisplay = container.querySelector('h4');
            textDisplay.textContent = newText;
            cancelQuestionEdit(questionId);
        }
    } catch (error) {
        alert('Ошибка при сохранении. Попробуйте еще раз.');
    }
}


document.getElementById('questionBuilderModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeQuestionBuilder();
    }
});


function openBulkQuestionBuilder() {
    document.getElementById('bulkQuestionBuilderModal').classList.remove('hidden');
    document.getElementById('bulkQuestionBuilderModal').classList.add('flex');


    document.body.classList.add('modal-open');
}

function closeBulkQuestionBuilder() {
    document.getElementById('bulkQuestionBuilderModal').classList.add('hidden');
    document.getElementById('bulkQuestionBuilderModal').classList.remove('flex');


    document.body.classList.remove('modal-open');
}


function questionBuilder() {
    return {
        questions: [
            {
                text: '',
                type: 'radio',
                dropdownOpen: false,
                answers: [
                    { text: 'Да', weight: 1 },
                    { text: 'Нет', weight: 0 }
                ]
            }
        ],

        addQuestion() {
            this.questions.push({
                text: '',
                type: 'radio',
                dropdownOpen: false,
                answers: [
                    { text: 'Да', weight: 1 },
                    { text: 'Нет', weight: 0 }
                ]
            });
        },

        removeQuestion(index) {
            if (this.questions.length > 1) {
                this.questions.splice(index, 1);
            }
        },

        addAnswer(qIndex) {
            this.questions[qIndex].answers.push({ text: '', weight: 0 });
        },

        removeAnswer(qIndex, aIndex) {
            if (this.questions[qIndex].answers.length > 2) {
                this.questions[qIndex].answers.splice(aIndex, 1);
            }
        },

        updateAnswersForType(qIndex, type) {
            if (type === 'scale') {

                this.questions[qIndex].answers = [
                    { text: '1', weight: 1 },
                    { text: '2', weight: 2 },
                    { text: '3', weight: 3 },
                    { text: '4', weight: 4 },
                    { text: '5', weight: 5 }
                ];
            } else if (this.questions[qIndex].answers.length === 5 && type !== 'scale') {

                this.questions[qIndex].answers = [
                    { text: 'Да', weight: 1 },
                    { text: 'Нет', weight: 0 }
                ];
            }
        },

        toggleQuestionDropdown(qIndex) {

            this.questions.forEach((q, index) => {
                if (index !== qIndex) {
                    q.dropdownOpen = false;
                }
            });
            this.questions[qIndex].dropdownOpen = !this.questions[qIndex].dropdownOpen;
        },

        selectQuestionType(qIndex, type) {
            this.questions[qIndex].type = type;
            this.questions[qIndex].dropdownOpen = false;
            this.updateAnswersForType(qIndex, type);
        },

        getTypeLabel(type) {
            const typeLabels = {
                'radio': 'Один вариант ответа',
                'checkbox': 'Множественный выбор',
                'scale': 'Шкала оценки',
                'text': 'Текстовый ответ'
            };
            return typeLabels[type] || 'Неизвестный тип';
        }
    }
}


document.getElementById('bulkQuestionBuilderModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeBulkQuestionBuilder();
    }
});


document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        const questionModal = document.getElementById('questionBuilderModal');
        const bulkModal = document.getElementById('bulkQuestionBuilderModal');

        if (!questionModal.classList.contains('hidden')) {
            closeQuestionBuilder();
        } else if (!bulkModal.classList.contains('hidden')) {
            closeBulkQuestionBuilder();
        }
    }
});
</script>
@endsection
