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
        Schema::create('sections', function (Blueprint $table) {
            $table->id();
               $table->json('name_section');
               $table->boolean('status');

    $table->foreignId('grade_id')
          ->constrained('grades')
        //   ->restrictOnDelete()
          ;

          // ✅ لا تُحذف المرحلة إذا مرتبطة بأقسام

    $table->foreignId('class_id')
          ->constrained('classrooms')
        //   ->restrictOnDelete()
          ;
          // ✅ لا يُحذف الصف إذا مرتبط بأقسام
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sections');
    }
};
