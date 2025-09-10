<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\LaporanService;
use Illuminate\Pagination\LengthAwarePaginator;
use Carbon\Carbon; // <-- Pastikan Carbon di-import

class LaporanApiController extends Controller
{
    protected $laporanService;

    public function __construct(LaporanService $laporanService)
    {
        $this->laporanService = $laporanService;
    }

    /**
     * Menyediakan data laporan keuangan dalam format JSON untuk API.
     * Menerima filter 'tahun' dan 'bulan'.
     */
    public function getLaporan(Request $request)
    {
        // Validasi input dari API
        $request->validate([
            'tahun' => 'nullable|digits:4',
            'bulan' => 'nullable|integer|min:1|max:12',
            'page' => 'nullable|integer|min:1',
            'per_page' => 'nullable|integer|min:1|max:100',
        ]);

        // Ambil input tahun dan bulan, atau gunakan tanggal saat ini sebagai default
        $currentYear = $request->input('tahun', date('Y'));
        // PERBAIKAN DI SINI: Konversi bulan menjadi integer
        $currentMonth = intval($request->input('bulan', date('m')));

        // Buat rentang tanggal dari input, sama seperti di LaporanController
        $startDate = Carbon::createFromDate($currentYear, $currentMonth, 1)->startOfMonth()->toDateString();
        $endDate = Carbon::createFromDate($currentYear, $currentMonth, 1)->endOfMonth()->toDateString();

        $user = $request->user(); // Dapatkan user yang sedang login via token Sanctum

        // Panggil service yang sama untuk mendapatkan data
        // Tidak ada duplikasi logika bisnis!
        $data = $this->laporanService->getLaporanData($user, $startDate, $endDate);

        // Paginate manual untuk API
        $perPage = $request->input('per_page', 15);
        $page = $request->input('page', 1);
        
        $paginatedTransactions = new LengthAwarePaginator(
            $data['riwayatTransaksi']->forPage($page, $perPage)->values(), // .values() untuk mereset key array
            $data['riwayatTransaksi']->count(),
            $perPage,
            $page
        );

        // Kembalikan response dalam format JSON yang terstruktur
        return response()->json([
            'success' => true,
            'message' => 'Data laporan berhasil diambil',
            'data' => [
                'summary' => [
                    'total_pemasukan' => $data['totalPemasukan'],
                    'total_pengeluaran' => $data['totalPengeluaran'],
                    'saldo_akhir' => $data['saldoAkhir'],
                    // PERBAIKAN DI SINI JUGA
                    'periode' => Carbon::create()->month($currentMonth)->format('F') . ' ' . $currentYear,
                ],
                'chart' => [
                    'labels' => $data['chart']['labels'],
                    'pemasukan_values' => $data['chart']['pemasukanValues'],
                    'pengeluaran_values' => $data['chart']['pengeluaranValues'],
                ],
                'transactions' => $paginatedTransactions, // Data transaksi lengkap dengan info paginasi
            ]
        ]);
    }
}