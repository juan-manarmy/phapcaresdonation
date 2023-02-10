<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CfsPost extends Model
{
    //

    protected $fillable = ['title','details','request_items','banner_path','is_yearend','is_active'];

    public function cfsrequests()
    {
        return $this->hasMany(CfsRequest::class);
    }

    public function cfsdonors()
    {
        return $this->hasMany(CfsDonor::class);
    }
    
}
