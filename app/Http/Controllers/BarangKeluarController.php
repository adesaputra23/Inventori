<?php

namespace App\Http\Controllers;

use App\BarangKeluar;
use App\Persediaan;
use App\PersediaanBarang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BarangKeluarController extends Controller
{
    public function index() {
        $title = 'Barang Keluar';
        $getPersediaanBarang = Persediaan::get();
        $listBarangKeluar = BarangKeluar::get();
        $persediaanBarang = [];
        $persediaanBarangUpdate = [];
        foreach ($getPersediaanBarang as $key => $value) {
            if ($value->jumlah_masuk > $value->jumlah_keluar) {
                $nest['id_persediaan'] = $value->id_persediaan;
                $nest['kode_persediaan'] = Persediaan::gnerateKode($value->id_persediaan);
                $nest['nama_barang'] = $value->nama_barang;
                array_push($persediaanBarang, $nest);
            }
            $nestUpdate['id_persediaan'] = $value->id_persediaan;
            $nestUpdate['kode_persediaan'] = Persediaan::gnerateKode($value->id_persediaan);
            $nestUpdate['nama_barang'] = $value->nama_barang;
            array_push($persediaanBarangUpdate, $nestUpdate);
        }
        $compact = ['title', 'persediaanBarang', 'persediaanBarangUpdate', 'listBarangKeluar'];
        return view('barang_keluar.index', compact($compact));
    }

    public function AddBarangKeluar(Request $req)
    {
        DB::beginTransaction();
        try {
            if ($req->aksi === "tambah") {
                $saveBarangKeluar = new BarangKeluar();
            }else{
                $saveBarangKeluar = BarangKeluar::where('kode_barang_keluar', $req->kode)->first();
            }
            $saveBarangKeluar->kode_barang_keluar = $req->kode;
            $saveBarangKeluar->id_persediaan = $req->persediaan;
            $saveBarangKeluar->tanggal_keluar = $req->tanggal_keluar;
            $saveBarangKeluar->no_surat_keluar = $req->no_surat;
            $saveBarangKeluar->uraian = $req->uraian;
            $saveBarangKeluar->jumlah_keluar = $req->jumlah_barang;
            $saveBarangKeluar->ket_keluar = $req->keterangan;
            $saveBarangKeluar->save();

            $persediaanBarang = Persediaan::pluck('id_persediaan')->toArray();
            $barangKeluar = BarangKeluar::whereIn('id_persediaan', $persediaanBarang)->get()->groupBy('id_persediaan');
            foreach ($barangKeluar as $key => $value) {
                $sumVal = $value->sum('jumlah_keluar');
                $updateKeluarPersediaanBarangSync = Persediaan::where('id_persediaan', $key)->first();
                $updateKeluarPersediaanBarangSync->jumlah_keluar = $sumVal;
                $jumlahSisaBarangSync = $updateKeluarPersediaanBarangSync->jumlah_masuk - $updateKeluarPersediaanBarangSync->jumlah_keluar;
                $updateKeluarPersediaanBarangSync->jumlah_sisa = $jumlahSisaBarangSync;
                $hargaTambahSync = $updateKeluarPersediaanBarangSync->jumlah_masuk * $updateKeluarPersediaanBarangSync->harga_satuan;
                $hargaKurangSync = $updateKeluarPersediaanBarangSync->jumlah_keluar * $updateKeluarPersediaanBarangSync->harga_satuan;
                $hargaSisaSync = $updateKeluarPersediaanBarangSync->jumlah_sisa * $updateKeluarPersediaanBarangSync->harga_satuan;
                $updateKeluarPersediaanBarangSync->harga_tambah = $hargaTambahSync;
                $updateKeluarPersediaanBarangSync->harga_kurang = $hargaKurangSync;
                $updateKeluarPersediaanBarangSync->harga_sisa = $hargaSisaSync;
                $updateKeluarPersediaanBarangSync->save();
            }

            DB::commit();
            return redirect()->route('keluar.index')->with('success', "{$req->aksi} data");
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            return redirect()->route('keluar.index')->with('error', "{$req->aksi} data : {$th->getMessage()}");
        }
    }

    public function getKode(Request $req)
    {
        try {
            if ($req->type === "getKode") {
                $barangKeluar = BarangKeluar::where('kode_barang_keluar', $req->kode)->first();
                return response()->json($barangKeluar);
            }else{
                $result = BarangKeluar::where('kode_barang_keluar', $req->kode)->first();
                if (!empty($result)) {
                    return false;
                }else {
                    return true;
                }
            }
            return false;
        } catch (\Throwable $th) {
            return response()->json($th->getMessage());
        }
    }

    public function destroy(Request $req)
    {
        DB::beginTransaction();
        try {
            $barangKeluar = BarangKeluar::where('kode_barang_keluar', $req->data_uniq)->first();
            BarangKeluar::where('kode_barang_keluar', $req->data_uniq)->delete();
            $cekJumlahBarangKeluar = BarangKeluar::where('id_persediaan', $barangKeluar->id_persediaan)->sum('jumlah_keluar');
            $updateKeluarPersediaanBarang = Persediaan::where('id_persediaan', $barangKeluar->id_persediaan)->first();
            $jumlahKeluarBarang = $cekJumlahBarangKeluar;
            $updateKeluarPersediaanBarang->jumlah_keluar = $jumlahKeluarBarang;
            $jumlahSisaBarang = $updateKeluarPersediaanBarang->jumlah_masuk - $updateKeluarPersediaanBarang->jumlah_keluar;
            $updateKeluarPersediaanBarang->jumlah_sisa = $jumlahSisaBarang;
            $hargaTambah = $updateKeluarPersediaanBarang->jumlah_masuk * $updateKeluarPersediaanBarang->harga_satuan;
            $hargaKurang = $updateKeluarPersediaanBarang->jumlah_keluar * $updateKeluarPersediaanBarang->harga_satuan;
            $hargaSisa = $updateKeluarPersediaanBarang->jumlah_sisa * $updateKeluarPersediaanBarang->harga_satuan;
            $updateKeluarPersediaanBarang->harga_tambah = $hargaTambah;
            $updateKeluarPersediaanBarang->harga_kurang = $hargaKurang;
            $updateKeluarPersediaanBarang->harga_sisa = $hargaSisa;
            $updateKeluarPersediaanBarang->save();
            DB::commit();
            return redirect()->route('keluar.index')->with('success', "hapus data");
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('keluar.index')->with('error', "hapus data : {$th->getMessage()}");
        }
    }
}
