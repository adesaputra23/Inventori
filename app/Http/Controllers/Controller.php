<?php

namespace App\Http\Controllers;

use App\Persediaan;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function saveImage($file, $req)
    {
        if (isset($file)) {

            if ($req->aksi === 'edit') {
                $Persediaan = Persediaan::where('id_persediaan', $req->id_persediaan)->first();
                $fileName = $file->getClientOriginalName();
                if ($Persediaan != null && ($Persediaan->foto_barang === $fileName)) {
                    return $fileName;
                }
            }

            $extension = $file->getClientOriginalExtension();
            $fileName = str_replace(" ", "_", $req->format_kode).'_'.date('His').'.'.$extension;
            $tujuan_upload = 'img_barang';
            $file->move($tujuan_upload,$fileName);
            return $fileName;
        }
    }

}
