<?php

namespace App\Http\Controllers;

use App\Exports\PenjualanBulananExport;
use App\Exports\PenjualanHarianExport;
use App\Transaksi;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class LaporanPenjualanController extends Controller
{
    public function index()
    {
        return Transaksi::with('user')->get();
    }


    public function download(Request $request)
    {
        return Excel::download(new PenjualanHarianExport($request), 'penjualan.xlsx');

    }

    public function getDetail($ids)
    {
        return Transaksi::select('transaksi_produks.jumlah', 'produks.nama'
            , 'produks.code_produk', 'harga_barangs.harga')
            ->join('transaksi_produks', 'transaksi_produks.transaksi_id', '=', 'transaksis.id')
            ->join('produks', 'produks.id', '=', 'transaksi_produks.produk_id')
            ->join('harga_barangs', 'harga_barangs.produk_id', '=', 'produks.id')
            ->where('transaksis.id', '=', $ids)
            ->where('harga_barangs.status_penggunaan', '=', 1)->get();
    }

    public function getUser($ids)
    {
        return Transaksi::with('user')
            ->where('id', '=', $ids)->first();
    }
}
