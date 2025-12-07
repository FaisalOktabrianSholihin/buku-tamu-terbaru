{{-- <x-filament-panels::page>

</x-filament-panels::page> --}}

<x-filament-panels::page>
    {{-- Load Library Signature Pad & QR Code --}}
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>

    <div class="space-y-6" x-data="{ activeTab: 'scan' }">

        {{-- SECTION 1: SCANNER / INPUT --}}
        <x-filament::section>
            <x-slot name="heading">
                🔍 Pencarian Data Tamu
            </x-slot>
            <x-slot name="description">
                Scan QR Code atau masukkan kode secara manual
            </x-slot>

            {{-- Tabs Header --}}
            <div class="flex gap-2 mb-6">
                <button @click="activeTab = 'scan'" type="button"
                    :class="activeTab === 'scan'
                        ?
                        'bg-primary-600 text-white' :
                        'bg-gray-100 text-gray-600 dark:bg-gray-800 dark:text-gray-400'"
                    class="flex-1 py-3 px-4 rounded-lg font-medium text-sm transition-all flex items-center justify-center gap-2 hover:shadow-md">
                    <span class="text-lg">📷</span>
                    <span>Scan QR Code</span>
                </button>
                <button @click="activeTab = 'manual'" type="button"
                    :class="activeTab === 'manual'
                        ?
                        'bg-primary-600 text-white' :
                        'bg-gray-100 text-gray-600 dark:bg-gray-800 dark:text-gray-400'"
                    class="flex-1 py-3 px-4 rounded-lg font-medium text-sm transition-all flex items-center justify-center gap-2 hover:shadow-md">
                    <span class="text-lg">⌨️</span>
                    <span>Input Manual</span>
                </button>
            </div>

            {{-- Tab Content --}}
            <div class="min-h-[300px]">
                {{-- Tab Scan --}}
                <div x-show="activeTab === 'scan'" wire:ignore class="space-y-4">
                    <div
                        class="bg-gray-50 dark:bg-gray-900 p-4 rounded-lg border-2 border-dashed border-gray-300 dark:border-gray-700">
                        <div id="reader" style="width: 100%; border-radius: 8px; overflow: hidden;"></div>
                    </div>
                    <div class="text-center text-sm text-gray-500 dark:text-gray-400">
                        📱 Arahkan QR Code ke kamera untuk scan otomatis
                    </div>
                </div>

                {{-- Tab Manual --}}
                <div x-show="activeTab === 'manual'" style="display: none;" class="space-y-4">
                    <div class="max-w-md mx-auto space-y-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                Kode QR Tamu
                            </label>
                            <x-filament::input type="text" wire:model="qr_manual"
                                placeholder="Contoh: 251127.M27.0001" wire:keydown.enter="cariManual"
                                class="text-center font-mono text-lg" />
                            {{-- <p class="mt-1 text-xs text-gray-500">Format: YYMMDD.XXX.NNNN</p> --}}
                        </div>

                        <x-filament::button wire:click="cariManual" color="primary" size="lg" class="w-full">
                            <span class="flex items-center justify-center gap-2">
                                <span>🔎</span>
                                <span>Cari Data Tamu</span>
                            </span>
                        </x-filament::button>
                    </div>
                </div>
            </div>

            {{-- Reset Button --}}
            <div class="mt-6 pt-4 border-t border-gray-200 dark:border-gray-700 text-center">
                <x-filament::button wire:click="resetForm" color="gray" size="sm" outlined>
                    <span class="flex items-center gap-1">
                        <span>🔄</span>
                        <span>Reset Form</span>
                    </span>
                </x-filament::button>
            </div>
        </x-filament::section>

        {{-- SECTION 2: HASIL DATA TAMU --}}
        @if ($is_found)
            <x-filament::section>
                <x-slot name="heading">
                    📋 Data Tamu Ditemukan
                </x-slot>
                <x-slot name="description">
                    Verifikasi data tamu sebelum validasi
                </x-slot>

                <div class="space-y-6">
                    {{-- Informasi Grid --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        {{-- Card 1 --}}
                        <div
                            class="bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900 dark:to-blue-800 p-4 rounded-lg border border-blue-200 dark:border-blue-700">
                            <div class="text-xs text-blue-600 dark:text-blue-300 font-semibold mb-1">TANGGAL KUNJUNGAN
                            </div>
                            <div class="text-lg font-bold text-blue-900 dark:text-blue-100">
                                {{ \Carbon\Carbon::parse($tanggal)->format('d M Y') }}
                            </div>
                        </div>

                        {{-- Card 2 --}}
                        <div
                            class="bg-gradient-to-br from-purple-50 to-purple-100 dark:from-purple-900 dark:to-purple-800 p-4 rounded-lg border border-purple-200 dark:border-purple-700">
                            <div class="text-xs text-purple-600 dark:text-purple-300 font-semibold mb-1">DIVISI TUJUAN
                            </div>
                            <div class="text-lg font-bold text-purple-900 dark:text-purple-100">
                                {{ \App\Models\Divisi::find($divisi_id)?->nama_divisi ?? '-' }}
                            </div>
                        </div>

                        {{-- Card 3 --}}
                        <div
                            class="bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900 dark:to-green-800 p-4 rounded-lg border border-green-200 dark:border-green-700">
                            <div class="text-xs text-green-600 dark:text-green-300 font-semibold mb-1">JUMLAH TAMU</div>
                            <div class="text-lg font-bold text-green-900 dark:text-green-100">
                                {{ $jumlah_tamu }} Orang
                            </div>
                        </div>
                    </div>

                    {{-- Table Data Tamu --}}
                    <div class="overflow-hidden rounded-lg border border-gray-200 dark:border-gray-700">
                        <table class="w-full text-sm">
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                <tr class="bg-gray-50 dark:bg-gray-800">
                                    <td colspan="2"
                                        class="px-4 py-3 font-bold text-gray-700 dark:text-gray-200 bg-gray-100 dark:bg-gray-900">
                                        👤 DATA TAMU UTAMA
                                    </td>
                                </tr>
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800">
                                    <td class="px-4 py-3 font-semibold text-gray-600 dark:text-gray-400 w-1/3">Nama
                                        Lengkap</td>
                                    <td class="px-4 py-3 text-gray-900 dark:text-gray-100">{{ $nama }}</td>
                                </tr>
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800">
                                    <td class="px-4 py-3 font-semibold text-gray-600 dark:text-gray-400">Jabatan</td>
                                    <td class="px-4 py-3 text-gray-900 dark:text-gray-100">{{ $jabatan }}</td>
                                </tr>
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800">
                                    <td class="px-4 py-3 font-semibold text-gray-600 dark:text-gray-400">No. HP</td>
                                    <td class="px-4 py-3 text-gray-900 dark:text-gray-100">{{ $no_hp }}</td>
                                </tr>
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800">
                                    <td class="px-4 py-3 font-semibold text-gray-600 dark:text-gray-400">Instansi</td>
                                    <td class="px-4 py-3 text-gray-900 dark:text-gray-100">{{ $instansi }}</td>
                                </tr>
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800">
                                    <td class="px-4 py-3 font-semibold text-gray-600 dark:text-gray-400">Bidang Usaha
                                    </td>
                                    <td class="px-4 py-3 text-gray-900 dark:text-gray-100">{{ $bidang_usaha }}</td>
                                </tr>
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800">
                                    <td class="px-4 py-3 font-semibold text-gray-600 dark:text-gray-400">Status</td>
                                    <td class="px-4 py-3">
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                            {{ $status_tamu }}
                                        </span>
                                    </td>
                                </tr>

                                <tr class="bg-gray-50 dark:bg-gray-800">
                                    <td colspan="2"
                                        class="px-4 py-3 font-bold text-gray-700 dark:text-gray-200 bg-gray-100 dark:bg-gray-900">
                                        📂 INFORMASI KUNJUNGAN
                                    </td>
                                </tr>
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800">
                                    <td class="px-4 py-3 font-semibold text-gray-600 dark:text-gray-400">Penerima Tamu
                                    </td>
                                    <td class="px-4 py-3 text-gray-900 dark:text-gray-100">{{ $penerima_tamu }}</td>
                                </tr>
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800">
                                    <td class="px-4 py-3 font-semibold text-gray-600 dark:text-gray-400">Nopol Kendaraan
                                    </td>
                                    <td class="px-4 py-3">
                                        <x-filament::input type="text" wire:model="nopol_kendaraan"
                                            placeholder="Contoh: B 1234 XYZ" />
                                    </td>
                                </tr>
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800">
                                    <td class="px-4 py-3 font-semibold text-gray-600 dark:text-gray-400 align-top">
                                        Keperluan</td>
                                    <td class="px-4 py-3 text-gray-900 dark:text-gray-100">{{ $keperluan }}</td>
                                </tr>

                                <tr class="bg-gray-50 dark:bg-gray-800">
                                    <td colspan="2"
                                        class="px-4 py-3 font-bold text-gray-700 dark:text-gray-200 bg-gray-100 dark:bg-gray-900">
                                        📅 AGENDA & KETERANGAN
                                    </td>
                                </tr>
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800">
                                    <td class="px-4 py-3 font-semibold text-gray-600 dark:text-gray-400 align-top">
                                        Agenda</td>
                                    <td class="px-4 py-3 text-gray-900 dark:text-gray-100">{{ $agenda }}</td>
                                </tr>
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800">
                                    <td class="px-4 py-3 font-semibold text-gray-600 dark:text-gray-400 align-top">
                                        Keterangan</td>
                                    <td class="px-4 py-3 text-gray-900 dark:text-gray-100">{{ $keterangan ?? '-' }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    {{-- Signature Section --}}
                    <div class="bg-gradient-to-br from-amber-50 to-amber-100 dark:from-amber-900 dark:to-amber-800 p-6 rounded-lg border-2 border-amber-300 dark:border-amber-700"
                        x-data="{
                            signaturePad: null,
                            init() {
                                let canvas = this.$refs.canvas;
                                this.signaturePad = new SignaturePad(canvas, {
                                    backgroundColor: 'rgba(255, 255, 255)',
                                    penColor: 'rgb(0, 0, 0)'
                                });
                                this.resizeCanvas();
                                window.addEventListener('resize', () => this.resizeCanvas());
                            },
                            resizeCanvas() {
                                let canvas = this.$refs.canvas;
                                let ratio = Math.max(window.devicePixelRatio || 1, 1);
                                canvas.width = canvas.offsetWidth * ratio;
                                canvas.height = canvas.offsetHeight * ratio;
                                canvas.getContext('2d').scale(ratio, ratio);
                            },
                            clear() {
                                this.signaturePad.clear();
                                @this.set('ttd_operator_base64', null);
                            },
                            save() {
                                if (!this.signaturePad.isEmpty()) {
                                    @this.set('ttd_operator_base64', this.signaturePad.toDataURL());
                                }
                            }
                        }">

                        <h3 class="font-bold text-lg text-amber-900 dark:text-amber-100 mb-4 flex items-center gap-2">
                            <span>✍️</span>
                            <span>Tanda Tangan Validasi Operator</span>
                        </h3>

                        <div class="bg-white rounded-lg border-2 border-dashed border-amber-400 p-2">
                            <canvas x-ref="canvas" style="width: 100%; height: 200px; display: block;"></canvas>
                        </div>

                        <div class="flex justify-between items-center mt-3">
                            <button type="button" @click="clear()"
                                class="text-sm text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 underline font-medium">
                                🗑️ Hapus Tanda Tangan
                            </button>
                            <p class="text-xs text-amber-700 dark:text-amber-300">
                                ⚠️ Pastikan tanda tangan sebelum validasi
                            </p>
                        </div>

                        {{-- Action Buttons --}}
                        <div class="grid grid-cols-2 gap-4 mt-6">
                            <x-filament::button wire:click.prevent="simpanValidasi" color="success" size="lg"
                                class="w-full" @click="save()">
                                <span class="flex items-center justify-center gap-2">
                                    <span class="text-xl">✅</span>
                                    <span class="font-bold">SETUJU</span>
                                </span>
                            </x-filament::button>

                            {{-- <x-filament::button wire:click.prevent="simpanTolakValidasi" color="danger"
                                size="lg" class="w-full" @click="save()">
                                <span class="flex items-center justify-center gap-2">
                                    <span class="text-xl">❌</span>
                                    <span class="font-bold">TOLAK</span>
                                </span>
                            </x-filament::button> --}}
                        </div>
                    </div>
                </div>
            </x-filament::section>
        @else
            {{-- Empty State --}}
            <x-filament::section>
                <div class="flex flex-col items-center justify-center py-16 text-center">
                    <div
                        class="w-24 h-24 bg-gray-100 dark:bg-gray-800 rounded-full flex items-center justify-center mb-4">
                        <span class="text-5xl">📋</span>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300 mb-2">
                        Belum Ada Data Tamu
                    </h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 max-w-sm">
                        Silakan scan QR Code atau masukkan kode secara manual untuk menampilkan data tamu
                    </p>
                </div>
            </x-filament::section>
        @endif

    </div>

    {{-- Script Scanner Logic (Sama seperti sebelumnya) --}}
    <script>
        document.addEventListener('livewire:initialized', () => {
            let html5QrcodeScanner;

            function startScanner() {
                if (!document.getElementById("reader")) return;
                if (html5QrcodeScanner?.getState() === 2) return;

                html5QrcodeScanner = new Html5Qrcode("reader");
                html5QrcodeScanner.start({
                        facingMode: "environment"
                    }, {
                        fps: 10,
                        qrbox: {
                            width: 250,
                            height: 250
                        }
                    },
                    (decodedText) => {
                        html5QrcodeScanner.pause();
                        @this.call('cekQr', decodedText);
                    }).catch(err => console.log("Camera Error", err));
            }

            startScanner();

            @this.on('form-reset', () => {
                if (html5QrcodeScanner) {
                    try {
                        html5QrcodeScanner.resume();
                    } catch (e) {
                        html5QrcodeScanner.clear().then(startScanner).catch(startScanner);
                    }
                } else {
                    startScanner();
                }
            });
        });
    </script>
</x-filament-panels::page>
