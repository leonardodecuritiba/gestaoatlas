<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Equipamento extends Model
{
    protected $table = 'equipamentos';
    protected $primaryKey = 'idequipamento';
    public $timestamps = true;
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
    public function getFoto()
    {
        return ($this->foto!='')?asset('../storage/uploads/'.$this->table.'/'.$this->foto):asset('imgs/cogs.png');
    }
    public function getFotoThumb()
    {
        return ($this->foto!='')?asset('../storage/uploads/'.$this->table.'/thumb_'.$this->foto):asset('imgs/cogs.png');
    }

    // ******************** RELASHIONSHIP ******************************
    // ************************** HAS **********************************
    public function marca()
    {
        return $this->hasOne('App\Marca', 'idmarca');
    }
    public function cliente()
    {
        return $this->belongsTo('App\Cliente', 'idcliente');
    }

    // ************************** HASMANY **********************************
    public function aparelho_manutencao()
    {
        return $this->hasMany('App\AparelhoManutencao', 'idequipamento');
    }
}
