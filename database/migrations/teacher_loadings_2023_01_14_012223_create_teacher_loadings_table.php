<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('teacher_loadings', function (Blueprint $table) {
            $table->id();
            $table->integer('version')->nullable();
            $table->integer('total_loading')->default(0);
            $table->integer('total_working_hours')->default(0);
            $table->integer('total_teaching_load')->default(0);
            $table->integer('total_admin_load')->default(0);
            $table->foreignId('timetable_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('teacher_id')->nullable()->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('teacher_loadings');
    }
};
