<?php

namespace App\Livewire;

use App\Exceptions\CheckoutException;
use App\Models\Barang;
use App\Models\User;
use App\Services\CheckoutService;
use Illuminate\Support\Facades\Schema;
use Livewire\Attributes\Computed;
use Livewire\Component;

class CashierTerminal extends Component
{
    public array $cart = [];

    public string $buyerName = '';

    public $selectedUser = null;

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
        $barang = Barang::query()->withCurrentStock()->find($barangId);

        if (! $barang) {
            return;
        }

        $nextQty = ($this->cart[$barangId]['qty'] ?? 0) + 1;

        if (! $this->ensureStockAvailable($barang, $nextQty)) {
            return;
        }

        $this->cart[$barangId] = [
            'barang_id' => $barang->barang_id,
            'nama' => $barang->barang_nama,
            'harga' => $barang->harga_jual,
            'qty' => $nextQty,
            'stock' => $barang->current_stock,
        ];
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

        $barang = Barang::query()->withCurrentStock()->find($barangId);

        if (! $barang) {
            $this->removeFromCart($barangId);

            return;
        }

        if (! $this->ensureStockAvailable($barang, (int) $qty)) {
            $this->cart[$barangId]['stock'] = $barang->current_stock;

            return;
        }

        $this->cart[$barangId]['qty'] = (int) $qty;
        $this->cart[$barangId]['stock'] = $barang->current_stock;
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

    protected function ensureStockAvailable(Barang $barang, int $qty): bool
    {
        if ($barang->current_stock <= 0) {
            return false;
        }

        if ($qty > $barang->current_stock) {
            return false;
        }

        return true;
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
                $this->cart[$barangId]['qty'] = $availableStock;
            }
        }
    }

    public function checkout()
    {
        $this->syncCartStock();

        if (empty($this->cart)) {
            return;
        }

        if (! $this->selectedUser) {
            return;
        }

        if (trim($this->buyerName) === '') {
            return;
        }

        try {
            $result = $this->checkoutService->checkout($this->cart, $this->selectedUser, $this->buyerName);

            $this->cart = [];
            $this->buyerName = '';
            $this->selectedUser = null;
        } catch (CheckoutException $e) {
            report($e);
        }
    }

    public function render()
    {
        if (
            ! Schema::hasTable('m_barang')
            || ! Schema::hasTable('m_user')
        ) {
            $barangs = collect();
            $users = collect();
        } else {
            $barangs = Barang::query()
                ->withCurrentStock()
                ->orderBy('barang_nama')
                ->get();
            $users = User::all();
        }

        return view('livewire.cashier-terminal', [
            'barangs' => $barangs,
            'users' => $users,
        ]);
    }
}
