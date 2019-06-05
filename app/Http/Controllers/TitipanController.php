<?php

namespace App\Http\Controllers;

use App\BarangTitipan;
use App\Titipan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TitipanController extends Controller
{
    public function createPenitip(Request $request)
    {
        $data = new Titipan;
        $data->nama_owner = $request->nama;
        $data->save();
        return response(json_encode($data), 201);
    }

    public function simpanData($id)
    {
        $data = Titipan::find($id);
        $data->status = 1;
        $data->save();
        return BarangTitipan::where('titipan_id', '=', $id)->get();
    }

    public function getAllPenitip()
    {
        return Titipan::where('status_pengambilan', '=', 0)->get();
    }

    public function getAllPenitipSelesai()
    {
        return Titipan::select('nama_owner', 'titipans.created_at', 'titipans.id', 'potongan_pengambilan'
            , DB::raw('SUM(barang_titipans.harga_barang*transaksi_titipans.jumlah) as harga_barang'))
            ->join('barang_titipans','barang_titipans.titipan_id','=','titipans.id')
            ->join('transaksi_titipans', 'transaksi_titipans.produk_titipan_id', '=', 'barang_titipans.id')
            ->where('status_pengambilan', '=', 1)
            ->groupBy('barang_titipans.titipan_id','transaksi_titipans.produk_titipan_id','titipans.nama_owner','titipans.created_at','titipans.id','titipans.potongan_pengambilan')
            ->get();
    }

    public function getName($id)
    {
        return Titipan::find($id);
    }

    public function getAllProduk($id)
    {
        $data = BarangTitipan::with('allPenjualanTitipan')->where('titipan_id', '=', $id)->get();
        return $data;
    }
}
