<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StatusParcela extends Model
{
    use SoftDeletes;
    public $timestamps = true;
    protected $table = 'status_parcelas';
    protected $primaryKey = 'id';
    protected $fillable = [
        'descricao',
    ];

    // ******************** RELASHIONSHIP ******************************
    // ********************** BELONGS ********************************

    // ************************** HASMANY **********************************
    public function parcelas()
    {
        return $this->hasMany('App\Models\Parcela', 'idstatus_parcela');
    }
}
