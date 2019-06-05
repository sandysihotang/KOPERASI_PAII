<?php

namespace App\Http\Controllers;

use App\Exports\UserExport;
use App\HargaBarang;
use App\HargaJual;
use App\Kategori;
use App\New_produk;
use App\Produk;
use App\VendorProduk;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class ProdukController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public static $vendor = null;
    public static $telp_vendor = null;

    public function getForMore()
    {
        return Produk::selectRaw('produks.nama,(select SUM(jumlah) from transaksi_produks where produks.id=transaksi_produks.produk_id) as jumlah')
            ->orderBy('jumlah','DESC')
            ->limit(5)->get();
    }

    public function createKategori(Request $request)
    {
        $data = new Kategori();
        $data->nama_kategori = $request->kategori;
        $data->save();

    }

    public function getHargaJual($id)
    {
        return HargaJual::where('produk_id', '=', $id)->first();
    }

    public function ubahHargaJual(Request $request, $id)
    {
        $find = HargaJual::where('produk_id', '=', $id)->first();
        if ($find == null) {
            $data = new HargaJual;
            $data->produk_id = $id;
            $data->harga_jual = $request->harga;
            $data->save();
        } else {
            $find->harga_jual = $request->harga;
            $find->save();
        }
    }

    public function index()
    {
        return Produk::select('produks.id', 'produks.nama', 'kategoris.nama_kategori',
            'produks.code_produk', 'harga_juals.harga_jual', 'produks.jumlah_barang', 'produks.updated_at')
            ->join('kategoris', 'kategoris.id', '=', 'produks.kategori_id')
            ->leftjoin('harga_juals', 'harga_juals.produk_id', '=', 'produks.id')
            ->get();
    }

    public function getCode($code)
    {
        return Produk::select('produks.nama', 'produks.id')
            ->where('produks.code_produk', $code)
            ->get();
    }

    public function hargaBarangPerItem($id)
    {
        return HargaBarang::where('produk_id', '=', $id)
            ->where('harga', '<>', 0)
            ->get();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getPemasukan()
    {
        $iduser = Auth::id();
        return VendorProduk::select('new_produks.id', 'produks.nama',
            'produks.code_produk',
            'new_produks.harga',
            'new_produks.jumlah')->join('new_produks', 'vendor_produks.id', '=', 'new_produks.vendor_produk_id')
            ->join('produks', 'new_produks.produk_id', '=', 'produks.id')
            ->where('status_data', 0)
            ->where('vendor_produks.user_id', $iduser)
            ->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $vendor_produk = VendorProduk::where('status_data', '=', 0)
            ->first();
        $id = Auth::id();
        if ($vendor_produk != null) {
            $id = $vendor_produk->id;
        } else {
            $new_vendor_produk = new VendorProduk();
            $new_vendor_produk->user_id = $id;
            $new_vendor_produk->save();
            $id = $new_vendor_produk->id;
        }
        $product = new Produk();
        $product->kategori_id = $request->kategori;
        $product->nama = $request->nama;
        $product->code_produk = $request->kode;
        $product->save();

        $newproduct = new New_produk();
        $newproduct->produk_id = $product->id;
        $newproduct->harga = $request->harga;
        $newproduct->vendor_produk_id = $id;
        $newproduct->jumlah = $request->jumlah;
        $newproduct->save();
    }

    public function getTotal()
    {
        $id = Auth::id();
        return VendorProduk::with('produk')->where('status_data', 0)
            ->where('user_id', $id)
            ->get();
    }

    public function productTambah(Request $request)
    {
        $vendor_produk = VendorProduk::where('status_data', '=', 0)
            ->first();
        $id = Auth::id();
        if ($vendor_produk != null) {
            $id = $vendor_produk->id;
        } else {
            $new_vendor_produk = new VendorProduk();
            $new_vendor_produk->user_id = $id;
            $new_vendor_produk->save();
            $id = $new_vendor_produk->id;
        }
        $productFind = New_produk::where('produk_id', '=', $request->id)
            ->where('vendor_produk_id', '=', $id)
            ->first();
        if ($productFind == null) {
            $newproduct = new New_produk();
            $newproduct->produk_id = $request->id;
            $newproduct->harga = $request->harga;
            $newproduct->vendor_produk_id = $id;
            $newproduct->jumlah = $request->jumlah;
            $newproduct->save();
        } else {
            $productFind->jumlah = $productFind->jumlah + $request->jumlah;
            $productFind->harga = $request->harga;
            $productFind->vendor_produk_id = $id;
            $productFind->save();
        }
    }

    public function getSpesifikProduk($id)
    {
        return New_produk::find($id);
    }

    public function editNewProduk(Request $request, $id)
    {
        $newproduk = New_produk::find($id);
        $newproduk->harga = $request[0]['harga'];
        $newproduk->jumlah = $request[0]['jumlah'];
        $newproduk->save();
        return $newproduk;
    }

    public function deleteProduk($id)
    {
        $newproduk = New_produk::find($id);
        $newproduk->delete();
    }

    public function download(Request $request)
    {
        self::$vendor = $request->formValues['nama'];
        self::$telp_vendor = $request->formValues['telp'];
        date_default_timezone_set("Asia/Bangkok");
        $date = date('Y-m-d');
        $file = 'Pemasukan/' . $date . '/pemasukan';
        $file .= $date . '-' . date('h-i-s');
        $file .= '.xlsx';
        Excel::store(new UserExport, $file);
        self::saveVendor(self::$vendor, $file, Auth::id(), self::$telp_vendor);
    }

    public function setHarga()
    {
        $dataToUpdate = VendorProduk::select('new_produks.produk_id', 'new_produks.harga', 'new_produks.jumlah')
            ->where('vendor_produks.status_data', '=', 0)
            ->join('new_produks', 'new_produks.vendor_produk_id', '=', 'vendor_produks.id')
            ->join('produks', 'produks.id', '=', 'new_produks.produk_id')
            ->get();
        foreach ($dataToUpdate as $tes) {
            $updateData = Produk::find($tes->produk_id);
            $updateData->jumlah_barang = $tes->jumlah;
            $updateData->update();

            $harga = HargaBarang::where([
                'produk_id' => $tes->produk_id,
                'harga' => $tes->harga
            ])->first();
            if (!$harga) {
                $hargaNew = new HargaBarang();
                $hargaNew->produk_id = $tes->produk_id;
                $hargaNew->harga = $tes->harga;
                $hargaNew->save();

                HargaBarang::where('produk_id', '=', $hargaNew->produk_id)
                    ->where('harga', '!=', $hargaNew->harga)
                    ->update(['status_penggunaan' => 0]);
            } else {
                $harga->status_penggunaan = 1;
                $harga->save();
                HargaBarang::where([
                    ['produk_id', '=', $harga->produk_id],
                    ['harga', '<>', $harga->harga]
                ])->update(['status_penggunaan' => 0]);
            }
        }
    }

    private function saveVendor($vendor, $file, $id, $telp)
    {
        self::setHarga();
        $vendors = VendorProduk::where('status_data', '=', 0)->first();
        $vendors->user_id = $id;
        $vendors->no_telepon = $telp;
        $vendors->status_data = 1;
        $vendors->excel_file = $file;
        $vendors->nama_vendor = $vendor;
        $vendors->update();
    }
}
