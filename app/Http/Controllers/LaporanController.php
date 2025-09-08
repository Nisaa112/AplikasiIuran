<?php

namespace App\Http\Controllers;

use App\Models\PembayaranIuran;
use App\Models\Pengeluaran;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel; // Add this line
use App\Exports\LaporanKeuanganExport; // Add this line
use Barryvdh\DomPDF\Facade\Pdf; // Add this line

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        // Total Pemasukan
        $totalPemasukan = PembayaranIuran::where('id_user', Auth::id())
            ->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
                return $query->whereBetween('tgl_bayar', [$startDate, $endDate]);
            })->sum('jumlah');

        // Total Pengeluaran
        $totalPengeluaran = Pengeluaran::where('id_user', Auth::id())
            ->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
                return $query->whereBetween('tgl_pengeluaran', [$startDate, $endDate]);
            })->sum('jumlah');

        // Chart Data Pemasukan
        $pemasukanChartData = PembayaranIuran::where('id_user', Auth::id())
            ->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
                return $query->whereBetween('tgl_bayar', [$startDate, $endDate]);
            })
            ->select(DB::raw('DATE(tgl_bayar) as date'), DB::raw('SUM(jumlah) as total'))
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();
        
        // Chart Data Pengeluaran
        $pengeluaranChartData = Pengeluaran::where('id_user', Auth::id())
            ->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
                return $query->whereBetween('tgl_pengeluaran', [$startDate, $endDate]);
            })
            ->select(DB::raw('DATE(tgl_pengeluaran) as date'), DB::raw('SUM(jumlah) as total'))
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        $labels = $pemasukanChartData->pluck('date')->merge($pengeluaranChartData->pluck('date'))->unique()->sort()->values();

        $pemasukanValues = $labels->map(function ($date) use ($pemasukanChartData) {
            $item = $pemasukanChartData->where('date', $date)->first();
            return $item ? $item->total : 0;
        });

        $pengeluaranValues = $labels->map(function ($date) use ($pengeluaranChartData) {
            $item = $pengeluaranChartData->where('date', $date)->first();
            return $item ? $item->total : 0;
        });

        // Riwayat Transaksi
        $riwayatPembayaran = PembayaranIuran::where('id_user', Auth::id())
            ->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
                return $query->whereBetween('tgl_bayar', [$startDate, $endDate]);
            })
            ->with('member')
            ->get()
            ->map(function ($item) {
                return (object) [
                    'tipe' => 'Pemasukan',
                    'nama' => $item->member->nama ?? 'Tidak diketahui',
                    'jumlah' => $item->jumlah,
                    'tanggal' => $item->tgl_bayar,
                    'keterangan' => $item->catatan,
                ];
            });

        $riwayatPengeluaran = Pengeluaran::where('id_user', Auth::id())
            ->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
                return $query->whereBetween('tgl_pengeluaran', [$startDate, $endDate]);
            })
            ->get()
            ->map(function ($item) {
                return (object) [
                    'tipe' => 'Pengeluaran',
                    'nama' => $item->nama,
                    'jumlah' => $item->jumlah,
                    'tanggal' => $item->tgl_pengeluaran,
                    'keterangan' => $item->keterangan,
                ];
            });

        $riwayatTransaksi = $riwayatPembayaran->merge($riwayatPengeluaran)->sortByDesc('tanggal');

        // Paginate the combined results manually
        $perPage = 10;
        $page = request()->get('page', 1);
        $paginatedRiwayatTransaksi = new \Illuminate\Pagination\LengthAwarePaginator(
            $riwayatTransaksi->forPage($page, $perPage),
            $riwayatTransaksi->count(),
            $perPage,
            $page,
            ['path' => url()->current()]
        );

        return view('dashboard', [
            'labels' => $labels,
            'pemasukanValues' => $pemasukanValues,
            'pengeluaranValues' => $pengeluaranValues,
            'totalPemasukan' => $totalPemasukan,
            'totalPengeluaran' => $totalPengeluaran,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'riwayatTransaksi' => $paginatedRiwayatTransaksi,
        ]);
    }
    
    public function exportExcel(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $riwayatPembayaran = PembayaranIuran::where('id_user', Auth::id())
            ->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
                return $query->whereBetween('tgl_bayar', [$startDate, $endDate]);
            })
            ->with('member')
            ->get()
            ->map(function ($item) {
                return [
                    'Tipe Transaksi' => 'Pemasukan',
                    'Nama' => $item->member->nama ?? 'Tidak diketahui',
                    'Jumlah' => $item->jumlah,
                    'Tanggal' => $item->tgl_bayar,
                    'Keterangan' => $item->catatan,
                ];
            });

        $riwayatPengeluaran = Pengeluaran::where('id_user', Auth::id())
            ->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
                return $query->whereBetween('tgl_pengeluaran', [$startDate, $endDate]);
            })
            ->get()
            ->map(function ($item) {
                return [
                    'Tipe Transaksi' => 'Pengeluaran',
                    'Nama' => $item->nama,
                    'Jumlah' => $item->jumlah,
                    'Tanggal' => $item->tgl_pengeluaran,
                    'Keterangan' => $item->keterangan,
                ];
            });

        $riwayatTransaksi = $riwayatPembayaran->merge($riwayatPengeluaran)->sortBy('Tanggal');

        return Excel::download(new LaporanKeuanganExport($riwayatTransaksi), 'laporan_keuangan.xlsx');
    }

    public function exportPDF(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $riwayatPembayaran = PembayaranIuran::where('id_user', Auth::id())
            ->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
                return $query->whereBetween('tgl_bayar', [$startDate, $endDate]);
            })
            ->with('member')
            ->get()
            ->map(function ($item) {
                return (object) [
                    'tipe' => 'Pemasukan',
                    'nama' => $item->member->nama ?? 'Tidak diketahui',
                    'jumlah' => $item->jumlah,
                    'tanggal' => $item->tgl_bayar,
                    'keterangan' => $item->catatan,
                ];
            });

        $riwayatPengeluaran = Pengeluaran::where('id_user', Auth::id())
            ->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
                return $query->whereBetween('tgl_pengeluaran', [$startDate, $endDate]);
            })
            ->get()
            ->map(function ($item) {
                return (object) [
                    'tipe' => 'Pengeluaran',
                    'nama' => $item->nama,
                    'jumlah' => $item->jumlah,
                    'tanggal' => $item->tgl_pengeluaran,
                    'keterangan' => $item->keterangan,
                ];
            });

        $riwayatTransaksi = $riwayatPembayaran->merge($riwayatPengeluaran)->sortBy('tanggal');
        
        $totalPemasukan = $riwayatPembayaran->sum('jumlah');
        $totalPengeluaran = $riwayatPengeluaran->sum('jumlah');
        $saldoAkhir = $totalPemasukan - $totalPengeluaran;

        $pdf = Pdf::loadView('exports.laporan_keuangan_pdf', compact('riwayatTransaksi', 'startDate', 'endDate', 'totalPemasukan', 'totalPengeluaran', 'saldoAkhir'));
        return $pdf->download('laporan_keuangan.pdf');
    }
}