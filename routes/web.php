<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('authv2.login');
// });
// Auth::routes();
// Route::get('/', 'HomeController@index')->name('home');

Route::get('/', function () {
    return redirect()->route('home');
});

Route::middleware(['guest'])->group(function () {
    Route::get('/login', 'UserController@Login')->name('login');
    Route::post('/login-proses', 'UserController@LoginProses')->name('login.proses');
});

Route::group(['middleware' => 'auth'], function(){
    Route::get('/logout', 'UserController@Logout')->name('logout');
    Route::get('/home', 'HomeController@index')->name('home');

    // User
    Route::name('user')->prefix('user')->group(
        function() {
            Route::get('/index', 'UserController@index')->name('.index');
            Route::post('/add-user', 'UserController@AddUser')->name('.add.user');
            Route::post('/uniq-nik', 'UserController@UniqNik')->name('.uniq.nik');
            Route::get('/get-nik', 'UserController@GetNik')->name('.get.nik');
            Route::get('/hapus-user', 'UserController@HapusUser')->name('.hapus.user');
            Route::post('/ubah-pass-user', 'UserController@ubahPasswordUser')->name('.ubah.password');
        }
    );

    Route::name('persediaan')->prefix('persediaan')->group(
        function(){
            Route::get('/index', 'PersediaanBarangController@index')->name('.index');
            Route::get('/get-id-persediaan', 'PersediaanBarangController@GetIdPersediaan')->name('.get.id_persediaan');
            Route::post('/add-persediaan', 'PersediaanBarangController@AddPersediaan')->name('.add.persediaan');
            Route::get('/hapus-persediaan', 'PersediaanBarangController@destroy')->name('.hapus.persediaan');
        }
    );

    Route::name('kategori')->prefix('kategori')->group(
        function(){
            Route::get('/index', 'KategoriController@index')->name('.index');
            Route::post('/add-kategori', 'KategoriController@create')->name('.add.kategori');
            Route::get('/get-kode', 'KategoriController@getKode')->name('.get.kode');
            Route::get('/hapus-kategori', 'KategoriController@destroy')->name('.hapus.kategori');
        }
    );

    Route::name('keluar')->prefix('keluar')->group(
        function(){
            Route::get('/index', 'BarangKeluarController@index')->name('.index');
            Route::post('/add-keluar', 'BarangKeluarController@AddBarangKeluar')->name('.add.keluar');
            Route::get('/get-kode', 'BarangKeluarController@getKode')->name('.get.kode');
            Route::get('/hapus-keluar', 'BarangKeluarController@destroy')->name('.hapus.keluar');
        }
    );

    Route::name('ruang')->prefix('ruang')->group(
        function(){
            Route::get('/index', 'RuangController@index')->name('.index');
            Route::post('/add-ruang', 'RuangController@AddRuang')->name('.add.ruang');
            Route::get('/get-kode', 'RuangController@getKode')->name('.get.kode');
            Route::get('/hapus-ruang', 'RuangController@destroy')->name('.hapus.ruang');
        }
    );

    Route::name('perpindahan')->prefix('perpindahan')->group(
        function(){
            Route::get('/index', 'PerpindahanController@index')->name('.index');
            Route::post('/add-perpindahan', 'PerpindahanController@AddPerpindahan')->name('.add.perpindahan');
            Route::get('/get-kode', 'PerpindahanController@getKode')->name('.get.kode');
            Route::get('/hapus-perpindahan', 'PerpindahanController@destroy')->name('.hapus.perpindahan');
        }
    );

    // Laporan PDF
    Route::name('laporan')->prefix('laporan')->group(
        function(){
            Route::get('/pdf-inventory','LaporanController@InventoryPDF')->name('.inventory.pdf');
            Route::get('/pdf-persediaan-barang','LaporanController@PersediaanBarangPDF')->name('.persediaan.barang.pdf');
            Route::get('/pdf-barang-keluar','LaporanController@BarangKeluarPDF')->name('.barang.keluar.pdf');
            Route::get('/pdf-perpindahan-barang','LaporanController@PerpindahanBarangPDF')->name('.perpindahan.barang.pdf');
        }
    );

    Route::get('/profile/{nik}', 'UserController@Profile')->name('profile');
    Route::post('/upload-gambar/{nik}', 'UserController@UploadGambar')->name('upload.gambar');
    Route::post('/update-profile/{nik}', 'UserController@UpdateProfile')->name('update.profile');

});


