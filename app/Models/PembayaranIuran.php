<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperPembayaranIuran
 */
class PembayaranIuran extends Model
{
    use HasFactory;

    protected $table = 'pembayaran_iuran';
    protected $fillable = [
        'id_user',
        'id_member',
        'jumlah',
        'catatan',
        'tgl_bayar',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function member()
    {
        return $this->belongsTo(Member::class, 'id_member');
    }
}
