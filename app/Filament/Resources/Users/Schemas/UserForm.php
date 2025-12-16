<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('email')
                    ->label('Email address')
                    ->email()
                    ->required(),
                // DateTimePicker::make('email_verified_at'),
                TextInput::make('password')
                    ->password()
                    ->revealable()
                    ->required(),
                Select::make('roles')
                    // ->multiple()
                    ->preload()
                    ->relationship('roles', 'name')
                    ->required(),
                // TextInput::make('id_divisi')
                //     ->numeric()
                //     ->default(null), 
            ]);
    }
}
