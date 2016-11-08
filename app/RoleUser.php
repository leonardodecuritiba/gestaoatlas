<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class RoleUser extends Model
{
    protected $table = 'role_user';
    public $timestamps = true;
    protected $fillable = [
        'iduser',
        'role_id'
    ];

    // ******************** RELASHIONSHIP ******************************
    // ************************** HAS **********************************
    public function user()
    {
        return $this->hasOne('App\User', 'iduser', 'iduser');
    }
    public function role()
    {
        return $this->hasOne('App\Role', 'id', 'role_id');
    }
}