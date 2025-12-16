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
            width: 11%;
            background-color: #f0f0f0;
            font-weight: bold;
            font-size: 9px;
        }

        .ukuran {
            width: 25%;
            font-size: 10px;
        }

        .ukurana {
            width: 45%;
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
        // Pastikan pengecekan ini sesuai dengan data di DB Anda
        $statusName = strtoupper($tamu->status->nama_status ?? '');

        // Logika penentuan tipe tamu
        $isSupplier = str_contains($statusName, 'SUPPLIER') || str_contains($statusName, 'SUPLIER');
        $isCustomer = str_contains($statusName, 'CUSTOMER') || str_contains($statusName, 'BUYER');
        $isEkspedisi = str_contains($statusName, 'EKSPEDISI');

        // Jika bukan Supplier dan bukan Customer, maka dianggap UMUM
        // $isUmum = !$isSupplier && !$isCustomer;
        $isUmum = !$isSupplier && !$isCustomer && !$isEkspedisi;

        $divisiName = strtoupper($tamu->divisi->nama_divisi ?? '');
        $isDirektur = str_contains($divisiName, 'DIREKTUR');
        $isSevp = str_contains($divisiName, 'SEVP');
        $isGm = str_contains($divisiName, 'GM');
        $isKadiv = str_contains($divisiName, 'KADIV');
        $isLainnya = !($isDirektur || $isSevp || $isGm || $isKadiv);

        // Jumlah baris rowspan untuk nama tamu
        $totalRowsItem5 = 1 + $tamu->pengiring->count();
    @endphp

    {{-- Header Kop Surat --}}
    <div style="margin-bottom: 10px;">
        <table style="width: 100%; border: 1px solid black; border-collapse: collapse;">
            <tr>
                {{-- KOLOM KIRI: LOGO --}}
                <td
                    style="width: 25%; text-align: center; border-right: 1px solid black; vertical-align: middle; padding: 10px;">
                    <img src="{{ public_path('images/logo-company.png') }}" style="max-width: 100px; height: auto;">
                </td>

                {{-- KOLOM KANAN: TABEL INFORMASI --}}
                <td style="width: 70%; padding: 0; vertical-align: top;">
                    <table style="width: 100%; border-collapse: collapse; margin: 0;">
                        <tr>
                            <td colspan="2"
                                style="text-align: center; font-weight: bold; font-size: 16px; padding: 15px 5px; border-bottom: 1px solid black; border-right: none; border-left: none; border-top: none;">
                                PT MITRATANI DUA TUJUH
                            </td>
                        </tr>
                        <tr>
                            <td
                                style="width: 60%; text-align: center; font-weight: bold; border-bottom: 1px solid black; border-right: 1px solid black; padding: 5px;">
                                VISITOR CONTROL
                            </td>
                            <td style="width: 40%; padding: 5px; font-weight: bold; border-bottom: 1px solid black;">
                                NO REV: 06
                            </td>
                        </tr>
                        <tr>
                            <td
                                style="text-align: center; font-weight: bold; border-right: 1px solid black; padding: 5px;">
                                266/06/F/J/DTKT/J
                            </td>
                            <td style="padding: 5px; font-weight: bold;">
                                Tgl : 08-12-2025
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

        {{-- [MODIFIKASI 1] BIDANG USAHA: Hanya muncul jika UMUM --}}
        @if ($isUmum)
            <tr>
                <td class="label-col">2. BIDANG USAHA</td>
                <td colspan="2" class="ukuran">{{ $tamu->bidang_usaha }}</td>
            </tr>
        @endif

        {{-- ITEM 3: STATUS (Checkbox otomatis tercentang sesuai logika PHP di atas) --}}
        <tr>
            <td class="label-col">3. STATUS</td>
            <td colspan="2" class="ukuran">
                <span class="mb-1"><span class="checkbox">{{ $isSupplier ? '✔' : '' }}</span> Supplier</span>
                <span class="mb-1"><span class="checkbox">{{ $isCustomer ? '✔' : '' }}</span> Customer/Buyer</span>
                <span class="mb-1"><span class="checkbox">{{ $isEkspedisi ? '✔' : '' }}</span> Ekspedisi</span>
                <span><span class="checkbox">{{ $isUmum ? '✔' : '' }}</span> Umum</span>
            </td>
        </tr>

        <tr>
            <td class="label-col">4. JUMLAH TAMU</td>
            <td colspan="2" class="ukuran">{{ $tamu->jumlah_tamu }} Orang</td>
        </tr>

        {{-- ITEM 5: NAMA TAMU --}}
        <tr>
            <td rowspan="{{ $totalRowsItem5 }}" class="label-col">5. NAMA TAMU & JABATAN</td>
            <td class="ukurana" style="width: 5%">1.</td>
            {{-- Tampilkan Jabatan hanya jika Umum, jika Driver/Supplier mungkin jabatan tidak perlu detail --}}
            <td class="ukuran">{{ $tamu->nama }} ({{ $tamu->jabatan }})</td>
        </tr>

        @foreach ($tamu->pengiring as $index => $pengiring)
            <tr>
                <td>{{ $index + 2 }}.</td>
                <td class="ukurana">{{ $pengiring->nama }} ({{ $pengiring->jabatan }})</td>
            </tr>
        @endforeach

        <tr>
            <td class="label-col">6. TANGGAL/JAM KEDATANGAN</td>
            <td colspan="2" class="ukuran">{{ $tamu->created_at->format('d/m/Y / H:i') }}</td>
        </tr>
        <tr>
            <td class="label-col">7. TANGGAL/JAM KEPULANGAN</td>
            <td colspan="2" class="ukuran">
                {{ $tamu->updated_at > $tamu->created_at ? $tamu->updated_at->format('d/m/Y / H:i') : '-' }}</td>
        </tr>

        {{-- [MODIFIKASI 2] BERTEMU DENGAN: Hanya muncul jika UMUM --}}
        @if ($isUmum)
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
        @endif

        <tr>
            <td class="label-col">9. NOPOL</td>
            <td colspan="2" class="ukuran" style="min-height: 50px;">
                {{ $tamu->nopol_kendaraan }}
            </td>
        </tr>

        <tr>
            <td class="label-col">10. AGENDA / KEPERLUAN</td>
            <td colspan="2" class="ukuran" style="min-height: 50px;">
                {{ $tamu->keperluan }}
            </td>
        </tr>

        <tr>
            <td class="label-col">11. KETERANGAN</td>
            <td colspan="2" class="ukuran">
                <div style="margin-bottom: 4px;">a. No. Hp : {{ $tamu->no_hp }}</div>
                <div style="margin-bottom: 4px;">b. No. Seal : {{ $tamu->no_seal ?? '-' }}</div>
            </td>
        </tr>

        {{-- [MODIFIKASI 3] TANDA TANGAN DINAMIS --}}
        <tr>
            <td colspan="3" class="no-border" style="padding: 0; padding-top: 20px;">

                <table style="width: 100%; border-collapse: collapse; text-align: center;">
                    {{-- 
                        LOGIKA HEADER TTD: 
                        Jika UMUM = 4 Kolom (25% each)
                        Jika SUPPLIER/CUSTOMER = 2 Kolom (50% each)
                    --}}
                    <tr>
                        @if ($isUmum)
                            {{-- HEADER TTD UMUM --}}
                            <td
                                style="width: 25%; font-weight: bold; background-color: #f0f0f0; border: 1px solid black;">
                                TAMU</td>
                            <td
                                style="width: 25%; font-weight: bold; background-color: #f0f0f0; border: 1px solid black;">
                                SATPAM</td>
                            <td
                                style="width: 25%; font-weight: bold; background-color: #f0f0f0; border: 1px solid black;">
                                OPERATOR</td>
                            <td
                                style="width: 25%; font-weight: bold; background-color: #f0f0f0; border: 1px solid black;">
                                PENERIMA</td>
                        @else
                            {{-- HEADER TTD SUPPLIER/CUSTOMER --}}
                            <td
                                style="width: 50%; font-weight: bold; background-color: #f0f0f0; border: 1px solid black;">
                                TAMU / DRIVER</td>
                            <td
                                style="width: 50%; font-weight: bold; background-color: #f0f0f0; border: 1px solid black;">
                                SATPAM</td>
                        @endif
                    </tr>

                    <tr>
                        {{-- ================= TTD TAMU (SELALU ADA) ================= --}}
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

                        {{-- ================= TTD SATPAM (SELALU ADA) ================= --}}
                        <td
                            style="vertical-align: bottom; border: 1px solid black; height: 100px; padding-bottom: 5px;">
                            @if ($tamu->tandaTangan && $tamu->tandaTangan->ttd_satpam)
                                <img src="{{ public_path('storage/' . $tamu->tandaTangan->ttd_satpam) }}"
                                    style="max-height: 70px; max-width: 90%; margin-bottom: 5px;">
                                <div
                                    style="font-size: 11px; font-weight: bold; border-top: 1px dotted black; width: 80%; margin: 0 auto; padding-top: 2px;">
                                    {{ strtoupper($tamu->tandaTangan->nama_satpam ?? '(....................)') }}
                                </div>
                            @else
                                <br><br><br>
                                <div style="font-size: 11px;">(....................)</div>
                            @endif
                        </td>

                        {{-- ================= TTD OPERATOR & PENERIMA (HANYA UMUM) ================= --}}
                        @if ($isUmum)
                            {{-- OPERATOR --}}
                            <td
                                style="vertical-align: bottom; border: 1px solid black; height: 100px; padding-bottom: 5px;">
                                @if ($tamu->tandaTangan && $tamu->tandaTangan->ttd_operator)
                                    <img src="{{ public_path('storage/' . $tamu->tandaTangan->ttd_operator) }}"
                                        style="max-height: 70px; max-width: 90%; margin-bottom: 5px;">
                                    <div
                                        style="font-size: 11px; font-weight: bold; border-top: 1px dotted black; width: 80%; margin: 0 auto; padding-top: 2px;">
                                        {{ strtoupper($tamu->tandaTangan->nama_operator ?? '(....................)') }}
                                    </div>
                                @else
                                    <br><br><br>
                                    <div style="font-size: 11px;">(....................)</div>
                                @endif
                            </td>

                            {{-- PENERIMA --}}
                            <td
                                style="vertical-align: bottom; border: 1px solid black; height: 100px; padding-bottom: 5px;">
                                @if ($tamu->tandaTangan && $tamu->tandaTangan->ttd_penerima)
                                    <img src="{{ public_path('storage/' . $tamu->tandaTangan->ttd_penerima) }}"
                                        style="max-height: 70px; max-width: 90%; margin-bottom: 5px;">
                                @else
                                    <br><br><br>
                                @endif

                                <div
                                    style="font-size: 11px; font-weight: bold; border-top: 1px dotted black; width: 80%; margin: 0 auto; padding-top: 2px;">
                                    @if (!empty($tamu->penerima_tamu))
                                        {{ strtoupper($tamu->penerima_tamu) }}
                                    @else
                                        (....................)
                                    @endif
                                </div>
                            </td>
                        @endif

                    </tr>
                </table>

            </td>
        </tr>
    </table>
</body>

</html>
