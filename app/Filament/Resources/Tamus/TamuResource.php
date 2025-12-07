<?php

namespace App\Filament\Resources\Tamus;

use App\Filament\Resources\Tamus\Pages\CreateTamu;
use App\Filament\Resources\Tamus\Pages\EditTamu;
use App\Filament\Resources\Tamus\Pages\ListTamus;
use App\Filament\Resources\Tamus\Schemas\TamuForm;
use App\Filament\Resources\Tamus\Tables\TamusTable;
use App\Models\Tamu;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use UnitEnum;
use Filament\Tables\Table;

class TamuResource extends Resource
{
    protected static ?string $model = Tamu::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-identification';

    protected static ?string $recordTitleAttribute = 'Tamu';

    protected static ?string $navigationLabel = 'Tamu';

    protected static ?string $modelLabel = 'Tamu';

    protected static ?string $pluralModelLabel = 'Data Tamu';

    protected static string|UnitEnum|null $navigationGroup = 'Master Data';

    public static function form(Schema $schema): Schema
    {
        return TamuForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TamusTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListTamus::route('/'),
            // 'create' => CreateTamu::route('/create'),
            // 'edit' => EditTamu::route('/{record}/edit'),
        ];
    }
}
