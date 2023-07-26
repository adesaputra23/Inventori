<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Perpindahan extends Model
{
    protected $table        = 'tb_perpindahan';
    protected $primaryKey   = 'id_perpindahan';
    protected $keyType      = 'int';
    public $incrementing    = true;
    public $timestamps      = true;

    public function ruang()
    {
        return $this->hasOne('App\Ruang', 'kode_ruang', 'kode_ruang');
    }

    public function persediaan()
    {
        return $this->hasOne('App\Persediaan', 'id_persediaan', 'id_persediaan');
    }
}
