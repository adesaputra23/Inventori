<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PersediaanKode extends Model
{
    protected $table        = 'tb_kode_persediaan';
    protected $primaryKey   = 'id_kode_persediaan';
    protected $keyType      = 'int';
    public $incrementing    = true;
    public $timestamps      = true;

    public function idKategori()
    {
        return $this->hasOne('App\Kategori', 'id_kategori', 'id_kategori');
    }

}
