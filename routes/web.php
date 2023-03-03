<?php

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Route;

// controller admin
use App\Http\Controllers\aDashboard;
use App\Http\Controllers\aKategori;
use App\Http\Controllers\aItem;
use App\Http\Controllers\aAdmin;
use App\Http\Controllers\aAuth;
use App\Http\Controllers\aSupplier;
use App\Http\Controllers\aPelanggan;
use App\Http\Controllers\aItemMasuk;
use App\Http\Controllers\aItemKeluar;
use App\Http\Controllers\aLaporan;

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

Route::get('gambar/{filename}', function ($filename)
{
	$path = public_path('uploads/pic/'.$filename);

    if(!File::exists($path))
	{
        $path = public_path('uploads/pic/noimage.jpg');
    }

    $file = File::get($path);
    $type = File::mimeType($path);

    $response = Response::make($file, 200);
    $response->header("Content-Type", $type);

    return $response;

});

//  ================================= ADMIN ROUTE ===============================

// DASHBOARD
Route::get('/', [aDashboard::class, 'index']);
Route::get('/dashboard', [aDashboard::class, 'index'])->middleware('ceklogin');

// AUTH
Route::get('/auth/login', [aAuth::class, 'login']);
Route::get('/auth/profile', [aAuth::class, 'profile'])->middleware('ceklogin');
Route::any('/auth/actlogin', [aAuth::class, 'actlogin']);
Route::any('/auth/actlogout', [aAuth::class, 'actlogout']);
Route::any('/auth/actupdateprofile', [aAuth::class, 'actupdateprofile']);

// Kategori
Route::any('/kategori/list', [aKategori::class, 'list'])->middleware('ceklogin');
Route::any('/kategori/tambah', [aKategori::class, 'tambah'])->middleware('ceklogin');
Route::any('/kategori/edit/{id}', [aKategori::class, 'edit'])->middleware('ceklogin');
Route::any('/kategori/acttambah', [aKategori::class, 'acttambah'])->middleware('ceklogin');
Route::any('/kategori/actedit', [aKategori::class, 'actedit'])->middleware('ceklogin');
Route::any('/kategori/acthapus/{id}', [aKategori::class, 'acthapus'])->middleware('ceklogin');

// Item
Route::any('/item/list', [aItem::class, 'list'])->middleware('ceklogin');
Route::any('/item/tambah', [aItem::class, 'tambah'])->middleware('ceklogin');
Route::any('/item/edit/{id}', [aItem::class, 'edit'])->middleware('ceklogin');
Route::any('/item/acttambah', [aItem::class, 'acttambah'])->middleware('ceklogin');
Route::any('/item/actedit', [aItem::class, 'actedit'])->middleware('ceklogin');
Route::any('/item/acthapus/{id}', [aItem::class, 'acthapus'])->middleware('ceklogin');
Route::any('/item/stoklist', [aItem::class, 'stoklist'])->middleware('ceklogin');

// Admin
Route::any('/admin/list', [aAdmin::class, 'list'])->middleware('ceklogin');
Route::any('/admin/tambah', [aAdmin::class, 'tambah'])->middleware('ceklogin');
Route::any('/admin/edit/{id}', [aAdmin::class, 'edit'])->middleware('ceklogin');
Route::any('/admin/acttambah', [aAdmin::class, 'acttambah'])->middleware('ceklogin');
Route::any('/admin/actedit', [aAdmin::class, 'actedit'])->middleware('ceklogin');
Route::any('/admin/acthapus/{id}', [aAdmin::class, 'acthapus'])->middleware('ceklogin');

