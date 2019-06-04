<?php

namespace App\Http\Controllers;

use App\BarangTitipan;
use App\HargaJual;
use App\Produk;
use App\Transaksi;
use App\TransaksiProduk;
use App\TransaksiTitipan;
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
        $data = Produk::where('code_produk', '=', $kode)->first();
        if ($data != null) {
            $merge = array();
            $merge['nama'] = $data->nama;
            $merge['from'] = 1;
            $merge['jumlah_barang'] = $data->jumlah_barang;
            $merge['id'] = $data->id;
            return $merge;
        } else {
            $titipan = BarangTitipan::where('code_barang', '=', $kode)->first();
            $merge = array();
            $merge['nama'] = $titipan->nama_barang;
            $merge['jumlah_barang'] = $titipan->jumlah_barang;
            $merge['id'] = $titipan->id;
            $merge['from'] = 2;
            return response(json_encode($merge), 201);
        }
    }

    public function checkHargaJual($id)
    {
        return HargaJual::where('produk_id', '=', $id)->first();
    }

    public function keranjang(Request $request, $state)
    {
        if ($state == 1) {
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
        } else {
            $firstOrNew = Transaksi::where('kode_transaksi', '=', $request->kode)->first();
            if ($firstOrNew == null) {
                $newTransaksi = new Transaksi();
                $newTransaksi->kode_transaksi = $request->kode;
                $newTransaksi->user_id = $request->user_id;
                $newTransaksi->save();
                $transaksiTitipan = new TransaksiTitipan();
                $transaksiTitipan->produk_titipan_id = $request->id_produk;
                $transaksiTitipan->transaksi_id = $newTransaksi->id;
                $transaksiTitipan->jumlah = $request->jumlah;
                $transaksiTitipan->save();
            } else {

                $produkFirtsOrNew = TransaksiTitipan::where('produk_titipan_id', '=', $request->id_produk)
                    ->where('transaksi_id', '=', $firstOrNew->id)
                    ->first();
                if ($produkFirtsOrNew == null) {
                    $transaksiProduks = new TransaksiTitipan();
                    $transaksiProduks->produk_titipan_id = $request->id_produk;
                    $transaksiProduks->transaksi_id = $firstOrNew->id;
                    $transaksiProduks->jumlah = $request->jumlah;
                    $transaksiProduks->save();
                } else {
                    $produkFirtsOrNew->jumlah = $produkFirtsOrNew->jumlah + $request->jumlah;
                    $produkFirtsOrNew->save();
                }
            }
        }
    }

    public function getBarcode($id)
    {
        return BarangTitipan::where('titipan_id', '=', $id)->get();
    }

    public function getProduk($kode)
    {
        $id = Transaksi::where('kode_transaksi', '=', $kode)->first();
        if ($id == null)
            return array();
        $dataProduk = TransaksiProduk::select('transaksi_produks.id', 'produks.nama', 'produks.code_produk', 'harga_juals.harga_jual', 'transaksi_produks.jumlah', 'transaksi_produks.created_at')
            ->join('produks', 'produks.id', '=', 'transaksi_produks.produk_id')
            ->join('harga_juals', 'harga_juals.produk_id', '=', 'transaksi_produks.produk_id')
            ->where('transaksi_produks.transaksi_id', '=', $id->id)
            ->get();
        $merge = array();
        foreach ($dataProduk as $i) {
            $temp = array();
            $temp['id'] = $i->id;
            $temp['nama'] = $i->nama;
            $temp['state'] = 1;
            $temp['code_produk'] = $i->code_produk;
            $temp['harga_jual'] = $i->harga_jual;
            $temp['jumlah'] = $i->jumlah;
            $temp['dibuat'] = $i->created_at;
            array_push($merge, $temp);
        }
        $dataTitipan = TransaksiTitipan::select('transaksi_titipans.id', 'barang_titipans.nama_barang', 'barang_titipans.code_barang', 'barang_titipans.harga_barang', 'transaksi_titipans.jumlah', 'transaksi_titipans.created_at')
            ->join('barang_titipans', 'barang_titipans.id', '=', 'transaksi_titipans.produk_titipan_id')
            ->where('transaksi_titipans.transaksi_id', '=', $id->id)
            ->get();
        foreach ($dataTitipan as $i) {
            $temp = array();
            $temp['id'] = $i->id;
            $temp['nama'] = $i->nama_barang;
            $temp['code_produk'] = $i->code_barang;
            $temp['harga_jual'] = $i->harga_barang;
            $temp['state'] = 2;
            $temp['jumlah'] = $i->jumlah;
            $temp['dibuat'] = $i->created_at;
            array_push($merge, $temp);
        }
        usort($merge, function ($a, $b) {
            return $a['dibuat'] < $b['dibuat'];
        });
        return response(json_encode($merge), 201);
    }

    public function deleteProduk($id, $state)
    {
        if ($state == 1) {
            $data = TransaksiProduk::find($id);
            $data->delete();
        } else {
            $data = TransaksiTitipan::find($id);
            $data->delete();
        }
    }

    public function penyelesaian(Request $request, $code)
    {
        $data = Transaksi::where('kode_transaksi', '=', $code)->first();
        $data->status = 1;
        $data->total_harga = $request->total;
        $data->save();
        $dataProduk = TransaksiProduk::select('transaksi_produks.produk_id', 'transaksi_produks.jumlah')
            ->join('transaksis', 'transaksis.id', '=', 'transaksi_produks.transaksi_id')
            ->where('kode_transaksi', '=', $code)->get();
        foreach ($dataProduk as $tes) {
            $save = Produk::where('id', '=', $tes->produk_id)->first();
            $save->jumlah_barang = $save->jumlah_barang - $tes->jumlah;
            $save->save();
        }
        $dataTitipan = TransaksiTitipan::select('transaksi_titipans.produk_titipan_id', 'transaksi_titipans.jumlah')
            ->join('transaksis', 'transaksis.id', '=', 'transaksi_titipans.transaksi_id')
            ->where('kode_transaksi', '=', $code)->get();
        foreach ($dataTitipan as $tes) {
            $save = BarangTitipan::where('id', '=', $tes->produk_titipan_id)->first();
            $save->jumlah_barang = $save->jumlah_barang - $tes->jumlah;
            $save->save();
        }
    }
}
