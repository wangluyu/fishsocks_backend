<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('user_id')->comment('用户id');
            $table->integer('port_id')->comment('端口id');
            $table->bigInteger('flow')->comment('需要的流量数');
            $table->integer('coupon_id')->default(0)->comment('代金券id');
            $table->decimal('amount', 4, 1)->comment('金额');
            $table->tinyInteger('type')->default(0)->comment('缴费类型 1:微信 2：支付宝 3：现金');
            $table->tinyInteger('status')->default(0)->comment('缴费状态 -1：已取消 0：待缴费 1：成功');
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
        Schema::dropIfExists('payments');
    }
}
