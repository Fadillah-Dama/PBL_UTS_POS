<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Barang extends Model
{
    use HasFactory;

    protected $table = 'm_barang';

    protected $primaryKey = 'barang_id';

    protected $fillable = [
        'kategori_id',
        'barang_kode',
        'barang_nama',
        'harga_beli',
        'harga_jual',
    ];

    protected $appends = [
        'current_stock',
    ];

    public function getCurrentStockAttribute(): int
    {
        $added = (int) ($this->getAttribute('stok_masuk') ?? $this->stok()->sum('stok_jumlah'));
        $sold = (int) ($this->getAttribute('stok_keluar') ?? $this->penjualanDetail()->sum('jumlah'));

        return $added - $sold;
    }

    public function scopeWithCurrentStock(Builder $query): Builder
    {
        return $query
            ->withSum('stok as stok_masuk', 'stok_jumlah')
            ->withSum('penjualanDetail as stok_keluar', 'jumlah');
    }

    public function kategori(): BelongsTo
    {
        return $this->belongsTo(Kategori::class, 'kategori_id', 'kategori_id');
    }

    public function stok(): HasMany
    {
        return $this->hasMany(Stok::class, 'barang_id', 'barang_id');
    }

    public function penjualanDetail(): HasMany
    {
        return $this->hasMany(PenjualanDetail::class, 'barang_id', 'barang_id');
    }
}
