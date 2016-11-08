<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Kit extends Model
{
    use SoftDeletes;
    protected $table = 'kits';
    protected $primaryKey = 'idkit';
    public $timestamps = true;
    protected $fillable = [
        'nome',
        'descricao',
        'observacao',
    ];

    public function valor_total()
    {
        $val = $this->hasMany('App\PecaKit', 'idkit')->sum('valor_total');
        return number_format($val,2,',','.');
    }
    // ******************** FUNCTIONS ******************************
    public function has_insumos()
    {
        return ($this->insumos()->count() > 0);
    }
    public function getCreatedAtAttribute($value)
    {
        if($value != NULL) return Carbon::createFromFormat('Y-m-d H:i:s', $value)->format('d/m/Y H:i');
    }
    // ******************** RELASHIONSHIP ******************************
    // ********************** BELONGS ********************************

    // ************************** HASMANY **********************************
    public function pecas_kit()
    {
        return $this->hasMany('App\PecaKit', 'idkit');
    }
    public function insumos()
    {
        return $this->hasMany('App\Insumo', 'idinsumo');
    }
}
