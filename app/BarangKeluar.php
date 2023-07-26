<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BarangKeluar extends Model
{
    protected $table        = 'tb_barang_keluar';
    protected $primaryKey   = 'id_barang_keluar';
    protected $keyType      = 'int';
    public $incrementing    = true;
    public $timestamps      = true;

    public function persediaan()
    {
        return $this->hasOne('App\Persediaan', 'id_persediaan', 'id_persediaan');
    }
}
