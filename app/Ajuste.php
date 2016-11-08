<?php

namespace App;

use App\Helpers\DataHelper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ajuste extends Model
{
    use SoftDeletes;
    protected $table = 'ajustes';
    protected $primaryKey = 'id';
    public $timestamps = true;
    protected $fillable = [
        'meta_key',
        'meta_value'
    ];

    public function getCreatedAtAttribute($value)
    {
        return DataHelper::getPrettyDateTime($value);
    }
    static public function getByMetaKey($value)
    {
        return self::where('meta_key',$value)->first();
    }
}
