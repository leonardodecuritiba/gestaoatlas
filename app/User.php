<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Zizaco\Entrust\Traits\EntrustUserTrait;

class User extends Authenticatable
{
    use EntrustUserTrait; // add this trait to your user model
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    use EntrustUserTrait;
    protected $table = 'users';
    protected $primaryKey = 'iduser';
    protected $fillable = [
        'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

	public function is( $name = null ) {
		$role = $this->roles->first();

		return ( $name == null ) ? $role : ( $role->name == $name );
	}

    // ******************** RELASHIONSHIP ******************************
    public function colaborador()
    {
        return $this->belongsTo('App\Colaborador', 'iduser', 'iduser');
    }
    // ************************** HAS **********************************

//
//    public function role_user()
//    {
//        return $this->hasOne('App\RoleUser', 'iduser');
//    }
}
