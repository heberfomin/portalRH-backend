<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class tbEnderecos extends Model
{
    use Notifiable;
    protected $table = 'tbEnderecos';
    protected $primaryKey = 'enderecoAk';
    protected $dateFormat = 'd/m/y H:i:s';
    public $timestamps = false;
    protected $fillable = 
    [
        'codFuncionario','desLogradouro','desNumero','desComplemento',
        'desCEP','desBairro','desCidade','desEstado','desPais',
        'indPrincipal','indTipo' 
    ];
}
