<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperPengeluaran
 */
class Pengeluaran extends Model
{
    use HasFactory;

    protected $table = 'pengeluarans';
    protected $fillable = [
        'id_user',
        'nama',
        'jumlah',
        'tgl_pengeluaran',
        'keterangan',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}
