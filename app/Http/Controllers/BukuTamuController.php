<?php

namespace App\Http\Controllers;

use App\Models\Tamu;
use App\Models\TamuPengiring;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

use App\Models\TandaTangan; // Load model baru
use Illuminate\Support\Facades\Storage; // Untuk simpan file gambar

class BukuTamuController extends Controller
{
    public function index()
    {
        return view('bukutamu.form');
    }

    public function store(Request $request)
    {
        // Validasi
        $request->validate([
            'tanggal' => 'required|date',
            'divisi' => 'required',
            'penerima_tamu' => 'required|string',
            'keperluan' => 'required|string',
            'nopol_kendaraan' => 'required|string',
            'nama' => 'required|string',
            'jabatan' => 'required|string',
            'no_hp' => 'required|string',
            'instansi' => 'required|string',
            'bidang_usaha' => 'required|string',
            'jumlah_tamu' => 'required|integer|min:1',
        ]);

        // --- MULAI LOGIKA GENERATE QR CODE ---

        // 1. Ambil waktu sekarang
        $now = Carbon::now();

        // 2. Buat format tanggal: 2 angka tahun, 2 bulan, 2 tanggal (cth: 251126)
        $dateCode = $now->format('ymd');

        // 3. Kode Statis
        $staticCode = 'M27';

        // 4. Hitung jumlah tamu yang dibuat HARI INI untuk menentukan urutan
        // Kita gunakan created_at agar reset setiap hari baru
        $countToday = Tamu::whereDate('created_at', $now->toDateString())->count();

        // 5. Tambahkan 1 untuk tamu saat ini dan format menjadi 4 digit (0001)
        $sequence = sprintf("%04d", $countToday + 1);

        // 6. Gabungkan menjadi format: 251126.M27.0001
        $generatedQrCode = "{$dateCode}.{$staticCode}.{$sequence}";


        // --- SELESAI LOGIKA GENERATE QR CODE ---

        $tamu = Tamu::create([
            'nama' => $request->nama,
            'jabatan' => $request->jabatan,
            'instansi' => $request->instansi,
            'no_hp' => $request->no_hp,
            'jumlah_tamu' => $request->jumlah_tamu,
            'penerima_tamu' => $request->penerima_tamu,
            'nopol_kendaraan' => $request->nopol_kendaraan,
            'bidang_usaha' => $request->bidang_usaha,
            // 'status_tamu' => $request->status_tamu,
            'id_divisi' => $request->divisi,
            'id_status' => $request->status,
            'keperluan' => $request->keperluan,
            'qr_code' => $generatedQrCode,
        ]);

        // ini untuk ttd 
        if ($request->filled('ttd_tamu_base64')) { // Cek input hidden dari view

            // Ambil data base64 (format: data:image/png;base64,.....)
            $image_parts = explode(";base64,", $request->ttd_tamu_base64);

            if (count($image_parts) == 2) {
                $image_base64 = base64_decode($image_parts[1]);

                // Buat nama file unik
                $fileName = 'ttd_tamu_' . $tamu->id . '_' . time() . '.png';
                $path = 'tanda_tangan/' . $fileName;

                // Simpan fisik file ke storage/app/public/tanda_tangan
                Storage::disk('public')->put($path, $image_base64);

                // Simpan ke Tabel 'tanda_tangans'
                TandaTangan::create([
                    'id_tamu' => $tamu->id,   // Ambil ID dari tamu yg baru dibuat
                    'ttd_tamu' => $path,      // Path file gambar
                    // Kolom ttd_satpam, dll dibiarkan null dulu
                ]);
            }
        }

        // if ($request->has('nama_pengiring')) {
        $list_nama = $request->input('nama_pengiring', []);
        $list_jabatan = $request->input('jabatan_pengiring', []);
        if (is_array($list_nama) && count($list_nama) > 0) {

            foreach ($list_nama as $key => $nama) {
                // Hanya simpan jika nama tidak kosong
                if (!empty($nama)) {

                    TamuPengiring::create([
                        // Sesuai field di Model Anda:
                        'id_tamu' => $tamu->id,  // ID dari tamu utama yg baru dibuat
                        'nama'    => $nama,
                        'jabatan' => $list_jabatan[$key] ?? '-', // Pakai strip jika jabatan kosong
                    ]);
                }
            }
        }
        // }

        // return redirect()->back()->with('success', 'Data tamu berhasil disimpan. Kode QR: ' . $generatedQrCode);
        return redirect()->back()
            ->with('success', 'Data berhasil disimpan. QR Code sedang didownload...')
            ->with('new_qr_code', $generatedQrCode)
            ->with('nama_tamu', $tamu->nama);
    }
}
