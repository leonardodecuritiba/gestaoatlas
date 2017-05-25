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
    public function faturamentos()
    {
        return $this->hasMany('App\Models\Faturamento', 'idstatus_fechamento');
    }
}
