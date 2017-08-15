<?php

namespace App\Models\Commons;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Brand extends Model
{
    use SoftDeletes;
    public $timestamps = true;
    protected $fillable = [
        'description',
    ];

    // ************************** RELASHIONSHIP **********************************

    public function tools()
    {
        return $this->hasMany('App\Models\Inputs\Tools', 'id', 'idbrand');
    }

    public function patterns()
    {
        return $this->hasMany('App\Models\Inputs\Pattern', 'id', 'idbrand');
    }

}


