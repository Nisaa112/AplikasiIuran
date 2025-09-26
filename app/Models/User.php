<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;

/**
 * @mixin IdeHelperUser
 */
class User extends Authenticatable implements JWTSubject
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'serial_number',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return[];
    }

    public function pembayaranIuran()
    {
        return $this->hasMany(pembayaranIuran::class, 'id_user');
    }

    public function member()
    {
        return $this->hasMany(Member::class, 'id_user');
    }

    public function pengeluaran()
    {
        return $this->hasMany(Pengeluaran::class, 'id_user');
    }

    public function histori()
    {
        return $this->hasMany(histori::class, 'id_user');
    }

    public function profil()
    {
        return $this->hasMany(Profil::class, 'id_user');
    }
}
