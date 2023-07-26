<?php

namespace App\Http\Controllers;

use App\BarangKeluar;
use App\Kategori;
use App\Perpindahan;
use App\Persediaan;
use App\Ruang;
use App\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $title = "Home";
        $countRuang = Ruang::count();
        $countKategori = Kategori::count();
        $countPersediaan = Persediaan::count();
        $countBarangKeluar = BarangKeluar::count();
        $countPerpindahanBarang = Perpindahan::count();
        $countUser = User::count();
        $compac = ['title', 'countRuang', 'countKategori', 'countPersediaan', 'countBarangKeluar', 'countPerpindahanBarang', 'countUser'];
        return view('home', compact($compac));
    }
}
