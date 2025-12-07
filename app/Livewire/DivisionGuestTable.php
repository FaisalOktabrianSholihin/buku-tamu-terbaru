<?php

namespace App\Livewire;

use App\Models\Tamu;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Livewire\Component;
use Filament\Actions\Concerns\InteractsWithActions; // <--- TAMBAHKAN INI
use Filament\Actions\Contracts\HasActions;


class DivisionGuestTable extends Component implements HasTable, HasForms
{
    use InteractsWithTable;
    use InteractsWithForms;
    use InteractsWithActions;

    // Kita butuh ID Divisi yang dikirim dari Widget utama
    public $divisiId;

    public function table(Table $table): Table
    {
        return $table
            ->query(
                // Query yang sama seperti logika Anda sebelumnya
                Tamu::query()
                    ->where('id_divisi', $this->divisiId)
                    ->whereBetween('id_visit_status', [2, 4])
                    ->orderBy('created_at', 'asc')
            )
            ->columns([
                TextColumn::make('nama')
                    ->label('Nama')
                    ->searchable(),

                TextColumn::make('instansi')
                    ->label('Instansi')
                    ->searchable(),

                TextColumn::make('penerima_tamu')
                    ->label('Penerima'),
                // ->default('-'),

                TextColumn::make('created_at')
                    ->label('Masuk')
                    ->dateTime('d M Y H:i'),

                TextColumn::make('updated_at')
                    ->label('Keluar')
                    ->state(function (Tamu $record) {
                        return $record->id_visit_status == 5
                            ? $record->updated_at
                            : null; // tampilkan '-' jika bukan status 5
                    })
                    ->dateTime('d M Y H:i')
                    ->placeholder('-'),

                TextColumn::make('created_at_diff')
                    ->label('Durasi')
                    ->state(fn(Tamu $record) => $record->created_at->diffForHumans())
                    ->color('warning'),
            ])
            ->paginated(false); // Opsional: matikan pagination jika datanya sedikit
    }

    public function render()
    {
        return view('livewire.division-guest-table');
    }
}
