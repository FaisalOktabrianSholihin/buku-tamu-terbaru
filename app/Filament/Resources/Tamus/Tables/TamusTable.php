<?php

namespace App\Filament\Resources\Tamus\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ImportAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;

use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Tamu;
use Filament\Actions\Action as ActionsAction;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\TamuExport;
use Filament\Actions\Action;



class TamusTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama')
                    ->searchable(),
                TextColumn::make('instansi')
                    ->searchable(),
                TextColumn::make('no_hp')
                    ->searchable(),
                TextColumn::make('divisi.nama_divisi')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('visitStatus.status')
                    ->numeric()
                    ->badge()
                    ->sortable(),
                TextColumn::make('keperluan')
                    ->searchable(),
                TextColumn::make('qr_code')
                    ->searchable(),
                TextColumn::make('pdf_view_count')
                    ->label('PDF Dilihat')
                    ->icon('heroicon-m-eye')
                    ->numeric()
                    ->sortable()
                    ->alignCenter(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
            ->recordActions([
                EditAction::make(),
                ActionsAction::make('exportPdf')
                    ->label('Export PDF')
                    ->icon('heroicon-o-document-arrow-down') // Ikon download
                    ->color('primary') // Warna tombol
                    ->action(function (Tamu $record) {
                        // 1. Increment counter di database
                        $record->increment('pdf_view_count');

                        // 2. Load relasi yang dibutuhkan agar tidak query berulang di view
                        $record->load(['divisi', 'status', 'pengiring', 'tandaTangan']);

                        // 3. Render View Blade menjadi HTML
                        $pdf = Pdf::loadView('pdf.visitor-form', ['tamu' => $record]);

                        // 4. Set ukuran kertas (opsional, default A4 portrait)
                        $pdf->setPaper('A4', 'portrait');

                        // 5. Download file PDF
                        // Gunakan streamDownload agar file langsung terdownload di browser
                        return response()->streamDownload(function () use ($pdf) {
                            echo $pdf->output();
                        }, 'Form-Kunjungan-' . str_replace(' ', '-', $record->nama) . '-' . date('YmdHis') . '.pdf');
                    }),
            ])
            // ->toolbarActions([
            //     BulkActionGroup::make([
            //         DeleteBulkAction::make(),
            //     ]),
            // ]);
            ->toolbarActions([
                Action::make('exportExcel')
                    ->label('Export Excel')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('success')
                    ->form([
                        DatePicker::make('start_date')->label('Tanggal Awal')->required(),
                        DatePicker::make('end_date')->label('Tanggal Akhir')->required(),
                    ])
                    ->action(function (array $data) {
                        return Excel::download(
                            new TamuExport($data['start_date'], $data['end_date']),
                            'data-tamu-' . $data['start_date'] . '_sd_' . $data['end_date'] . '.xlsx'
                        );
                    }),

                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
