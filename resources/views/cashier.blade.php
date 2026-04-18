<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Minimalist POS</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-gray-100 text-gray-900 antialiased">
    <div class="min-h-screen">
        <div class="mx-auto flex min-h-screen max-w-7xl flex-col gap-6 p-4 sm:p-6 lg:flex-row lg:gap-8 lg:p-8">
            <main class="w-full lg:w-[70%]">
                <div class="mb-6 flex items-end justify-between">
                    <div>
                        <p class="text-sm font-medium uppercase tracking-[0.3em] text-gray-400">Point of Sale</p>
                        <h1 class="mt-2 text-3xl font-semibold tracking-tight text-gray-900">Cafe Menu</h1>
                    </div>
                    <div class="rounded-full border border-gray-200 bg-white px-4 py-2 text-sm text-gray-500 shadow-sm">
                        12 Items
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-3">
                    <article class="overflow-hidden rounded-3xl border border-gray-200 bg-white shadow-sm">
                        <div class="flex h-40 items-center justify-center bg-gray-200 text-sm font-medium uppercase tracking-[0.25em] text-gray-500">
                            Image
                        </div>
                        <div class="space-y-2 p-5">
                            <h2 class="text-lg font-semibold text-gray-900">Cappuccino</h2>
                            <p class="text-sm text-gray-500">Espresso with steamed milk foam.</p>
                            <p class="text-base font-semibold text-gray-900">$4.50</p>
                        </div>
                    </article>

                    <article class="overflow-hidden rounded-3xl border border-gray-200 bg-white shadow-sm">
                        <div class="flex h-40 items-center justify-center bg-gray-200 text-sm font-medium uppercase tracking-[0.25em] text-gray-500">
                            Image
                        </div>
                        <div class="space-y-2 p-5">
                            <h2 class="text-lg font-semibold text-gray-900">Latte</h2>
                            <p class="text-sm text-gray-500">Smooth espresso with creamy milk.</p>
                            <p class="text-base font-semibold text-gray-900">$4.00</p>
                        </div>
                    </article>

                    <article class="overflow-hidden rounded-3xl border border-gray-200 bg-white shadow-sm">
                        <div class="flex h-40 items-center justify-center bg-gray-200 text-sm font-medium uppercase tracking-[0.25em] text-gray-500">
                            Image
                        </div>
                        <div class="space-y-2 p-5">
                            <h2 class="text-lg font-semibold text-gray-900">Cold Brew</h2>
                            <p class="text-sm text-gray-500">Slow-steeped coffee served chilled.</p>
                            <p class="text-base font-semibold text-gray-900">$4.75</p>
                        </div>
                    </article>

                    <article class="overflow-hidden rounded-3xl border border-gray-200 bg-white shadow-sm">
                        <div class="flex h-40 items-center justify-center bg-gray-200 text-sm font-medium uppercase tracking-[0.25em] text-gray-500">
                            Image
                        </div>
                        <div class="space-y-2 p-5">
                            <h2 class="text-lg font-semibold text-gray-900">Mocha</h2>
                            <p class="text-sm text-gray-500">Chocolate espresso topped with milk.</p>
                            <p class="text-base font-semibold text-gray-900">$5.25</p>
                        </div>
                    </article>

                    <article class="overflow-hidden rounded-3xl border border-gray-200 bg-white shadow-sm">
                        <div class="flex h-40 items-center justify-center bg-gray-200 text-sm font-medium uppercase tracking-[0.25em] text-gray-500">
                            Image
                        </div>
                        <div class="space-y-2 p-5">
                            <h2 class="text-lg font-semibold text-gray-900">Butter Croissant</h2>
                            <p class="text-sm text-gray-500">Flaky pastry baked fresh daily.</p>
                            <p class="text-base font-semibold text-gray-900">$3.25</p>
                        </div>
                    </article>

                    <article class="overflow-hidden rounded-3xl border border-gray-200 bg-white shadow-sm">
                        <div class="flex h-40 items-center justify-center bg-gray-200 text-sm font-medium uppercase tracking-[0.25em] text-gray-500">
                            Image
                        </div>
                        <div class="space-y-2 p-5">
                            <h2 class="text-lg font-semibold text-gray-900">Blueberry Muffin</h2>
                            <p class="text-sm text-gray-500">Soft muffin with berry filling.</p>
                            <p class="text-base font-semibold text-gray-900">$3.75</p>
                        </div>
                    </article>

                    <article class="overflow-hidden rounded-3xl border border-gray-200 bg-white shadow-sm">
                        <div class="flex h-40 items-center justify-center bg-gray-200 text-sm font-medium uppercase tracking-[0.25em] text-gray-500">
                            Image
                        </div>
                        <div class="space-y-2 p-5">
                            <h2 class="text-lg font-semibold text-gray-900">Matcha Latte</h2>
                            <p class="text-sm text-gray-500">Earthy matcha blended with milk.</p>
                            <p class="text-base font-semibold text-gray-900">$5.00</p>
                        </div>
                    </article>

                    <article class="overflow-hidden rounded-3xl border border-gray-200 bg-white shadow-sm">
                        <div class="flex h-40 items-center justify-center bg-gray-200 text-sm font-medium uppercase tracking-[0.25em] text-gray-500">
                            Image
                        </div>
                        <div class="space-y-2 p-5">
                            <h2 class="text-lg font-semibold text-gray-900">Iced Tea</h2>
                            <p class="text-sm text-gray-500">Brewed tea with lemon slices.</p>
                            <p class="text-base font-semibold text-gray-900">$3.50</p>
                        </div>
                    </article>

                    <article class="overflow-hidden rounded-3xl border border-gray-200 bg-white shadow-sm">
                        <div class="flex h-40 items-center justify-center bg-gray-200 text-sm font-medium uppercase tracking-[0.25em] text-gray-500">
                            Image
                        </div>
                        <div class="space-y-2 p-5">
                            <h2 class="text-lg font-semibold text-gray-900">Bagel Sandwich</h2>
                            <p class="text-sm text-gray-500">Toasted bagel with egg and cheese.</p>
                            <p class="text-base font-semibold text-gray-900">$6.50</p>
                        </div>
                    </article>
                </div>
            </main>

            <aside class="w-full lg:w-[30%]">
                <div class="lg:sticky lg:top-8">
                    <section class="rounded-[2rem] border border-gray-200 bg-white p-6 shadow-sm">
                        <div class="border-b border-gray-200 pb-5">
                            <p class="text-sm font-medium uppercase tracking-[0.3em] text-gray-400">Order</p>
                            <h2 class="mt-2 text-2xl font-semibold tracking-tight text-gray-900">Active Cart</h2>
                        </div>

                        <div class="space-y-4 py-6">
                            <div class="flex items-start justify-between gap-4 rounded-2xl bg-gray-50 p-4">
                                <div>
                                    <h3 class="font-medium text-gray-900">Cappuccino</h3>
                                    <p class="mt-1 text-sm text-gray-500">1 x Regular</p>
                                </div>
                                <p class="font-semibold text-gray-900">$4.50</p>
                            </div>

                            <div class="flex items-start justify-between gap-4 rounded-2xl bg-gray-50 p-4">
                                <div>
                                    <h3 class="font-medium text-gray-900">Butter Croissant</h3>
                                    <p class="mt-1 text-sm text-gray-500">2 x Fresh baked</p>
                                </div>
                                <p class="font-semibold text-gray-900">$6.50</p>
                            </div>

                            <div class="flex items-start justify-between gap-4 rounded-2xl bg-gray-50 p-4">
                                <div>
                                    <h3 class="font-medium text-gray-900">Iced Tea</h3>
                                    <p class="mt-1 text-sm text-gray-500">1 x Lemon</p>
                                </div>
                                <p class="font-semibold text-gray-900">$3.50</p>
                            </div>
                        </div>

                        <div class="space-y-3 border-t border-gray-200 pt-6">
                            <div class="flex items-center justify-between text-sm text-gray-500">
                                <span>Subtotal</span>
                                <span>$14.50</span>
                            </div>
                            <div class="flex items-center justify-between text-sm text-gray-500">
                                <span>Tax</span>
                                <span>$1.45</span>
                            </div>
                            <div class="flex items-center justify-between border-t border-dashed border-gray-200 pt-4 text-lg font-semibold text-gray-900">
                                <span>Total</span>
                                <span>$15.95</span>
                            </div>
                        </div>

                        <button class="mt-6 w-full rounded-2xl bg-gray-900 px-6 py-4 text-base font-semibold text-white transition hover:bg-gray-800">
                            Pay with Cash
                        </button>
                    </section>
                </div>
            </aside>
        </div>
    </div>
</body>
</html>
