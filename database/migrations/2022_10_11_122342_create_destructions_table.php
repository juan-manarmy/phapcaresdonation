<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDestructionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('destructions', function (Blueprint $table) {
            $table->id();
            $table->string('destruction_no');
            $table->string('pdrf_no');
            $table->integer('beneficiary_id');
            $table->string('beneficiary_new')->nullable();
            $table->string('notice_to');
            $table->string('pickup_address');
            $table->string('pickup_contact_person');
            $table->string('pickup_contact_no');
            $table->dateTime('pickup_date');
            $table->string('other_pickup_instructions');
            $table->string('delivery_contact_person');
            $table->string('delivery_address');
            $table->string('delivery_authorized_recipient');
            $table->string('delivery_contact_no');
            $table->dateTime('delivery_date');
            $table->string('other_delivery_instructions');
            $table->double('total_medicine', 10, 2)->nullable();
            $table->double('total_promats', 10, 2)->nullable();
            $table->double('total_destructed_products', 10, 2)->nullable();
            $table->integer('requester_user_id')->nullable();
            $table->dateTime('request_destruction_date')->nullable();
            $table->string('reasons_rejected_destruction')->nullable();
            $table->dateTime('verified_date')->nullable();
            $table->integer('verified_by_user_id')->nullable();
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
        Schema::dropIfExists('destructions');
    }
}
