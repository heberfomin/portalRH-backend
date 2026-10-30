<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class vwDocPeriodo extends Model
{
    use Notifiable;
    protected $table = 'vw_doc_periodo';
    protected $primaryKey = 'codFuncionario';
    protected $dateFormat = 'd/m/y H:i:s';
    public $timestamps = false;
    protected $fillable = 
    [ 
        'codFuncionario','datDocumento','numPeriodo','desPeriodo', 'HoleriteAk','Holerite','PontoAk','Ponto'
    ];
}
