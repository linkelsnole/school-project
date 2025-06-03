<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\UserTestProgress;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $completedTests = UserTestProgress::where('user_id', $user->id)
            ->whereIn('status', ['completed', 'verified'])
            ->count();

        $verifiedTests = UserTestProgress::where('user_id', $user->id)
            ->where('status', 'verified')
            ->count();

        $stats = [
            'completed_tests' => $completedTests,
            'finished_tests' => $completedTests,
            'verified_tests' => $verifiedTests
        ];

        $recentTests = UserTestProgress::where('user_id', $user->id)
            ->whereIn('status', ['completed', 'verified'])
            ->orderBy('completed_at', 'desc')
            ->limit(5)
            ->get();

        $availableTests = [
            [
                'title' => 'Темперамент',
                'category' => 'Общие',
                'url' => '/tests/temperament',
                'completed' => false
            ],
            [
                'title' => 'Тип личности',
                'category' => 'Общие',
                'url' => '/tests/personality-type',
                'completed' => false
            ],
            [
                'title' => 'Карта интересов',
                'category' => 'Общие',
                'url' => '/tests/interests-map',
                'completed' => false
            ],
            [
                'title' => 'Якоря карьеры',
                'category' => 'Общие',
                'url' => '/tests/career-anchors',
                'completed' => false
            ]
        ];

        $recommendedJobs = [
            [
                'title' => 'Стажер-программист',
                'company' => 'IT-Компания "Будущее"',
                'salary' => 'от 30 000 ₽',
                'url' => '#'
            ],
            [
                'title' => 'Вакансия на модерацию',
                'company' => 'Компания для проверки',
                'salary' => 'По договоренности',
                'url' => '#'
            ],
            [
                'title' => 'Тестовая вакансия администратора',
                'company' => 'Тестовая компания',
                'salary' => 'от 30 000 ₽',
                'url' => '#'
            ]
        ];

        return view('profile.index', compact('user', 'stats', 'recentTests', 'availableTests', 'recommendedJobs'));
    }
}
