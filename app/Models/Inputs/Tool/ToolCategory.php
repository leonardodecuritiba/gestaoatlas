<?php

namespace App\Models\Inputs\Tool;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ToolCategory extends Model
{
    use SoftDeletes;
    public $timestamps = true;
    protected $fillable = [
        'description',
    ];

    // ************************** RELASHIONSHIP **********************************

    public function tools()
    {
        return $this->hasMany('App\Models\Inputs\Tools', 'id', 'idcategory');
    }

}


