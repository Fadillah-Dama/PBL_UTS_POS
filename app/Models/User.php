<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements FilamentUser
{
    use HasFactory, Notifiable;

    protected $table = 'm_user';

    protected $primaryKey = 'user_id';

    protected $fillable = [
        'level_id',
        'username',
        'email',
        'nama',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    public function getNameAttribute(): string
    {
        return $this->nama;
    }

    public function hasLevel(string ...$codes): bool
    {
        $levelCode = $this->level?->level_kode;

        return in_array($levelCode, $codes, true);
    }

    public function isAdmin(): bool
    {
        return $this->hasLevel('ADM');
    }

    public function isCashier(): bool
    {
        return $this->hasLevel('KSR');
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return $this->hasLevel('ADM', 'KSR');
    }

    public function level(): BelongsTo
    {
        return $this->belongsTo(Level::class, 'level_id', 'level_id');
    }

    public function penjualan(): HasMany
    {
        return $this->hasMany(Penjualan::class, 'user_id', 'user_id');
    }

    public function stok(): HasMany
    {
        return $this->hasMany(Stok::class, 'user_id', 'user_id');
    }
}
