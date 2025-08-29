<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipeIuran extends Model
{
    use HasFactory;

    protected $table = 'tipe_iuran';
    protected $fillable = [
        'name',
        'deskripsi',
        'nominal',
        'period'
    ];
}
