<?php

namespace App\Http\Controllers;

use App\BarangTitipan;
use Illuminate\Http\Request;

class BarangTitipanController extends Controller
{
    public function tambahProduk(Request $request, $id)
    {
        $data = new BarangTitipan();
        $data->titipan_id = $id;
        $data->code_barang = substr(md5(time() . mt_rand(1, 1000000)),0,12);
        $data->nama_barang = $request->nama;
        $data->harga_barang = $request->harga;
        $data->jumlah_barang = $request->jumlah;
        $data->save();
    }
    public function getProduk($id){
        return BarangTitipan::join('titipans','titipans.id','=','barang_titipans.titipan_id')
            ->where('titipan_id','=',$id)
            ->where('status','=',0)
            ->get();
    }
    public function getTotal($id){
        return BarangTitipan::join('titipans','titipans.id','=','barang_titipans.titipan_id')
            ->where('titipan_id','=',$id)
            ->where('status','=',0)
            ->get();
    }
    public function update(Request $request,$id){
        $data=BarangTitipan::find($id);
        $data->harga_barang=$request[0]['harga'];
        $data->jumlah_barang=$request[0]['jumlah'];
        $data->save();
    }
    public function getDetail($id){
        return BarangTitipan::find($id);
    }
    public function delete($id){
        $data=BarangTitipan::find($id);
        $data->delete();
    }
}
