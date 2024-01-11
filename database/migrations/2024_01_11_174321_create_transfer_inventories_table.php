<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransferInventoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transfer_inventories', function (Blueprint $table) {
            $table->id();
            $table->string('ttif_no');
            $table->string('notice_to');
            $table->string('transfer_date');
            $table->string('daff_no');
            $table->string('pickup_organization_name');
            $table->string('pickup_address');
            $table->string('pickup_contact_person');
            $table->string('pickup_contact_no');
            $table->string('pickup_date');
            $table->string('pickup_other_instruction');
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
        Schema::dropIfExists('transfer_inventories');
    }
}
