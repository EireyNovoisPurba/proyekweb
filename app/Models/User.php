<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

// 1. BAGIAN YANG DIGANTI: Tambahkan 'role' dan 'arsiparis_id' langsung ke dalam kurung atribut ini
#[Fillable(['name', 'email', 'password', 'foto_profil', 'role', 'arsiparis_id'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

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

    // 2. BAGIAN YANG DITAMBAHKAN: Relasi fungsi arsiparis dari instruksi AI
    // (Simpan di bagian paling bawah sebelum penutup class)
    public function arsiparis()
    {
        return $this->belongsTo(Arsiparis::class, 'arsiparis_id');
    }

    // Relasi: Satu Arsiparis bisa memiliki satu akun User
    public function user()
    {
        return $this->hasOne(User::class, 'arsiparis_id');
    }
}