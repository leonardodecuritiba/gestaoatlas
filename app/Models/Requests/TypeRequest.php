<?php

namespace App\Models\Requests;

use App\Helpers\DataHelper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TypeRequest extends Model
{
    const _TYPE_SELOS_ = 1;
    const _TYPE_LACRES_ = 2;
    const _TYPE_PADROES_ = 3;
    const _TYPE_FERRAMENTAS_ = 4;
    const _TYPE_EQUIPAMENTOS_ = 5;
	const _TYPE_VEICULOS_ = 6;
	const _TYPE_PECAS_ = 7;

    use SoftDeletes;
    public $timestamps = true;
    protected $fillable = [
        'description',
    ];

    public function getCreatedAtAttribute($value)
    {
        return DataHelper::getPrettyDateTime($value);
    }

}

