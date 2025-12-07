<!DOCTYPE html>
<html>

<head>
    <title>Visitor Form</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid black;
            padding: 5px;
            vertical-align: top;
        }

        .no-border {
            border: none;
        }

        .header-table td {
            border: none;
            padding: 2px;
        }

        .title {
            font-weight: bold;
            text-align: center;
            font-size: 14px;
            background-color: #f0f0f0;
        }

        .label-col {
            width: 40%;
            background-color: #f0f0f0;
            font-weight: bold;
            font-size: 9px;
        }

        .ukuran {
            width: 25%;
            /* background-color: #f0f0f0; */
            /* font-weight: bold; */
            font-size: 10px;
        }

        .ukurana {
            width: 45%;
            /* font-weight: bold; */
            font-size: 10px;
            border: none;
        }

        /* GAYA CHECKBOX */
        .checkbox {
            display: inline-block;
            width: 14px;
            height: 14px;
            border: 1px solid black;
            margin-right: 5px;
            text-align: center;
            line-height: 14px;
            font-size: 14px;
            font-family: 'DejaVu Sans', sans-serif;
        }

        /* Utility margin */
        .mb-1 {
            margin-bottom: 4px;
            display: block;
        }
    </style>
</head>

<body>

    @php
        // --- LOGIKA STATUS & DIVISI ---
        $statusName = strtoupper($tamu->status->nama_status ?? '');
        $isSupplier = str_contains($statusName, 'SUPPLIER');
        $isCustomer = str_contains($statusName, 'CUSTOMER') || str_contains($statusName, 'BUYER');
        $isUmum = !$isSupplier && !$isCustomer;

        $divisiName = strtoupper($tamu->divisi->nama_divisi ?? '');
        $isDirektur = str_contains($divisiName, 'DIREKTUR');
        $isSevp = str_contains($divisiName, 'SEVP');
        $isGm = str_contains($divisiName, 'GM');
        $isKadiv = str_contains($divisiName, 'KADIV');
        $isLainnya = !($isDirektur || $isSevp || $isGm || $isKadiv);

        // --- HITUNG ROWSPAN UNTUK ITEM 5 (DINAMIS) ---
        // Jumlah baris = 1 (Tamu Utama) + Jumlah Pengiring
        $totalRowsItem5 = 1 + $tamu->pengiring->count();
    @endphp

    {{-- Header Kop Surat --}}
    {{-- <div style="text-align: center; margin-bottom: 10px; border: 1px solid black; padding: 10px;">
        <h3>PT. MITRATANI DUA TUJUH</h3>
        <table class="header-table" style="width: 100%">
            <tr>
                <td style="width: 60%; text-align: center; font-weight: bold; font-size: 16px;">VISITOR CONTROL</td>
                <td>No Rev : 05</td>
            </tr>
            <tr>
                <td style="text-align: center;">Nomor Surat (Contoh)</td>
                <td>Tgl Terbit : {{ date('d-m-Y') }}</td>
            </tr>
        </table>
    </div> --}}
    {{-- Header Kop Surat Baru --}}
    <div style="margin-bottom: 10px;">
        <table style="width: 100%; border: 1px solid black; border-collapse: collapse;">
            <tr>
                {{-- KOLOM KIRI: LOGO --}}
                <td
                    style="width: 25%; text-align: center; border-right: 1px solid black; vertical-align: middle; padding: 10px;">
                    {{-- 
                        CATATAN: Jika gambar tidak muncul (tanda silang merah/kosong) saat di-export,
                        Ganti {{ asset(...) }} menjadi {{ public_path(...) }} 
                    --}}
                    <img src="{{ public_path('images/logo-company.png') }}" style="max-width: 100px; height: auto;">
                </td>

                {{-- KOLOM KANAN: TABEL INFORMASI --}}
                <td style="width: 70%; padding: 0; vertical-align: top;">
                    <table style="width: 100%; border-collapse: collapse; margin: 0;">

                        {{-- Baris 1: Nama PT --}}
                        <tr>
                            <td colspan="2"
                                style="text-align: center; font-weight: bold; font-size: 16px; padding: 15px 5px; border-bottom: 1px solid black; border-right: none; border-left: none; border-top: none;">
                                PT MITRATANI DUA TUJUH
                            </td>
                        </tr>

                        {{-- Baris 2: Visitor Control & No Rev --}}
                        <tr>
                            <td
                                style="width: 60%; text-align: center; font-weight: bold; border-bottom: 1px solid black; border-right: 1px solid black; padding: 5px;">
                                VISITOR CONTROL
                            </td>
                            <td style="width: 40%; padding: 5px; border-bottom: 1px solid black;">
                                NO REV: 06
                            </td>
                        </tr>

                        {{-- Baris 3: Nomor Surat & Tanggal --}}
                        <tr>
                            <td style="text-align: center; border-right: 1px solid black; padding: 5px;">
                                {{-- Membuat format nomor surat dinamis: Tgl.Kode.ID --}}
                                {{-- Contoh: 251129.MT27.0001 --}}
                                {{-- {{ date('dmy') }}.MT27.{{ str_pad($tamu->id, 4, '0', STR_PAD_LEFT) }} --}}
                                266/06/F/J/DTKT/J
                            </td>
                            <td style="padding: 5px;">
                                Tgl Terbit : {{ date('d-m-Y') }}
                            </td>
                        </tr>

                    </table>
                </td>
            </tr>
        </table>
    </div>

    <table>
        <tr>
            <td colspan="3" class="title">FORM KUNJUNGAN TAMU</td>
        </tr>
        <tr>
            <td class="label-col">1. NAMA PERUSAHAAN / INSTANSI</td>
            <td colspan="2" class="ukuran">{{ $tamu->instansi }}</td>
        </tr>
        <tr>
            <td class="label-col">2. BIDANG USAHA</td>
            <td colspan="2" class="ukuran">{{ $tamu->bidang_usaha }}</td>
        </tr>

        {{-- ITEM 3: STATUS --}}
        <tr>
            <td class="label-col">3. STATUS</td>
            <td colspan="2" class="ukuran">
                <span class="mb-1"><span class="checkbox">{{ $isSupplier ? '✔' : '' }}</span> Supplier</span>
                <span class="mb-1"><span class="checkbox">{{ $isCustomer ? '✔' : '' }}</span> Customer/Buyer</span>
                <span><span class="checkbox">{{ $isUmum ? '✔' : '' }}</span> Umum</span>
            </td>
        </tr>

        <tr>
            <td class="label-col">4. JUMLAH TAMU</td>
            <td colspan="2" class="ukuran">{{ $tamu->jumlah_tamu }} Orang</td>
        </tr>

        {{-- ITEM 5: NAMA TAMU (DINAMIS ROWSPAN) --}}
        <tr>
            {{-- Rowspan menyesuaikan jumlah orang --}}
            <td rowspan="{{ $totalRowsItem5 }}" class="label-col">5. NAMA TAMU & JABATAN</td>

            {{-- Tamu Utama (Selalu ada) --}}
            <td class="ukurana"style="width: 5%">1.</td>
            <td class="ukuran">{{ $tamu->nama }} ({{ $tamu->jabatan }})</td>
        </tr>

        {{-- Loop Pengiring (Jika ada) --}}
        @foreach ($tamu->pengiring as $index => $pengiring)
            <tr>
                <td>{{ $index + 2 }}.</td>
                <td class="ukurana">{{ $pengiring->nama }} ({{ $pengiring->jabatan }})</td>
            </tr>
        @endforeach
        {{-- Tidak ada lagi loop kosong (filler) di sini --}}


        <tr>
            <td class="label-col">6. TANGGAL/JAM KEDATANGAN</td>
            <td colspan="2" class="ukuran">{{ $tamu->created_at->format('d/m/Y / H:i') }}</td>
        </tr>
        <tr>
            <td class="label-col">7. TANGGAL/JAM KEPULANGAN</td>
            <td colspan="2" class="ukuran">
                {{ $tamu->updated_at > $tamu->created_at ? $tamu->updated_at->format('d/m/Y / H:i') : '-' }}</td>
        </tr>

        {{-- ITEM 8: BERTEMU DENGAN (KOMPAK / TANPA FILLER) --}}
        <tr>
            <td class="label-col">8. BERTEMU DENGAN</td>
            <td colspan="2" class="ukuran">
                <div class="mb-1"><span class="checkbox">{{ $isDirektur ? '✔' : '' }}</span> DIREKTUR</div>
                <div class="mb-1"><span class="checkbox">{{ $isSevp ? '✔' : '' }}</span> SEVP</div>
                <div class="mb-1"><span class="checkbox">{{ $isGm ? '✔' : '' }}</span> GM</div>
                <div class="mb-1"><span class="checkbox">{{ $isKadiv ? '✔' : '' }}</span> KADIV</div>

                <div>
                    <span class="checkbox">{{ $isLainnya ? '✔' : '' }}</span>
                    LAINNYA: {{ $isLainnya ? $tamu->divisi->nama_divisi ?? '-' : '.....................' }}
                    ({{ $tamu->penerima_tamu }})
                </div>
            </td>
        </tr>
        {{-- Filler rows di bawah Item 8 SUDAH DIHAPUS agar dempet dengan Item 9 --}}

        <tr>
            <td class="label-col">9. NOPOL</td>
            <td colspan="2" class="ukuran" style="min-height: 50px;">
                {{ $tamu->nopol_kendaraan }}
            </td>
        </tr>

        {{-- ITEM 9: AGENDA (CUKUP 1 BARIS) --}}
        <tr>
            <td class="label-col">10. AGENDA / KEPERLUAN</td>
            <td colspan="2" class="ukuran" style="min-height: 50px;">
                {{ $tamu->keperluan }}
            </td>
        </tr>

        {{-- ITEM 10: KETERANGAN (FORMAT A, B, C) --}}
        <tr>
            <td class="label-col">11. KETERANGAN</td>
            <td colspan="2" class="ukuran">
                {{-- Menggunakan table tanpa border di dalam cell agar rapi, atau text biasa --}}
                <div style="margin-bottom: 4px;">a. No. Hp : {{ $tamu->no_hp }}</div>
                <div style="margin-bottom: 4px;">b. No. Seal : {{ $tamu->no_seal }}</div>
                {{-- Data no_seal belum ada di DB, saya kasih strip (-) --}}
                {{-- <div>c. No. Seal : {{ $tamu->no_seal }} </div> --}}
            </td>
        </tr>

        {{-- BAGIAN TANDA TANGAN --}}
        <tr>
            <td colspan="3" class="no-border" style="padding: 0; padding-top: 20px;">
                <table style="width: 100%; border-collapse: collapse; text-align: center;">
                    {{-- Header --}}
                    <tr>
                        <td style="width: 25%; font-weight: bold; background-color: #f0f0f0; border: 1px solid black;">
                            TAMU</td>
                        <td style="width: 25%; font-weight: bold; background-color: #f0f0f0; border: 1px solid black;">
                            SATPAM</td>
                        <td style="width: 25%; font-weight: bold; background-color: #f0f0f0; border: 1px solid black;">
                            OPERATOR</td>
                        <td style="width: 25%; font-weight: bold; background-color: #f0f0f0; border: 1px solid black;">
                            PENERIMA</td>
                    </tr>

                    {{-- Isi Tanda Tangan --}}
                    <tr>
                        {{-- 1. TAMU --}}
                        <td
                            style="vertical-align: bottom; border: 1px solid black; height: 100px; padding-bottom: 5px;">
                            @if ($tamu->tandaTangan && $tamu->tandaTangan->ttd_tamu)
                                <img src="{{ public_path('storage/' . $tamu->tandaTangan->ttd_tamu) }}"
                                    style="max-height: 70px; max-width: 90%; margin-bottom: 5px;">
                                <div
                                    style="font-size: 11px; font-weight: bold; border-top: 1px dotted black; width: 80%; margin: 0 auto; padding-top: 2px;">
                                    {{ strtoupper($tamu->nama) }}
                                </div>
                            @else
                                <br><br><br>
                                <div style="font-size: 11px;">(....................)</div>
                            @endif
                        </td>

                        {{-- 2. SATPAM --}}
                        <td
                            style="vertical-align: bottom; border: 1px solid black; height: 100px; padding-bottom: 5px;">
                            @if ($tamu->tandaTangan && $tamu->tandaTangan->ttd_satpam)
                                <img src="{{ public_path('storage/' . $tamu->tandaTangan->ttd_satpam) }}"
                                    style="max-height: 70px; max-width: 90%; margin-bottom: 5px;">

                                {{-- Mengambil Nama Satpam dari Database --}}
                                <div
                                    style="font-size: 11px; font-weight: bold; border-top: 1px dotted black; width: 80%; margin: 0 auto; padding-top: 2px;">
                                    {{-- Jika nama_satpam ada isinya, tampilkan. Jika kosong, tampilkan User Login yg nge-print (fallback) atau titik-titik --}}
                                    {{ strtoupper($tamu->tandaTangan->nama_satpam ?? '(....................)') }}
                                </div>
                            @else
                                <br><br><br>
                                <div style="font-size: 11px;">(....................)</div>
                            @endif
                        </td>



                        {{-- 4. OPERATOR --}}
                        <td
                            style="vertical-align: bottom; border: 1px solid black; height: 100px; padding-bottom: 5px;">
                            @if ($tamu->tandaTangan && $tamu->tandaTangan->ttd_operator)
                                <img src="{{ public_path('storage/' . $tamu->tandaTangan->ttd_operator) }}"
                                    style="max-height: 70px; max-width: 90%; margin-bottom: 5px;">

                                {{-- Mengambil Nama Operator dari Database --}}
                                <div
                                    style="font-size: 11px; font-weight: bold; border-top: 1px dotted black; width: 80%; margin: 0 auto; padding-top: 2px;">
                                    {{ strtoupper($tamu->tandaTangan->nama_operator ?? '(....................)') }}
                                </div>
                            @else
                                <br><br><br>
                                <div style="font-size: 11px;">(....................)</div>
                            @endif
                        </td>
                        {{-- 3. PENERIMA --}}
                        <td
                            style="vertical-align: bottom; border: 1px solid black; height: 100px; padding-bottom: 5px;">
                            @if ($tamu->tandaTangan && $tamu->tandaTangan->ttd_penerima)
                                <img src="{{ public_path('storage/' . $tamu->tandaTangan->ttd_penerima) }}"
                                    style="max-height: 70px; max-width: 90%; margin-bottom: 5px;">
                                <div
                                    style="font-size: 11px; font-weight: bold; border-top: 1px dotted black; width: 80%; margin: 0 auto; padding-top: 2px;">
                                    {{ strtoupper($tamu->penerima_tamu) }}
                                </div>
                            @else
                                <br><br><br>
                                <div style="font-size: 11px;">(....................)</div>
                            @endif
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>

</html>
