<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    //php artisan migrate:refresh --seed --seeder=BasicAdminPermissionSeeder
    public function up()
    {
        Schema::create('teachers', function (Blueprint $table) {
            $table->id();
            $table->string('full_name')->unique();
            $table->string('last_name');
            $table->string('first_name');
            $table->string('middle_name');
            $table->string('specialization'); //Major
            $table->string('gender');
            $table->string('designation_name')->nullable(); //nullable?
            $table->string('designation_period')->nullable();
            // $table->string('image')->nullable;
            $table->timestamps();

            // relation
            $table->bigInteger('department_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('teachers');
    }
};
