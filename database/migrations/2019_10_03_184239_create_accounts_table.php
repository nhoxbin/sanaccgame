<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accounts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedInteger('game_id');
            $table->foreign('game_id')->references('id')->on('games')->onDelete('cascade');
            $table->string('username');
            $table->string('password');
            $table->string('contact_phone', 10);
            $table->string('contact_link');
            $table->unsignedDecimal('price', 15, 2);
            $table->string('info');
            $table->longText('pictures')->nullable();
            $table->tinyInteger('client_status')->default(0)->comment('-1: Ngừng bán (tài khoản sai TT), 0: Đang bán, 1: Đã bán, 2: Đã xác nhận');
            $table->tinyInteger('admin_status')->default(0)->comment('-1: Sai TT, 0: Chờ xác nhận, 1: Đã xác nhận');
            $table->longText('description')->nullable();
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
        Schema::dropIfExists('accounts');
    }
}
