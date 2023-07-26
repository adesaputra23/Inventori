<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PersediaanBarang extends Model
{
    protected $table        = 'tb_persediaan';
    protected $primaryKey   = 'id_persediaan';
    protected $keyType      = 'int';
    public $incrementing    = true;
    public $timestamps      = true;
}
