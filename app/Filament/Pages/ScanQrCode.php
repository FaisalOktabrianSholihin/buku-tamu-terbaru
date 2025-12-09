<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Models\Tamu;
use App\Models\TandaTangan;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Filament\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Storage;
use BackedEnum;
use Filament\Tables\Columns\ImageColumn;

class ScanQrCode extends Page implements HasForms, HasTable
{
    use InteractsWithTable;
    use InteractsWithForms;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-qr-code';
    protected static ?string $navigationLabel = 'Validasi Satpam (Masuk)';
    protected static ?string $title = 'Validasi Pos Satpam';
    protected string $view = 'filament.pages.scan-qr-code';

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()?->can('View:ScanQrCode');
    }

    // State Scanner & Filter Tabel
    public $qr_code_search = '';

    // State Data Tamu (Untuk Edit)
    public ?Tamu $selectedTamu = null; // Model asli

    // Properti Form (Agar bisa diedit)
    public $nama;
    public $instansi;
    public $keperluan;
    public $penerima_tamu;
    public $jumlah_tamu;
    public $nopol_kendaraan;

    // Tanda Tangan
    public $ttd_satpam_base64;

    public function mount()
    {
        $this->resetValidasi();
    }

    // --- REVISI 5: AUTO-SEARCH FUNCTION ---
    public function updatedQrCodeSearch($value)
    {
        // Hanya mencari jika input tidak kosong
        if (!empty($value)) {
            $this->cekQr($value);
        } else {
            // Jika dikosongkan, reset tabel
            $this->resetTable();
        }
    }

    // --- LOGIKA CEK QR ---
    public function cekQr($code)
    {
        // Cari data berdasarkan QR
        $tamu = Tamu::where('qr_code', $code)->first();

        if (!$tamu) {
            Notification::make()->danger()->title('Data QR Tidak Ditemukan')->send();
            $this->resetTable(); // Kosongkan tabel
            return;
        }

        // Cek Status (Revisi 1 Sebelumnya)
        if ($tamu->id_visit_status == 2) {
            Notification::make()->warning()
                ->title('Tamu Sudah Divalidasi Sebelumnya!')
                ->body('Tamu ini sudah melakukan Check-In.')
                ->persistent()
                ->send();
            $this->resetTable();
            return;
        }

        if ($tamu->id_visit_status == 6) {
            Notification::make()->danger()
                ->title('Tamu Sudah Ditolak!')
                ->body('Validasi untuk tamu ini sebelumnya telah ditolak.')
                ->persistent()
                ->send();
            $this->resetTable();
            return;
        }

        // Jika Status == 1 (Pending/Baru), Lanjut tampilkan di tabel
        $this->qr_code_search = $code;
        $this->resetTable();
        Notification::make()->success()->title('Data Ditemukan. Silakan klik Validasi di tabel.')->send();
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(function () {
                $query = Tamu::query();
                if ($this->qr_code_search) {
                    // Hanya tampilkan tamu yang statusnya 1 (Pending)
                    $query->where('qr_code', $this->qr_code_search)->where('id_visit_status', 1);
                } else {
                    $query->whereRaw('1 = 0'); // Sembunyikan jika kosong
                }
                return $query;
            })
            ->columns([
                TextColumn::make('created_at')->label('Tanggal')->date('d M Y H:i'),
                TextColumn::make('nama'),
                TextColumn::make('instansi'),
                // ImageColumn::make('tandaTangan.ttd_satpam') // Mengakses relasi tandaTangan -> kolom ttd_satpam
                //     ->label('TTD')
                //     ->disk('public') // Wajib public
                //     ->size(60)
                //     ->circular(),

            ])
            ->actions([
                Action::make('proses_validasi')
                    ->label('Validasi')
                    ->icon('heroicon-o-pencil-square')
                    ->button()
                    ->color('primary')
                    ->action(fn(Tamu $record) => $this->pilihTamu($record)),
            ]);
    }

    public function pilihTamu(Tamu $tamu)
    {
        // Cek lagi statusnya (double check)
        if ($tamu->id_visit_status == 2 || $tamu->id_visit_status == 6) {
            Notification::make()->warning()->title('Status tamu tidak valid untuk diproses.')->send();
            return;
        }

        $this->selectedTamu = $tamu;

        // Pindahkan data DB ke properti form agar bisa diedit
        $this->nama = $tamu->nama;
        $this->instansi = $tamu->instansi;
        $this->keperluan = $tamu->keperluan;
        $this->penerima_tamu = $tamu->penerima_tamu;
        $this->jumlah_tamu = $tamu->jumlah_tamu;
        $this->nopol_kendaraan = $tamu->nopol_kendaraan;
    }

    public function simpanValidasi()
    {
        if (!$this->selectedTamu) return;

        if (empty($this->ttd_satpam_base64)) {
            Notification::make()->danger()->title('Tanda tangan satpam wajib diisi!')->send();
            return;
        }

        // Update Semua Data yang mungkin diedit
        $this->selectedTamu->update([
            'nama' => $this->nama,
            'instansi' => $this->instansi,
            'keperluan' => $this->keperluan,
            'penerima_tamu' => $this->penerima_tamu,
            'jumlah_tamu' => $this->jumlah_tamu,
            'nopol_kendaraan' => $this->nopol_kendaraan,
            'id_visit_status' =>  2, // Set jadi Checkin
        ]);

        $this->simpanGambarTTD($this->selectedTamu->id);

        Notification::make()->success()->title('Tamu Berhasil Check-In & Validasi')->send();
        $this->resetValidasi();
    }

    public function simpanTolakValidasi()
    {
        if (!$this->selectedTamu) return;

        if (empty($this->ttd_satpam_base64)) {
            Notification::make()->danger()->title('Tanda tangan satpam wajib diisi!')->send();
            return;
        }

        // Update status tolak, tapi data edit tetap disimpan
        $this->selectedTamu->update([
            'nama' => $this->nama,
            'instansi' => $this->instansi,
            'nopol_kendaraan' => $this->nopol_kendaraan,
            'id_visit_status' =>  6, // Set jadi Ditolak
        ]);

        $this->simpanGambarTTD($this->selectedTamu->id);

        Notification::make()->success()->title('Validasi Tamu Ditolak')->send();
        $this->resetValidasi();
    }

    private function simpanGambarTTD($tamuId)
    {
        $image = str_replace('data:image/png;base64,', '', $this->ttd_satpam_base64);
        $image = str_replace(' ', '+', $image);
        $imageData = base64_decode($image);
        $filename = 'ttd_satpam_' . $tamuId . '_' . time() . '.png';

        Storage::disk('public')->put('ttd/' . $filename, $imageData);

        TandaTangan::updateOrCreate(
            ['id_tamu' => $tamuId],
            [
                'ttd_satpam' => 'ttd/' . $filename,
                'nama_satpam' => auth()->user()->name,
                'updated_at' => now(),
            ]
        );
    }

    // REVISI 4: FULL RESET FUNCTION
    public function resetValidasi()
    {
        $this->selectedTamu = null;
        // Reset semua properti form dan pencarian
        $this->reset(['nama', 'instansi', 'keperluan', 'penerima_tamu', 'jumlah_tamu', 'nopol_kendaraan', 'ttd_satpam_base64', 'qr_code_search']);
        $this->resetTable();
        // Dispatch event untuk mereset scanner jika sedang aktif
        $this->dispatch('form-reset');
    }

    public function batalValidasi()
    {
        $this->selectedTamu = null;
    }
}
