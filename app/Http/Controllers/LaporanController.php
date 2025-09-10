<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Services\LaporanService; // <-- Panggil Service
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LaporanKeuanganExport;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanController extends Controller
{
    protected $laporanService;

    public function __construct(LaporanService $laporanService)
    {
        $this->laporanService = $laporanService;
    }

    // Di dalam file LaporanController.php
    public function index(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $user = Auth::user();

        // Panggil service untuk mendapatkan SEMUA data
        $data = $this->laporanService->getLaporanData($user, $startDate, $endDate);

        // Paginate manual untuk view
        $perPage = 10;
        $page = $request->get('page', 1);
        $paginatedRiwayatTransaksi = new LengthAwarePaginator(
            $data['riwayatTransaksi']->forPage($page, $perPage),
            $data['riwayatTransaksi']->count(),
            $perPage,
            $page,
            ['path' => url()->current(), 'query' => $request->query()]
        );

        return view('laporan.index', [
            'totalPemasukan' => $data['totalPemasukan'],
            'totalPengeluaran' => $data['totalPengeluaran'],
            'startDate' => $startDate,
            'endDate' => $endDate,
            'riwayatTransaksi' => $paginatedRiwayatTransaksi,
            // Mengambil data chart dari service
            'labels' => $data['chart']['labels'],
            'pemasukanValues' => $data['chart']['pemasukanValues'],
            'pengeluaranValues' => $data['chart']['pengeluaranValues'],
        ]);
    }

    public function exportExcel(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $user = Auth::user();

        $data = $this->laporanService->getLaporanData($user, $startDate, $endDate);

        // Ubah koleksi objek menjadi koleksi array untuk diekspor
        $exportData = $data['riwayatTransaksi']->sortBy('tanggal')->map(function ($item) {
            return [
                'Tipe Transaksi' => $item->tipe,
                'Nama' => $item->nama,
                'Jumlah' => $item->jumlah,
                'Tanggal' => $item->tanggal,
                'Keterangan' => $item->keterangan,
            ];
        });

        $fileName = 'laporan_keuangan_' . now()->format('Ymd') . '.xlsx';
        return Excel::download(new LaporanKeuanganExport($exportData), $fileName);
    }

    public function exportPDF(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $user = Auth::user();

        $data = $this->laporanService->getLaporanData($user, $startDate, $endDate);

        $pdfData = [
            'riwayatTransaksi' => $data['riwayatTransaksi']->sortBy('tanggal'),
            'startDate' => $startDate,
            'endDate' => $endDate,
            'totalPemasukan' => $data['riwayatPembayaran']->sum('jumlah'),
            'totalPengeluaran' => $data['riwayatPengeluaran']->sum('jumlah'),
            'saldoAkhir' => $data['saldoAkhir'],
        ];

        $pdf = Pdf::loadView('exports.laporan_keuangan_pdf', $pdfData);
        $fileName = 'laporan_keuangan_' . now()->format('Ymd') . '.pdf';
        return $pdf->download($fileName);
    }
}