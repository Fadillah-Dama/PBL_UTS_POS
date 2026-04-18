<?php

namespace App\Livewire;

use App\Exceptions\CheckoutException;
use App\Models\Barang;
use App\Services\CheckoutService;
use Illuminate\Support\Facades\Schema;
use Livewire\Attributes\Computed;
use Livewire\Component;

class CashierTerminal extends Component
{
    public array $cart = [];

    public string $buyerName = '';

    protected CheckoutService $checkoutService;

    public function boot(CheckoutService $checkoutService): void
    {
        $this->checkoutService = $checkoutService;
    }

    public function mount(): void
    {
        $this->syncCartStock();
    }

    public function hydrate(): void
    {
        $this->syncCartStock();
    }

    public function addToCart($barangId)
    {
        $barang = $this->findBarang($barangId);

        if (! $barang) {
            return;
        }

        $qty = ($this->cart[$barangId]['qty'] ?? 0) + 1;

        if (! $this->canUseQty($barang, $qty)) {
            return;
        }

        $this->setCartItem($barang, $qty);
    }

    public function removeFromCart($barangId)
    {
        unset($this->cart[$barangId]);
    }

    public function updateQty($barangId, $qty)
    {
        if ($qty <= 0) {
            $this->removeFromCart($barangId);

            return;
        }

        if (! isset($this->cart[$barangId])) {
            return;
        }

        $barang = $this->findBarang($barangId);

        if (! $barang) {
            $this->removeFromCart($barangId);

            return;
        }

        $qty = (int) $qty;

        if (! $this->canUseQty($barang, $qty)) {
            $this->cart[$barangId]['stock'] = $barang->current_stock;

            return;
        }

        $this->setCartItem($barang, $qty);
    }

    #[Computed]
    public function grandTotal()
    {
        return collect($this->cart)->sum(fn ($item) => $item['harga'] * $item['qty']);
    }

    #[Computed]
    public function totalItems()
    {
        return collect($this->cart)->sum('qty');
    }

    protected function canUseQty(Barang $barang, int $qty): bool
    {
        return $qty > 0 && $qty <= $barang->current_stock;
    }

    protected function syncCartStock(): void
    {
        if ($this->cart === []) {
            return;
        }

        $catalog = Barang::query()
            ->whereIn('barang_id', array_keys($this->cart))
            ->withCurrentStock()
            ->get()
            ->keyBy('barang_id');

        foreach (array_keys($this->cart) as $barangId) {
            $barang = $catalog->get((int) $barangId);

            if (! $barang) {
                unset($this->cart[$barangId]);

                continue;
            }

            $availableStock = $barang->current_stock;

            $this->cart[$barangId]['stock'] = $availableStock;

            if ($availableStock <= 0) {
                unset($this->cart[$barangId]);

                continue;
            }

            if ($this->cart[$barangId]['qty'] > $availableStock) {
                $this->setCartItem($barang, $availableStock);
            }
        }
    }

    public function checkout()
    {
        $this->syncCartStock();

        if (empty($this->cart)) {
            return;
        }

        if (trim($this->buyerName) === '') {
            return;
        }

        try {
            $this->checkoutService->checkout($this->cart, auth()->id(), $this->buyerName);
            $this->cart = [];
            $this->buyerName = '';
        } catch (CheckoutException $e) {
            return;
        }
    }

    public function render()
    {
        if (! Schema::hasTable('m_barang')) {
            $barangs = collect();
        } else {
            $barangs = Barang::query()
                ->withCurrentStock()
                ->orderBy('barang_nama')
                ->get();
        }

        return view('livewire.cashier-terminal', [
            'barangs' => $barangs,
        ]);
    }

    protected function findBarang(int|string $barangId): ?Barang
    {
        return Barang::query()
            ->withCurrentStock()
            ->find($barangId);
    }

    protected function setCartItem(Barang $barang, int $qty): void
    {
        $this->cart[$barang->barang_id] = [
            'barang_id' => $barang->barang_id,
            'nama' => $barang->barang_nama,
            'harga' => $barang->harga_jual,
            'qty' => $qty,
            'stock' => $barang->current_stock,
        ];
    }
}
