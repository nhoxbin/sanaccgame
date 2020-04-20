<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone', 10)->unique()->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->unsignedDecimal('cash', 15, 2)->default(0);
            $table->boolean('type')->comment('0: acc mua, 1: acc bán');
            $table->string('password');
            $table->string('is_transfer')->default(0)->comment('chuyển tiền. 0: ko được, 1: được');
            $table->boolean('role')->default(0)->comment('0: Member, 1: Admin');
            $table->boolean('country')->comment('0: VN Customer, 1: Foreign Customer');
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
