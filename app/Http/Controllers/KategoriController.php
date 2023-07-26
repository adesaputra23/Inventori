<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Kategori;
use Illuminate\Support\Facades\DB;

class KategoriController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = "Kategori";
        $list_data = Kategori::all();
        $mapKategori = Kategori::typeKategori;
        $compact = ['title', 'list_data', 'mapKategori'];
        return view('kategori.index', compact($compact));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $req)
    {   
        try {
            $dateTime = date('Y-m-d H:i:s');
            if ($req->aksi === "tambah") {
                $saveKategori = new Kategori();
                $saveKategori->kode = $req->kode;
                $saveKategori->created_at = $dateTime;
            }else{
                $saveKategori = Kategori::where('kode', $req->kode)->first();
                $saveKategori->updated_at = $dateTime;
            }
            $saveKategori->nama_kategori = $req->nama_kategori;
            $saveKategori->type = $req->type;
            $saveKategori->save();
            return redirect()->route('kategori.index')->with('success', "$req->aksi data");
        } catch (\Throwable $th) {
            return redirect()->route('kategori.index')->with('error', "$req->aksi data. Error : $th->getMessage()");
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
            $kategori = Kategori::where('kode', $req->data_uniq)->delete();
            return redirect()->route('kategori.index')->with('success', " Hapus kode : {$req->data_uniq}");
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('kategori.index')->with('error', " Hapus kode : {$req->data_uniq} ".$th->getMessage());
        }
    }

    public function getKode(Request $req)
    {
        try {
            if ($req->type === "getKode") {
                $result = Kategori::where('kode', $req->kode)->first();
                return response()->json($result);
            }else{
                $result = Kategori::where('kode', $req->kode)->where('type', $req->isType)->first();
                if (!empty($result)) {
                    return false;
                }else {
                    return true;
                }
            }
        } catch (\Throwable $th) {
            return response()->json($th->getMessage());
        }
    }

}
