<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Destruction extends Model
{
    protected $fillable = ['destruction_no',
    'pdrf_no',
    'beneficiary_id',
    'notice_to',
    'pickup_address',
    'pickup_contact_person',
    'pickup_contact_no',
    'pickup_date',
    'other_pickup_instructions',
    'delivery_contact_person',
    'delivery_address',
    'delivery_authorized_recipient',
    'delivery_contact_no',
    'delivery_date',
    'other_delivery_instructions',
    'status'];

}
