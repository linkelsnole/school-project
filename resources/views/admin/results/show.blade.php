@extends('layouts.app')

@section('title', 'Результат теста: ' . $result->student->fio)

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <div class="mb-6">
            <a href="{{ route('admin.index') }}"
               class="text-gray-900 hover:text-gray-800 font-medium transition-colors">
                ← Назад к списку результатов
            </a>
        </div>

        <!-- Информация об ученике -->
        <div class="bg-white rounded-lg shadow-lg p-6 mb-6">


            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-3">Информация об ученике</h3>
                    <div class="space-y-2">
                        <div>
                            <span class="text-sm text-gray-600">ФИО:</span>
                            <span class="ml-2 font-medium">{{ $result->student->fio }}</span>
                        </div>
                        <div>
                            <span class="text-sm text-gray-600">Дата рождения:</span>
                            <span class="ml-2 font-medium">{{ $result->student->date_of_birth->format('d.m.Y') }}</span>
                        </div>
                    </div>
                </div>

                <div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-3">Информация о тесте</h3>
                    <div class="space-y-2">
                        <div>
                            <span class="text-sm text-gray-600">Название теста:</span>
                            <span class="ml-2 font-medium">{{ $result->test_title }}</span>
                        </div>
                        <div>
                            <span class="text-sm text-gray-600">Код теста:</span>
                            <code class="ml-2 bg-gray-100 px-2 py-1 rounded text-sm">{{ $result->test_code }}</code>
                        </div>
                        <div>
                            <span class="text-sm text-gray-600">Дата прохождения:</span>
                            <span class="ml-2 font-medium">{{ $result->created_at->format('d.m.Y H:i') }}</span>
                        </div>
                        <div>
                            <span class="text-sm text-gray-600">Статус:</span>
                            <span class="ml-2 px-2 py-1 text-xs font-medium bg-gray-100 text-gray-900 rounded-full">
                                Выполнено
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <!-- Ответы ученика -->
        <div class="bg-white rounded-lg shadow-lg p-8 mb-8">
            <h3 class="text-2xl font-bold text-gray-800 mb-8">Ответы ученика</h3>

            @if($result->answers && count($result->answers) > 0)
                <div class="space-y-8">
                    @php
                        $questionNumber = 1;

                        $maxScale = 5; // по умолчанию 5
                        $maxAnswerValue = 0;
                        foreach ($result->answers as $answer) {
                            if (is_numeric($answer) && $answer > $maxAnswerValue) {
                                $maxAnswerValue = $answer;
                            }
                        }
                        if ($maxAnswerValue > 5) {
                            $maxScale = 10;
                        }
                    @endphp
                    @foreach($result->answers as $questionId => $answer)
                        <div class="border border-gray-200 rounded-xl p-6 bg-gray-50">
                            <div class="mb-4">
                                <span class="text-lg font-semibold text-gray-800">Вопрос {{ $questionNumber }}:</span>
                            </div>
                            <div class="bg-white p-6 rounded-lg shadow-sm">
                                @if(is_array($answer))
                                    {{-- Множественный выбор (checkbox) --}}
                                    <div class="mb-3">
                                        <span class="text-lg font-semibold text-gray-900">Ответы:</span>
                                    </div>
                                    <div class="flex flex-wrap gap-3">
                                        @foreach($answer as $singleAnswer)
                                            <span class="inline-flex items-center px-4 py-2 rounded-full text-base font-medium bg-[#15B8A6] text-white">
                                                {{ $singleAnswer }}
                                            </span>
                                        @endforeach
                                    </div>
                                @elseif(is_numeric($answer) && $maxScale == 5)
                                    {{-- Шкала 1-5 --}}
                                    <div class="mb-4">
                                        <span class="text-lg font-semibold text-gray-900">Оценка по шкале:</span>
                                    </div>
                                    <div class="flex items-center gap-4">
                                        <div class="flex gap-2">
                                            @for($i = 1; $i <= 5; $i++)
                                                <div class="w-10 h-10 rounded-full border-2 flex items-center justify-center text-base font-bold
                                                    @if($i <= $answer)
                                                        border-gray-900 bg-[#15B8A6] text-white
                                                    @else
                                                        border-gray-300 bg-white text-gray-400
                                                    @endif
                                                ">
                                                    {{ $i }}
                                                </div>
                                            @endfor
                                        </div>
                                        <span class="text-xl font-bold text-gray-900">{{ $answer }} из 5</span>
                                    </div>
                                    <div class="mt-3 text-base text-gray-600">
                                        @if($answer == 1)
                                            Совсем не согласен
                                        @elseif($answer == 2)
                                            Скорее не согласен
                                        @elseif($answer == 3)
                                            Нейтрально
                                        @elseif($answer == 4)
                                            Скорее согласен
                                        @elseif($answer == 5)
                                            Полностью согласен
                                        @endif
                                    </div>
                                @elseif(is_numeric($answer) && $maxScale == 10)
                                    {{-- Шкала 1-10 --}}
                                    <div class="mb-4">
                                        <span class="text-lg font-semibold text-gray-900">Оценка по шкале:</span>
                                    </div>
                                    <div class="flex items-center gap-4">
                                        <div class="flex gap-2">
                                            @for($i = 1; $i <= 10; $i++)
                                                <div class="w-10 h-10 rounded-full border-2 flex items-center justify-center text-base font-bold
                                                    @if($i <= $answer)
                                                        border-gray-900 bg-[#15B8A6] text-white
                                                    @else
                                                        border-gray-300 bg-white text-gray-400
                                                    @endif
                                                ">
                                                    {{ $i }}
                                                </div>
                                            @endfor
                                        </div>
                                        <span class="text-xl font-bold text-gray-900">{{ $answer }} из 10</span>
                                    </div>
                                    <div class="mt-3 text-base text-gray-600">
                                        @if($answer >= 1 && $answer <= 3)
                                            Низкая важность
                                        @elseif($answer >= 4 && $answer <= 6)
                                            Средняя важность
                                        @elseif($answer >= 7 && $answer <= 8)
                                            Высокая важность
                                        @elseif($answer >= 9 && $answer <= 10)
                                            Критически важно
                                        @endif
                                    </div>
                                @else
                                    {{-- Обычный ответ (radio или text) --}}
                                    <span class="text-lg font-semibold text-gray-900">Ответ:  <span style=""; font-size: 1.125rem;">{{ $answer }}</span></span>
                                @endif
                            </div>
                        </div>
                        @php
                            $questionNumber++;
                        @endphp
                    @endforeach
                </div>
            @else
                <p class="text-gray-500 text-lg">Ответы не найдены</p>
            @endif
        </div>


        @if($result->score && count($result->score) > 0)
            <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Результаты</h3>

                <div class="space-y-3">
                    @foreach($result->score as $key => $value)
                        <div class="flex justify-between items-center p-3 bg-gray-50 rounded">
                            <span class="font-medium text-gray-700">{{ $key }}:</span>
                            <span class="text-lg font-bold" style="color: #724B73;">{{ $value }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif


        @if($result->test_code === 'personality-type' && $result->answers && count($result->answers) > 0)
            @php

                $typeCount = ['R' => 0, 'I' => 0, 'A' => 0, 'S' => 0, 'E' => 0, 'C' => 0];
                $typeNames = [
                    'R' => 'Реалистический (техника, ремесла)',
                    'I' => 'Исследовательский (наука, анализ)',
                    'A' => 'Артистический (творчество, искусство)',
                    'S' => 'Социальный (работа с людьми)',
                    'E' => 'Предприимчивый (бизнес, лидерство)',
                    'C' => 'Конвенциональный (документооборот, системность)'
                ];

                foreach ($result->answers as $answer) {
                    if (is_string($answer) && array_key_exists($answer, $typeCount)) {
                        $typeCount[$answer]++;
                    }
                }


                $maxCount = max($typeCount);
                $dominantTypes = array_keys($typeCount, $maxCount);
            @endphp

            <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Анализ типа личности</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    @foreach($typeCount as $type => $count)
                        <div class="flex justify-between items-center p-3 rounded-lg
                            @if(in_array($type, $dominantTypes) && $maxCount > 0)
                                bg-green-50 border border-green-200
                            @else
                                bg-gray-50
                            @endif
                        ">
                            <div>
                                <span class="font-bold text-lg">{{ $type }}</span>
                                <div class="text-sm text-gray-600">{{ $typeNames[$type] }}</div>
                            </div>
                            <span class="text-2xl font-bold
                                @if(in_array($type, $dominantTypes) && $maxCount > 0)
                                    text-green-600
                                @else
                                    text-gray-700
                                @endif
                            ">{{ $count }}</span>
                        </div>
                    @endforeach
                </div>

                @if($maxCount > 0)
                    <div class="p-4 bg-blue-50 rounded-lg border border-blue-200">
                        <h4 class="font-semibold text-blue-800 mb-2">Доминирующий тип личности:</h4>
                        <div class="space-y-1">
                            @foreach($dominantTypes as $type)
                                <div class="text-blue-700">
                                    <span class="font-bold">{{ $type }}</span> - {{ $typeNames[$type] }} ({{ $maxCount }} выборов)
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        @endif

        <div class="bg-white rounded-lg shadow-lg p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Действия</h3>

            <div class="flex flex-wrap gap-3">

                <a href="{{ route('admin.export', ['search' => $result->student->fio]) }}"
                   class="bg-gray-900 hover:bg-gray-800 text-white px-4 py-2 rounded-md font-medium transition-all">
                    Экспорт результата
                </a>


                <a href="{{ route('admin.index', ['search' => $result->student->fio]) }}"
                   class="hover:bg-gray-800 text-white px-4 py-2 rounded-md font-medium transition-all" style="background-color: #724B73;">
                    Все результаты ученика
                </a>

                <!-- Просмотр всех результатов этого теста -->
                <a href="{{ route('admin.index', ['test_code' => $result->test_code]) }}"
                   class="hover:bg-gray-700 text-white px-4 py-2 rounded-md font-medium transition-all" style="background-color: #724B73;">
                    Все результаты теста
                </a>
            </div>
        </div>

        <!-- Другие тесты этого ученика -->
        @php
            $otherResults = $result->student->testResults()
                ->where('id', '!=', $result->id)
                ->with('student')
                ->latest()
                ->get();
        @endphp

        @if($otherResults->count() > 0)
            <div class="bg-white rounded-lg shadow-lg p-6 mt-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">
                    Другие тесты этого ученика ({{ $otherResults->count() }})
                </h3>

                <div class="space-y-3">
                    @foreach($otherResults as $otherResult)
                        <div class="flex justify-between items-center p-3 border border-gray-200 rounded">
                            <div>
                                <div class="font-medium text-gray-900">{{ $otherResult->test_title }}</div>
                                <div class="text-sm text-gray-500">{{ $otherResult->created_at->format('d.m.Y H:i') }}</div>
                            </div>
                            <a href="{{ route('admin.results.show', $otherResult->id) }}"
                               class="font-medium transition-colors" style="color: #724B73;" onmouseover="this.style.color='#5a3a5b'" onmouseout="this.style.color='#724B73'">
                                Посмотреть
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
