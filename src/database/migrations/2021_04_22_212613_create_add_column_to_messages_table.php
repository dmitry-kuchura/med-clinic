<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAddColumnToMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('messages', function (Blueprint $table) {
            $table->enum('status', ['Queued', 'Accepted', 'Sent', 'Delivered', 'Read', 'Expired', 'Undelivered', 'Rejected', 'Unknown', 'Failed', 'Cancelled'])->default('Queued');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('add_column_to_messages');
    }
}
