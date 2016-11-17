<?php

namespace App;

use App\Helpers\DataHelper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ajuste extends Model
{
    public $timestamps = true;
    protected $table = 'ajustes';
    protected $primaryKey = 'id';
    protected $fillable = [
        'meta_key',
        'meta_value'
    ];

    static public function getByMetaKey($value)
    {
        return self::where('meta_key',$value)->first();
    }

    public function getCreatedAtAttribute($value)
    {
        return DataHelper::getPrettyDateTime($value);
    }
}
