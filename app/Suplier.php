<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Suplier extends Model
{
    protected $table        = 'tb_suplier';
    protected $primaryKey   = 'id_suplier';
    protected $keyType      = 'int';
    public $incrementing    = true;
    public $timestamps      = true;
}
