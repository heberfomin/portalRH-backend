<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class tbTelefones extends Model
{
    use Notifiable;
    protected $table = 'tbTelefones';
    protected $primaryKey = 'telefoneAk';
    protected $dateFormat = 'd/m/y H:i:s';
    public $timestamps = false;
    protected $fillable = 
    [ 
        'codFuncionario','desDDD','desTelefone','desTipo'
    ];
}
