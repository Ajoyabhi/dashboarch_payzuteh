<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('banks', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->index('user_id');
            $table->string('cus_name',80)->index('cus_name');
            $table->string('acc_number',20)->index('acc_number');
            $table->string('ifsc_code',20)->index('ifsc_code');
            $table->string('mobile_no',20)->index('mobile_no');
            $table->string('bank_name',50)->index('bank_name');
            $table->string('payment_type',10)->index('payment_type');
            $table->string('pincode',10)->index('pincode');
            $table->dateTime('created_at')->index('created_at');
            $table->dateTime('updated_at')->index('updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('banks');
    }
};
