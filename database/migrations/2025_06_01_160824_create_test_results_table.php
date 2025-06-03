<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('test_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->string('test_code', 100);
            $table->string('test_title');
            $table->json('answers');
            $table->json('score')->nullable();
            $table->timestamps();


            $table->unique(['student_id', 'test_code']);
            $table->index('test_code');
            $table->index('created_at');
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('test_results');
    }
};
