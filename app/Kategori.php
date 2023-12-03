<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    protected $table        = 'tb_kategori';
    protected $primaryKey   = 'id_kategori';
    protected $keyType      = 'int';
    public $incrementing    = true;
    public $timestamps      = true;

    const typeKategori = [
        1 => 'Golongan',
        2 => 'Bidang',
        3 => 'Kelompok',
        4 => 'Sub Kelompok',
        5 => 'Sub-sub Kelompok'
    ];

    public static function getIdKategori($kode, $type)
    {
        if ($type == "kode_register") {
            $kategori = [
                "id_kategori" => "00",
                "kode" => $kode,
            ];
            return $kategori;
        }else{
            // $aray_type = [
            //     'kode_golongan' => 1,
            //     'kode_bidang' => 2,
            //     'kode_kelompok' => 3,
            //     'kode_sub_kelompok' => 4,
            //     'kode_sub_sub_kelompok' => 5
            // ];
            // $typeKategori = $aray_type[$type];
            // $kategori = self::select('id_kategori', 'kode')->where('kode', $kode)->where('type', $typeKategori)->first()->toArray();
            $kategori = self::select('id_kategori', 'kode')->where('kode', $kode)->first()->toArray();
            return $kategori;
        }
        return false;

    }

}
