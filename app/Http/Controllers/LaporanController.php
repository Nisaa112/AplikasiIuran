<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LaporanController extends Controller
{
    public function laporanBulananAPI($bulan, $tahun)
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

    public function laporanBulananWeb($bulan, $tahun)
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

        return view('laporan.bulanan', compact('totalPemasukan', 'totalPengeluaran', 'saldo', 'bulan', 'tahun'));
    }

    public function laporanAnggotaAPI($bulan, $tahun)
    {
        $sudahBayar = Member::whereHas('pembayaranIuran', function ($query) use ($bulan, $tahun) {
            $query->whereMonth('tgl_bayar', $bulan)
                ->whereYear('tgl_bayar', $tahun);
        })->get();

        $belumBayar = Member::whereDoesntHave('pembayaranIuran', function ($query) use ($bulan, $tahun) {
            $query->whereMonth('tgl_bayar', $bulan)
                ->whereYear('tgl_bayar', $tahun);
        })->get();

        return response()->json([
            'bulan' => $bulan,
            'tahun' => $tahun,
            'sudah_bayar' => $sudahBayar,
            'belum_bayar' => $belumBayar,
        ]);
    }

    public function laporanAnggotaWeb($bulan, $tahun)
    {
        $sudahBayar = Member::whereHas('pembayaranIuran', function ($query) use ($bulan, $tahun) {
            $query->whereMonth('tgl_bayar', $bulan)
                ->whereYear('tgl_bayar', $tahun);
        })->get();

        $belumBayar = Member::whereDoesntHave('pembayaranIuran', function ($query) use ($bulan, $tahun) {
            $query->whereMonth('tgl_bayar', $bulan)
                ->whereYear('tgl_bayar', $tahun);
        })->get();

        return view('laporan.anggota', compact('sudahBayar', 'belumBayar', 'bulan', 'tahun'));
    }

    public function laporanKasAPI()
    {
        $totalPemasukan = DB::table('pembayaran_iuran')->sum('jumlah');
        $totalPengeluaran = DB::table('pengeluarans')->sum('jumlah');
        $saldo = $totalPemasukan - $totalPengeluaran;

        return response()->json([
            'total_pemasukan' => $totalPemasukan,
            'total_pengeluaran' => $totalPengeluaran,
            'saldo' => $saldo,
        ]);
    }

    public function laporanKasWeb()
    {
        $totalPemasukan = DB::table('pembayaran_iuran')->sum('jumlah');
        $totalPengeluaran = DB::table('pengeluarans')->sum('jumlah');
        $saldo = $totalPemasukan - $totalPengeluaran;

        return view('laporan.kas', compact('totalPemasukan', 'totalPengeluaran', 'saldo'));
    }
}
