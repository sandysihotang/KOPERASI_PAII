<?php

namespace App\Exports;

use App\Http\Controllers\LaporanPenjualanController;
use App\Http\Controllers\ProdukController;
use App\Transaksi;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Events\BeforeSheet;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class PenjualanHarianExport implements FromQuery, WithMapping, WithHeadings, WithColumnFormatting, WithEvents
{

    /**
     * @return \Illuminate\Support\Collection
     */
    /**
     * @return Builder
     */
    public $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function query()
    {
        $data = null;
        if ($this->request->id == 2) {

            $data = Transaksi::select('kode_transaksi', 'users.name'
                , 'total_harga', 'transaksis.created_at')
                ->join('users', 'users.id', '=', 'transaksis.user_id')
                ->whereMonth('transaksis.created_at', '=', $this->request->bulanSampai)
                ->whereMonth('transaksis.created_at', '=', $this->request->bulanMulai)
                ->whereYear('transaksis.created_at', '=', $this->request->tahun);
        } else if ($this->request->id == 1) {

            $data = Transaksi::select('kode_transaksi', 'users.name'
                , 'total_harga', 'transaksis.created_at')
                ->join('users', 'users.id', '=', 'transaksis.user_id')
                ->whereDay('transaksis.created_at', '<=', $this->request->hariSampai)
                ->whereDay('transaksis.created_at', '>=', $this->request->hariMulai)
                ->whereMonth('transaksis.created_at', '=', $this->request->bulan)
                ->whereYear('transaksis.created_at', '=', $this->request->tahun);
        } else if ($this->request->id == 3) {
            $data = Transaksi::select('kode_transaksi', 'users.name'
                , 'total_harga', 'transaksis.created_at')
                ->join('users', 'users.id', '=', 'transaksis.user_id')
                ->whereYear('transaksis.created_at', '=', $this->request->tahun);
        }
        return $data;
    }

    /**
     * @param mixed $row
     *
     * @return array
     */
    public function map($produk): array
    {
        return [
            $produk->kode_transaksi,
            $produk->name,
            $produk->total_harga,
            Date::dateTimeToExcel($produk->created_at)
        ];
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'Kode Transaksi',
            'Nama Kasir',
            'Total Harga Transaksi',
            'Tanggal Transaksi'
        ];
    }

    /**
     * @return array
     */
    public function columnFormats(): array
    {
        return [
            'C' => NumberFormat::FORMAT_CURRENCY_ID,
            'H' => NumberFormat::FORMAT_CURRENCY_ID,
            'D' => NumberFormat::FORMAT_DATE_YYYYMMDD2
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
                $event->sheet->setCellValue('G1', 'Total Penjualan: ');
                $data=null;
                if($this->request->id==1){
                    $data=Transaksi::whereDay('transaksis.created_at', '<=', $this->request->hariSampai)
                        ->whereDay('transaksis.created_at', '>=', $this->request->hariMulai)
                        ->whereMonth('transaksis.created_at', '=', $this->request->bulan)
                        ->whereYear('transaksis.created_at', '=', $this->request->tahun)
                        ->sum('total_harga');
                }else if($this->request->id==2){
                    $data=Transaksi::whereMonth('transaksis.created_at', '<=', $this->request->bulanSampai)
                        ->whereMonth('transaksis.created_at', '>=', $this->request->bulanMulai)
                        ->whereYear('transaksis.created_at', '=', $this->request->tahun)->sum('total_harga');
                }else{
                    $data=Transaksi::whereYear('transaksis.created_at', '=', $this->request->tahun)->sum('total_harga');
                }
                $event->sheet->setCellValue('H1',$data);
//
//                $event->sheet->setCellValue('G2', 'Kode Transaksi: ');
//                $event->sheet->setCellValue('H2',Transaksi::where('id', '=', LaporanPenjualanController::$id)->first()->kode_transaksi);
//
            },

            AfterSheet::class => function (AfterSheet $event) use ($styleArray) {
                $event->sheet->getStyle('A1:E1')->applyFromArray($styleArray);
            },
        ];
    }
}
