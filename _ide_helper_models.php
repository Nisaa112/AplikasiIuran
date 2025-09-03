<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * @property int $id
 * @property int $id_user
 * @property string $nama
 * @property string $alamat
 * @property string $no_hp
 * @property string|null $email
 * @property string|null $foto
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\PembayaranIuran|null $pembayaranIuran
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Member newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Member newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Member query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Member whereAlamat($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Member whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Member whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Member whereFoto($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Member whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Member whereIdUser($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Member whereNama($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Member whereNoHp($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Member whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperMember {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $id_user
 * @property int $id_member
 * @property string $jumlah
 * @property string|null $catatan
 * @property string $tgl_bayar
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Member $member
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PembayaranIuran newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PembayaranIuran newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PembayaranIuran query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PembayaranIuran whereCatatan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PembayaranIuran whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PembayaranIuran whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PembayaranIuran whereIdMember($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PembayaranIuran whereIdUser($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PembayaranIuran whereJumlah($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PembayaranIuran whereTglBayar($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PembayaranIuran whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperPembayaranIuran {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $id_user
 * @property string $nama
 * @property string $jumlah
 * @property string $tgl_pengeluaran
 * @property string $keterangan
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pengeluaran newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pengeluaran newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pengeluaran query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pengeluaran whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pengeluaran whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pengeluaran whereIdUser($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pengeluaran whereJumlah($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pengeluaran whereKeterangan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pengeluaran whereNama($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pengeluaran whereTglPengeluaran($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pengeluaran whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperPengeluaran {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Member> $member
 * @property-read int|null $member_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\PembayaranIuran> $pembayaranIuran
 * @property-read int|null $pembayaran_iuran_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Pengeluaran> $pengeluaran
 * @property-read int|null $pengeluaran_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperUser {}
}

