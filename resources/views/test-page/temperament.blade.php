@extends('layouts.app')

@section('title', $testData['title'])

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <!-- Кнопка назад -->
        <div class="mb-6">
            <a href="javascript:history.back()"
               class="inline-flex items-center text-gray-600 hover:text-gray-800 font-medium transition-all">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Назад
            </a>
        </div>

        <!-- Заголовок теста -->
        <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
            <div class="text-center mb-6">
                <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                </div>
                <h1 class="text-2xl font-bold text-gray-800 mb-2">{{ $testData['title'] }}</h1>
                <p class="text-gray-600">{{ $testData['description'] }}</p>
            </div>
        </div>

        <!-- Форма ввода данных ученика -->
        <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Ваши данные</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="studentFio" class="block text-sm font-medium text-gray-700 mb-2">
                        Ваше ФИО *
                    </label>
                    <input type="text"
                           id="studentFio"
                           name="fio"
                           required
                           placeholder="Иванов Иван Иванович"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                <div>
                    <label for="studentDob" class="block text-sm font-medium text-gray-700 mb-2">
                        Дата рождения *
                    </label>
                    <input type="date"
                           id="studentDob"
                           name="date_of_birth"
                           required
                           placeholder="01.06.2025"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
            </div>
        </div>

        <!-- Тест -->
        <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-6">ТЕСТ «ФОРМУЛА ТЕМПЕРАМЕНТА» (А. БЕЛОВА)</h2>
            <p class="text-gray-600 mb-6">Внимательно прочитайте список свойств, присущий тому или иному темпераменту, и отметьте "Да", если свойство вам присуще, и "Нет", если это свойство у вас не выражено.</p>

            <form id="testForm" class="space-y-8">
                @csrf
                <input type="hidden" id="testCode" value="{{ $testData['code'] }}">
                <input type="hidden" id="testTitle" value="{{ $testData['title'] }}">

                <!-- Флегматик -->
                <div class="border-l-4 border-blue-500 pl-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Флегматик</h3>
                    <div class="space-y-3">
                        <div class="flex items-center justify-between p-3 border border-gray-200 rounded-lg">
                            <span class="text-gray-700">1. Спокойны и хладнокровны</span>
                            <div class="flex gap-4">
                                <label class="flex items-center">
                                    <input type="radio" name="question_1" value="yes" class="mr-2 text-blue-600">
                                    <span>Да</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" name="question_1" value="no" class="mr-2 text-blue-600">
                                    <span>Нет</span>
                                </label>
                            </div>
                        </div>

                        <div class="flex items-center justify-between p-3 border border-gray-200 rounded-lg">
                            <span class="text-gray-700">2. Легко переносите обиды</span>
                            <div class="flex gap-4">
                                <label class="flex items-center">
                                    <input type="radio" name="question_2" value="yes" class="mr-2 text-blue-600">
                                    <span>Да</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" name="question_2" value="no" class="mr-2 text-blue-600">
                                    <span>Нет</span>
                                </label>
                            </div>
                        </div>

                        <div class="flex items-center justify-between p-3 border border-gray-200 rounded-lg">
                            <span class="text-gray-700">3. Малоактивны и вялы</span>
                            <div class="flex gap-4">
                                <label class="flex items-center">
                                    <input type="radio" name="question_3" value="yes" class="mr-2 text-blue-600">
                                    <span>Да</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" name="question_3" value="no" class="mr-2 text-blue-600">
                                    <span>Нет</span>
                                </label>
                            </div>
                        </div>

                        <div class="flex items-center justify-between p-3 border border-gray-200 rounded-lg">
                            <span class="text-gray-700">4. Постоянны в своих отношениях</span>
                            <div class="flex gap-4">
                                <label class="flex items-center">
                                    <input type="radio" name="question_4" value="yes" class="mr-2 text-blue-600">
                                    <span>Да</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" name="question_4" value="no" class="mr-2 text-blue-600">
                                    <span>Нет</span>
                                </label>
                            </div>
                        </div>

                        <div class="flex items-center justify-between p-3 border border-gray-200 rounded-lg">
                            <span class="text-gray-700">5. Осторожны и рассудительны</span>
                            <div class="flex gap-4">
                                <label class="flex items-center">
                                    <input type="radio" name="question_5" value="yes" class="mr-2 text-blue-600">
                                    <span>Да</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" name="question_5" value="no" class="mr-2 text-blue-600">
                                    <span>Нет</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Кнопка отправки -->
                <div class="text-center pt-6">
                    <button type="submit"
                            id="submitBtn"
                            class="bg-green-600 hover:bg-green-700 text-white py-3 px-8 rounded-md font-semibold transition-all disabled:bg-gray-400 disabled:cursor-not-allowed">
                        Завершить тест
                    </button>

                    <div id="submitStatus" class="mt-4 text-sm hidden">
                        <div class="inline-flex items-center">
                            <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-blue-600 mr-2"></div>
                            Отправка результатов...
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('testForm');
    const submitBtn = document.getElementById('submitBtn');
    const submitStatus = document.getElementById('submitStatus');

    form.addEventListener('submit', async function(e) {
        e.preventDefault();


        const fio = document.getElementById('studentFio').value.trim();
        const dob = document.getElementById('studentDob').value;

        if (!fio || !dob) {
            alert('Пожалуйста, заполните ФИО и дату рождения');
            return;
        }


        const answers = {};
        const questions = document.querySelectorAll('input[type="radio"]:checked');

        if (questions.length === 0) {
            alert('Пожалуйста, ответьте хотя бы на один вопрос');
            return;
        }

        questions.forEach(input => {
            const questionName = input.name;
            answers[questionName] = input.value;
        });

        submitBtn.disabled = true;
        submitStatus.classList.remove('hidden');

        const testData = {
            fio: fio,
            date_of_birth: dob,
            test_code: document.getElementById('testCode').value,
            test_title: document.getElementById('testTitle').value,
            answers: answers,
            score: null
        };

        try {
            const response = await fetch('/api/v1/results', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                },
                body: JSON.stringify(testData)
            });

            const result = await response.json();

            if (result.success) {

                document.body.innerHTML = `
                    <div class="container mx-auto px-4 py-8 text-center">
                        <div class="max-w-md mx-auto bg-white rounded-lg shadow-lg p-8">
                            <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                            <h1 class="text-2xl font-bold text-gray-800 mb-4">Тест завершен!</h1>
                            <p class="text-gray-600 mb-6">Спасибо за прохождение теста. Ваши результаты сохранены.</p>
                            <a href="/tests" class="bg-blue-600 hover:bg-blue-700 text-white py-2 px-6 rounded-md font-semibold transition-all">
                                Вернуться к тестам
                            </a>
                        </div>
                    </div>
                `;
            } else {
                alert('Ошибка при отправке результатов: ' + (result.message || 'Неизвестная ошибка'));
                submitBtn.disabled = false;
                submitStatus.classList.add('hidden');
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Ошибка при отправке результатов. Проверьте интернет-соединение.');
            submitBtn.disabled = false;
            submitStatus.classList.add('hidden');
        }
    });
});
</script>
@endsection
