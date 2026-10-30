<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class tbDocumentos extends Model
{
    use Notifiable;
    protected $table = 'tbDocumentos';
    protected $primaryKey = 'documentoAk';
    protected $dateFormat = 'd/m/y H:i:s';
    public $timestamps = false;
    protected $fillable = 
    [ 
       'codFuncionario','datDocumento','numPeriodo','codTipo','desArquivo'
    ];
}
