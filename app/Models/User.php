<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasAvatar; // ✅ Tambahkan interface untuk avatar
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements FilamentUser, HasAvatar
{
    use HasFactory, Notifiable, HasRoles;

    /**
     * Cek apakah user bisa akses panel
     */
    public function canAccessPanel(Panel $panel): bool
    {
        return $this->hasRole(['admin','agen']); // sesuaikan role kalau perlu
    }

    /**
     * Kolom yang boleh diisi
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'photo',
    ];

    /**
     * Kolom yang disembunyikan saat serialisasi
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Casting kolom
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * URL avatar untuk Filament
     */
    public function getFilamentAvatarUrl(): ?string
    {
        return $this->photo
            ? asset('storage/' . $this->photo) // ambil dari storage/users
            : 'https://ui-avatars.com/api/?name=' . urlencode($this->name); // fallback avatar
    }
}
