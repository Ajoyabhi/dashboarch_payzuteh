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
        Schema::create('payout_lists', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->index('user_id');
            $table->float('amount')->index('amount');
            $table->integer('bank_id')->index('bank_id');
            $table->smallInteger('status')->index('status')->comment('0:Pending, 1:Approved, 2:Rejected')->default('0');
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
        Schema::dropIfExists('payout_lists');
    }
};
