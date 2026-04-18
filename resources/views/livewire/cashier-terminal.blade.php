<div class="min-h-screen bg-slate-100">
    <div class="mx-auto flex min-h-screen max-w-7xl flex-col gap-6 px-4 py-6 sm:px-6 lg:flex-row lg:gap-8 lg:px-8 lg:py-10">
        <main class="w-full lg:w-[68%]">
            <section class="rounded-[2rem] border border-slate-200 bg-white p-6 shadow-sm">
                <div class="flex flex-col gap-4 border-b border-slate-200 pb-6 sm:flex-row sm:items-end sm:justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.35em] text-slate-500">Point Of Sale</p>
                        <h1 class="mt-3 text-3xl font-semibold tracking-tight text-slate-900 sm:text-4xl">Katalog barang siap jual</h1>
                        <p class="mt-2 max-w-2xl text-sm text-slate-500">Pilih produk, cek stok real-time, lalu lanjutkan transaksi tanpa melebihi persediaan.</p>
                    </div>
                    <div class="grid grid-cols-2 gap-3 text-sm">
                        <div class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-slate-900">
                            <p class="text-[11px] uppercase tracking-[0.3em] text-slate-500">Produk</p>
                            <p class="mt-1 text-2xl font-semibold">{{ count($barangs) }}</p>
                        </div>
                        <div class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-slate-900">
                            <p class="text-[11px] uppercase tracking-[0.3em] text-slate-500">Item Cart</p>
                            <p class="mt-1 text-2xl font-semibold">{{ $this->totalItems }}</p>
                        </div>
                    </div>
                </div>

                <div class="mt-6 grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-3">
                    @foreach($barangs as $barang)
                        @php
                            $stock = $barang->current_stock;
                            $inCart = $cart[$barang->barang_id]['qty'] ?? 0;
                            $isOutOfStock = $stock <= 0;
                        @endphp

                        <article class="flex flex-col overflow-hidden rounded-[1.75rem] border border-slate-200 bg-white shadow-sm transition hover:shadow-md">
                            <div class="border-b border-slate-200 p-5">
                                <div class="flex items-start justify-between gap-3">
                                    <div>
                                        <p class="text-[11px] uppercase tracking-[0.32em] text-slate-500">{{ $barang->barang_kode }}</p>
                                        <h2 class="mt-3 text-xl font-semibold leading-tight text-slate-900">{{ $barang->barang_nama }}</h2>
                                    </div>
                                    <span class="rounded-full border px-3 py-1 text-xs font-semibold {{ $isOutOfStock ? 'border-red-200 bg-red-50 text-red-700' : 'border-slate-200 bg-slate-50 text-slate-700' }}">
                                        {{ $isOutOfStock ? 'Stok Habis' : 'Stok '.$stock }}
                                    </span>
                                </div>
                            </div>

                            <div class="flex flex-1 flex-col justify-between p-5">
                                <div class="space-y-3">
                                    <div class="flex items-baseline justify-between gap-3">
                                        <p class="text-sm text-slate-500">Harga jual</p>
                                        <p class="text-xl font-semibold text-slate-900">Rp {{ number_format($barang->harga_jual, 0, ',', '.') }}</p>
                                    </div>

                                    <div class="rounded-2xl bg-slate-50 p-4 text-sm text-slate-600">
                                        <div class="flex items-center justify-between">
                                            <span>Di keranjang</span>
                                            <span class="font-semibold text-slate-900">{{ $inCart }}</span>
                                        </div>
                                        <div class="mt-2 flex items-center justify-between">
                                            <span>Sisa tersedia</span>
                                            <span class="font-semibold {{ $stock - $inCart <= 0 ? 'text-red-600' : 'text-slate-900' }}">{{ max($stock - $inCart, 0) }}</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-5">
                                    <button
                                        wire:click="addToCart({{ $barang->barang_id }})"
                                        @disabled($isOutOfStock || $inCart >= $stock)
                                        class="w-full rounded-2xl border px-4 py-3 text-sm font-semibold transition {{ $isOutOfStock || $inCart >= $stock ? 'cursor-not-allowed border-slate-200 bg-slate-100 text-slate-500' : 'border-slate-900 bg-slate-900 text-white hover:bg-slate-800' }}"
                                    >
                                        {{ $isOutOfStock ? 'Stok Habis' : ($inCart >= $stock ? 'Batas Stok Tercapai' : 'Tambah ke Keranjang') }}
                                    </button>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
            </section>
        </main>

        <aside class="w-full lg:w-[32%]">
            <div class="lg:sticky lg:top-8">
                <section class="overflow-hidden rounded-[2rem] border border-slate-200 bg-white text-slate-900 shadow-sm">
                    <div class="border-b border-slate-200 px-6 py-6">
                        <p class="text-xs font-semibold uppercase tracking-[0.35em] text-slate-500">Checkout</p>
                        <div class="mt-3 flex items-end justify-between gap-4">
                            <div>
                                <h2 class="text-2xl font-semibold tracking-tight">Keranjang aktif</h2>
                                <p class="mt-2 text-sm text-slate-500">{{ $this->totalItems }} item dipilih</p>
                            </div>
                            <div class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-right">
                                <p class="text-[11px] uppercase tracking-[0.3em] text-slate-500">Total</p>
                                <p class="mt-1 text-xl font-semibold">Rp {{ number_format($this->grandTotal, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="max-h-[26rem] space-y-4 overflow-y-auto px-6 py-6">
                        @forelse($cart as $id => $item)
                            <div class="rounded-[1.5rem] border border-slate-200 bg-slate-50 p-4">
                                <div class="flex items-start justify-between gap-4">
                                    <div class="min-w-0">
                                        <h3 class="truncate text-base font-semibold text-slate-900">{{ $item['nama'] }}</h3>
                                        <p class="mt-1 text-sm text-slate-500">Rp {{ number_format($item['harga'], 0, ',', '.') }} / item</p>
                                    </div>
                                    <button wire:click="removeFromCart({{ $id }})" class="text-xs font-semibold uppercase tracking-[0.2em] text-red-500 transition hover:text-red-600">
                                        Hapus
                                    </button>
                                </div>

                                <div class="mt-4 flex items-center justify-between gap-4">
                                    <div class="inline-flex items-center rounded-2xl border border-slate-200 bg-white p-1">
                                        <button wire:click="updateQty({{ $id }}, {{ $item['qty'] - 1 }})" class="h-10 w-10 rounded-xl text-lg text-slate-600 transition hover:bg-slate-100">-</button>
                                        <span class="min-w-14 text-center text-sm font-semibold">{{ $item['qty'] }}</span>
                                        <button wire:click="updateQty({{ $id }}, {{ $item['qty'] + 1 }})" class="h-10 w-10 rounded-xl text-lg text-slate-600 transition hover:bg-slate-100">+</button>
                                    </div>
                                    <p class="text-right text-lg font-semibold text-slate-900">Rp {{ number_format($item['harga'] * $item['qty'], 0, ',', '.') }}</p>
                                </div>

                                <div class="mt-3 flex items-center justify-between text-xs uppercase tracking-[0.2em]">
                                    <span class="text-slate-500">Stok tersedia</span>
                                    <span class="{{ ($item['stock'] ?? 0) > 0 ? 'text-slate-900' : 'text-red-600' }}">{{ $item['stock'] ?? 0 }}</span>
                                </div>
                            </div>
                        @empty
                            <div class="rounded-[1.5rem] border border-dashed border-slate-300 bg-slate-50 px-6 py-12 text-center">
                                <p class="text-lg font-semibold text-slate-900">Keranjang kosong</p>
                                <p class="mt-2 text-sm text-slate-500">Tambahkan barang dari katalog. Qty otomatis dibatasi stok tersedia.</p>
                            </div>
                        @endforelse
                    </div>

                    <div class="border-t border-slate-200 px-6 py-6">
                        <label for="buyer_name" class="mb-2 block text-sm font-medium text-slate-700">Nama pembeli</label>
                        <input
                            wire:model.live.debounce.300ms="buyerName"
                            id="buyer_name"
                            type="text"
                            placeholder="Masukkan nama pembeli"
                            class="w-full rounded-2xl border border-slate-200 bg-slate-50 p-3 text-sm text-slate-900 placeholder:text-slate-400 focus:border-slate-900 focus:ring-0"
                        >

                        <label for="kasir" class="mb-2 mt-5 block text-sm font-medium text-slate-700">Pilih kasir</label>
                        <select wire:model="selectedUser" id="kasir" class="w-full rounded-2xl border border-slate-200 bg-slate-50 p-3 text-sm text-slate-900 focus:border-slate-900 focus:ring-0">
                            <option value="">-- Pilih Kasir --</option>
                            @foreach($users as $user)
                                <option value="{{ $user->user_id }}">{{ $user->nama }}</option>
                            @endforeach
                        </select>

                        <div class="mt-5 rounded-2xl border border-slate-200 bg-slate-50 p-4 text-sm text-slate-600">
                            <div class="flex items-center justify-between">
                                <span>Jumlah item</span>
                                <span class="font-semibold text-slate-900">{{ $this->totalItems }}</span>
                            </div>
                            <div class="mt-2 flex items-center justify-between">
                                <span>Grand total</span>
                                <span class="text-lg font-semibold text-slate-900">Rp {{ number_format($this->grandTotal, 0, ',', '.') }}</span>
                            </div>
                        </div>

                        <button
                            wire:click="checkout"
                            wire:loading.attr="disabled"
                            @disabled(empty($cart) || ! $selectedUser)
                            class="mt-6 w-full rounded-2xl bg-slate-900 px-6 py-4 text-base font-semibold text-white transition hover:bg-slate-800 disabled:cursor-not-allowed disabled:bg-slate-300 disabled:text-slate-500"
                        >
                            <span wire:loading.remove>Checkout Sekarang</span>
                            <span wire:loading>Memproses...</span>
                        </button>

                        <p class="mt-3 text-center text-xs uppercase tracking-[0.24em] text-slate-500">
                            {{ empty($cart) ? 'Pilih produk untuk mulai checkout' : (blank($buyerName) ? 'Isi nama pembeli' : (! $selectedUser ? 'Pilih kasir sebelum checkout' : 'Siap diproses')) }}
                        </p>
                    </div>
                </section>
            </div>
        </aside>
    </div>

    @livewire('notifications')
</div>
