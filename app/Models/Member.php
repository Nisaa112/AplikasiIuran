<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperMember
 */
class Member extends Model
{
    use HasFactory;

    protected $table = 'members';
    protected $fillable = [
        'id_user',
        'nama',
        'alamat',
        'no_hp',
        'email',
        'foto',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function pembayaranIuran()
    {
        return $this->hasMany(PembayaranIuran::class, 'id_member');
    }
}
