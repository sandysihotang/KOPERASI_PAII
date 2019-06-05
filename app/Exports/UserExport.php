<?php

namespace App\Exports;

use App\Http\Controllers\ProdukController;
use App\New_produk;
use App\VendorProduk;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Events\BeforeSheet;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class UserExport implements FromQuery, WithMapping, WithHeadings, WithColumnFormatting, WithEvents
{
    /**
     * @return \Illuminate\Support\Collection
     */
    /**
     * @return Builder
     */
    public function query()
    {
        return New_produk::select('produks.nama','new_produks.jumlah','new_produks.harga','produks.created_at')
            ->join('produks','produks.id','=','new_produks.produk_id')
            ->join('vendor_produks','vendor_produks.id','new_produks.vendor_produk_id')
            ->where('vendor_produks.status_data','=',0)
            ->where('vendor_produks.user_id','=',Auth::id());
    }

    /**
     * @param mixed $row
     *
     * @return array
     */
    public function map($produk): array
    {
        return [
            $produk->nama,
            $produk->harga,
            $produk->jumlah,
            $produk->jumlah * $produk->harga,
            Date::dateTimeToExcel($produk->created_at)
        ];
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'Nama Barang',
            'Harga Barang Perbuah',
            'Jumlah Barang',
            'Total Harga',
            'Tanggal Masuk'
        ];
    }

    /**
     * @return array
     */
    public function columnFormats(): array
    {
        return [
            'B' => NumberFormat::FORMAT_CURRENCY_ID,
            'D' => NumberFormat::FORMAT_CURRENCY_ID,
            'E' => NumberFormat::FORMAT_DATE_YYYYMMDD2
        ];
    }

    /**
     * @return array
     */
    public function registerEvents(): array
    {
        $styleArray = [
            'font' => [
                'bold' => true
            ]
        ];
        return [
            BeforeSheet::class => function (BeforeSheet $event) {
                $event->sheet->setCellValue('G1', 'Vendor: ');
                $event->sheet->setCellValue('H1', ProdukController::$vendor);

                $event->sheet->setCellValue('G2', 'No Telepon Vendor: ');
                $event->sheet->setCellValue('H2', ProdukController::$telp_vendor);

                $event->sheet->setCellValue('G3', 'Tanggal Pemasukan: ');
                $event->sheet->setCellValue('H3', VendorProduk::select(DB::raw('DATE(created_at) as created_at'))->where('status_data','=',0)
                    ->where('user_id','=',Auth::id())->first()->created_at);


            },

            AfterSheet::class => function (AfterSheet $event) use ($styleArray) {
                $event->sheet->getStyle('A2:E2')->applyFromArray($styleArray);
            },
        ];
    }
}
