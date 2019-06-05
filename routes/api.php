<?php

use App\VendorProduk;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});

Route::post('profile', 'AuthController@updateProfile');
Route::post('verify/{id}', 'AuthController@ubah');
Route::post('forgotPassword', 'AuthController@forgotPassword');
Route::post('checkUser', 'AuthController@check');
Route::post('/register', 'AuthController@userRegister');
Route::group(['middleware' => 'auth:api'], function () {
    Route::post('createKategori', 'ProdukController@createKategori');
    Route::get('allProduk', 'ProdukController@index');
    Route::get('getUserKonfirmation', 'AuthController@getUserKonfimation');
    Route::get('getCode/{code}', 'ProdukController@getCode');
    Route::get('konfirmasi/{code}', 'AuthController@konfirmasi');
    Route::post('addProductBaru', 'ProdukController@store');
    Route::get('getKategori', 'KategoriController@index');
    Route::get('getPemasukan', 'ProdukController@getPemasukan');
    Route::post('addProductTambah', 'ProdukController@productTambah');
    Route::get('getTotal', 'ProdukController@getTotal');
    Route::get('hargaBarangItem/{id}', 'ProdukController@hargaBarangPerItem');
    Route::get('getHargaJual/{id}', 'ProdukController@getHargaJual');
    Route::post('ubahHargaJual/{id}', 'ProdukController@ubahHargaJual');


    Route::get('getProduk/{id}', 'ProdukController@getSpesifikProduk');
    Route::put('editNewProduk/{id}', 'ProdukController@editNewProduk');
    Route::delete('deleteNewProduk/{id}', 'ProdukController@deleteProduk');
    Route::post('createExcel', 'ProdukController@download');


    Route::get('getLaporanPemasukan', 'LaporanPemasukanController@index');
    Route::get('getDetailLaporan/{id}', 'LaporanPemasukanController@getDetail');
    Route::get('getDetailVendor/{id}', 'LaporanPemasukanController@getDetailVendor');
    Route::get('downloadExcel/{id}', function ($id) {
        $data = VendorProduk::find($id);
        return response()->download(storage_path('app/' . $data->excel_file));
    });
    Route::get('kodeTransaksi', 'TransaksiController@kode');
    Route::get('getBarang/{kode}', 'TransaksiController@spesifik');
    Route::post('tambahProdukTransaksi/{state}', 'TransaksiController@keranjang');
    Route::get('produkTransaksi/{kode}', 'TransaksiController@getProduk');
    Route::get('checkHargaJual/{id}', 'TransaksiController@checkHargaJual');
    Route::get('getBarcode/{id}', 'TransaksiController@getBarcode');
    Route::delete('deleteProduk/{id}/{state}', 'TransaksiController@deleteProduk');
    Route::post('selesaiTransaksi/{id}', 'TransaksiController@penyelesaian');

    Route::get('getLaporanPenjualan', "LaporanPenjualanController@index");
    Route::get('getForMore', "ProdukController@getForMore");
    Route::post('downloadPenjualanExcel', 'LaporanPenjualanController@download');

    Route::get('getDetailPenjualan/{id}', "LaporanPenjualanController@getDetail");
    Route::get('getDetailUser/{id}', "LaporanPenjualanController@getUser");


    Route::post('createNewPenitip', 'TitipanController@createPenitip');
    Route::post('tambahBarang/{id}', 'BarangTitipanController@tambahProduk');
    Route::post('ambilTitipan/{id}', 'BarangTitipanController@ambilTitipan');
    Route::get('getBarangTitipan/{id}', 'BarangTitipanController@getProduk');
    Route::get('getTotalTitipan/{id}', 'BarangTitipanController@getTotal');
    Route::get('getTitipan/{id}', 'BarangTitipanController@getDetail');
    Route::get('ambilHargaTitipan/{id}', 'BarangTitipanController@ambilHargaTitipan');
    Route::get('simpanData/{id}', 'TitipanController@simpanData');
    Route::get('allPenitip', 'TitipanController@getAllPenitip');
    Route::get('allPenitipSelesai', 'TitipanController@getAllPenitipSelesai');
    Route::get('getDetailUserPenitip/{id}', 'TitipanController@getName');
    Route::get('getDetailPenitipan/{id}', 'TitipanController@getAllProduk');
    Route::put('editTitipan/{id}', 'BarangTitipanController@update');
    Route::delete('deleteTitipan/{id}', 'BarangTitipanController@delete');
});
