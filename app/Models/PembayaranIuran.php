<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PembayaranIuran extends Model
{
    use HasFactory;

    protected $table = 'pembayaran_iuran';
    protected $fillable = [
        'id_tipe_iuran',
        'id_user',
        'jumlah',
        'catatan',
        'tgl_bayar',
    ];

    public function tipe_iuran()
    {
        return $this->belongsTo(TipeIuran::class, 'id_tipe_iuran');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}
