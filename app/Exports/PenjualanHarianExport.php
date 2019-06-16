<?php

namespace App\Exports;

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
                ->whereYear('transaksis.created_at', '=', $this->request->tahun);
        } else if ($this->request->id == 1) {

            $data = Transaksi::select('kode_transaksi', 'users.name'
                , 'total_harga', 'transaksis.created_at')
                ->join('users', 'users.id', '=', 'transaksis.user_id')
                ->whereDay('transaksis.created_at', '=', $this->request->hariSampai)
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

//
//                $event->sheet->setCellValue('G2', 'Kode Transaksi: ');
//                $event->sheet->setCellValue('H2',Transaksi::where('id', '=', LaporanPenjualanController::$id)->first()->kode_transaksi);
//
            },

            AfterSheet::class => function (AfterSheet $event) use ($styleArray) {
                $event->sheet->getStyle('A1:E1')->applyFromArray($styleArray);
                $data = null;
                $cnt = null;
                if ($this->request->id == 1) {
                    $data = Transaksi::whereDay('transaksis.created_at', '<=', $this->request->hariSampai)
                        ->whereMonth('transaksis.created_at', '=', $this->request->bulan)
                        ->whereYear('transaksis.created_at', '=', $this->request->tahun)
                        ->sum('total_harga');
                    $cnt = Transaksi::whereDay('transaksis.created_at', '<=', $this->request->hariSampai)
                        ->whereMonth('transaksis.created_at', '=', $this->request->bulan)
                        ->whereYear('transaksis.created_at', '=', $this->request->tahun)
                        ->count('id');
                } else if ($this->request->id == 2) {
                    $data = Transaksi::whereMonth('transaksis.created_at', '<=', $this->request->bulanSampai)
                        ->whereYear('transaksis.created_at', '=', $this->request->tahun)->sum('total_harga');
                    $cnt=Transaksi::whereMonth('transaksis.created_at', '<=', $this->request->bulanSampai)
                        ->whereYear('transaksis.created_at', '=', $this->request->tahun)->count('id');
                } else {
                    $data = Transaksi::whereYear('transaksis.created_at', '=', $this->request->tahun)->sum('total_harga');
                    $cnt=Transaksi::whereYear('transaksis.created_at', '=', $this->request->tahun)->count('id');
                }

                $event->sheet->setCellValue('A'.($cnt+3), 'Total Penjualan: ');
                $event->sheet->setCellValue('B'.($cnt+3),'Rp '.$this->convert($data).',00');
            },
        ];
    }

    public function convert($data){
        $temp=''.$data;
        $newData='';
        $cnt=0;
        for ($i=strlen($temp)-1 ; $i>=0;$i--){
            $newData.=$temp[$i];
            $cnt++;
            if($cnt==3 && $i!=0){
                $newData.='.';
                $cnt=0;
            }
        }
        $convert='';
        for ($i=strlen($newData)-1 ; $i>=0;$i--)$convert.=$newData[$i];
        return $convert;
    }
}
