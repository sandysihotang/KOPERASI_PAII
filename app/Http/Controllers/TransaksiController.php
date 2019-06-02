<?php

namespace App\Http\Controllers;

use App\Produk;
use App\Transaksi;
use App\TransaksiProduk;
use Illuminate\Http\Request;

class TransaksiController extends Controller
{
    public function kode()
    {
        $kode_barang = Transaksi::where('status', '=', 1)->max('kode_transaksi');

        $nourut = (int)substr($kode_barang, 8, 4);
        $nourut++;
        $id_pemesanan = sprintf("%03s", $nourut);
        return $id_pemesanan;
    }

    public function spesifik($kode)
    {
        return Produk::where('code_produk', '=', $kode)->first();
    }

    public function keranjang(Request $request)
    {
        $firstOrNew = Transaksi::where('kode_transaksi', '=', $request->kode)->first();
        if ($firstOrNew == null) {
            $newTransaksi = new Transaksi();
            $newTransaksi->kode_transaksi = $request->kode;
            $newTransaksi->user_id = $request->user_id;
            $newTransaksi->save();
            $transaksiProduks = new TransaksiProduk();
            $transaksiProduks->produk_id = $request->id_produk;
            $transaksiProduks->transaksi_id = $newTransaksi->id;
            $transaksiProduks->jumlah = $request->jumlah;
            $transaksiProduks->save();
        } else {

            $produkFirtsOrNew = TransaksiProduk::where('produk_id', '=', $request->id_produk)
                ->where('transaksi_id', '=', $firstOrNew->id)
                ->first();
            if ($produkFirtsOrNew == null) {
                $transaksiProduks = new TransaksiProduk();
                $transaksiProduks->produk_id = $request->id_produk;
                $transaksiProduks->transaksi_id = $firstOrNew->id;
                $transaksiProduks->jumlah = $request->jumlah;
                $transaksiProduks->save();
            } else {
                $produkFirtsOrNew->jumlah = $produkFirtsOrNew->jumlah + $request->jumlah;
                $produkFirtsOrNew->save();
            }
        }
    }

    public function getProduk($kode)
    {
        $id = Transaksi::where('kode_transaksi', '=', $kode)->first();
        if ($id == null)
            return array();
        $data = TransaksiProduk::select('transaksi_produks.id', 'produks.nama', 'produks.code_produk', 'harga_juals.harga_jual', 'transaksi_produks.jumlah')
            ->join('produks', 'produks.id', '=', 'transaksi_produks.produk_id')
            ->join('harga_juals', 'harga_juals.produk_id', '=', 'transaksi_produks.produk_id')
            ->where('transaksi_produks.transaksi_id', '=', $id->id)
            ->get();

        return $data;
    }

    public function deleteProduk($id)
    {
        $data = TransaksiProduk::find($id);
        $data->delete();
    }

    public function penyelesaian(Request $request, $code)
    {
        $data = Transaksi::where('kode_transaksi', '=', $code)->first();
        $data->status = 1;
        $data->total_harga = $request->total;
        $data->save();
        $dataTemp = TransaksiProduk::select('transaksi_produks.produk_id', 'transaksi_produks.jumlah')
            ->join('transaksis', 'transaksis.id', '=', 'transaksi_produks.transaksi_id')
            ->where('kode_transaksi', '=', $code)->get();
        foreach ($dataTemp as $tes) {
            $save = Produk::where('id', '=', $tes->produk_id)->first();
            $save->jumlah_barang = $save->jumlah_barang - $tes->jumlah;
            $save->save();
        }
    }
}
