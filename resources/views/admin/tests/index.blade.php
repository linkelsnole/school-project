@extends('layouts.app')

@section('title', 'Конструктор тестов')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-7xl">
    <!-- Page Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between mb-6">
            <div className="space-y-1">
                <h1 class="text-3xl font-bold tracking-tight">Конструктор тестов</h1>
                <p class="text-muted-foreground">Создавайте и редактируйте психологические тесты</p>
            </div>
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.index') }}" class="inline-flex items-center text-sm font-medium text-muted-foreground hover:text-foreground transition-colors">
                    ← Назад к результатам
                </a>
                <form action="{{ route('logout') }}" method="POST" class="inline"></form>
                </form>
            </div>
        </div>

        <!-- Create Button -->
        <div class="mb-6">
            <button onclick="openCreateTestModal()" class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-all duration-200 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-gray-500 focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 h-10 px-4 py-2 bg-[#724B73] cursor-pointer" style="color: white;">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Создать новый тест
            </button>
        </div>

        <!-- Success Message -->
        @if(session('success'))
            <div class="rounded-lg border-gray-200 bg-green-50 p-4 text-green-800 text-sm mb-6">
                <div class="flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    {{ session('success') }}
                </div>
            </div>
        @endif
    </div>

    <!-- Tests Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($tests as $test)
            <div class="rounded-lg bg-card text-card-foreground shadow-sm hover:shadow-md transition-shadow flex flex-col h-full">
                <div class="p-6 flex flex-col flex-grow">
                    <!-- Card Header -->
                    <div class="flex items-start justify-between mb-4">
                        <h3 class="text-lg font-semibold leading-none tracking-tight">{{ $test->title }}</h3>
                        <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium {{ $test->status === 'active' ? 'bg-green-100 text-green-700 border border-gray-200' : 'bg-gray-100 text-gray-700 border border-gray-200' }}">
                            {{ $test->status === 'active' ? 'Активный' : 'Неактивный' }}
                        </span>
                    </div>

                    <!-- Test Details -->
                    <div class="space-y-2 mb-4 text-sm text-muted-foreground">
                        <div class="flex items-center justify-between">
                            <span>Код:</span>
                            <code class="relative rounded bg-muted px-[0.3rem] py-[0.2rem] font-mono text-sm font-medium">{{ $test->code }}</code>
                        </div>
                        <div class="flex items-center justify-between">
                            <span>Вопросов:</span>
                            <span class="font-medium">{{ $test->questions_count }}</span>
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="flex-grow">
                        @if($test->description)
                            <p class="text-sm text-muted-foreground mb-4 line-clamp-3">{{ $test->description }}</p>
                        @endif
                    </div>

                    <!-- Action Buttons -->
                    <div class="mt-auto">
                        <div class="flex gap-2 mb-4">
                            <a href="{{ route('admin.tests.show', $test) }}"
                               class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-all duration-200 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-gray-500 focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 h-9 px-3 flex-1" style="background-color: #101828; color: white;" onmouseover="this.style.backgroundColor='#1f2937'" onmouseout="this.style.backgroundColor='#101828'">
                                Редактировать
                            </a>

                            @if($test->status === 'active')
                                <a href="/tests/{{ $test->code }}" target="_blank"
                                   class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-all duration-200 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-gray-500 focus-visible:ring-offset-2 border border-gray-300 bg-white text-gray-700 hover:bg-gray-50 hover:border-gray-400 active:bg-gray-100 h-9 px-3">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                    </svg>
                                    Просмотр
                                </a>
                            @endif
                        </div>

                        <!-- Footer Actions -->
                        <div class="pt-4 border-t border-gray-200 flex justify-end items-center">
                            <form action="{{ route('admin.tests.destroy', $test) }}" method="POST"
                                  onsubmit="return confirm('Вы уверены, что хотите удалить этот тест? Все вопросы и результаты будут удалены!')"
                                  class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="inline-flex items-center text-sm font-medium text-muted-foreground hover:text-destructive transition-colors">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                    Удалить
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <!-- Empty State -->
            <div class="col-span-full">
                <div class="rounded-lg bg-card text-card-foreground shadow-sm p-12 text-center">
                    <div class="mx-auto w-16 h-16 bg-muted rounded-full flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold mb-2">Нет тестов</h3>
                    <p class="text-sm text-muted-foreground mb-6 max-w-sm mx-auto">Создайте первый тест для начала работы с конструктором психологических тестов</p>
                    <button onclick="openCreateTestModal()"
                       class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-all duration-200 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-gray-500 focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 h-10 px-8 py-2" style="background-color: #101828; color: white;" onmouseover="this.style.backgroundColor='#1f2937'" onmouseout="this.style.backgroundColor='#101828'">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Создать первый тест
                    </button>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Create Test Modal -->
    <div id="createTestModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6" style="width: 500px; max-width: 90vw;">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Создать новый тест</h3>

            <form action="{{ route('admin.tests.store') }}" method="POST">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Название теста *</label>
                        <input type="text" name="title" required
                               placeholder="Например: Тест на темперамент"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <p class="text-xs text-gray-500 mt-1">Код теста для URL будет создан автоматически</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Описание теста</label>
                        <textarea name="description" rows="3"
                                  placeholder="Краткое описание теста, его цели и особенности..."
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"></textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Статус *</label>
                        <div class="relative">
                            <div id="statusDropdown"
                                 class="w-full px-3 py-2 border border-gray-300 rounded-md bg-white cursor-pointer focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                 onclick="toggleStatusDropdown()">
                                <div class="flex justify-between items-center">
                                    <span id="selectedStatus">Активный</span>
                                    <svg class="w-5 h-5 text-gray-400 transition-transform duration-200" id="statusDropdownIcon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </div>
                            </div>

                            <div id="statusDropdownOptions" class="absolute z-10 w-full mt-1 bg-white border border-gray-300 rounded-md shadow-lg hidden">
                                <div class="py-2">
                                    <div class="px-3 py-2 hover:bg-gray-50 cursor-pointer transition-colors duration-150"
                                         onclick="selectStatusOption('active', 'Активный')">
                                        <div class="font-medium text-gray-900">Активный</div>
                                        <div class="text-sm text-gray-500">Тест будет доступен для прохождения</div>
                                    </div>
                                    <div class="px-3 py-2 hover:bg-gray-50 cursor-pointer transition-colors duration-150"
                                         onclick="selectStatusOption('inactive', 'Неактивный')">
                                        <div class="font-medium text-gray-900">Неактивный</div>
                                        <div class="text-sm text-gray-500">Тест не будет доступен для прохождения</div>
                                    </div>
                                </div>
                            </div>

                            <input type="hidden" name="status" id="statusInput" value="active" required>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Неактивные тесты не будут доступны для прохождения</p>
                    </div>

                    <div class="flex gap-3 pt-4">
                        <button type="submit"
                                style="background-color: #101828"
                                onmouseover="this.style.backgroundColor='#1f2937'"
                                onmouseout="this.style.backgroundColor='#101828'"
                                class="flex-1 px-4 py-2 text-white rounded transition-colors">
                            Создать тест
                        </button>
                        <button type="button" onclick="closeCreateTestModal()"
                                class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded hover:bg-gray-50 transition-colors">
                            Отмена
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openCreateTestModal() {
    document.getElementById('createTestModal').classList.remove('hidden');
    document.getElementById('createTestModal').classList.add('flex');
}

function closeCreateTestModal() {
    document.getElementById('createTestModal').classList.add('hidden');
    document.getElementById('createTestModal').classList.remove('flex');
}

function toggleStatusDropdown() {
    const dropdown = document.getElementById('statusDropdownOptions');
    const icon = document.getElementById('statusDropdownIcon');

    if (dropdown.classList.contains('hidden')) {
        dropdown.classList.remove('hidden');
        icon.style.transform = 'rotate(180deg)';
    } else {
        dropdown.classList.add('hidden');
        icon.style.transform = 'rotate(0deg)';
    }
}

function selectStatusOption(value, text) {
    document.getElementById('selectedStatus').textContent = text;
    document.getElementById('statusInput').value = value;
    document.getElementById('statusDropdownOptions').classList.add('hidden');
    document.getElementById('statusDropdownIcon').style.transform = 'rotate(0deg)';
}

document.addEventListener('click', function(event) {
    const dropdown = document.getElementById('statusDropdown');
    const options = document.getElementById('statusDropdownOptions');

    if (dropdown && !dropdown.contains(event.target) && !options.contains(event.target)) {
        options.classList.add('hidden');
        document.getElementById('statusDropdownIcon').style.transform = 'rotate(0deg)';
    }
});

document.getElementById('createTestModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeCreateTestModal();
    }
});
</script>
@endsection
