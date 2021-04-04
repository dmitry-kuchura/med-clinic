<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('logs', function (Blueprint $table) {
            $table->id();

            $table->longText('message');
            $table->longText('context')->nullable();
            $table->enum('level', ['info', 'debug', 'alert', 'warning', 'error', 'exception'])->index();

            $table->longText('request_headers')->nullable();
            $table->longText('request')->nullable();
            $table->longText('response_headers')->nullable();
            $table->longText('response')->nullable();

            $table->string('remote_addr')->nullable();
            $table->string('user_agent')->nullable();

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
        Schema::dropIfExists('logs');
    }
}
