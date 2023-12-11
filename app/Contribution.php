<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Contribution extends Model
{
    //
    protected $fillable = [
        'cfs_id',
        'contribution_no',
        'member_id',
        'distributor',
        'inventory_location',
        'contribution_date'];
}
