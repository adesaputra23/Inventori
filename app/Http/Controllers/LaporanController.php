<?php

namespace App\Http\Controllers;

use App\BarangKeluar;
use App\Perpindahan;
use App\PersediaanBarang;
use Illuminate\Http\Request;
use PDF;
class LaporanController extends Controller
{
    public function InventoryPDF(Request $request)
    {
        try {
            $title = 'Laporan Kartu Inventory Brang';
            $listData = PersediaanBarang::where('jumlah_keluar', '!=', null);
            if (isset($request->filter) || isset($request->cetakPdf)) {
                $tanggal_start = $request->tanggal_start;
                $tanggal_end = $request->tanggal_akhir;
                $listData = $listData->whereBetween('tanggal_persediaan', [$request->tanggal_start, $request->tanggal_akhir]);
            }
            $listData = $listData->get();
            if (count($listData) == 0) {
                $listData = PersediaanBarang::where('jumlah_keluar', '!=', null)->get();
            }
            if (isset($request->cetakPdf)) {
                $includeFile = 'LaporanPDF.inventory_pdf';
                $compact = ['title', 'listData', 'tanggal_start', 'tanggal_end', 'includeFile'];
                $pdf = PDF::loadView('LaporanPDF.pdf', compact($compact))->setPaper('A4', 'landscape');
                return $pdf->download($title.'.pdf');
            }
            $compact = ['title', 'listData'];
            return view('LaporanPDF.laporan_inventory', compact($compact));
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }

    public function PersediaanBarangPDF(Request $request)
    {
        try {
            $title = 'Laporan Kartu Persediaan Barang';
            $listData = PersediaanBarang::get();
            if (isset($request->filter) || isset($request->cetakPdf)) {
                $tanggal_start = $request->tanggal_start;
                $tanggal_end = $request->tanggal_akhir;
                $listData = PersediaanBarang::whereBetween('tanggal_persediaan', [$request->tanggal_start, $request->tanggal_akhir])->get();
                if(count($listData) == 0)$listData = PersediaanBarang::get();
            }
            if (isset($request->cetakPdf)) {
                $includeFile = 'LaporanPDF.persediaan_pdf';
                $compact = ['title', 'listData', 'tanggal_start', 'tanggal_end', 'includeFile'];
                $pdf = PDF::loadView('LaporanPDF.pdf', compact($compact))->setPaper('A4', 'landscape');
                return $pdf->download($title.'.pdf');
            }
            $compact = ['title', 'listData'];
            return view('LaporanPDF.laporan_persediaan', compact($compact));
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }

    public function BarangKeluarPDF(Request $request)
    {
        try {
            $title = 'Laporan Kartu Barang Keluar';
            $listData = BarangKeluar::all();
            if (isset($request->filter) || isset($request->cetakPdf)) {
                $tanggal_start = $request->tanggal_start;
                $tanggal_end = $request->tanggal_akhir;
                $listData = BarangKeluar::whereBetween('tanggal_keluar', [$tanggal_start, $tanggal_end])->get();
                if (count($listData) == 0)$listData = BarangKeluar::get();
            }
            if (isset($request->cetakPdf)) {
                $includeFile = 'LaporanPDF.keluar_pdf';
                $compact = ['title', 'listData', 'tanggal_start', 'tanggal_end', 'includeFile'];
                $pdf = PDF::loadView('LaporanPDF.pdf', compact($compact))->setPaper('A4', 'landscape');
                return $pdf->download($title.'.pdf');
            }
            $compact = ['title', 'listData'];
            return view('LaporanPDF.laporan_keluar', compact($compact));
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }

    public function PerpindahanBarangPDF(Request $request)
    {
        try {
            $title = 'Laporan Kartu Perpindahan Barang';
            $listData = Perpindahan::all();
            if (isset($request->filter) || isset($request->cetakPdf)) {
                $tanggal_start = $request->tanggal_start;
                $tanggal_end = $request->tanggal_akhir;
                $listData = Perpindahan::whereBetween('tanggal_perpindahan', [$tanggal_start, $tanggal_end])->get();
                if (count($listData) == 0)$listData = Perpindahan::get();
            }
            if (isset($request->cetakPdf)) {
                $includeFile = 'LaporanPDF.perpindahan_pdf';
                $compact = ['title', 'listData', 'tanggal_start', 'tanggal_end', 'includeFile'];
                $pdf = PDF::loadView('LaporanPDF.pdf', compact($compact))->setPaper('A4', 'landscape');
                return $pdf->download($title.'.pdf');
            }
            $compact = ['title', 'listData'];
            return view('LaporanPDF.laporan_perpindahan', compact($compact));
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }
}
