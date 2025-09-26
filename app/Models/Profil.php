<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profil extends Model
{
    use HasFactory;

    protected $table = 'profile';
    protected $fillable = [
        'id_user',
        'nama_kegiatan',
        'alamat'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}
