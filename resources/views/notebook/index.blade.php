@extends('layouts.app')

@section('title', 'Электронная тетрадь')

@section('content')
<div class="min-h-screen bg-gray-50/50">
    <div class="bg-gradient-to-r from-[#C8E4FC] to-[#FFC2C2]">
        <div class="container mx-auto px-4 py-16">
            <div class="text-center">
                <h1 class="text-5xl font-bold text-white mb-6">Психологические тесты</h1>
                <p class="text-2xl text-white/90">Пройдите тестирование и узнайте больше о своих личностных особенностях</p>
            </div>
        </div>
    </div>

    <main class="max-w-7xl mx-auto px-6 py-8">

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            @foreach($tests as $test)
                <div class="bg-white shadow-sm hover:shadow-md transition-all duration-300 rounded-lg">
                    <div class="p-6 h-full flex flex-col">
                        <div class="flex items-center justify-center w-12 h-12 bg-gray-100 rounded-lg mb-4">
                            @if($test['id'] == 'temperament')
                                <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                </svg>
                            @elseif($test['id'] == 'personality-type')
                                <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            @elseif($test['id'] == 'interests-map')
                                <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
                                </svg>
                            @elseif($test['id'] == 'career-anchors')
                                <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                </svg>
                            @else
                                <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                            @endif
                        </div>

                        <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ $test['title'] }}</h3>

                        @if(isset($test['type']) && $test['type'] === 'dynamic')
                            <div class="text-xs text-gray-500 mb-3">
                                Описание
                            </div>
                        @endif

                        <p class="text-sm text-gray-600 mb-4 line-clamp-2 flex-grow">{{ $test['description'] }}</p>

                        <div class="flex flex-col gap-2 mt-auto">
                            <a href="{{ $test['url'] }}"
                               class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-md text-sm font-medium ring-offset-white transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-gray-400 focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-gray-900 text-white hover:bg-gray-800 h-10 px-4 py-2">
                                Пройти тест
                            </a>
                            <button type="button"
                                    class="modal-open-btn inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-md text-sm font-medium ring-offset-white transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-gray-400 focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-gray-200 bg-white hover:bg-gray-50 hover:text-gray-900 h-10 px-4 py-2"
                                    data-test="{{ $test['id'] }}">
                                Подробнее
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>


        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <div class="bg-white shadow-sm rounded-lg">
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Скачать все материалы</h3>
                            <p class="text-sm text-gray-600 mb-4 md:mr-20">Получите все тесты в формате PDF и QR-код для доступа к онлайн версии</p>
                            <a href="{{ route('download.materials') }}"
                               class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-md text-sm font-medium ring-offset-white transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-gray-400 focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-gray-900 text-white hover:bg-gray-800 h-10 px-4 py-2">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                Скачать материалы
                            </a>
                        </div>
                        <div class="h-16 w-16 bg-gray-100 rounded-lg flex items-center justify-center">
                            <svg class="h-8 w-8 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Карточка "Контакты агрегаторов" -->
            <div class="bg-white shadow-sm rounded-lg">
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Контакты агрегаторов</h3>
                            <p class="text-sm text-gray-600 mb-4 md:mr-20">Получите контактную информацию агрегаторов для поиска работы и стажировок</p>
                            <a href="#"
                               class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-md text-sm font-medium ring-offset-white transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-gray-400 focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-gray-900 text-white hover:bg-gray-800 h-10 px-4 py-2">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                </svg>
                                Открыть контакты
                            </a>
                        </div>
                        <div class="h-16 w-16 bg-gray-100 rounded-lg flex items-center justify-center">
                            <svg class="h-8 w-8 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <div class="bg-gradient-to-r from-[#C8E4FC] to-[#FFC2C2]">
        <div class="container mx-auto px-4 py-12">
            <div class="flex flex-col lg:flex-row items-center justify-between">
                <div class="mb-8 lg:mb-0">
                    <h2 class="text-3xl font-bold mb-4 text-white">Молодежная биржа труда</h2>
                    <p class="text-xl text-white/90 mb-6">Отсканируйте QR-код для мгновенного доступа к актуальным вакансиям, стажировкам и возможностям для молодых специалистов.</p>
                    <a href="https://ktzn.lenobl.ru/ru/deiatelnost/professionalnaya-orientaciya/mir-professij/"
                       class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-md text-sm font-medium transition-colors border-2 border-white text-white hover:bg-white hover:text-black h-10 px-8 py-2"
                       target="_blank">
                       Перейти на биржу труда
                    </a>
                </div>

                <div class="flex items-center justify-center">
                    <div class="w-40 h-40 bg-white rounded-lg flex items-center justify-center">
                        <img src="{{ asset('images/qr-notebook-exchange.jpg') }}" alt="QR Code" class="w-full h-full object-contain rounded-lg">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@foreach($tests as $test)
    <div id="modal-{{ $test['id'] }}" class="fixed inset-0 bg-black/85 hidden z-50 flex items-center justify-center p-4 transition-all duration-300 ease-out opacity-0">
        <div class="bg-white shadow-lg rounded-lg max-w-2xl w-full max-h-[80vh] overflow-y-auto transform scale-95 transition-all duration-300 ease-out">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">{{ $test['title'] }}</h3>
                    <button type="button" class="modal-close-btn inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-md text-sm font-medium ring-offset-white transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-gray-400 focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-gray-200 bg-white hover:bg-gray-50 hover:text-gray-900 h-8 w-8" data-test="{{ $test['id'] }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <div class="text-sm text-gray-600 leading-relaxed mb-6">
                    {{ $test['description'] }}
                </div>
                <div class="flex items-center justify-center mb-6">
                    <div class="bg-gray-50 p-4 rounded-lg">

                        <div class="flex justify-center">
                            <img src="{{ route('qr.test', $test['id']) }}" alt="QR код теста" class="w-32 h-32">
                        </div>
                    </div>
                </div>
                <div class="flex flex-col gap-2 mt-auto">
                    <a href="{{ $test['url'] }}"
                       class="flex-1 inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-md text-sm font-medium ring-offset-white transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-gray-400 focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-gray-900 text-white hover:bg-gray-800 h-10 px-4 py-2 text-center">
                        Пройти тест
                    </a>
                    @if(isset($test['type']) && $test['type'] === 'static')
                        <a href="{{ $test['pdf'] ?? '#' }}" download
                           class="flex-1 inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-md text-sm font-medium ring-offset-white transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-gray-400 focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-gray-200 bg-white hover:bg-gray-50 hover:text-gray-900 h-10 px-4 py-2 text-center">
                            Скачать PDF
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endforeach

<script>
document.addEventListener('DOMContentLoaded', function() {
    const openButtons = document.querySelectorAll('.modal-open-btn');
    const closeButtons = document.querySelectorAll('.modal-close-btn');

    openButtons.forEach(button => {
        button.addEventListener('click', function() {
            const testId = this.getAttribute('data-test');
            const modal = document.getElementById('modal-' + testId);
            const modalContent = modal.querySelector('.bg-white');

            if (modal) {
                modal.classList.remove('hidden');
                document.body.style.overflow = 'hidden';


                setTimeout(() => {
                    modal.classList.remove('opacity-0');
                    modalContent.classList.remove('scale-95');
                    modalContent.classList.add('scale-100');
                }, 10);
            }
        });
    });

    closeButtons.forEach(button => {
        button.addEventListener('click', function() {
            const testId = this.getAttribute('data-test');
            const modal = document.getElementById('modal-' + testId);
            const modalContent = modal.querySelector('.bg-white');

            if (modal) {
                modal.classList.add('opacity-0');
                modalContent.classList.remove('scale-100');
                modalContent.classList.add('scale-95');

                setTimeout(() => {
                    modal.classList.add('hidden');
                    document.body.style.overflow = '';
                }, 300);
            }
        });
    });


    document.querySelectorAll('[id^="modal-"]').forEach(modal => {
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                const testId = modal.id.replace('modal-', '');
                const closeBtn = modal.querySelector('.modal-close-btn[data-test="' + testId + '"]');
                if (closeBtn) {
                    closeBtn.click();
                }
            }
        });
    });
});
</script>
@endsection
