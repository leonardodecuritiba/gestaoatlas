<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Equipamento extends Model
{
    public $timestamps = true;
    protected $table = 'equipamentos';
    protected $primaryKey = 'idequipamento';
    protected $fillable = [
        'idcliente',
        'idmarca',
        'idcolaborador_criador',
        'idcolaborador_validador',
        'validated_at',
        'descricao',
        'foto',
        'modelo',
        'numero_serie'
    ];
    // ******************** FUNCTIONS ****************************
    public function has_aparelho_manutencao()
    {
        return ($this->aparelho_manutencao()->count() > 0);
    }

    public function aparelho_manutencao()
    {
        return $this->hasMany('App\AparelhoManutencao', 'idequipamento');
    }

    public function getFoto()
    {
        return ($this->attributes['foto'] != '') ? asset('uploads/' . $this->table . '/' . $this->attributes['foto']) : asset('imgs/cogs.png');
    }

    // ******************** RELASHIONSHIP ******************************
    // ************************** HAS **********************************

    public function getFotoThumb()
    {
        return ($this->attributes['foto'] != '') ? asset('uploads/' . $this->table . '/thumb_' . $this->attributes['foto']) : asset('imgs/cogs.png');
    }

    public function marca()
    {
        return $this->belongsTo('App\Marca', 'idmarca');
    }

    // ************************** HASMANY **********************************

    public function cliente()
    {
        return $this->belongsTo('App\Cliente', 'idcliente');
    }
}
