<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class UpdateDoctorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('doctors')
            ->orWhere(function($query) {
                $query->where('first_name', 'like','%LAB%')
                    ->where('last_name', 'like','%LAB%')
                    ->where('middle_name', 'like','%LAB%');
            })
            ->update(['is_lab' => true]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
