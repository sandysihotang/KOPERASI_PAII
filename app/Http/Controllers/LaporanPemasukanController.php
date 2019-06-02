<?php

namespace App\Http\Controllers;

use App\New_produk;
use App\VendorProduk;
use Illuminate\Support\Facades\Request;

class LaporanPemasukanController extends Controller
{
    public function index()
    {
        return VendorProduk::all();
    }

    public function getDetail($id)
    {
        return New_produk::with('getSource')
            ->where('vendor_produk_id', '=', $id)->get();
    }

    public function getDetailVendor($id){
        $data=VendorProduk::where('id','=',$id)
            ->with('penerima')->get();
        return response()->json(['data'=>$data],200);
    }
}
