<?php

namespace App\Services;

use App\Models\PembayaranIuran;
use App\Models\Pengeluaran;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Contracts\Auth\Authenticatable;

class LaporanService
{
    public function getLaporanData(Authenticatable $user, $startDate = null, $endDate = null): array
    {
        $totalPemasukan = PembayaranIuran::where('id_user', $user->id)
            ->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
                return $query->whereBetween('tgl_bayar', [$startDate, $endDate]);
            })->sum('jumlah');

        $totalPengeluaran = Pengeluaran::where('id_user', $user->id)
            ->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
                return $query->whereBetween('tgl_pengeluaran', [$startDate, $endDate]);
            })->sum('jumlah');

        $riwayatPembayaran = PembayaranIuran::where('id_user', $user->id)
            ->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
                return $query->whereBetween('tgl_bayar', [$startDate, $endDate]);
            })
            ->with('member')
            ->get();

        $riwayatPengeluaran = Pengeluaran::where('id_user', $user->id)
            ->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
                return $query->whereBetween('tgl_pengeluaran', [$startDate, $endDate]);
            })
            ->get();

        // --- LOGIKA BARU UNTUK CHART MINGGUAN ---
        $weeklyChartData = $this->generateWeeklyChartData($riwayatPembayaran, $riwayatPengeluaran, $startDate);

        // --- FORMAT DATA UNTUK TABEL ---
        $formattedRiwayatPembayaran = $riwayatPembayaran->map(function ($item) {
             return (object) ['tipe' => 'Pemasukan', 'nama' => $item->member->nama ?? 'Tidak diketahui', 'jumlah' => $item->jumlah, 'tanggal' => $item->tgl_bayar, 'keterangan' => $item->catatan];
        });
        $formattedRiwayatPengeluaran = $riwayatPengeluaran->map(function ($item) {
             return (object) ['tipe' => 'Pengeluaran', 'nama' => $item->nama, 'jumlah' => $item->jumlah, 'tanggal' => $item->tgl_pengeluaran, 'keterangan' => $item->keterangan];
        });

        $riwayatTransaksi = $formattedRiwayatPembayaran->merge($formattedRiwayatPengeluaran)->sortByDesc('tanggal');

        return [
            'totalPemasukan' => $totalPemasukan,
            'totalPengeluaran' => $totalPengeluaran,
            'saldoAkhir' => $totalPemasukan - $totalPengeluaran,
            'riwayatTransaksi' => $riwayatTransaksi,
            'chart' => $weeklyChartData, // Menggunakan data chart mingguan
        ];
    }

    /**
     * Helper function untuk mengelompokkan data transaksi per minggu.
     */
    private function generateWeeklyChartData($pemasukan, $pengeluaran, $startDate)
    {
        // =======================================================
        // VVVV INI ADALAH PERBAIKAN PENTING VVVV
        // =======================================================
        
        // Tentukan tanggal referensi dengan aman. Jika $startDate null, gunakan tanggal saat ini.
        $refDate = $startDate ? Carbon::parse($startDate) : Carbon::now();
        $refDate->startOfMonth(); // Pastikan kita mulai dari awal bulan

        // =======================================================
        // ^^^^ AKHIR DARI PERBAIKAN PENTING ^^^^
        
        $weeksInMonth = $refDate->weeksInMonth;

        $labels = [];
        for ($i = 1; $i <= $weeksInMonth; $i++) {
            $labels[] = "Minggu " . $i;
        }

        $pemasukanValues = array_fill(0, $weeksInMonth, 0);
        $pengeluaranValues = array_fill(0, $weeksInMonth, 0);

        foreach ($pemasukan as $item) {
            $week = Carbon::parse($item->tgl_bayar)->weekOfMonth;
            $index = $week - 1;
            if (isset($pemasukanValues[$index])) {
                $pemasukanValues[$index] += $item->jumlah;
            }
        }

        foreach ($pengeluaran as $item) {
            $week = Carbon::parse($item->tgl_pengeluaran)->weekOfMonth;
            $index = $week - 1;
            if (isset($pengeluaranValues[$index])) {
                $pengeluaranValues[$index] += $item->jumlah;
            }
        }

        return [
            'labels' => $labels,
            'pemasukanValues' => $pemasukanValues,
            'pengeluaranValues' => $pengeluaranValues,
        ];
    }
}