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
        Schema::create('loads', function (Blueprint $table) {
            $table->id();
            $table->integer('loadable_id');
            $table->string('loadable_type');
            $table->integer('load_equivalent')->nullable();
            $table->timestamps();
            $table->foreignId('teacher_loading_id')->constrained()->onDelete('cascade');
        });
    }

    /**
         * Reverse the migrations.
         *
         * @return void
         */
    public function down()
    {
        Schema::dropIfExists('loads');
    }
};
