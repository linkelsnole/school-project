<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\UserTestProgress;

class UserTestProgressSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::firstOrCreate([
            'email' => 'test@example.com'
        ], [
            'name' => 'Тестовый пользователь',
            'password' => bcrypt('password')
        ]);

        $testProgress = [
            [
                'test_id' => 'temperament',
                'test_title' => 'Темперамент',
                'test_category' => 'Общие',
                'status' => 'completed',
                'score' => 85,
                'completed_at' => now()->subDays(5)
            ],
            [
                'test_id' => 'personality-type',
                'test_title' => 'Тип личности',
                'test_category' => 'Общие',
                'status' => 'completed',
                'score' => 92,
                'completed_at' => now()->subDays(3)
            ],
            [
                'test_id' => 'interests-map',
                'test_title' => 'Карта интересов',
                'test_category' => 'Общие',
                'status' => 'completed',
                'score' => 78,
                'completed_at' => now()->subDays(1)
            ],
            [
                'test_id' => 'career-anchors',
                'test_title' => 'Якоря карьеры',
                'test_category' => 'Общие',
                'status' => 'pending',
                'score' => null,
                'completed_at' => null
            ]
        ];

        foreach ($testProgress as $progress) {
            UserTestProgress::updateOrCreate([
                'user_id' => $user->id,
                'test_id' => $progress['test_id']
            ], $progress);
        }
    }
}
