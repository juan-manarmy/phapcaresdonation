<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDestructedProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('destructed_products', function (Blueprint $table) {
            $table->id();
            $table->integer('destruction_id');
            $table->integer('inventory_id');
            $table->string('product_type');
            $table->string('product_code');
            $table->string('product_name');
            $table->integer('quantity');
            $table->string('lot_no');
            $table->dateTime('expiry_date');
            $table->double('unit_cost', 10, 2);
            $table->double('total', 10, 2);
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
        Schema::dropIfExists('destructed_products');
    }
}
