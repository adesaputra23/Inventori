<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Kategori;
use Illuminate\Support\Facades\DB;
use App\Persediaan;
use App\PersediaanKode;
use App\Suplier;

class PersediaanBarangController extends Controller
{
    public function index()
    {
        $title = "Barang Masuk";
        // $listKategori = Kategori::get()->groupBy('type');
        $listKategori = Kategori::get();
        $listPersediaan = Persediaan::with('suplier')->get();
        $listSuplier = Suplier::get();
        $compact = ['title', 'listKategori', 'listPersediaan', 'listSuplier'];
        return view('persediaan_barang.index', compact($compact));
    }

    public function AddPersediaan(Request $req)
    {
        DB::beginTransaction();
        try {
            $date = date('Y-m-d H:i:s');
            $file = $req->file('file_gambar');
            $isImage = Controller::saveImage($file, $req);
            if($req->aksi === "tambah"){
                $savePersediaan = new Persediaan();
                $savePersediaan->created_at = $date;
            }else{
                $savePersediaan = Persediaan::where('id_persediaan', $req->id_persediaan)->first();
                $savePersediaan->updated_at = $date;
                $deleteKodePersediaan = PersediaanKode::where('id_persediaan', $req->id_persediaan);
                $deleteKodePersediaan->delete();
            }
            $savePersediaan->kode_suplier = $req->suplier;
            $savePersediaan->nama_barang = $req->nama_barang;
            $savePersediaan->skpd = $req->skpd;
            $savePersediaan->persediaan_no_surat = $req->no_surat;
            $savePersediaan->jumlah_masuk = $req->jumlah_masuk;
            $savePersediaan->harga_satuan = $req->harga_satuan;
            $savePersediaan->ket_persediaan = $req->keterangan;
            $savePersediaan->tanggal_persediaan = $req->tanggal_persediaan;
            $savePersediaan->foto_barang = $isImage;
            $savePersediaan->save();

            // kode kategor save
            $arrayKodeKategori = [];
            $golongan = Kategori::getIdKategori($req->kode_golongan, 'kode_golongan');
            // $bidang = Kategori::getIdKategori($req->kode_bidang, 'kode_bidang');
            // $kelompok = Kategori::getIdKategori($req->kode_kelompok, 'kode_kelompok');
            // $sub_kelompok = Kategori::getIdKategori($req->kode_sub_kelompok, 'kode_sub_kelompok');
            // $sub_sub_kelompok = Kategori::getIdKategori($req->kode_sub_sub_kelompok, 'kode_sub_sub_kelompok');
            $register = Kategori::getIdKategori($req->kode_registrasi, 'kode_register');
            // array_push($arrayKodeKategori, $golongan, $bidang, $kelompok, $sub_kelompok, $sub_sub_kelompok, $register);
            array_push($arrayKodeKategori, $golongan, $register);
            foreach ($arrayKodeKategori as $key => $value) {
                $saveKodeKategori = new PersediaanKode();
                $saveKodeKategori->id_persediaan = $savePersediaan->id_persediaan;
                $saveKodeKategori->id_kategori = $value['id_kategori'];
                $saveKodeKategori->kode_kategori = $value['kode'];
                $saveKodeKategori->save();
            }
            DB::commit();
            return redirect()->route('persediaan.index')->with('success', "{$req->aksi} data");
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('persediaan.index')->with('error', "{$req->aksi} data : ".$th->getMessage());
        }
    }

    public function GetIdPersediaan(Request $req)
    {
        try {
            if(isset($req->idPersediaan)){
                $getId = Persediaan::with('suplier')->where('id_persediaan', $req->idPersediaan)->first();
                $kodeBarang = Persediaan::gnerateKode($req->idPersediaan);
                $getKodePersediaan = PersediaanKode::where('id_persediaan', $req->idPersediaan)->get();
                $data = [
                    'persediaan' => $getId,
                    'kodeBarang' => $kodeBarang,
                    'getKodePersediaan' => $getKodePersediaan,
                ];
                return response()->json($data);
            }
        } catch (\Throwable $th) {
            return response()->json($th->getMessage());
        }
    }

    public function destroy(Request $req)
    {
        DB::beginTransaction();
        try {
            $persediaanBarang = Persediaan::where('id_persediaan', $req->data_uniq);
            $persediaanKode = PersediaanKode::where('id_persediaan', $req->data_uniq);
            $persediaanBarang->delete();
            $persediaanKode->delete();
            DB::commit();
            return redirect()->route('persediaan.index')->with('success', "Hapus data");
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('persediaan.index')->with('error', "Hapus data : ".$th->getMessage());
        }
    }
}
