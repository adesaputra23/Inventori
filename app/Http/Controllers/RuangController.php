<?php

namespace App\Http\Controllers;

use App\Ruang;
use Illuminate\Http\Request;

class RuangController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = 'Ruang';
        $listData = Ruang::get();
        $compack = ['title', 'listData'];
        return view('ruang.index', compact($compack));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    public function AddRuang(Request $req)
    {
        try {
            if ($req->aksi === "tambah") {
                $saveRuang = new Ruang();
                $saveRuang->kode_ruang = $req->kode;
            }else{
                $saveRuang = Ruang::where('kode_ruang', $req->kode)->first();
            }
            $saveRuang->nama_ruang = $req->nama_ruangan;
            $saveRuang->ket_ruang = $req->keterangan;
            $saveRuang->save();
            return redirect()->route('ruang.index')->with('success', "$req->aksi data");
        } catch (\Throwable $th) {
            return redirect()->route('ruang.index')->with('error', "$req->aksi data ".$th->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getKode(Request $request)
    {
        try {
            if ($request->type === 'edit') {
                $data = Ruang::where('kode_ruang', $request->kode)->first();
                return response()->json($data);
            }else{
                $result = Ruang::where('kode_ruang', $request->kode)->count();
                if ($result > 0) {
                    return false;
                }else {
                    return true;
                }
            }
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
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
            $hapusData = Ruang::where('kode_ruang', $req->data_uniq);
            $hapusData->delete();
            return redirect()->route('ruang.index')->with('success', "hapus data");
        } catch (\Throwable $th) {
            return redirect()->route('ruang.index')->with('error', "hapus data ".$th->getMessage());
        }
    }
}
