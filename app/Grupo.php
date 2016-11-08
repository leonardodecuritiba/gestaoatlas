<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Grupo extends Model
{
    use SoftDeletes;
    protected $table = 'grupos';
    protected $primaryKey = 'idgrupo';
    public $timestamps = true;
    protected $fillable = [
        'descricao',
    ];

    // ******************** RELASHIONSHIP ******************************
    // ********************** BELONGS ********************************
    public function peca()
    {
        return $this->belongsTo('App\Peca', 'idpeca');
    }
    // ************************** HASMANY **********************************
}
