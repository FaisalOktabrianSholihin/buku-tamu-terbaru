<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Models\Tamu;
use Illuminate\Support\Facades\Storage; // Untuk simpan file gambar
use App\Models\TandaTangan; // Load model tanda tangan
use Filament\Notifications\Notification;
use BackedEnum;

class ValidasiPenerimaTamu extends Page
{
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-qr-code';
    protected static ?string $navigationLabel = 'Validasi Penerima Tamu';
    protected static ?string $title = 'Validasi Penerima Tamu';
    protected string $view = 'filament.pages.validasi-penerima-tamu';

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()?->can('View:ValidasiPenerimaTamu');
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
    public $ttd_penerima_base64;

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

        if ($tamu->id_visit_status != 3) {
            $this->resetForm();
            Notification::make()
                ->danger()
                ->title('Anda belum validasi operator.')
                ->send();
            return;
        }

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

    // --- LOGIC SIMPAN DIPERBAIKI ---
    public function simpanValidasi()
    {
        $tamu = Tamu::find($this->tamu_id);

        if ($tamu) {

            // 1. Cek Tanda Tangan
            if (empty($this->ttd_penerima_base64)) {
                Notification::make()->danger()->title('Tanda tangan penerima wajib diisi!')->send();
                return;
            }

            // 2. Update Data Utama
            $tamu->update([
                'nama' => $this->nama,
                'nopol_kendaraan' => $this->nopol_kendaraan,
                'jumlah_tamu' => $this->jumlah_tamu,
                'id_visit_status' =>  4,
            ]);

            /*
        |--------------------------------------------------------------------------
        | 3. Simpan TTD Penerima (FILE) ke STORAGE, BUKAN BASE64 KE DATABASE
        |--------------------------------------------------------------------------
        */

            // Hilangkan prefix base64
            $image = str_replace('data:image/png;base64,', '', $this->ttd_penerima_base64);
            $image = str_replace(' ', '+', $image);
            $imageData = base64_decode($image);

            // Nama file unik
            $filename = 'ttd_penerima_' . $tamu->id . '_' . time() . '.png';

            // Simpan ke storage/app/public/ttd
            Storage::disk('public')->put('ttd_penerima/' . $filename, $imageData);

            // Simpan PATH ke database
            TandaTangan::updateOrCreate(
                ['id_tamu' => $tamu->id],
                [
                    'ttd_penerima' => 'ttd_penerima/' . $filename,
                    'nama_satpam' => auth()->user()->name,
                    'updated_at' => now(),
                ]
            );

            Notification::make()->success()->title('Validasi Penerima Tamu berhasil')->send();
            $this->resetForm();
        }
    }

    // public function simpanTolakValidasi()
    // {
    //     $tamu = Tamu::find($this->tamu_id);

    //     if ($tamu) {

    //         // 1. Cek Tanda Tangan
    //         if (empty($this->ttd_penerima_base64)) {
    //             Notification::make()->danger()->title('Tanda tangan penerima tamu wajib diisi!')->send();
    //             return;
    //         }

    //         // 2. Update Data Utama
    //         $tamu->update([
    //             'nama' => $this->nama,
    //             'nopol_kendaraan' => $this->nopol_kendaraan,
    //             'jumlah_tamu' => $this->jumlah_tamu,
    //             // 'status_tamu' => 'Check In',
    //             'id_visit_status' =>  6,
    //         ]);

    //         /*
    //     |--------------------------------------------------------------------------
    //     | 3. Simpan TTD Penerima Tamu (FILE) ke STORAGE, BUKAN BASE64 KE DATABASE
    //     |--------------------------------------------------------------------------
    //     */

    //         // Hilangkan prefix base64
    //         $image = str_replace('data:image/png;base64,', '', $this->ttd_penerima_base64);
    //         $image = str_replace(' ', '+', $image);
    //         $imageData = base64_decode($image);

    //         // Nama file unik
    //         $filename = 'ttd_satpam_' . $tamu->id . '_' . time() . '.png';

    //         // Simpan ke storage/app/public/ttd
    //         Storage::disk('public')->put('ttd/' . $filename, $imageData);

    //         // Simpan PATH ke database
    //         TandaTangan::updateOrCreate(
    //             ['id_tamu' => $tamu->id],
    //             [
    //                 'ttd_satpam' => 'ttd/' . $filename,
    //                 'updated_at' => now(),
    //             ]
    //         );

    //         Notification::make()->success()->title('Validasi Tamu Di Tolak')->send();
    //         $this->resetForm();
    //     }
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
            'ttd_penerima_base64'
        ]);

        // Pastikan list di-reset ke array kosong
        $this->pengirings_list = [];

        $this->dispatch('form-reset');
    }
}
