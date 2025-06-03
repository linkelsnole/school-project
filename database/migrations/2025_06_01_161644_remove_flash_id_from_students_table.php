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
        Schema::table('students', function (Blueprint $table) {

            $table->dropUnique(['flash_id', 'fio', 'date_of_birth']);
            $table->dropIndex(['flash_id']);


            $table->dropColumn('flash_id');


            $table->unique(['fio', 'date_of_birth']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {

            $table->string('flash_id')->after('date_of_birth');


            $table->dropUnique(['fio', 'date_of_birth']);


            $table->unique(['flash_id', 'fio', 'date_of_birth']);
            $table->index('flash_id');
        });
    }
};
