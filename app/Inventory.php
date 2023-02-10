<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    protected $fillable = [
        'member_id',
        'product_type',
        'product_code',
        'product_name',
        'quantity',
        'lot_no',
        'mfg_date',
        'expiry_date',
        'drug_reg_no',
        'unit_cost',
        'total',
        'job_no'];
}
