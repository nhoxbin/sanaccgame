<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNganluongsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nganluongs', function (Blueprint $table) {
            $table->string('recharge_bill_id')->primary();
            $table->foreign('recharge_bill_id')->references('id')->on('recharge_bills')->onDelete('cascade');
            $table->string('token');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('nganluongs');
    }
}
