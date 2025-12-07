<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Buku Tamu</title>
    <link rel="icon" href="{{ asset('images/logo-kunjunganku.png') }}" type="image/png">
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>

    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            padding: 20px;
        }

        .container {
            max-width: 800px;
            margin: auto;
            background: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
        }

        fieldset {
            border: 1px solid #ccc;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 6px;
        }

        legend {
            font-weight: bold;
            color: #555;
            padding: 0 10px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .form-group input[type="text"],
        .form-group input[type="date"],
        .form-group input[type="datetime-local"],
        .form-group input[type="number"],
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }

        .form-group textarea {
            resize: vertical;
        }

        .submit-button {
            display: block;
            width: 100%;
            padding: 10px;
            background-color: #00620c;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
        }

        .submit-button:hover {
            background-color: #008a12;
        }

        /* Kartu canvas (hidden) */
        #cardCanvas {
            display: none;
        }
    </style>
</head>

<body>
    <div class="container">

        {{-- <h2>📝 Form Buku Tamu</h2> --}}
        {{-- <h2>Buku Tamu <br>Mitratani Dua Tujuh</h2> --}}

        {{-- <div
            style="display: flex; align-items: center; justify-content: center; gap: 15px; margin-bottom: 20px; text-align: center;">
            <img src="{{ asset('images/logo-company.png') }}" alt="Logo" style="width: 60px; height: auto;">

            <h2 style="margin: 0; line-height: 1.2;">
                Buku Tamu <br>Mitratani Dua Tujuh
            </h2>
        </div> --}}

        <div style="display: flex; align-items: center;justify-content: space-between; margin-bottom: 25px;">

            <!-- Logo Danantara (KIRI) -->
            <img src="{{ asset('images/logo-danantara.png') }}" alt="Logo Danantara"
                style="width: 125px; height: auto;">

            <!-- Judul (TENGAH) -->
            <h2 style="margin: 0; text-align: center; flex: 1;">
                Buku Tamu Digital <br> PT Mitratani Dua Tujuh
            </h2>

            <!-- Logo Company (KANAN) -->
            <img src="{{ asset('images/logo-company.png') }}" alt="Logo Company" style="width: 120px; height: auto;">
        </div>




        {{-- Notifikasi sukses kustom --}}
        @if (session('success'))
            <div id="custom-success-notification" class="custom-notification show">
                {{ session('success') }}
            </div>
        @endif

        <style>
            /* Gaya CSS Kustom */
            .custom-notification {
                padding: 15px 20px;
                background: #187e03;
                color: white;
                border-radius: 8px;
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
                position: fixed;
                top: 20px;
                right: 20px;
                z-index: 1000;
                opacity: 0;
                transition: opacity 0.5s ease-in-out, transform 0.5s ease-in-out;
                transform: translateY(-20px);
            }

            .custom-notification.show {
                opacity: 1;
                transform: translateY(0);
            }
        </style>

        <script>
            // Ambil elemen notifikasi (jika ada)
            const customAlert = document.getElementById('custom-success-notification');

            if (customAlert) {
                setTimeout(() => {
                    customAlert.classList.remove('show');
                    setTimeout(() => {
                        customAlert.remove();
                    }, 500);
                }, 5000);
            }
        </script>


        {{-- Form --}}
        <form action="{{ route('bukutamu.store') }}" method="POST">
            @csrf

            {{-- INFORMASI KUNJUNGAN --}}
            <fieldset>
                <legend>Informasi Kunjungan</legend>

                <div class="form-group">
                    <label>Tanggal:</label>
                    <input type="date" name="tanggal" value="{{ date('Y-m-d') }}" required readonly>
                </div>

                <div class="form-group">
                    <label>Divisi yang Tujuan:</label>
                    <select name="divisi" required>
                        <option value="">-- Pilih Divisi --</option>
                        @foreach (\App\Models\Divisi::all() as $d)
                            <option value="{{ $d->id }}">{{ $d->nama_divisi }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>Penerima Tamu:</label>
                    <input type="text" name="penerima_tamu" placeholder="Nama petugas yang menerima" required>
                </div>

                <div class="form-group">
                    <label>Keperluan:</label>
                    <textarea name="keperluan" rows="3" required></textarea>
                </div>

                <div class="form-group">
                    <label>Nopol Kendaraan:</label>
                    <input type="text" name="nopol_kendaraan" placeholder="Contoh: B 1234 ABC" required>
                </div>
            </fieldset>

            {{-- TAMU UTAMA --}}
            <fieldset>
                <legend>Data Tamu Utama</legend>

                <div class="form-group">
                    <label>Nama Tamu Utama:</label>
                    <input type="text" name="nama" required>
                </div>

                <div class="form-group">
                    <label>Jabatan:</label>
                    <input type="text" name="jabatan" required>
                </div>

                <div class="form-group">
                    <label>No HP:</label>
                    <input type="number" name="no_hp" required>
                </div>

                <div class="form-group">
                    <label>Nama Perusahaan:</label>
                    <input type="text" name="instansi" required>
                </div>

                <div class="form-group">
                    <label>Bidang Usaha:</label>
                    <input type="text" name="bidang_usaha" required>
                </div>

                <div class="form-group">
                    <label>Status:</label>
                    <select name="status" required>
                        <option value="">-- Pilih Status --</option>
                        @foreach (\App\Models\Status::all() as $d)
                            <option value="{{ $d->id }}">{{ $d->nama_status }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>Jumlah Tamu Total (Termasuk Utama):</label>
                    {{-- Tambahkan ID di sini untuk dipanggil JS --}}
                    <input type="number" id="jumlah_tamu" name="jumlah_tamu" min="1" value="1" required>
                </div>
            </fieldset>

            {{-- PENGIRING (DINAMIS) --}}
            <fieldset id="fieldset-pengiring" style="display: none;">
                <legend>Data Pengiring</legend>
                <div id="container-pengiring"></div>
            </fieldset>

            {{-- PERSETUJUAN --}}
            <fieldset>
                <legend>Persetujuan Tamu</legend>

                <div style="margin-bottom: 10px;">
                    <label style="font-weight: bold; cursor:pointer;">
                        <input type="checkbox" id="cek_persetujuan" style="transform: scale(1.3); margin-right: 8px;"
                            required>
                        Saya telah membaca dan menyetujui peraturan tamu
                    </label>
                </div>
            </fieldset>

            <!-- POPUP -->
            <div id="popup-rules"
                style="display:none; position:fixed; top:0; left:0; width:100%; height:100%;
            background:rgba(0,0,0,0.6); z-index:9999;">

                <div
                    style="background:white; width:90%; max-width:650px; margin:8% auto; padding:25px; border-radius:8px;">
                    {{-- <h3 style="margin-top:0;">Peraturan Tamu Perusahaan</h3>

                    <ol style="margin-left:20px; font-size:15px;">
                        <li>Dilarang membawa barang berbahaya.</li>
                        <li>Wajib menjaga ketertiban dan kebersihan area perusahaan.</li>
                        <li>Wajib mengikuti instruksi keamanan dari petugas.</li>
                        <li>Dilarang mengambil foto/video tanpa izin.</li>
                    </ol> --}}

                    <h3 style="margin-top:0;">Peraturan Tamu Perusahaan</h3>

                    <ol style="margin-left:20px; font-size:15px;">
                        <li>Dilarang memasuki area yang teridentifikasi area terbatas tanpa pendamping. Jika
                            diperlukan
                            hubungi petugas.</li>
                        <li>Semua tamu dimohon untuk memakai Visitor ID yang telah disediakan.</li>
                        <li>Membuang sampah & puntung rokok pada tempatnya, dan tidak diperkenankan meludah
                            sembarangan.
                        </li>
                        <li>Merokok hanya diperbolehkan di area khusus merokok (smoking area).</li>
                        <li>Tidak diperkenankan makan, minum dan atau membawa makanan, minuman yang memungkinkan
                            mengandung allergen ke dalam ruang proses.</li>
                        <li>Mencuci tangan sebelum masuk ruang proses.</li>
                        <li>Gunakan pakaian (khusus) sesuai dengan yang dipersyaratkan jika memasuki area tertentu
                            (dalam ruang proses/pengolahan), dan dilarang mengenakannya di area luar proses.</li>
                        <li>Tidak diperkenankan mengambil dokumentasi & membawa ponsel, kamera, serta alat komunikasi
                            lain di area proses tanpa izin (Pabrik/pengolahan, gudang, utilitas).</li>
                        <li>Dilarang membawa produk jadi dan barang-barang lain keluar area tanpa seijin dari
                            manajemen
                            PT Mitratani Dua Tujuh.</li>
                        <li>Semua tamu diharap menjaga sarana dan prasarana di area PT. Mitratani Dua Tujuh.</li>
                        <li>Dilarang memberikan tip ke petugas karyawan Mitratani Dua Tujuh.</li>
                        <li>Tamu/Visitor wajib melapor dan menyerahkan ID Card, Form Visitor sebelum meninggalkan
                            area
                            PT Mitratani Dua Tujuh.</li>
                        <li>Bila melihat keadaan darurat mohon hubungi pihak berwenang (security, pihak terkait).
                        </li>
                        <li>Berpakaian rapi dan sopan (tidak diperkenankan memakai celana pendek dan singlet).</li>
                    </ol>

                    <button id="btn-setuju"
                        style="margin-top:20px; width:100%; padding:10px; background:#00620c; 
                       color:white; border:none; border-radius:5px; cursor:pointer;">
                        Setuju & Lanjutkan
                    </button>
                </div>
            </div>

            {{-- AREA TANDA TANGAN --}}
            <fieldset>
                <legend>Tanda Tangan Tamu</legend>
                <div class="form-group">
                    <p style="font-size: 0.9em; color: #666;">Silakan tanda tangan di kotak ini:</p>

                    <div style="border: 2px dashed #00620c; background: #fff; border-radius: 5px;">
                        <canvas id="signature-canvas" style="width: 100%; height: 200px; display: block;"></canvas>
                    </div>

                    <button type="button" id="clear-signature"
                        style="margin-top: 10px; background: #d9534f; color: white; border: none; padding: 5px 10px; border-radius: 3px; cursor: pointer;">Hapus
                        Tanda Tangan</button>

                    <input type="hidden" name="ttd_tamu_base64" id="ttd_tamu_base64">
                </div>
            </fieldset>

            <button type="submit" class="submit-button">Simpan Data Tamu</button>
        </form>

        <div style="text-align: center; margin-top: 20px;">
            <a href="/admin/login" style="color: #333; text-decoration: none; font-weight: 600; font-size: 14px;">
                Apakah anda karyawan ? Login
            </a>
        </div>


    </div>

    {{-- Container Rahasia untuk generate QR Code (Tidak perlu ditampilkan ke user) --}}
    <div id="qrcode-container" style="display:none;"></div>

    <!-- Canvas tempat desain kartu (hidden) -->
    <canvas id="cardCanvas" width="800" height="1200" style="display:none;"></canvas>

    <style>
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: scale(0.95);
            }

            to {
                opacity: 1;
                transform: scale(1);
            }
        }
    </style>

    <script>
        /**
         * generateDesignedCard(qrData, qrCodeText, namaTamu)
         * - qrData: base64 image data (data:image/png;base64,...)
         * - qrCodeText: string yang ditampilkan di bawah QR
         * - namaTamu: nama tamu yang akan ditampilkan
         *
         * Fungsi ini menggambar kartu di canvas #cardCanvas dan otomatis mendownloadnya.
         */
        function generateDesignedCard(qrData, qrCodeText, namaTamu) {
            try {
                const canvas = document.getElementById("cardCanvas");
                const ctx = canvas.getContext("2d");

                // Clear
                ctx.clearRect(0, 0, canvas.width, canvas.height);

                // ==== BACKGROUND PUTIH ====
                ctx.fillStyle = "#ffffff";
                ctx.fillRect(0, 0, canvas.width, canvas.height);

                // ==== HEADER HIJAU ====
                ctx.fillStyle = "#0FA958";
                ctx.fillRect(0, 0, canvas.width, 200);

                // ==== LOGO KIRI (opsional) - jika Anda mau menambahkan logo, bisa dimuat di sini ====
                // contoh (jika ada logo di public/images/logo.png): 
                // let logo = new Image(); logo.src = "/images/logo.png"; logo.onload = () => { ctx.drawImage(logo, 30, 30, 150, 140); }

                // ==== TULISAN MITRATANI DUA TUJUH ====
                ctx.fillStyle = "#ffffff";
                ctx.font = "bold 36px Arial";
                ctx.textAlign = "center";
                ctx.fillText("PT MITRATANI DUA TUJUH", canvas.width / 2, 125);

                // Tarik QR sebagai gambar
                let qrImg = new Image();
                qrImg.crossOrigin = "anonymous";
                qrImg.src = qrData;

                qrImg.onload = function() {
                    // Gambar QR di tengah
                    const qrSize = 500;
                    const qrX = (canvas.width - qrSize) / 2;
                    const qrY = 260;
                    ctx.drawImage(qrImg, qrX, qrY, qrSize, qrSize);

                    // ==== TEKS KODE QR ====
                    ctx.fillStyle = "#000";
                    ctx.font = "bold 28px Arial";
                    ctx.textAlign = "center";
                    ctx.fillText(qrCodeText, canvas.width / 2, qrY + qrSize + 40);

                    // ==== NAMA TAMU ====
                    ctx.fillStyle = "#333";
                    ctx.font = "bold 36px Arial";
                    ctx.fillText(namaTamu, canvas.width / 2, qrY + qrSize + 95);

                    // ==== FOOTER DOUBLE WAVE ESTETIK ====

                    // Wave 1 (gelombang utama - hijau gelap)
                    ctx.fillStyle = "#0FA958";
                    ctx.beginPath();
                    ctx.moveTo(0, canvas.height - 170);
                    ctx.bezierCurveTo(
                        canvas.width * 0.30, canvas.height - 80,
                        canvas.width * 0.70, canvas.height - 260,
                        canvas.width, canvas.height - 170
                    );
                    ctx.lineTo(canvas.width, canvas.height);
                    ctx.lineTo(0, canvas.height);
                    ctx.closePath();
                    ctx.fill();

                    // Wave 2 (gelombang kedua - hijau lebih cerah)
                    ctx.fillStyle = "#12C46B";
                    ctx.beginPath();
                    ctx.moveTo(0, canvas.height - 120);
                    ctx.bezierCurveTo(
                        canvas.width * 0.35, canvas.height - 30,
                        canvas.width * 0.65, canvas.height - 210,
                        canvas.width, canvas.height - 120
                    );
                    ctx.lineTo(canvas.width, canvas.height);
                    ctx.lineTo(0, canvas.height);
                    ctx.closePath();
                    ctx.fill();

                    // ==== TEKS FOOTER ====
                    ctx.fillStyle = "#000000";
                    ctx.font = "bold 27px Arial";

                    ctx.textAlign = "center";
                    ctx.fillText(
                        "Terima kasih telah berkunjung — PT. Mitratani Dua Tujuh",
                        canvas.width / 2,
                        canvas.height - 45
                    );


                    // ==== DOWNLOAD OTOMATIS KARTU ====
                    try {
                        const cardURL = canvas.toDataURL("image/png");
                        const a = document.createElement("a");
                        a.href = cardURL;
                        // aman menggunakan qrCodeText (jika ada karakter aneh, replace spasi dengan underscore)
                        const safeName = String(qrCodeText || "tamu").replace(/\s+/g, "_");
                        a.download = "KARTU_TAMU_" + safeName + ".png";
                        document.body.appendChild(a);
                        a.click();
                        document.body.removeChild(a);
                    } catch (err) {
                        console.error("Gagal download kartu:", err);
                    }
                };

                qrImg.onerror = function(e) {
                    console.error("Gagal memuat gambar QR untuk kartu:", e);
                };
            } catch (err) {
                console.error("generateDesignedCard error:", err);
            }
        }
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // --- 1. LOGIKA AUTO-DOWNLOAD QR CODE & GENERATE KARTU (BARU) ---
            @if (session('new_qr_code'))
                (function() {
                    const codeString = "{{ session('new_qr_code') }}";
                    console.log("Mendownload QR untuk: " + codeString);

                    // 1. Temp container (qrcode-container) sudah ada di DOM
                    const qrDiv = document.getElementById('qrcode-container');

                    // 2. Generate QR Code menggunakan qrcode.js
                    const qrCode = new QRCode(qrDiv, {
                        text: codeString,
                        width: 256,
                        height: 256,
                        colorDark: "#000000",
                        colorLight: "#ffffff",
                        correctLevel: QRCode.CorrectLevel.H
                    });

                    // 3. Tunggu sebentar agar library selesai me-render.
                    // Lebih andal: cek apakah ada elemen <img> lalu tunggu load event atau gunakan interval kecil.
                    const tryFetchImg = () => {
                        const img = qrDiv.querySelector('img');

                        if (img && img.src) {
                            // // 3.a Download QR biasa
                            // try {
                            //     const link = document.createElement('a');
                            //     link.href = img.src;
                            //     link.download = 'QR_TAMU_' + codeString + '.png';
                            //     document.body.appendChild(link);
                            //     link.click();
                            //     document.body.removeChild(link);
                            // } catch (e) {
                            //     console.error("Gagal auto-download QR:", e);
                            // }

                            // 3.b Generate kartu desain (jika ingin gunakan nama tamu, ambil dari session/old)
                            const namaTamu = "{{ session('nama_tamu') ?? (old('nama') ?? 'Tamu') }}";

                            // Jika gambar sudah complete, panggil langsung; bila belum, tunggu onload.
                            if (img.complete) {
                                generateDesignedCard(img.src, codeString, namaTamu);
                            } else {
                                img.onload = function() {
                                    generateDesignedCard(img.src, codeString, namaTamu);
                                };
                            }

                            return true;
                        }
                        return false;
                    };

                    // Coba berkali (maks 20x) setiap 200ms sampai ketemu gambar (atau timeout)
                    let attempts = 0;
                    const intervalId = setInterval(() => {
                        attempts++;
                        if (tryFetchImg() || attempts > 20) {
                            clearInterval(intervalId);
                            if (attempts > 20) {
                                // fallback: coba sekali pakai toDataURL dari canvas QR (jika qrcode.js membuat canvas)
                                const canvasInside = qrDiv.querySelector('canvas');
                                if (canvasInside) {
                                    try {
                                        const dataURL = canvasInside.toDataURL('image/png');
                                        // download
                                        const link = document.createElement('a');
                                        link.href = dataURL;
                                        link.download = 'QR_TAMU_' + codeString + '.png';
                                        document.body.appendChild(link);
                                        link.click();
                                        document.body.removeChild(link);

                                        // generate card
                                        const namaTamu =
                                            "{{ session('nama_tamu') ?? (old('nama') ?? 'Tamu') }}";
                                        generateDesignedCard(dataURL, codeString, namaTamu);
                                    } catch (err) {
                                        console.error("Fallback: gagal ambil canvas QR:", err);
                                    }
                                } else {
                                    console.warn(
                                        "Tidak dapat menemukan elemen img atau canvas QR setelah beberapa percobaan."
                                    );
                                }
                            }
                        }
                    }, 200);
                })();
            @endif

            // --- 2. NOTIFIKASI (KODE LAMA ANDA) ---
            const customAlert2 = document.getElementById('custom-success-notification');
            if (customAlert2) {
                setTimeout(() => {
                    customAlert2.classList.remove('show');
                    setTimeout(() => {
                        customAlert2.remove();
                    }, 500);
                }, 5000);
            }

            // --- 3. PENGIRING (KODE LAMA ANDA) ---
            const inputJumlah = document.getElementById('jumlah_tamu');
            const fieldsetPengiring = document.getElementById('fieldset-pengiring');
            const containerPengiring = document.getElementById('container-pengiring');

            function generatePengiringForms() {
                const totalTamu = parseInt(inputJumlah.value) || 1;
                const jumlahPengiring = totalTamu - 1;
                containerPengiring.innerHTML = '';

                if (jumlahPengiring > 0) {
                    fieldsetPengiring.style.display = 'block';
                    for (let i = 1; i <= jumlahPengiring; i++) {
                        const html = `
                        <div class="pengiring-item" style="margin-bottom: 20px; border-bottom: 1px dashed #ccc; padding-bottom: 15px;">
                            <p style="font-weight:bold; margin-bottom:10px; color:#00620c;"># Pengiring ${i}</p>
                            <div class="form-group">
                                <label>Nama Pengiring:</label>
                                <input type="text" name="nama_pengiring[]" placeholder="Nama Pengiring ke-${i}" required>
                            </div>
                            <div class="form-group">
                                <label>Jabatan Pengiring:</label>
                                <input type="text" name="jabatan_pengiring[]" placeholder="Jabatan Pengiring ke-${i}">
                            </div>
                        </div>`;
                        containerPengiring.insertAdjacentHTML('beforeend', html);
                    }
                } else {
                    fieldsetPengiring.style.display = 'none';
                }
            }
            inputJumlah.addEventListener('input', generatePengiringForms);
            generatePengiringForms();

            // --- 4. TANDA TANGAN (KODE LAMA ANDA) ---
            const canvas = document.getElementById('signature-canvas');
            const signaturePad = new SignaturePad(canvas, {
                backgroundColor: 'rgba(255, 255, 255, 0)',
                penColor: 'rgb(0, 0, 0)'
            });

            function resizeCanvas() {
                const ratio = Math.max(window.devicePixelRatio || 1, 1);
                canvas.width = canvas.offsetWidth * ratio;
                canvas.height = canvas.offsetHeight * ratio;
                canvas.getContext("2d").scale(ratio, ratio);
            }
            window.addEventListener("resize", resizeCanvas);
            resizeCanvas();

            document.getElementById('clear-signature').addEventListener('click', function() {
                signaturePad.clear();
            });

            document.querySelector('form').addEventListener('submit', function(e) {
                if (!signaturePad.isEmpty()) {
                    const dataURL = signaturePad.toDataURL('image/png');
                    document.getElementById('ttd_tamu_base64').value = dataURL;
                }
            });

            // === POPUP PERATURAN TAMU ===
            const checkbox = document.getElementById('cek_persetujuan');
            const popup = document.getElementById('popup-rules');
            const btnSetuju = document.getElementById('btn-setuju');

            // Saat checkbox disentuh
            checkbox.addEventListener('click', function(e) {
                if (!checkbox.checked) {
                    e.preventDefault(); // jangan centang
                    popup.style.display = "block"; // tampilkan popup
                }
            });

            // Ketika tombol SETUJU ditekan
            btnSetuju.addEventListener('click', function(e) {
                e.preventDefault(); // cegah submit FORM
                popup.style.display = "none";
                checkbox.checked = true; // centang otomatis
            });


        });
    </script>

    <footer class="footer-copyright"
        style="text-align:center; margin-top: 40px; padding: 20px 10px; background:#f7f7f7; color:#444; border-top:1px solid #ddd;">
        <p style="font-size: 14px; margin:0;">
            © 2025 PT Mitratani Dua Tujuh. Dikelola oleh Teknologi Informasi.<br>
            Semua Hak Cipta Dilindungi Undang-Undang.
        </p>
    </footer>

</body>

</html>
