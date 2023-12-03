<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Persediaan extends Model
{
    protected $table        = 'tb_persediaan';
    protected $primaryKey   = 'id_persediaan';
    protected $keyType      = 'int';
    public $incrementing    = true;
    public $timestamps      = true;

    public static function gnerateKode($id)
    {
        $kode_persediaan = PersediaanKode::select('kode_kategori')->where('id_persediaan', $id)->get()->toArray();
        $hotel_name = array_column($kode_persediaan, 'kode_kategori');
        return join('.',$hotel_name);
    }

    public function suplier()
    {
        return $this->hasOne('App\Suplier', 'kode_suplier', 'kode_suplier');
    }
}
