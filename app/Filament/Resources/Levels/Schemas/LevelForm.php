<?php

namespace App\Filament\Resources\Levels\Schemas;

use Filament\Forms\Components\TextInput;

class LevelForm
{
    public static function schema(): array
    {
        return [
            TextInput::make('level_kode')
                ->label('Kode Level')
                ->required()
                ->maxLength(10)
                ->unique(ignoreRecord: true),
            TextInput::make('level_nama')
                ->label('Nama Level')
                ->required()
                ->maxLength(100),
        ];
    }
}
