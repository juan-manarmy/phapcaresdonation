<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AllocatedProduct extends Model
{
    protected $fillable = [
        'allocation_id',
        'inventory_id',
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
        'medicine_status',
        'job_no',
        'status'
    ];
}
