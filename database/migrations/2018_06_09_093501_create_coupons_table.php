<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCouponsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->decimal('amount')->comment('代金券金额');
            $table->integer('user_id')->comment('用户id');
            $table->tinyInteger('type')->comment('代金券类型 1:金额 2：折扣');
            $table->tinyInteger('status')->comment('代金券状态 1：已使用 0：未使用 -1：已过期');
            $table->dateTime('expiration')->comment('过期时间');
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
        Schema::dropIfExists('coupons');
    }
}
