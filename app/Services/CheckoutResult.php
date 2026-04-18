<?php

namespace App\Services;

readonly class CheckoutResult
{
    public function __construct(
        public string $saleCode,
        public string $cashierName,
        public string $buyerName,
        public int $total,
        public int $penjualanId,
    ) {}
}
