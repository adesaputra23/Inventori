<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ruang extends Model
{
    protected $table        = 'tb_ruang';
    protected $primaryKey   = 'id_ruang';
    protected $keyType      = 'int';
    public $incrementing    = true;
    public $timestamps      = true;
}
