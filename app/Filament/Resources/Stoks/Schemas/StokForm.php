<?php

namespace App\Filament\Resources\Stoks\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Illuminate\Support\Facades\Auth;

class StokForm
{
    public static function schema(): array
    {
        return [
            Select::make('supplier_id')
                ->label('Supplier')
                ->relationship('supplier', 'supplier_nama')
                ->required()
                ->searchable()
                ->preload(),
            Select::make('barang_id')
                ->label('Barang')
                ->relationship('barang', 'barang_nama')
                ->required()
                ->searchable()
                ->preload(),
            DateTimePicker::make('stok_tanggal')
                ->label('Tanggal Stok')
                ->required()
                ->default(now()),
            TextInput::make('stok_jumlah')
                ->label('Jumlah Stok')
                ->numeric()
                ->required(),
            Select::make('user_id')
                ->label('User')
                ->relationship('user', 'nama')
                ->required()
                ->default(Auth::id())
                ->disabled()
                ->dehydrated(),
        ];
    }
}
