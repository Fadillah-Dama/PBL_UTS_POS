<?php

namespace App\Filament\Resources\Penjualans\Schemas;

use App\Models\Barang;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Illuminate\Support\Facades\Auth;

class PenjualanForm
{
    public static function schema(): array
    {
        return [
            TextInput::make('penjualan_kode')
                ->label('Kode Penjualan')
                ->required()
                ->maxLength(20)
                ->unique(ignoreRecord: true),
            TextInput::make('pembeli')
                ->label('Nama Pembeli')
                ->required()
                ->maxLength(100),
            DateTimePicker::make('penjualan_tanggal')
                ->label('Tanggal Penjualan')
                ->required()
                ->default(now()),
            Select::make('user_id')
                ->label('Kasir')
                ->relationship('user', 'nama')
                ->required()
                ->default(Auth::id())
                ->disabled()
                ->dehydrated(),
            Repeater::make('penjualanDetail')
                ->label('Detail Barang')
                ->relationship()
                ->schema([
                    Select::make('barang_id')
                        ->label('Barang')
                        ->options(Barang::all()->pluck('barang_nama', 'barang_id'))
                        ->required()
                        ->reactive()
                        ->afterStateUpdated(fn ($state, callable $set) => $set('harga', Barang::find($state)?->harga_jual ?? 0)),
                    TextInput::make('harga')
                        ->label('Harga')
                        ->numeric()
                        ->required()
                        ->prefix('Rp')
                        ->readOnly(),
                    TextInput::make('jumlah')
                        ->label('Jumlah')
                        ->numeric()
                        ->required()
                        ->default(1),
                ])
                ->columns(3)
                ->columnSpanFull()
                ->grid(1),
        ];
    }
}
