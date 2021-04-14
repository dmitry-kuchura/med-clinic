<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePatientsVisitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('patients_visits', function (Blueprint $table) {
            $table->id();

            $table->string('comment')->nullable();
            $table->string('patient_name')->nullable();
            $table->string('doctor_name')->nullable();
            $table->longText('result')->nullable();
            $table->bigInteger('external_id');
            $table->timestamp('visited_at');
            $table->boolean('is_marked')->default(false);

            $table->unsignedBigInteger('patient_id');
            $table->unsignedBigInteger('doctor_id');
            $table->foreign('patient_id')->references('id')->on('patients');
            $table->foreign('doctor_id')->references('id')->on('doctors');

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
        Schema::dropIfExists('patients_visits');
    }
}
