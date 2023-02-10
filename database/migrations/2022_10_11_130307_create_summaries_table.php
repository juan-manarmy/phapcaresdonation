<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSummariesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('summaries', function (Blueprint $table) {
            $table->id();
            $table->integer('member_id');
            $table->string('month');
            $table->string('year');
            $table->string('product_code');
            $table->string('product_name');	
            $table->string('lot_no');
            $table->double('unit_cost', 10, 2);
            $table->integer('beginning_balance_quantity');
            $table->integer('movements_quantity')->nullable();
            $table->integer('ending_balance_quantity');
            $table->double('ending_balance_value', 10, 2);
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
        Schema::dropIfExists('summaries');
    }
}
