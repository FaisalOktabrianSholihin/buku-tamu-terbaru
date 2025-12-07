<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Models\Tamu;
use Illuminate\Support\Facades\Storage; // Untuk simpan file gambar
use App\Models\TandaTangan; // Load model tanda tangan
use Filament\Notifications\Notification;
use BackedEnum;

class ValidasiOperator extends Page
{
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-qr-code';
    protected static ?string $navigationLabel = 'Validasi Operator';
    protected static ?string $title = 'Validasi Operator';
    protected string $view = 'filament.pages.validasi-operator';

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()?->can('View:ValidasiOperator');
    }

    // State Data Tamu
    public $tamu_id;
    public $nama;
    public $nopol_kendaraan;
    public $jumlah_tamu;
    public $keperluan;
    public $jabatan;
    public $no_hp;
    public $instansi;
    public $penerima_tamu;
    public $bidang_usaha;

    // Properti Tambahan (Sesuai View & DB)
    public $tanggal;
    public $divisi_id;
    public $status_tamu;
    public $agenda;
    public $keterangan;

    // Default Array Kosong (PENTING AGAR TIDAK ERROR COUNT NULL)
    public $pengirings_list = [];

    // Tanda Tangan
    public $ttd_operator_base64;

    public $is_found = false;
    public $qr_manual = '';

    // ... (Fungsi cekQr dan cariManual sama seperti sebelumnya) ...
    public function cekQr($code)
    {
        $this->qr_manual = $code;
        $this->prosesCari($code);
    }

    public function cariManual()
    {
        if (empty($this->qr_manual)) {
            Notification::make()->warning()->title('Harap isi kode QR')->send();
            return;
        }
        $this->prosesCari($this->qr_manual);
    }

    // --- LOGIC PENCARIAN DIPERBAIKI ---
    private function prosesCari($code)
    {
        // Ambil Tamu beserta relasi pengirings
        $tamu = Tamu::where('qr_code', $code)->with('pengiring')->first();

        // --- CEK STATUS VALIDASI SATPAM ---
        if ($tamu->id_visit_status != 2) {
            $this->resetForm();
            Notification::make()
                ->danger()
                ->title('Anda belum validasi satpam.')
                ->send();
            return;
        }

        if ($tamu) {

            // CEK VALIDASI SATPAM
            // if ($tamu->id_visit_status != 2) {
            //     $this->resetForm();
            //     $this->dispatch('open-modal', id: 'belumValidasiSatpam');
            //     return;
            // }

            if ($tamu) {
                $this->tamu_id = $tamu->id;
                $this->nama = $tamu->nama;
                $this->nopol_kendaraan = $tamu->nopol_kendaraan;
                $this->jumlah_tamu = $tamu->jumlah_tamu;
                $this->keperluan = $tamu->keperluan;
                $this->jabatan = $tamu->jabatan;
                $this->no_hp = $tamu->no_hp;
                $this->instansi = $tamu->instansi;
                $this->penerima_tamu = $tamu->penerima_tamu;
                $this->bidang_usaha = $tamu->bidang_usaha;

                // Kolom tanggal & status (Sesuaikan nama kolom di DB jika beda)
                $this->tanggal = $tamu->created_at->format('Y-m-d');
                $this->divisi_id = $tamu->id_divisi;
                $this->status_tamu = $tamu->status_tamu;

                // Asumsi agenda/ket ada di tabel tamus (jika tidak ada, hapus baris ini)
                $this->agenda = $tamu->keperluan;
                $this->keterangan = '-';

                // --- AMBIL DATA DARI TABEL TAMU_PENGIRINGS ---
                // Kita ubah Collection jadi Array agar bisa di-loop di View
                if ($tamu->pengirings) {
                    $this->pengirings_list = $tamu->pengirings->toArray();
                } else {
                    $this->pengirings_list = [];
                }
                // ----------------------------------------------

                $this->is_found = true;
                Notification::make()->success()->title('Data Tamu Ditemukan!')->send();
            } else {
                $this->resetForm(); // Reset semua jika tidak ketemu
                Notification::make()->danger()->title('QR Code Tidak Terdaftar!')->send();
            }
        }
    }

    // --- LOGIC SIMPAN DIPERBAIKI ---
    public function simpanValidasi()
    {
        $tamu = Tamu::find($this->tamu_id);

        if ($tamu) {

            // 1. Cek Tanda Tangan
            if (empty($this->ttd_operator_base64)) {
                Notification::make()->danger()->title('Tanda tangan operator wajib diisi!')->send();
                return;
            }

            // 2. Update Data Utama
            $tamu->update([
                'nama' => $this->nama,
                'nopol_kendaraan' => $this->nopol_kendaraan,
                'jumlah_tamu' => $this->jumlah_tamu,
                'id_visit_status' =>  3,
            ]);

            /*
        |--------------------------------------------------------------------------
        | 3. Simpan TTD Operator (FILE) ke STORAGE, BUKAN BASE64 KE DATABASE
        |--------------------------------------------------------------------------
        */

            // Hilangkan prefix base64
            $image = str_replace('data:image/png;base64,', '', $this->ttd_operator_base64);
            $image = str_replace(' ', '+', $image);
            $imageData = base64_decode($image);

            // Nama file unik
            $filename = 'ttd_operator_' . $tamu->id . '_' . time() . '.png';

            // Simpan ke storage/app/public/ttd
            Storage::disk('public')->put('ttd_operator/' . $filename, $imageData);

            // Simpan PATH ke database
            TandaTangan::updateOrCreate(
                ['id_tamu' => $tamu->id],
                [
                    'ttd_operator' => 'ttd_operator/' . $filename,
                    'nama_operator' => auth()->user()->name,
                    'updated_at' => now(),
                ]
            );

            Notification::make()->success()->title('Validasi Operator Berhasil')->send();
            $this->resetForm();
        }
    }

    // protected function getActions(): array
    // {
    //     return [
    //         \Filament\Actions\Action::make('belumValidasiSatpam')
    //             ->label('Peringatan')
    //             ->modalHeading('Validasi Satpam Belum Dilakukan')
    //             ->modalDescription('Tamu ini belum divalidasi oleh satpam. Silakan lakukan validasi satpam terlebih dahulu.')
    //             ->modalIcon('heroicon-o-shield-exclamation')
    //             ->modalIconColor('danger')
    //             ->modalSubmitAction(false) // Tidak ada tombol submit
    //             ->modalCancelActionLabel('Tutup'),
    //     ];
    // }

    public function resetForm()
    {
        $this->reset([
            'tamu_id',
            'nama',
            'nopol_kendaraan',
            'jumlah_tamu',
            'keperluan',
            'jabatan',
            'instansi',
            'penerima_tamu',
            'bidang_usaha',
            'no_hp',
            'is_found',
            'qr_manual',
            'tanggal',
            'divisi_id',
            'status_tamu',
            'agenda',
            'keterangan',
            'pengirings_list',
            'ttd_operator_base64'
        ]);

        // Pastikan list di-reset ke array kosong
        $this->pengirings_list = [];

        $this->dispatch('form-reset');
    }
}
