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
        Schema::create('tests', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique()->comment('Уникальный код для URL');
            $table->string('title')->comment('Название теста');
            $table->text('description')->nullable()->comment('Описание теста');
            $table->enum('status', ['active', 'inactive'])->default('active')->comment('Статус теста');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tests');
    }
};
