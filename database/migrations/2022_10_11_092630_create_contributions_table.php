<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContributionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    
    public function up()
    {
        Schema::create('contributions', function (Blueprint $table) {
            $table->id();
            $table->string('contribution_no');
            $table->integer('cfs_id')->nullable();
            $table->integer('member_id');
            $table->string('distributor');
            $table->date('contribution_date');
            $table->string('pickup_address')->nullable();
            $table->string('pickup_contact_person')->nullable();
            $table->string('pickup_contact_no')->nullable();
            $table->dateTime('pickup_date')->nullable();
            $table->string('delivery_address')->nullable();
            $table->string('delivery_contact_person')->nullable();
            $table->string('delivery_contact_no')->nullable();
            $table->dateTime('delivery_date')->nullable();
            $table->double('total_medicine', 10, 2)->nullable();
            $table->double('total_promats', 10, 2)->nullable();
            $table->double('total_donation', 10, 2)->nullable();
            $table->integer('requester_user_id')->nullable();
            $table->string('tel_no')->nullable();
            $table->string('fax_no')->nullable();
            $table->string('email')->nullable();
            $table->dateTime('registered_date')->nullable();
            $table->string('reasons_rejected_contribution')->nullable();
            $table->dateTime('approval_date')->nullable();
            $table->integer('approval_user_id')->nullable();
            $table->string('reasons_rejected_donation')->nullable();
            $table->dateTime('verified_date')->nullable();
            $table->integer('verified_by_user_id')->nullable();
            $table->string('dnd_no')->nullable();
            $table->string('notice_to')->nullable();
            $table->dateTime('dnd_date')->nullable();
            $table->string('dnd_contact_person')->nullable();
            $table->string('pickup_instructions')->nullable();
            $table->string('reasons_rejected_dnd')->nullable();
            $table->dateTime('dnd_approval_date')->nullable();
            $table->integer('dnd_approval_user_id')->nullable();
            $table->string('didrf_no')->nullable();
            $table->string('daff_no')->nullable();
            $table->dateTime('inbound_date')->nullable();
            $table->string('reasons_rejected_inbound')->nullable();
            $table->dateTime('didrf_approval_date')->nullable();
            $table->integer('didrf_approval_user_id')->nullable();
            $table->tinyInteger('status')->default(1);
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
        Schema::dropIfExists('contributions');
    }
}
