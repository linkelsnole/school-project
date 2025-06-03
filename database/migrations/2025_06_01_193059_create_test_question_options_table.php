<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('test_question_options', function (Blueprint $table) {
            $table->id();
            $table->foreignId('question_id')->constrained('test_questions')->onDelete('cascade')->comment('ID вопроса');
            $table->string('option_text')->comment('Текст варианта ответа');
            $table->string('option_value')->comment('Значение для обработки');
            $table->tinyInteger('weight')->default(0)->comment('Вес ответа для балльных тестов');
            $table->boolean('is_correct')->nullable()->comment('Правильный ли ответ для квизов');
            $table->integer('order_index')->default(0)->comment('Порядок варианта');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('test_question_options');
    }
};
