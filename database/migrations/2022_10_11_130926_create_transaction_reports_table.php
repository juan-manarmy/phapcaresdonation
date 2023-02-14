<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transaction_reports', function (Blueprint $table) {
            $table->id();
            $table->integer('member_id');
            $table->integer('user_id')->nullable();
            $table->integer('beneficiary_id')->nullable();
            $table->integer('contribution_id')->nullable();
            $table->integer('allocation_id')->nullable();
            $table->integer('destruction_id')->nullable();
            $table->integer('inventory_id')->nullable();
            $table->string('month');
            $table->string('year');
            $table->string('contribution_no')->nullable();
            $table->string('allocation_no')->nullable();
            $table->string('destruction_no')->nullable();
            $table->string('product_code');
            $table->string('product_name');
            $table->string('lot_no');
            $table->integer('opening_balance_quantity');
            $table->string('transaction_type');
            $table->integer('quantity');
            $table->double('unit_cost', 10, 2);
            $table->integer('receipt_quantity')->nullable();
            $table->integer('issuance_quantity')->nullable();
            $table->integer('destruction_quantity')->nullable();
            $table->double('receipt_amount', 10, 2)->nullable();
            $table->double('issuance_amount', 10, 2)->nullable();
            $table->double('destruction_amount', 10, 2)->nullable();
            $table->dateTime('mfg_date')->nullable();
            $table->dateTime('expiry_date');
            $table->string('remarks')->nullable();
            $table->string('job_no')->nullable();
            $table->integer('status');
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
        Schema::dropIfExists('transaction_reports');
    }
}
