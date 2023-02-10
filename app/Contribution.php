<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Contribution extends Model
{
    //
    protected $fillable = ['contribution_no','member_id','distributor','contribution_date'];
}
