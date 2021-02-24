<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePatientsTestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('patients_tests', function (Blueprint $table) {
            $table->id();

            $table->string('mark')->nullable();
            $table->string('result')->nullable();
            $table->string('reference_values')->nullable();
            $table->string('file')->nullable();

            $table->unsignedBigInteger('test_id');
            $table->unsignedBigInteger('patient_id');

            $table->foreign('patient_id')->references('id')->on('patients');
            $table->foreign('test_id')->references('id')->on('tests');

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
        Schema::dropIfExists('patients_tests');
    }
}
