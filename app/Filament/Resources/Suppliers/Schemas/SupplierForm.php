<?php

namespace App\Filament\Resources\Suppliers\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;

class SupplierForm
{
    public static function schema(): array
    {
        return [
            TextInput::make('supplier_kode')
                ->label('Kode Supplier')
                ->required()
                ->maxLength(10)
                ->unique(ignoreRecord: true),
            TextInput::make('supplier_nama')
                ->label('Nama Supplier')
                ->required()
                ->maxLength(100),
            Textarea::make('supplier_alamat')
                ->label('Alamat Supplier')
                ->required()
                ->maxLength(255),
        ];
    }
}
