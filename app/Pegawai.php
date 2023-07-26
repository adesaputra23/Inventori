<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pegawai extends Model
{
    protected $table        = 'tb_pegawai';
    protected $primaryKey   = 'nik';
    protected $keyType      = 'string';
    public $timestamps      = true;
}
