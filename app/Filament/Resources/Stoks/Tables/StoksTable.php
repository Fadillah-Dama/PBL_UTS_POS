<?php

namespace App\Filament\Resources\Stoks\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class StoksTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('stok_id')
                    ->label('ID'),
                TextColumn::make('barang.barang_nama')
                    ->label('Barang')
                    ->searchable(),
                TextColumn::make('supplier.supplier_nama')
                    ->label('Supplier')
                    ->searchable(),
                TextColumn::make('stok_jumlah')
                    ->label('Jumlah'),
                TextColumn::make('stok_tanggal')
                    ->label('Tanggal')
                    ->dateTime(),
                TextColumn::make('user.nama')
                    ->label('User'),
            ])
            ->filters([
                //
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
