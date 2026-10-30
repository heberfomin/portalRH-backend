<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class tbEmails extends Model
{
    use Notifiable;
    protected $table = 'tbEmails';
    protected $primaryKey = 'emailAk';
    protected $dateFormat = 'd/m/y H:i:s';
    public $timestamps = false;
    protected $fillable = 
    [ 
       'codFuncionario','desEmail','indTipo'
    ];
}
