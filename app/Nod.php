<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Nod extends Model
{
    //
    protected $fillable = [
    'form_id',
    'user_id',
    'cfs_post_id',
    'brand_name',
    'generic_name',
    'strength',
    'dosage_form',
    'package_size',
    'quantity',
    'lotbatch_no',
    'expiry_date',
    'trade_price',
    'total',
    'status_of_medicine'];
}
