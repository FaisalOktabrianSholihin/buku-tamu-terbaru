<?php

namespace App\Filament\Widgets;

use App\Models\Divisi;
use App\Models\Tamu;
use Filament\Actions\Action as ActionsAction;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\Facades\Blade;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\DatePicker;
use Illuminate\Support\HtmlString;
use Illuminate\Database\Eloquent\Builder;

class DivisionStatsTable extends BaseWidget
{
    protected static ?string $heading = 'Monitoring Kunjungan Divisi';
    protected static ?int $sort = 3;
    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table                                                                                                                          
    {
        return $table
            ->query(
                Divisi::query()->withCount([
                    'tamus as in_count' => fn($q) => $q->whereBetween('id_visit_status', [2, 4]),
                    'tamus as out_count' => fn($q) => $q->where('id_visit_status', 5),
                    'tamus as total_count',
                ])
            )

            ->defaultSort('in_count', 'desc')

            ->columns([
                Tables\Columns\TextColumn::make('nama_divisi')
                    ->label('Divisi')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('in_count')
                    ->label('IN')
                    ->badge()
                    ->color('success')
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('out_count')
                    ->label('OUT')
                    ->badge()
                    ->color('danger')
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('total_count')
                    ->label('Total')
                    ->alignCenter(),
            ])
            ->filters(
                [
                    Filter::make('periode')
                        ->form([
                            DatePicker::make('created_from')->label('Tanggal Awal'),
                            DatePicker::make('created_until')->label('Tanggal Akhir')->default(now()),
                        ])
                        ->query(fn(Builder $query) => $query)
                ]
            ) //
            ->actions([
                ActionsAction::make('detail')
                    ->label('Detail')
                    ->icon('heroicon-o-eye')
                    ->modalHeading(fn($record) => "Daftar Tamu — {$record->nama_divisi}")
                    ->modalWidth('5xl')
                    ->modalSubmitAction(false)
                    ->modalCancelAction(false) // Biasanya tombol cancel juga disembunyikan jika hanya view
                    ->modalContent(function ($record) {
                        return new HtmlString(
                            Blade::render(
                                // 1. Di dalam string, gunakan variabel $record_id
                                '@livewire("division-guest-table", ["divisiId" => $record_id])',

                                // 2. Definisikan nilai $record_id di array data
                                ['record_id' => $record->id]
                            )
                        );
                    }),
            ]);
    }
}
