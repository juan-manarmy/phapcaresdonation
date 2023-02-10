<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDonationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('donations', function (Blueprint $table) {
            $table->id();
            $table->integer('contribution_id');
            $table->string('product_type')->nullable();
            $table->string('product_code')->nullable();
            $table->string('product_name');
            $table->string('generic_name')->nullable();
            $table->string('strength')->nullable();
            $table->string('dosage_form')->nullable();
            $table->string('package_size')->nullable();
            $table->integer('quantity')->nullable();
            $table->string('lot_no')->nullable();
            $table->dateTime('mfg_date')->nullable();
            $table->dateTime('expiry_date')->nullable();
            $table->string('drug_reg_no')->nullable();
            $table->double('unit_cost', 10, 2)->nullable();
            $table->double('total', 10, 2)->nullable();
            $table->string('medicine_status')->nullable();
            $table->string('job_no')->nullable();
            $table->string('uom')->nullable();
            $table->string('remarks')->nullable();
            $table->integer('status')->nullable();
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
        Schema::dropIfExists('donations');
    }
}
