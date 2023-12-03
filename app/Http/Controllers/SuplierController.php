<?php

namespace App\Http\Controllers;

use App\Suplier;
use Illuminate\Http\Request;

class SuplierController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = 'Suplier';
        $listData = Suplier::get();
        $compact = ['title', 'listData'];
        return view('suplier.index', compact($compact));
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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            if ($request->aksi === "tambah") {
                $save = new Suplier();
                $save->kode_suplier = $request->kode_suplier;
            } else {
                $save = Suplier::where('kode_suplier', $request->kode_suplier)->first();
            }
            $save->nama_suplier = $request->nama_suplier;
            $save->instansi = $request->nama_instansi;
            $save->no_telpon = $request->no_telpon;
            $save->alamat = $request->alamat;
            $save->created_at = date('Y-m-d H:i:s');
            $save->save();
            return redirect()->route('suplier.index')->with('success', "$request->aksi data");
        } catch (\Throwable $th) {
            return redirect()->route('suplier.index')->with('error', "$request->aksi data " . $th->getMessage());
        }
    }

    public function getKode(Request $request)
    {
        try {
            if ($request->type === 'edit') {
                $data = Suplier::where('kode_suplier', $request->kode)->first();
                return response()->json($data);
            } else {
                $result = Suplier::where('kode_suplier', $request->kode)->count();
                if ($result > 0) {
                    return false;
                } else {
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
            $hapusData = Suplier::where('kode_suplier', $req->data_uniq);
            $hapusData->delete();
            return redirect()->route('suplier.index')->with('success', "hapus data");
        } catch (\Throwable $th) {
            return redirect()->route('suplier.index')->with('error', "hapus data " . $th->getMessage());
        }
    }
}
