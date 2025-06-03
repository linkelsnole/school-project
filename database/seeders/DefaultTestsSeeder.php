<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Test;
use App\Models\QuestionCategory;
use App\Models\TestQuestion;
use App\Models\TestQuestionOption;

class DefaultTestsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $temperamentTest = Test::updateOrCreate(
            ['code' => 'temperament'],
            [
                'title' => 'Темперамент',
                'description' => 'Определение типа темперамента: холерик, сангвиник, флегматик, меланхолик. Тест поможет понять особенности вашего поведения и реакций.',
                'status' => 'active'
            ]
        );


        QuestionCategory::firstOrCreate(['test_id' => $temperamentTest->id, 'title' => 'Холерик'], ['order_index' => 1]);
        QuestionCategory::firstOrCreate(['test_id' => $temperamentTest->id, 'title' => 'Сангвиник'], ['order_index' => 2]);
        QuestionCategory::firstOrCreate(['test_id' => $temperamentTest->id, 'title' => 'Флегматик'], ['order_index' => 3]);
        QuestionCategory::firstOrCreate(['test_id' => $temperamentTest->id, 'title' => 'Меланхолик'], ['order_index' => 4]);


        $personalityTest = Test::updateOrCreate(
            ['code' => 'personality-type'],
            [
                'title' => 'Тип личности',
                'description' => 'Определение типа личности по методике MBTI. Узнайте свои сильные стороны и предпочтения в работе и общении.',
                'status' => 'active'
            ]
        );


        QuestionCategory::firstOrCreate(['test_id' => $personalityTest->id, 'title' => 'Экстраверсия'], ['order_index' => 1]);
        QuestionCategory::firstOrCreate(['test_id' => $personalityTest->id, 'title' => 'Интроверсия'], ['order_index' => 2]);
        QuestionCategory::firstOrCreate(['test_id' => $personalityTest->id, 'title' => 'Логика'], ['order_index' => 3]);
        QuestionCategory::firstOrCreate(['test_id' => $personalityTest->id, 'title' => 'Этика'], ['order_index' => 4]);


        $interestsTest = Test::updateOrCreate(
            ['code' => 'interests-map'],
            [
                'title' => 'Карта интересов',
                'description' => 'Выявление профессиональных интересов и склонностей. Поможет определить наиболее подходящие сферы деятельности.',
                'status' => 'active'
            ]
        );


        QuestionCategory::firstOrCreate(['test_id' => $interestsTest->id, 'title' => 'Технические науки'], ['order_index' => 1]);
        QuestionCategory::firstOrCreate(['test_id' => $interestsTest->id, 'title' => 'Гуманитарные науки'], ['order_index' => 2]);
        QuestionCategory::firstOrCreate(['test_id' => $interestsTest->id, 'title' => 'Творчество'], ['order_index' => 3]);
        QuestionCategory::firstOrCreate(['test_id' => $interestsTest->id, 'title' => 'Социальная сфера'], ['order_index' => 4]);


        $careerTest = Test::updateOrCreate(
            ['code' => 'career-anchors'],
            [
                'title' => 'Якоря карьеры',
                'description' => 'Определение карьерных ценностей и мотивов. Тест поможет понять, что для вас наиболее важно в профессиональной деятельности.',
                'status' => 'active'
            ]
        );


        QuestionCategory::firstOrCreate(['test_id' => $careerTest->id, 'title' => 'Автономия'], ['order_index' => 1]);
        QuestionCategory::firstOrCreate(['test_id' => $careerTest->id, 'title' => 'Стабильность'], ['order_index' => 2]);
        QuestionCategory::firstOrCreate(['test_id' => $careerTest->id, 'title' => 'Служение'], ['order_index' => 3]);
        QuestionCategory::firstOrCreate(['test_id' => $careerTest->id, 'title' => 'Вызов'], ['order_index' => 4]);

        $this->command->info('✅ Обновлены 4 базовых теста с категориями');
    }
}
