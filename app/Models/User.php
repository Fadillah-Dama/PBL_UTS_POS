<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
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
