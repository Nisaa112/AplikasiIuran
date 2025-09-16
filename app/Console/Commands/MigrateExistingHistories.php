<?php

namespace App\Console\Commands;

use App\Models\histori;
use App\Models\Pengeluaran;
use App\Models\PembayaranIuran;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class MigrateExistingHistories extends Command
{
    protected $signature = 'migrate:histories';
    protected $description = 'Migrate existing Pengeluaran and Pembayaran Iuran data to the Histories table.';

    public function handle()
    {
        $this->info('Starting migration of existing data to histories table...');

        // 1. Migrasi data Pemasukan (PembayaranIuran)
        $pemasukan = PembayaranIuran::all();
        $this->info('Migrating ' . $pemasukan->count() . ' Pemasukan records...');
        foreach ($pemasukan as $item) {
            histori::create([
                'id_user' => $item->id_user,
                'keterangan' => 'Pemasukan dari ' . $item->member->nama,
                'jumlah' => $item->jumlah,
                'tgl_transaksi' => $item->tgl_bayar,
                'jenis_transaksi' => 'pemasukan',
            ]);
        }
        $this->info('Pemasukan records migrated successfully.');

        // 2. Migrasi data Pengeluaran
        $pengeluaran = Pengeluaran::all();
        $this->info('Migrating ' . $pengeluaran->count() . ' Pengeluaran records...');
        foreach ($pengeluaran as $item) {
            histori::create([
                'id_user' => $item->id_user,
                'keterangan' => 'Pengeluaran: ' . $item->nama,
                'jumlah' => $item->jumlah,
                'tgl_transaksi' => $item->tgl_pengeluaran,
                'jenis_transaksi' => 'pengeluaran',
            ]);
        }
        $this->info('Pengeluaran records migrated successfully.');

        $this->info('All data migrated successfully!');
        return 0;
    }
}