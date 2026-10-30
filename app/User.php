<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;
    protected $dateFormat = 'd/m/y H:i:s';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'username', 'email', 'password', 'perfil', 'codFuncionario'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'created_at', 'updated_at',
    ];

        public function getCreatedAtAttribute($timestamp) 
    { 
        //return $timestamp->format('M d, Y'); 
        return Carbon::parse($timestamp)->format('d/m/y H:i:s');
    }
    public function getUpdatedAtAttribute($timestamp) 
    { 
        //return $timestamp->format('d/m/y H:i:s'); 
        return Carbon::parse($timestamp)->format('d/m/y H:i:s');
    }
}
