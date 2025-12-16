<?php

namespace App\Http\Controllers;

use App\Models\Tamu;
use App\Models\TamuPengiring;
use App\Models\TandaTangan;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

class BukuTamuController extends Controller
{
    public function index()
    {
        return view('bukutamu.form');
    }

    public function store(Request $request)
    {
        // 1. Definisikan aturan dasar (Wajib untuk Suplier, Customer, DAN Umum)
        $rules = [
            'tanggal'         => 'required|date',
            'status'          => 'required', // Ini sekarang dropdown paling atas
            'divisi'          => 'required',
            'nama'            => 'required|string',
            'instansi'        => 'required|string',
            'nopol_kendaraan' => 'required|string',
            'keperluan'       => 'required|string',
        ];

        // 2. Logic Kondisional berdasarkan ID Status dari Database
        // ID 3 = Umum (Sesuai gambar database Anda)
        if ($request->status == '3') {
            $rules['penerima_tamu'] = 'required|string';
            $rules['jabatan']       = 'required|string';
            $rules['no_hp']         = 'required|string';
            $rules['bidang_usaha']  = 'required|string';
            $rules['jumlah_tamu']   = 'required|integer|min:1';
        }

        // Jalankan Validasi
        $request->validate($rules);

        // --- Generate QR Code (Tetap Sama) ---
        $now = Carbon::now();
        $dateCode = $now->format('ymd');
        $staticCode = 'M27';
        $countToday = Tamu::whereDate('created_at', $now->toDateString())->count();
        $sequence = sprintf("%04d", $countToday + 1);
        $generatedQrCode = "{$dateCode}.{$staticCode}.{$sequence}";

        // --- Persiapan Data Simpan ---

        // Cek apakah tamu umum atau bukan
        $isUmum = ($request->status == '3');

        $tamu = Tamu::create([
            'qr_code'         => $generatedQrCode,
            'nama'            => $request->nama,
            'instansi'        => $request->instansi,
            'nopol_kendaraan' => $request->nopol_kendaraan,
            'keperluan'       => $request->keperluan,
            'id_status'       => $request->status, // Simpan ID status (1, 2,, 4 atau 3)
            'id_divisi'       => $request->divisi,

            // Jika BUKAN Umum (Suplier/Customer), isi default/null
            'jabatan'         => $isUmum ? $request->jabatan : 'Driver/Kurir',
            'no_hp'           => $isUmum ? $request->no_hp : '-',
            'jumlah_tamu'     => $isUmum ? $request->jumlah_tamu : 1,
            'penerima_tamu'   => $isUmum ? $request->penerima_tamu : '-',
            'bidang_usaha'    => $isUmum ? $request->bidang_usaha : '-',

            // id_divisi di-set NULL jika Suplier/Customer
            // 'id_divisi'       => $isUmum ? $request->divisi : null,
        ]);

        // --- Simpan Tanda Tangan (Tetap Sama) ---
        if ($request->filled('ttd_tamu_base64')) {
            // ... kode simpan gambar ttd sama persis ...
            $image_parts = explode(";base64,", $request->ttd_tamu_base64);
            if (count($image_parts) == 2) {
                $image_base64 = base64_decode($image_parts[1]);
                $fileName = 'ttd_tamu_' . $tamu->id . '_' . time() . '.png';
                $path = 'tanda_tangan/' . $fileName;
                Storage::disk('public')->put($path, $image_base64);

                TandaTangan::create([
                    'id_tamu' => $tamu->id,
                    'ttd_tamu' => $path,
                ]);
            }
        }

        // --- Simpan Pengiring (Hanya Jika Umum) ---
        if ($isUmum) {
            $list_nama = $request->input('nama_pengiring', []);
            $list_jabatan = $request->input('jabatan_pengiring', []);

            if (is_array($list_nama) && count($list_nama) > 0) {
                foreach ($list_nama as $key => $nama) {
                    if (!empty($nama)) {
                        TamuPengiring::create([
                            'id_tamu' => $tamu->id,
                            'nama'    => $nama,
                            'jabatan' => $list_jabatan[$key] ?? '-',
                        ]);
                    }
                }
            }
        }

        // Return tetap sama
        return redirect()->back()
            ->with('success', 'Data berhasil disimpan...')
            ->with('new_qr_code', $generatedQrCode)
            ->with('nama_tamu', $tamu->nama);
    }
}
