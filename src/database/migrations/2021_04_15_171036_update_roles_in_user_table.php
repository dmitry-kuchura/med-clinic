<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class UpdateRolesInUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $doctors = DB::table('doctors')->select('user_id')->get();

        foreach ($doctors as $doctor) {
            DB::table('users')->where('id', $doctor->user_id)->update(['role' => 'doctor']);
        }

        $patients = DB::table('patients')->select('user_id')->get();

        foreach ($patients as $patient) {
            DB::table('users')->where('id', $patient->user_id)->update(['role' => 'patient']);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    }
}