// Supplier
Route::any('/supplier/list', [aSupplier::class, 'list'])->middleware('ceklogin');
Route::any('/supplier/tambah', [aSupplier::class, 'tambah'])->middleware('ceklogin');
Route::any('/supplier/edit/{id}', [aSupplier::class, 'edit'])->middleware('ceklogin');
Route::any('/supplier/acttambah', [aSupplier::class, 'acttambah'])->middleware('ceklogin');
Route::any('/supplier/actedit', [aSupplier::class, 'actedit'])->middleware('ceklogin');
Route::any('/supplier/acthapus/{id}', [aSupplier::class, 'acthapus'])->middleware('ceklogin');

// Pelanggan
Route::any('/pelanggan/list', [aPelanggan::class, 'list'])->middleware('ceklogin');
Route::any('/pelanggan/tambah', [aPelanggan::class, 'tambah'])->middleware('ceklogin');
Route::any('/pelanggan/edit/{id}', [aPelanggan::class, 'edit'])->middleware('ceklogin');
Route::any('/pelanggan/acttambah', [aPelanggan::class, 'acttambah'])->middleware('ceklogin');
Route::any('/pelanggan/actedit', [aPelanggan::class, 'actedit'])->middleware('ceklogin');
Route::any('/pelanggan/acthapus/{id}', [aPelanggan::class, 'acthapus'])->middleware('ceklogin');

// Item Masuk
Route::any('/itemmasuk/list', [aItemMasuk::class, 'list'])->middleware('ceklogin');
Route::any('/itemmasuk/tambah', [aItemMasuk::class, 'tambah'])->middleware('ceklogin');
Route::any('/itemmasuk/edit/{id}', [aItemMasuk::class, 'edit'])->middleware('ceklogin');
Route::any('/itemmasuk/acttambah', [aItemMasuk::class, 'acttambah'])->middleware('ceklogin');
Route::any('/itemmasuk/actedit', [aItemMasuk::class, 'actedit'])->middleware('ceklogin');
Route::any('/itemmasuk/acthapus/{id}', [aItemMasuk::class, 'acthapus'])->middleware('ceklogin');

// Item Keluar
Route::any('/itemkeluar/list', [aItemKeluar::class, 'list'])->middleware('ceklogin');
Route::any('/itemkeluar/tambah', [aItemKeluar::class, 'tambah'])->middleware('ceklogin');
Route::any('/itemkeluar/edit/{id}', [aItemKeluar::class, 'edit'])->middleware('ceklogin');
Route::any('/itemkeluar/acttambah', [aItemKeluar::class, 'acttambah'])->middleware('ceklogin');
Route::any('/itemkeluar/actedit', [aItemKeluar::class, 'actedit'])->middleware('ceklogin');
Route::any('/itemkeluar/acthapus/{id}', [aItemKeluar::class, 'acthapus'])->middleware('ceklogin');

// LAPORAN
Route::any('/laporan/grafikstokitem', [aLaporan::class, 'grafik_stokitem']);
Route::any('/laporan/grafikitemmasuk', [aLaporan::class, 'grafik_itemmasuk']);
Route::any('/laporan/grafikitemkeluar', [aLaporan::class, 'grafik_itemkeluar']);
Route::any('/laporan/stokitem', [aLaporan::class, 'stokitem']);
Route::any('/laporan/itemmasuk', [aLaporan::class, 'itemmasuk']);
Route::any('/laporan/itemkeluar', [aLaporan::class, 'itemkeluar']);
Route::any('/laporan/pelanggan', [aLaporan::class, 'pelanggan']);
Route::any('/laporan/supplier', [aLaporan::class, 'supplier']);
Route::any('/laporan/cetak/stokitem', [aLaporan::class, 'cetak_stokitem']);
Route::any('/laporan/cetak/itemmasuk', [aLaporan::class, 'cetak_itemmasuk']);
Route::any('/laporan/cetak/itemkeluar', [aLaporan::class, 'cetak_itemkeluar']);
Route::any('/laporan/cetak/pelanggan', [aLaporan::class, 'cetak_pelanggan']);
Route::any('/laporan/cetak/supplier', [aLaporan::class, 'cetak_supplier']);
