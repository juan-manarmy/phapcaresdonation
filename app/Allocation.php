<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Allocation extends Model
{
    //
    protected $fillable = ['allocation_no','dna_no','beneficiary_id','notice_to','authorized_representative','position',
    'contact_number','delivery_address','delivery_date','other_delivery_instructions','status'];

}
