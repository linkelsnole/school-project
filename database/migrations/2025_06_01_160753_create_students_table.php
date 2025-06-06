<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('fio');
            $table->date('date_of_birth');
            $table->string('flash_id');
            $table->timestamps();


            $table->unique(['flash_id', 'fio', 'date_of_birth']);
            $table->index('flash_id');
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
