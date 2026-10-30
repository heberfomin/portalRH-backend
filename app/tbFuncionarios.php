<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class tbFuncionarios extends Model
{
    use Notifiable;
    protected $table = 'tbFuncionarios';
    protected $primaryKey = 'funcionarioAk';
    protected $dateFormat = 'd/m/y H:i:s';
    public $timestamps = false;
    protected $fillable = 
    [
        'funcionarioAk','codFuncionario','codRH','codIDFederal',
        'codIDERP','desFuncionario','desAbreviado','datAdmissao',
        'datDemissao','indAtivo','desIP','desLatitude','desLongitude'
    ];

}
