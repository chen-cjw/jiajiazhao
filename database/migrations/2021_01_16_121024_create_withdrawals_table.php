<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWithdrawalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('withdrawals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedDecimal('amount',10,2)->comment('提现金额');
            $table->string('name')->comment('姓名');
            $table->string('bank_of_deposit')->comment('开户行');
            $table->string('bank_card_number')->comment('银行卡号');
            $table->string('image')->nullable()->comment('打款凭证');
            $table->enum('is_accept',[0,1,2])->default(0)->comment('提现是否通过审核');
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
        Schema::dropIfExists('withdrawals');
    }
}
