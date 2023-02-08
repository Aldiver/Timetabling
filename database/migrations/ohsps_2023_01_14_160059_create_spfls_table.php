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
        Schema::create('ohsps', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('load_id');
            $table->string('load_type');
            $table->string('load_equivalent');
            $table->string('spfl_type');
            $table->string('hours_per_week');
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
        Schema::dropIfExists('spfls');
    }
};
