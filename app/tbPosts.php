<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class tbPosts extends Model
{
    use Notifiable;
    protected $table = 'tbPosts';
    protected $primaryKey = 'postsAk';
    protected $dateFormat = 'd/m/y H:i:s';
    public $timestamps = false;
    protected $fillable = 
    [
        'codFuncionario','codOrigem','desAssunto','desPost','datPost','indStatus'
    ];
    protected $appends = ['desOrigem','desTo'];
    public function getDesOrigemAttribute()
    {
        $data = tbFuncionarios::where('codFuncionario',$this->codOrigem)->first();
        return $data['desFuncionario'];
    }
    public function getDesToAttribute()
    {
        $data = tbFuncionarios::where('codFuncionario',$this->codFuncionario)->first();
        return $data['desFuncionario'];
    }
    public function tbFuncionarios()
    {
        return hasOne('tbFuncionarios','codFuncionario','codFuncionario');
    }
}
