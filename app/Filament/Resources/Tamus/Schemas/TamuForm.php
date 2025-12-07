<?php

namespace App\Filament\Resources\Tamus\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class TamuForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nama')
                    ->required(),
                TextInput::make('instansi')
                    ->required(),
                TextInput::make('no_hp')
                    ->required(),
                TextInput::make('id_divisi')
                    ->numeric()
                    ->default(null),
                TextInput::make('id_status')
                    ->numeric()
                    ->default(null),
                TextInput::make('keperluan')
                    ->required(),
                TextInput::make('qr_code')
                    ->default(null),
            ]);
    }
}
