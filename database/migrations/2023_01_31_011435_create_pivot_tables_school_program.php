<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration
{
    public function up()
    {
        Schema::create('gradelevel_school_program', function (Blueprint $table) {
            $table->foreignId('gradelevel_id')->constrained('gradelevels');
            $table->foreignId('school_program_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('school_program_section', function (Blueprint $table) {
            $table->foreignId('section_id')->constrained()->onDelete('cascade');
            $table->foreignId('school_program_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('classday_school_program', function (Blueprint $table) {
            $table->foreignId('classday_id')->constrained()->onDelete('cascade');
            $table->foreignId('school_program_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('school_program_timeslot', function (Blueprint $table) {
            $table->foreignId('timeslot_id')->constrained()->onDelete('cascade');
            $table->foreignId('school_program_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('school_program_teacher', function (Blueprint $table) {
            $table->foreignId('teacher_id')->constrained()->onDelete('cascade');
            $table->foreignId('school_program_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('school_program_section');
        Schema::dropIfExists('school_program_teacher');
        Schema::dropIfExists('gradelevel_school_program');
        Schema::dropIfExists('classday_school_program');
        Schema::dropIfExists('school_program_timeslot');
    }
};


