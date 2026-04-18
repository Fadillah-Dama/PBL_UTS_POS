<?php

namespace App\Filament\Resources\Barangs\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class BarangsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('barang_id')
                    ->label('ID'),
                TextColumn::make('barang_kode')
                    ->label('Kode')
                    ->searchable(),
                TextColumn::make('barang_nama')
                    ->label('Nama')
                    ->searchable(),
                TextColumn::make('kategori.kategori_nama')
                    ->label('Kategori')
                    ->searchable(),
                TextColumn::make('harga_beli')
                    ->label('Harga Beli')
                    ->money('IDR', locale: 'id_ID'),
                TextColumn::make('harga_jual')
                    ->label('Harga Jual')
                    ->money('IDR', locale: 'id_ID'),
            ])
            ->filters([
                SelectFilter::make('kategori_id')
                    ->label('Kategori')
                    ->relationship('kategori', 'kategori_nama')
                    ->searchable()
                    ->preload(),
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
