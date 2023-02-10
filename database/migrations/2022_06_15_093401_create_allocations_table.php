<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAllocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('allocations', function (Blueprint $table) {
            $table->id();
            $table->string('allocation_no');
            $table->string('dna_no');
            $table->integer('beneficiary_id');
            $table->string('beneficiary_new')->nullable();
            $table->string('notice_to');
            $table->string('authorized_representative');
            $table->string('position');
            $table->string('contact_number');
            $table->string('delivery_address');
            $table->dateTime('delivery_date');
            $table->string('other_delivery_instructions')->nullable();
            $table->double('total_medicine', 10, 2)->nullable();
            $table->double('total_promats', 10, 2)->nullable();
            $table->double('total_allocated_products', 10, 2)->nullable();
            $table->integer('requester_user_id')->nullable();
            $table->dateTime('allocation_date')->nullable();
            $table->string('reasons_rejected_allocation')->nullable();
            $table->dateTime('verified_date')->nullable();
            $table->integer('verified_by_user_id')->nullable();
            $table->string('reasons_rejected_terms')->nullable();
            $table->dateTime('dtac_approval_date')->nullable();
            $table->integer('dtac_approval_user_id')->nullable();
            $table->string('dodrf_no')->nullable();
            $table->string('reasons_failed_outbound')->nullable();
            $table->dateTime('dodrf_approval_date')->nullable();
            $table->integer('dodrf_approval_user_id')->nullable();
            $table->integer('status')->default('1');
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
        Schema::dropIfExists('allocations');
    }
}
