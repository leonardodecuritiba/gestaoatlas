<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StatusFechamento extends Model
{
    use SoftDeletes;
    public $timestamps = true;
    protected $table = 'status_fechamento';
    protected $primaryKey = 'id';
    protected $fillable = [
        'descricao',
    ];

    // ******************** RELASHIONSHIP ******************************
    // ********************** BELONGS ********************************

    // ************************** HASMANY **********************************
    public function fechamentos()
    {
        return $this->hasMany('App\Models\Fechamento', 'idstatus_fechamento');
    }
}
