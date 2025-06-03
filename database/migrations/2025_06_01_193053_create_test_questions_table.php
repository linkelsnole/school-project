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
        Schema::create('test_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('test_id')->constrained()->onDelete('cascade')->comment('ID теста');
            $table->foreignId('category_id')->nullable()->constrained('question_categories')->onDelete('set null')->comment('ID категории');
            $table->text('question_text')->comment('Текст вопроса');
            $table->enum('question_type', ['radio', 'checkbox', 'text', 'scale'])->default('radio')->comment('Тип вопроса');
            $table->integer('order_index')->default(0)->comment('Порядок вопроса в тесте');
            $table->boolean('is_required')->default(true)->comment('Обязательный ли вопрос');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('test_questions');
    }
};
