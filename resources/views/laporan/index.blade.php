@extends('templates.header')

@section('content')
<!-- {{ dd($labels, $pemasukanValues, $pengeluaranValues) }} -->
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Laporan Keuangan
        <small>Bulan {{ \Carbon\Carbon::create()->month((int)$currentMonth)->format('F') }} {{ $currentYear }}</small>
    </h1>
    <ol class="breadcrumb">
        {{-- PERUBAHAN KECIL: Link Home sekarang berfungsi --}}
        <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Laporan Keuangan</li>
    </ol>
</section>

<!-- Main content -->
<section class="content">

    <!-- Filter dan Tombol Export -->
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Filter Laporan</h3>
                </div>
                <div class="box-body">
                    {{-- PERUBAHAN UTAMA: Action form sekarang benar --}}
                    <form action="{{ route('laporan.index') }}" method="get" class="form-inline">
                        <div class="form-group">
                            <label for="tahun">Tahun:</label>
                            <select name="tahun" id="tahun" class="form-control">
                                @for ($year = date('Y'); $year >= 2020; $year--)
                                <option value="{{ $year }}" {{ $currentYear == $year ? 'selected' : '' }}>{{ $year }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="form-group" style="margin-left: 10px;">
                            <label for="bulan">Bulan:</label>
                            <select name="bulan" id="bulan" class="form-control">
                                @for ($month = 1; $month <= 12; $month++)
                                <option value="{{ str_pad($month, 2, '0', STR_PAD_LEFT) }}" {{ $currentMonth == str_pad($month, 2, '0', STR_PAD_LEFT) ? 'selected' : '' }}>
                                    {{ \Carbon\Carbon::create()->month($month)->format('F') }}
                                </option>
                                @endfor
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary" style="margin-left: 10px;">Tampilkan</button>

                        <div class="pull-right">
                            <a href="{{ route('laporan.export.excel', request()->query()) }}" class="btn btn-success"><i class="fa fa-file-excel-o"></i> Export Excel</a>
                            <a href="{{ route('laporan.export.pdf', request()->query()) }}" class="btn btn-danger"><i class="fa fa-file-pdf-o"></i> Export PDF</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Summary Box -->
    <div class="row">
        <div class="col-lg-4 col-xs-12">
            <div class="small-box bg-green">
                <div class="inner">
                    <h3>Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</h3>
                    <p>Total Pemasukan</p>
                </div>
                <div class="icon"><i class="ion ion-arrow-up-c"></i></div>
            </div>
        </div>
        <div class="col-lg-4 col-xs-12">
            <div class="small-box bg-red">
                <div class="inner">
                    <h3>Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</h3>
                    <p>Total Pengeluaran</p>
                </div>
                <div class="icon"><i class="ion ion-arrow-down-c"></i></div>
            </div>
        </div>
        <div class="col-lg-4 col-xs-12">
            <div class="small-box bg-aqua">
                <div class="inner">
                    <h3>Rp {{ number_format($saldoAkhir, 0, ',', '.') }}</h3>
                    <p>Saldo Akhir</p>
                </div>
                <div class="icon"><i class="ion ion-briefcase"></i></div>
            </div>
        </div>
    </div>

    <!-- Chart Box -->
    <div class="row">
        <div class="col-md-12">
            <div class="box box-success">
                <div class="box-header with-border">
                    <h3 class="box-title">Grafik Keuangan Mingguan</h3>
                </div>
                <div class="box-body">
                    <div class="chart">
                        <canvas id="financialChart" style="height: 250px;"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Transaction History Table -->
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Riwayat Transaksi</h3>
                </div>
                <div class="box-body table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th style="width: 10px">#</th>
                                <th>Tanggal</th>
                                <th>Tipe</th>
                                <th>Keterangan</th>
                                <th class="text-right">Jumlah</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($riwayatTransaksi as $transaksi)
                            <tr>
                                <td>{{ $loop->iteration + ($riwayatTransaksi->currentPage() - 1) * $riwayatTransaksi->perPage() }}</td>
                                <td>{{ \Carbon\Carbon::parse($transaksi->tanggal)->format('d M Y') }}</td>
                                <td>
                                    @if($transaksi->tipe == 'Pemasukan')
                                    <span class="label label-success">Pemasukan</span>
                                    @else
                                    <span class="label label-danger">Pengeluaran</span>
                                    @endif
                                </td>
                                <td>{{ $transaksi->nama }} - {{ $transaksi->keterangan }}</td>
                                <td class="text-right">Rp {{ number_format($transaksi->jumlah, 0, ',', '.') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center">Tidak ada data transaksi pada periode ini.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="box-footer clearfix">
                    {{ $riwayatTransaksi->links() }}
                </div>
            </div>
        </div>
    </div>

</section>
<!-- /.content -->
@endsection

@push('scripts')
<!-- ChartJS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.min.js"></script>
<script>
$(function () {
    'use strict';

    // Mengambil data dari Blade dan mem-parsingnya dengan aman
    const labels = JSON.parse('{!! json_encode($labels) !!}');
    const pemasukanValues = JSON.parse('{!! json_encode($pemasukanValues) !!}');
    const pengeluaranValues = JSON.parse('{!! json_encode($pengeluaranValues) !!}');

    var financialChartCanvas = $('#financialChart').get(0).getContext('2d');

    var financialChartData = {
        labels  : labels, // Menggunakan variabel yang sudah di-parse
        datasets: [
            {
                label               : 'Pemasukan',
                backgroundColor     : 'rgba(60,141,188,0.9)',
                borderColor         : 'rgba(60,141,188,0.8)',
                data                : pemasukanValues // Menggunakan variabel yang sudah di-parse
            },
            {
                label               : 'Pengeluaran',
                backgroundColor     : 'rgba(210, 214, 222, 1)',
                borderColor         : 'rgba(210, 214, 222, 1)',
                data                : pengeluaranValues // Menggunakan variabel yang sudah di-parse
            }
        ]
    };

    var financialChartOptions = {
        responsive              : true,
        maintainAspectRatio     : false,
        datasetFill             : false,
        scales: {
            yAxes: [{
                ticks: {
                    beginAtZero: true,
                    callback: function(value, index, values) {
                        return 'Rp ' + value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                    }
                }
            }]
        },
        tooltips: {
            callbacks: {
                label: function(tooltipItem, data) {
                    var label = data.datasets[tooltipItem.datasetIndex].label || '';
                    if (label) {
                        label += ': ';
                    }
                    label += 'Rp ' + tooltipItem.yLabel.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                    return label;
                }
            }
        }
    };

    // Ini adalah bar chart
    var barChart = new Chart(financialChartCanvas, {
        type: 'bar',
        data: financialChartData,
        options: financialChartOptions
    });
});
</script>
@endpush