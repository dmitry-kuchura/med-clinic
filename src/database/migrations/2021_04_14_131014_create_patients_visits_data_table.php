<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePatientsVisitsDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('patients_visits_data', function (Blueprint $table) {
            $table->id();

            $table->string('category')->nullable();
            $table->string('template')->nullable();
            $table->longText('data')->nullable();

            $table->unsignedBigInteger('visit_id');
            $table->foreign('visit_id')->references('id')->on('patients_visits');

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
        Schema::dropIfExists('patients_visits_data');
    }
}
