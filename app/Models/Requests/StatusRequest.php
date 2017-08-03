<?php

namespace App\Models\Requests;

use App\Helpers\DataHelper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StatusRequest extends Model
{
    const _STATUS_AGUARDANDO_ = 1;
    const _STATUS_ACEITA_ = 2;
    const _STATUS_NEGADA_ = 3;

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

