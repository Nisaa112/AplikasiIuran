<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class histori extends Model
{
    use HasFactory;

    protected $table = 'histories';
    protected $fillable = [
        'id_user',
        'keterangan',
        'jumlah',
        'tgl_transaksi',
        'jenis_transaksi',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}
