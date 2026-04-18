<?php

namespace Tests\Feature;

use App\Livewire\CashierTerminal;
use App\Models\Barang;
use App\Models\Kategori;
use App\Models\Level;
use App\Models\Penjualan;
use App\Models\PenjualanDetail;
use App\Models\Stok;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class CashierCheckoutTest extends TestCase
{
    use RefreshDatabase;

    public function test_checkout_creates_sale_and_sale_details(): void
    {
        [$cashier, $barang] = $this->createCheckoutFixtures(initialStock: 5);

        Livewire::test(CashierTerminal::class)
            ->set('buyerName', 'Budi')
            ->set('selectedUser', (string) $cashier->getKey())
            ->call('addToCart', $barang->getKey())
            ->call('updateQty', $barang->getKey(), 3)
            ->call('checkout')
            ->assertSet('cart', [])
            ->assertSet('buyerName', '')
            ->assertSet('selectedUser', null);

        $this->assertDatabaseCount('t_penjualan', 1);
        $this->assertDatabaseHas('t_penjualan', [
            'user_id' => $cashier->getKey(),
            'pembeli' => 'Budi',
        ]);
        $this->assertDatabaseHas('t_penjualan_detail', [
            'barang_id' => $barang->getKey(),
            'jumlah' => 3,
            'harga' => $barang->harga_jual,
        ]);
    }

    public function test_quantity_is_capped_by_available_stock(): void
    {
        [, $barang] = $this->createCheckoutFixtures(initialStock: 2);

        Livewire::test(CashierTerminal::class)
            ->call('addToCart', $barang->getKey())
            ->call('updateQty', $barang->getKey(), 5)
            ->assertSet("cart.{$barang->getKey()}.qty", 1)
            ->assertSet("cart.{$barang->getKey()}.stock", 2);
    }

    public function test_checkout_requires_buyer_name(): void
    {
        [$cashier, $barang] = $this->createCheckoutFixtures(initialStock: 5);

        Livewire::test(CashierTerminal::class)
            ->set('selectedUser', (string) $cashier->getKey())
            ->call('addToCart', $barang->getKey())
            ->call('checkout')
            ->assertSet("cart.{$barang->getKey()}.qty", 1)
            ->assertSet('buyerName', '');

        $this->assertDatabaseCount('t_penjualan', 0);
    }

    public function test_checkout_stops_when_stock_is_fully_consumed_before_payment(): void
    {
        [$cashier, $barang] = $this->createCheckoutFixtures(initialStock: 3);

        $component = Livewire::test(CashierTerminal::class)
            ->set('buyerName', 'Budi')
            ->set('selectedUser', (string) $cashier->getKey())
            ->call('addToCart', $barang->getKey())
            ->call('updateQty', $barang->getKey(), 3);

        $penjualan = Penjualan::query()->create([
            'user_id' => $cashier->getKey(),
            'pembeli' => 'Walk In',
            'penjualan_kode' => 'SLS-TAKEN1',
            'penjualan_tanggal' => now(),
        ]);

        PenjualanDetail::query()->create([
            'penjualan_id' => $penjualan->getKey(),
            'barang_id' => $barang->getKey(),
            'harga' => $barang->harga_jual,
            'jumlah' => 3,
        ]);

        $component
            ->call('checkout')
            ->assertSet('cart', []);

        $this->assertDatabaseCount('t_penjualan', 1);
    }

    public function test_barang_current_stock_uses_aggregated_query_data(): void
    {
        [, $barang] = $this->createCheckoutFixtures(initialStock: 8);

        $penjualan = Penjualan::query()->create([
            'user_id' => User::query()->firstOrFail()->getKey(),
            'pembeli' => 'Walk In',
            'penjualan_kode' => 'SLS-TEST01',
            'penjualan_tanggal' => now(),
        ]);

        PenjualanDetail::query()->create([
            'penjualan_id' => $penjualan->getKey(),
            'barang_id' => $barang->getKey(),
            'harga' => $barang->harga_jual,
            'jumlah' => 3,
        ]);

        $freshBarang = Barang::query()
            ->whereKey($barang->getKey())
            ->withCurrentStock()
            ->firstOrFail();

        $this->assertSame(5, $freshBarang->current_stock);
    }

    protected function createCheckoutFixtures(int $initialStock): array
    {
        $level = Level::query()->create([
            'level_kode' => 'ADM',
            'level_nama' => 'Admin',
        ]);

        $cashier = User::query()->create([
            'level_id' => $level->getKey(),
            'username' => 'cashier',
            'email' => 'cashier@example.com',
            'nama' => 'Cashier Test',
            'password' => 'password',
        ]);

        $kategori = Kategori::query()->create([
            'kategori_kode' => 'FOOD',
            'kategori_nama' => 'Food',
        ]);

        $supplier = Supplier::query()->create([
            'supplier_kode' => 'SUP1',
            'supplier_nama' => 'Supplier Test',
            'supplier_alamat' => 'Jl. Test',
        ]);

        $barang = Barang::query()->create([
            'kategori_id' => $kategori->getKey(),
            'barang_kode' => 'BRG1',
            'barang_nama' => 'Nasi Goreng',
            'harga_beli' => 10000,
            'harga_jual' => 15000,
        ]);

        Stok::query()->create([
            'supplier_id' => $supplier->getKey(),
            'barang_id' => $barang->getKey(),
            'user_id' => $cashier->getKey(),
            'stok_tanggal' => now(),
            'stok_jumlah' => $initialStock,
        ]);

        return [$cashier, $barang];
    }
}
