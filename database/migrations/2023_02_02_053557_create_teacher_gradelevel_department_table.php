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
        Schema::create('teacher_gl_dep', function (Blueprint $table) {
            $table->foreignId('teacher_id')->constrained()->onDelete('cascade');
            $table->foreignId('gradelevel_id')->constrained()->onDelete('cascade');
            $table->foreignId('department_id')->constrained()->onDelete('cascade');
            $table->unique(['teacher_id', 'gradelevel_id', 'department_id']);
        });
    }


    /**
         * Reverse the migrations.
         *
         * @return void
         */
    public function down()
    {
        Schema::dropIfExists('teacher_gradelevel_department');
    }
};
