<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LaporanController extends Controller
{
    public function laporanBulanan($bulan, $tahun)
    {
        $totalPemasukan = DB::table('pembayaran_iuran')
            ->whereMonth('tgl_bayar', $bulan)
            ->whereYear('tgl_bayar', $tahun)
            ->sum('jumlah');

        $totalPengeluaran = DB::table('pengeluarans')
            ->whereMonth('tgl_pengeluaran', $bulan)
            ->whereYear('tgl_pengeluaran', $tahun)
            ->sum('jumlah');

        $saldo = $totalPemasukan - $totalPengeluaran;

        return response()->json([
            'bulan' => $bulan,
            'tahun' => $tahun,
            'pemasukan' => $totalPemasukan,
            'pengeluaran' => $totalPengeluaran,
            'saldo' => $saldo,
        ]);
    }

    public function laporanAnggota($bulan, $tahun)
    {
        $sudahBayar = Member::whereHas('pembayaranIuran', function ($query) use ($bulan, $tahun) {
            $query->whereMonth('tanggal_bayar', $bulan)
                ->whereYear('tanggal_bayar', $tahun);
        })->get();

        $belumBayar = Member::whereDoesntHave('pembayaranIuran', function ($query) use ($bulan, $tahun) {
            $query->whereMonth('tanggal_bayar', $bulan)
                ->whereYear('tanggal_bayar', $tahun);
        })->get();

        return response()->json([
            'bulan' => $bulan,
            'tahun' => $tahun,
            'sudah_bayar' => $sudahBayar,
            'belum_bayar' => $belumBayar,
        ]);
    }

    public function laporanKas()
    {
        $totalPemasukan = DB::table('pembayaran_iuran')->sum('jumlah');
        $totalPengeluaran = DB::table('pembayaran_iuran')->sum('jumlah');
        $saldo = $totalPemasukan - $totalPengeluaran;

        return response()->json([
            'total_pemasukan' => $totalPemasukan,
            'total_pengluaran' => $totalPengeluaran,
            'saldo' => $saldo,
        ]);
    }
}
