<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePatientsAppointmentsTemplatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('patients_appointments', function (Blueprint $table) {
            $table->id();

            $table->timestamp('appointment_at');
            $table->string('comment')->nullable();
            $table->string('doctor_name')->nullable();
            $table->unsignedBigInteger('patient_id');
            $table->bigInteger('external_id');

            $table->foreign('patient_id')->references('id')->on('patients');

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
        Schema::dropIfExists('patients_appointmens');
    }
}
