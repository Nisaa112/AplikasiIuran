<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class LaporanKeuanganExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    protected $data;

    public function __construct(Collection $data)
    {
        $this->data = $data;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        // Data yang diterima dari controller sudah siap,
        // jadi kita langsung mengembalikannya saja.
        return $this->data;
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        // Ini akan menjadi baris header (judul kolom) di file Excel Anda
        return [
            'Tipe Transaksi',
            'Nama',
            'Jumlah',
            'Tanggal',
            'Keterangan'
        ];
    }
}