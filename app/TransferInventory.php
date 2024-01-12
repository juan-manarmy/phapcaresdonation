<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TransferInventory extends Model
{
    protected $fillable = [
        'ttif_no',
        'contribution_id',
        'notice_to',
        'transfer_date',
        'daff_no',
        'pickup_organization_name',
        'pickup_address',
        'pickup_contact_person',
        'pickup_contact_no',
        'pickup_date',
        'pickup_other_instruction'];
}
