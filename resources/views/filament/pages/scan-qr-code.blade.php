<x-filament-panels::page>
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>

    @if (!$selectedTamu)
        {{-- BAGIAN SCANNER & TABEL --}}
        <div class="space-y-6" x-data="{ activeTab: 'scan' }">
            <x-filament::section>
                <x-slot name="heading">🔍 Cari Tamu</x-slot>
                <div class="flex gap-2 mb-4">
                    <button @click="activeTab = 'scan'" type="button"
                        :class="activeTab === 'scan' ? 'bg-primary-600 text-white' : 'bg-gray-100 text-gray-600'"
                        class="px-4 py-2 rounded-lg font-medium transition-all">📷 Scan QR</button>
                    <button @click="activeTab = 'manual'" type="button"
                        :class="activeTab === 'manual' ? 'bg-primary-600 text-white' : 'bg-gray-100 text-gray-600'"
                        class="px-4 py-2 rounded-lg font-medium transition-all">⌨️ Manual Input</button>
                </div>

                <div x-show="activeTab === 'scan'" wire:ignore>
                    <div id="reader"
                        style="width: 100%; max-width: 500px; margin: 0 auto; border-radius: 8px; overflow: hidden;">
                    </div>
                </div>

                {{-- REVISI 5: AUTO-SEARCH PADA MANUAL INPUT --}}
                <div x-show="activeTab === 'manual'" style="display: none;">
                    <div class="max-w-md mx-auto">
                        <x-filament::input type="text" wire:model.live.debounce.750ms="qr_code_search"
                            placeholder="Masukkan Kode QR untuk mencari otomatis..." />
                    </div>
                </div>
            </x-filament::section>

            <div class="mt-6">
                <h3 class="text-lg font-bold mb-2">📋 Hasil Pencarian</h3>
                {{ $this->table }}
            </div>
        </div>
    @else
        {{-- BAGIAN FORM EDIT & VALIDASI --}}
        <div class="space-y-6">
            <div class="flex gap-3">
                <x-filament::button wire:click="batalValidasi" color="gray" size="sm" outlined>
                    ⬅️ Kembali ke Scanner
                </x-filament::button>
                {{-- REVISI 4: TOMBOL RESET BARU --}}
                <x-filament::button wire:click="resetValidasi" color="warning" size="sm" outlined>
                    🗑️ Reset Semua Form
                </x-filament::button>
            </div>

            <x-filament::section>
                <x-slot name="heading">
                    <div class="flex items-center gap-2"><span>✅</span> Validasi & Edit Data Tamu</div>
                </x-slot>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    {{-- REVISI 2 & 3: FIELD EDITABLE DENGAN BORDER & LABEL BOLD --}}

                    <div>
                        <label class="mb-1 block text-sm font-semibold text-gray-800 dark:text-gray-100">Nama
                            Tamu</label>
                        <x-filament::input.wrapper>
                            <x-filament::input type="text" wire:model="nama" />
                        </x-filament::input.wrapper>
                    </div>

                    <div>
                        <label class="mb-1 block text-sm font-semibold text-gray-800 dark:text-gray-100">Instansi /
                            Perusahaan</label>
                        <x-filament::input.wrapper>
                            <x-filament::input type="text" wire:model="instansi" />
                        </x-filament::input.wrapper>
                    </div>

                    <div>
                        <label
                            class="mb-1 block text-sm font-semibold text-gray-800 dark:text-gray-100">Keperluan</label>
                        <x-filament::input.wrapper>
                            <x-filament::input type="text" wire:model="keperluan" />
                        </x-filament::input.wrapper>
                    </div>

                    <div>
                        <label class="mb-1 block text-sm font-semibold text-gray-800 dark:text-gray-100">Bertemu
                            Dengan</label>
                        <x-filament::input.wrapper>
                            <x-filament::input type="text" wire:model="penerima_tamu" />
                        </x-filament::input.wrapper>
                    </div>

                    <div>
                        <label class="mb-1 block text-sm font-semibold text-gray-800 dark:text-gray-100">Jumlah Tamu
                            (Orang)</label>
                        <x-filament::input.wrapper>
                            <x-filament::input type="number" wire:model="jumlah_tamu" />
                        </x-filament::input.wrapper>
                    </div>

                    <div>
                        <label class="mb-1 block text-sm font-semibold text-gray-800 dark:text-gray-100">
                            Nomor Polisi Kendaraan <span class="text-red-500">*</span>
                        </label>
                        <x-filament::input.wrapper>
                            <x-filament::input type="text" wire:model="nopol_kendaraan"
                                placeholder="Contoh: B 1234 XYZ" />
                        </x-filament::input.wrapper>
                    </div>
                </div>

                <hr class="my-6 border-gray-200 dark:border-gray-700">

                {{-- SIGNATURE PAD SECTION --}}
                {{-- Container Utama Signature Pad --}}
                <div x-data="{
                    signaturePad: null,
                
                    init() {
                        // Init Signature Pad
                        let canvas = this.$refs.canvas;
                        this.signaturePad = new SignaturePad(canvas, {
                            backgroundColor: 'rgb(255, 255, 255)',
                            penColor: 'rgb(0, 0, 0)',
                            minWidth: 1.5,
                            maxWidth: 3.5
                        });
                
                        // Resize awal (kasih delay sedikit biar aman)
                        setTimeout(() => this.resizeCanvas(), 100);
                        setTimeout(() => this.resizeCanvas(), 500); // Double check
                        setTimeout(() => this.resizeCanvas(), 1000); // Triple check
                
                        // Resize saat layar diputar/diubah
                        window.addEventListener('resize', () => this.resizeCanvas());
                    },
                
                    // --- FUNGSI UTAMA: MEMASTIKAN UKURAN PAS ---
                    resizeCanvas() {
                        let canvas = this.$refs.canvas;
                        let container = this.$refs.container;
                
                        // Ambil ukuran kotak pembungkus yang sebenarnya di layar
                        let rect = container.getBoundingClientRect();
                
                        // Kalau ukuran belum terbaca (misal masih hidden), stop dulu
                        if (rect.width === 0 || rect.height === 0) return;
                
                        // Cek Rasio Layar (Retina Display support)
                        let ratio = Math.max(window.devicePixelRatio || 1, 1);
                
                        // Cek apakah ukuran internal SUDAH SAMA dengan ukuran tampilan?
                        // Kalau sudah sama, tidak perlu reset (biar tanda tangan gak hilang)
                        if (canvas.width === rect.width * ratio && canvas.height === rect.height * ratio) {
                            return;
                        }
                
                        // Simpan tanda tangan yang sudah ada (biar gak hilang saat resize)
                        let data = this.signaturePad ? this.signaturePad.toData() : null;
                
                        // SET UKURAN INTERNAL = UKURAN TAMPILAN
                        canvas.width = rect.width * ratio;
                        canvas.height = rect.height * ratio;
                
                        // Scale context biar gak pecah
                        let ctx = canvas.getContext('2d');
                        ctx.scale(ratio, ratio);
                
                        // Kembalikan data tanda tangan lama
                        this.signaturePad.clear();
                        if (data) {
                            this.signaturePad.fromData(data);
                        }
                    },
                
                    // --- TRIK BARU: RESIZE SAAT DISENTUH ---
                    // Ini menjamin ukuran pas sesaat sebelum user mulai nulis
                    checkSize(e) {
                        this.resizeCanvas();
                    },
                
                    clear() {
                        this.signaturePad.clear();
                        @this.set('ttd_satpam_base64', null);
                    },
                
                    save(methodName) {
                        if (this.signaturePad.isEmpty()) {
                            new Notification('Tanda tangan wajib diisi!').show();
                            return;
                        }
                        let dataUrl = this.signaturePad.toDataURL();
                        @this.set('ttd_satpam_base64', dataUrl);
                        @this.call(methodName);
                    }
                }" wire:key="signature-pad-{{ $selectedTamu->id }}">

                    <div class="mb-2">
                        <label class="text-sm font-semibold leading-6 text-gray-950 dark:text-white">
                            Silakan tanda tangan di kotak ini: <span class="text-red-500">*</span>
                        </label>
                    </div>

                    {{-- 
        PENTING: wire:ignore 
        Mencegah Livewire mereset canvas jadi kecil lagi
    --}}
                    <div wire:ignore x-ref="container" {{-- Event listener untuk memaksa resize saat disentuh/diklik --}} x-on:mousedown="checkSize"
                        x-on:touchstart.passive="checkSize" class="w-full rounded-md overflow-hidden relative"
                        style="height: 250px; border: 2px dashed #16a34a; background-color: #fff;">

                        <canvas x-ref="canvas" class="w-full h-full block cursor-crosshair"
                            style="touch-action: none; width: 100%; height: 100%;">
                        </canvas>

                    </div>

                    <div class="mt-3">
                        <button type="button" @click="clear()"
                            class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-semibold rounded shadow-sm transition-colors duration-200">
                            Hapus Tanda Tangan
                        </button>
                    </div>

                    <div class="grid grid-cols-2 gap-4 mt-8">
                        <x-filament::button color="success" size="lg" class="w-full shadow-lg"
                            @click="save('simpanValidasi')">
                            <span class="font-bold">✅ SETUJU & MASUK</span>
                        </x-filament::button>

                        <x-filament::button color="danger" size="lg" class="w-full shadow-lg"
                            @click="save('simpanTolakValidasi')">
                            <span class="font-bold">❌ TOLAK TAMU</span>
                        </x-filament::button>
                    </div>
                </div>
            </x-filament::section>
        </div>
    @endif

    {{-- SCRIPTS FOR SCANNER --}}
    @if (!$selectedTamu)
        <script>
            document.addEventListener('livewire:initialized', () => {
                let html5QrcodeScanner;

                function startScanner() {
                    if (!document.getElementById("reader")) return;
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
                        }
                    ).catch(err => console.log(err));
                }
                startScanner();

                // Event listener untuk reset form/scanner
                @this.on('form-reset', () => {
                    if (html5QrcodeScanner) html5QrcodeScanner.clear().then(startScanner).catch(startScanner);
                    else startScanner();
                });
            });
        </script>
    @endif
</x-filament-panels::page>
