<?php

namespace App\Services;

use App\Exceptions\CheckoutException;
use App\Models\Barang;
use App\Models\Penjualan;
use App\Models\PenjualanDetail;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CheckoutService
{
    public function checkout(array $cart, int|string|null $cashierId, ?string $buyerName): CheckoutResult
    {
        if (empty($cart)) {
            throw new CheckoutException('Keranjang kosong.');
        }

        $cashier = User::query()->find($cashierId);

        if (! $cashier) {
            throw new CheckoutException('Kasir tidak valid.');
        }

        $buyerName = trim((string) $buyerName);

        if ($buyerName === '') {
            throw new CheckoutException('Nama pembeli wajib diisi.');
        }

        $barangIds = collect($cart)
            ->pluck('barang_id')
            ->map(fn ($id) => (int) $id)
            ->all();

        $saleCode = 'SLS-'.Str::upper(Str::random(8));

        $penjualan = DB::transaction(function () use ($cart, $cashier, $barangIds, $saleCode, $buyerName) {
            Barang::query()
                ->whereIn('barang_id', $barangIds)
                ->lockForUpdate()
                ->get();

            $catalog = Barang::query()
                ->whereIn('barang_id', $barangIds)
                ->withCurrentStock()
                ->get()
                ->keyBy('barang_id');

            $lineItems = collect($cart)->map(function (array $item) use ($catalog) {
                $barang = $catalog->get((int) $item['barang_id']);

                if (! $barang) {
                    throw new CheckoutException('Barang tidak ditemukan.');
                }

                $qty = (int) $item['qty'];
                $availableStock = $barang->current_stock;

                if ($qty <= 0) {
                    throw new CheckoutException("Qty untuk {$barang->barang_nama} tidak valid.");
                }

                if ($availableStock < $qty) {
                    throw new CheckoutException("Stok {$barang->barang_nama} tidak mencukupi (Tersedia: {$availableStock})");
                }

                return [
                    'barang' => $barang,
                    'qty' => $qty,
                    'harga' => (int) $item['harga'],
                ];
            });

            $penjualan = Penjualan::query()->create([
                'user_id' => $cashier->getKey(),
                'pembeli' => $buyerName,
                'penjualan_kode' => $saleCode,
                'penjualan_tanggal' => now(),
            ]);

            foreach ($lineItems as $lineItem) {
                PenjualanDetail::query()->create([
                    'penjualan_id' => $penjualan->getKey(),
                    'barang_id' => $lineItem['barang']->getKey(),
                    'harga' => $lineItem['harga'],
                    'jumlah' => $lineItem['qty'],
                ]);
            }

            return $penjualan;
        }, 3);

        return new CheckoutResult(
            saleCode: $saleCode,
            cashierName: $cashier->nama,
            buyerName: $buyerName,
            total: $this->calculateTotal(collect($cart)),
            penjualanId: $penjualan->getKey(),
        );
    }

    protected function calculateTotal(Collection $cart): int
    {
        return $cart->sum(fn (array $item) => ((int) $item['harga']) * ((int) $item['qty']));
    }
}
