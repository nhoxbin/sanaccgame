<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cards', function (Blueprint $table) {
            $table->string('recharge_bill_id')->primary();
            $table->foreign('recharge_bill_id')->references('id')->on('recharge_bills')->onDelete('cascade');
            $table->unsignedTinyInteger('sim_id')->nullable();
            $table->foreign('sim_id')->references('id')->on('sims')->onDelete('set null');
            $table->string('serial');
            $table->string('code');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cards');
    }
}
