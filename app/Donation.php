<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Donation extends Model
{
    protected $fillable = [
        'contribution_id',
        'product_type',
        'product_code',
        'product_name',
        'generic_name',
        'strength',
        'dosage_form',
        'package_size',
        'quantity',
        'lot_no',
        'mfg_date',
        'expiry_date',
        'drug_reg_no',
        'unit_cost',
        'total',
        'medicine_status',
        'job_no',
        'uom',
        'remarks',
        'status'];

    public function beneficiaries()
    {
        return $this->hasMany(Beneficiary::class);
    }
}
