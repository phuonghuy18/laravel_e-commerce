<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('customer_addresses', function (Blueprint $table) {
        $table->dropColumn(['zip', 'state']);
    });
}

public function down()
{
    Schema::table('customer_addresses', function (Blueprint $table) {
        $table->string('zip')->nullable();
        $table->string('state')->nullable();
    });
}

};
