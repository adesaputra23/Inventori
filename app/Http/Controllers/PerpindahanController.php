<?php

namespace App\Http\Controllers;

use App\Perpindahan;
use App\Persediaan;
use App\Ruang;
use Illuminate\Http\Request;

class PerpindahanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = "Perpindahan";
        $listPerpindahan = Perpindahan::get();
        $getIdPerpindahan = Perpindahan::pluck('id_persediaan')->toArray();
        $getPersediaanBarang = Persediaan::where('jumlah_sisa', '>', 0)->get();
        $getRuangan = Ruang::get();
        $persediaanBarang = [];
        $persediaanBarangUpdate = [];
        foreach ($getPersediaanBarang as $key => $value) {
            if (!in_array($value->id_persediaan, $getIdPerpindahan)) {
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
        $compact = ['title', 'persediaanBarang', 'persediaanBarangUpdate', 'getRuangan', 'listPerpindahan'];
        return view('perpindahan.index', compact($compact));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function AddPerpindahan(Request $request)
    {
        try {

            if ($request->aksi === 'tambah') {
                $savePerpindahan = new Perpindahan();
                $savePerpindahan->kode_perpindahan = $request->kode;
            }else{
                $savePerpindahan = Perpindahan::where('kode_perpindahan', $request->kode)->first();
            }
            $savePerpindahan->id_persediaan = $request->persediaan;
            $savePerpindahan->kode_ruang = $request->ruangan;
            $savePerpindahan->no_surat_perpindahan = $request->no_surat;
            $savePerpindahan->tanggal_perpindahan = $request->tanggal;
            // $savePerpindahan->uraian_perpindahan = $request->uraian;
            $savePerpindahan->ket_perpindahan = $request->keterangan;
            $savePerpindahan->save();

            return redirect()->route('perpindahan.index')->with('success', "{$request->aksi} Data");
        } catch (\Throwable $th) {
            return redirect()->route('perpindahan.index')->with('error', "{$request->aksi} Data {$th->getMessage()}");
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $req)
    {
        try {
            Perpindahan::where('kode_perpindahan', $req->data_uniq)->delete();
            return redirect()->route('perpindahan.index')->with('success', " Hapus kode : {$req->data_uniq}");
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('perpindahan.index')->with('error', " Hapus kode : {$req->data_uniq} ".$th->getMessage());
        }
    }

    public function getKode(Request $request)
    {
        try {
            if ($request->type === "getKode") {
                $perpindahanBarang = Perpindahan::where('kode_perpindahan', $request->kode)->first();
                return response()->json($perpindahanBarang);
            }else{
                $result = Perpindahan::where('kode_perpindahan', $request->kode)->first();
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
}
