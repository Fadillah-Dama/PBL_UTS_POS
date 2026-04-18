<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Illuminate\Support\Facades\Hash;

class UserForm
{
    public static function schema(): array
    {
        return [
            TextInput::make('username')
                ->label('Username')
                ->required()
                ->maxLength(20)
                ->unique(ignoreRecord: true),
            TextInput::make('nama')
                ->label('Nama')
                ->required()
                ->maxLength(100),
            TextInput::make('email')
                ->label('Email')
                ->email()
                ->required()
                ->maxLength(100)
                ->unique(ignoreRecord: true),
            TextInput::make('password')
                ->label('Password')
                ->password()
                ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                ->dehydrated(fn ($state) => filled($state))
                ->required(fn ($context) => $context === 'create'),
            Select::make('level_id')
                ->label('Level')
                ->relationship('level', 'level_nama')
                ->required()
                ->searchable()
                ->preload(),
        ];
    }
}
