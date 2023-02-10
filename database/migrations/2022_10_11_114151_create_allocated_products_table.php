<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAllocatedProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('allocated_products', function (Blueprint $table) {
            $table->id();
            $table->integer('allocation_id');
            $table->integer('inventory_id');
            $table->string('product_type');
            $table->string('product_code');
            $table->string('product_name');
            $table->integer('quantity');
            $table->string('lot_no');
            $table->dateTime('mfg_date');
            $table->dateTime('expiry_date');
            $table->string('drug_reg_no');
            $table->double('unit_cost', 10, 2);
            $table->double('total', 10, 2);
            $table->string('medicine_status');
            $table->string('job_no');
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
        Schema::dropIfExists('allocated_products');
    }
}
