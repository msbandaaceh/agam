<div class="page-wrapper">
    <div class="page-content">
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3"><?= $this->session->userdata('nama_client_app') ?></div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;" data-page="dashboard"><i
                                    class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Dokumentasi Teknis</li>
                    </ol>
                </nav>
            </div>
        </div>
        <h6 class="mb-0 text-uppercase">Dokumentasi Teknis Aplikasi</h6>
        <hr />

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <!-- Arsitektur & Teknologi -->
                        <div class="mb-4">
                            <h5 class="mb-3"><i class='bx bx-code-alt me-1'></i>Arsitektur & Teknologi</h5>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Komponen</th>
                                            <th>Teknologi</th>
                                            <th>Keterangan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><strong>Framework</strong></td>
                                            <td>CodeIgniter 3.x</td>
                                            <td>PHP Framework untuk backend</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Database</strong></td>
                                            <td>MySQL/MariaDB</td>
                                            <td>Database name: <code>rapat</code></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Frontend</strong></td>
                                            <td>Bootstrap 5, jQuery</td>
                                            <td>UI Framework dan JavaScript library</td>
                                        </tr>
                                        <tr>
                                            <td><strong>PDF Generator</strong></td>
                                            <td>dompdf</td>
                                            <td>Untuk generate dokumen PDF (undangan, notulen, presensi)</td>
                                        </tr>
                                        <tr>
                                            <td><strong>QR Code</strong></td>
                                            <td>endroid/qr-code</td>
                                            <td>Generate QR code untuk verifikasi dokumen</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Editor</strong></td>
                                            <td>CKEditor</td>
                                            <td>WYSIWYG editor untuk notulen</td>
                                        </tr>
                                        <tr>
                                            <td><strong>File Upload</strong></td>
                                            <td>Fancy File Uploader</td>
                                            <td>Upload dokumentasi rapat</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Struktur Direktori -->
                        <div class="mb-4">
                            <h5 class="mb-3"><i class='bx bx-folder me-1'></i>Struktur Direktori</h5>
                            <div class="card border">
                                <div class="card-body">
                                    <pre class="mb-0"><code>agam/
├── application/
│   ├── controllers/      # Controller files
│   │   ├── HalamanUtama.php
│   │   ├── HalamanLaporan.php
│   │   └── Api.php
│   ├── models/          # Model files
│   │   └── Model.php
│   ├── views/           # View files
│   │   ├── layout.php
│   │   ├── dashboard.php
│   │   ├── agenda_rapat.php
│   │   └── ...
│   ├── libraries/       # Custom libraries
│   │   ├── ApiHelper.php
│   │   ├── Pdf.php
│   │   └── TanggalHelper.php
│   └── config/          # Configuration files
│       ├── config.php
│       ├── database.php
│       └── routes.php
├── assets/              # Static assets (CSS, JS, images)
├── system/             # CodeIgniter core files
└── vendor/             # Composer dependencies</code></pre>
                                </div>
                            </div>
                        </div>

                        <!-- Struktur Database -->
                        <div class="mb-4">
                            <h5 class="mb-3"><i class='bx bx-data me-1'></i>Struktur Database</h5>
                            <div class="accordion" id="accordionDatabase">
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="dbHeading1">
                                        <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#dbCollapse1" aria-expanded="true">
                                            Tabel Utama
                                        </button>
                                    </h2>
                                    <div id="dbCollapse1" class="accordion-collapse collapse show"
                                        data-bs-parent="#accordionDatabase">
                                        <div class="accordion-body">
                                            <div class="table-responsive">
                                                <table class="table table-sm table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th>Tabel</th>
                                                            <th>Deskripsi</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td><code>register_rapat</code></td>
                                                            <td>Data agenda rapat (tanggal, waktu, tempat, agenda, dll)
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td><code>register_presensi_rapat</code></td>
                                                            <td>Data presensi pegawai pada rapat</td>
                                                        </tr>
                                                        <tr>
                                                            <td><code>dokumentasi_rapat</code></td>
                                                            <td>File dokumentasi/gambar rapat</td>
                                                        </tr>
                                                        <tr>
                                                            <td><code>peran</code></td>
                                                            <td>Peran pengguna (admin, operator, dll)</td>
                                                        </tr>
                                                        <tr>
                                                            <td><code>v_rapat</code></td>
                                                            <td>View gabungan data rapat dengan relasi</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="dbHeading2">
                                        <button class="accordion-button collapsed" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#dbCollapse2">
                                            Integrasi dengan SSO
                                        </button>
                                    </h2>
                                    <div id="dbCollapse2" class="accordion-collapse collapse"
                                        data-bs-parent="#accordionDatabase">
                                        <div class="accordion-body">
                                            <p>Aplikasi menggunakan database SSO untuk data pengguna. Tabel yang
                                                digunakan:</p>
                                            <ul>
                                                <li><code>v_users</code> - Data pengguna (dari database SSO)</li>
                                                <li><code>sys_users</code> - Data sistem pengguna (dari database SSO)
                                                </li>
                                                <li><code>ref_client_app</code> - Konfigurasi aplikasi client</li>
                                            </ul>
                                            <p><strong>Catatan:</strong> Database SSO diakses melalui API, bukan
                                                langsung.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Konfigurasi -->
                        <div class="mb-4">
                            <h5 class="mb-3"><i class='bx bx-cog me-1'></i>Konfigurasi Aplikasi</h5>
                            <div class="card border">
                                <div class="card-body">
                                    <h6 class="mb-3">File: <code>application/config/config.php</code></h6>
                                    <div class="table-responsive">
                                        <table class="table table-sm table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Parameter</th>
                                                    <th>Nilai</th>
                                                    <th>Keterangan</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td><code>base_url</code></td>
                                                    <td><code>http://SERVER_NAME/</code></td>
                                                    <td>Base URL aplikasi (auto-detect)</td>
                                                </tr>
                                                <tr>
                                                    <td><code>sso_server</code></td>
                                                    <td><code>http://sso.ms-bandaaceh.local/</code></td>
                                                    <td>URL server SSO</td>
                                                </tr>
                                                <tr>
                                                    <td><code>id_app</code></td>
                                                    <td><code>5</code></td>
                                                    <td>ID aplikasi di sistem SSO</td>
                                                </tr>
                                                <tr>
                                                    <td><code>encryption_key</code></td>
                                                    <td><code>M4hk4m4h@Bn4</code></td>
                                                    <td>Key untuk enkripsi data</td>
                                                </tr>
                                                <tr>
                                                    <td><code>api_key</code></td>
                                                    <td><code>M4hk4m4hBn4@2025</code></td>
                                                    <td>API key untuk autentikasi API</td>
                                                </tr>
                                                <tr>
                                                    <td><code>cookie_domain</code></td>
                                                    <td><code>.ms-bandaaceh.local</code></td>
                                                    <td>Domain untuk cookie sharing</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <h6 class="mb-3 mt-4">File: <code>application/config/database.php</code></h6>
                                    <div class="table-responsive">
                                        <table class="table table-sm table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Parameter</th>
                                                    <th>Nilai Default</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td><code>hostname</code></td>
                                                    <td><code>localhost</code></td>
                                                </tr>
                                                <tr>
                                                    <td><code>username</code></td>
                                                    <td><code>root</code></td>
                                                </tr>
                                                <tr>
                                                    <td><code>database</code></td>
                                                    <td><code>rapat</code></td>
                                                </tr>
                                                <tr>
                                                    <td><code>dbdriver</code></td>
                                                    <td><code>mysqli</code></td>
                                                </tr>
                                                <tr>
                                                    <td><code>char_set</code></td>
                                                    <td><code>utf8</code></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- API & Integrasi -->
                        <div class="mb-4">
                            <h5 class="mb-3"><i class='bx bx-plug me-1'></i>API & Integrasi</h5>
                            <div class="accordion" id="accordionAPI">
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="apiHeading1">
                                        <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#apiCollapse1" aria-expanded="true">
                                            ApiHelper Library
                                        </button>
                                    </h2>
                                    <div id="apiCollapse1" class="accordion-collapse collapse show"
                                        data-bs-parent="#accordionAPI">
                                        <div class="accordion-body">
                                            <p>Library untuk komunikasi dengan API SSO Server. Metode yang tersedia:</p>
                                            <ul>
                                                <li><strong><code>get($endpoint, $params)</code></strong> - GET request
                                                </li>
                                                <li><strong><code>post($endpoint, $payload)</code></strong> - POST
                                                    request</li>
                                                <li><strong><code>patch($endpoint, $payload)</code></strong> - PATCH
                                                    request</li>
                                            </ul>
                                            <p><strong>Contoh penggunaan:</strong></p>
                                            <pre class="bg-light p-3 rounded"><code>$params = [
    'tabel' => 'v_users',
    'kolom_seleksi' => 'userid',
    'seleksi' => 'USER123'
];
$result = $this->apihelper->get('apiclient/get_data_seleksi', $params);</code></pre>
                                        </div>
                                    </div>
                                </div>
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="apiHeading2">
                                        <button class="accordion-button collapsed" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#apiCollapse2">
                                            Endpoint API Internal
                                        </button>
                                    </h2>
                                    <div id="apiCollapse2" class="accordion-collapse collapse"
                                        data-bs-parent="#accordionAPI">
                                        <div class="accordion-body">
                                            <div class="table-responsive">
                                                <table class="table table-sm table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th>Endpoint</th>
                                                            <th>Method</th>
                                                            <th>Deskripsi</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td><code>api/reminder_rapat</code></td>
                                                            <td>GET</td>
                                                            <td>Mendapatkan daftar rapat 5 menit sebelum mulai (requires
                                                                api_key)</td>
                                                        </tr>
                                                        <tr>
                                                            <td><code>api/pembaharuan_data</code></td>
                                                            <td>POST</td>
                                                            <td>Update data melalui API (requires api_key)</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Sistem Keamanan -->
                        <div class="mb-4">
                            <h5 class="mb-3"><i class='bx bx-shield me-1'></i>Sistem Keamanan</h5>
                            <div class="card border">
                                <div class="card-body">
                                    <h6 class="mb-3">Fitur Keamanan yang Diterapkan:</h6>
                                    <ul>
                                        <li><strong>Autentikasi SSO:</strong> Login terintegrasi dengan sistem SSO</li>
                                        <li><strong>Role-Based Access Control (RBAC):</strong> Akses berdasarkan peran
                                            pengguna</li>
                                        <li><strong>Enkripsi Data:</strong> Menggunakan CodeIgniter Encryption untuk
                                            data sensitif</li>
                                        <li><strong>API Key Protection:</strong> Endpoint API memerlukan API key</li>
                                        <li><strong>Input Validation:</strong> Form validation menggunakan CodeIgniter
                                            Form Validation</li>
                                        <li><strong>Session Management:</strong> Session dengan timeout dan cookie
                                            security</li>
                                        <li><strong>Token Presensi:</strong> Validasi perangkat untuk presensi
                                            menggunakan token</li>
                                        <li><strong>Network Restriction:</strong> Presensi hanya bisa dilakukan dari
                                            jaringan WiFi kantor</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Sistem Peran -->
                        <div class="mb-4">
                            <h5 class="mb-3"><i class='bx bx-group me-1'></i>Sistem Peran (Role)</h5>
                            <div class="card border">
                                <div class="card-body">
                                    <h6 class="mb-3">Tingkat Akses:</h6>
                                    <div class="table-responsive">
                                        <table class="table table-sm table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Peran</th>
                                                    <th>Akses Menu</th>
                                                    <th>Fitur Khusus</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td><strong>Admin</strong></td>
                                                    <td>
                                                        <ul class="mb-0">
                                                            <li>Dashboard</li>
                                                            <li>Agenda Rapat</li>
                                                            <li>Presensi Rapat</li>
                                                            <li>Dokumen Rapat</li>
                                                            <li>Panduan</li>
                                                            <li>Dokumentasi Teknis</li>
                                                        </ul>
                                                    </td>
                                                    <td>
                                                        <ul class="mb-0">
                                                            <li>Mengelola peran pegawai</li>
                                                            <li>Presensi manual</li>
                                                            <li>Akses penuh semua fitur</li>
                                                        </ul>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Operator/Petugas</strong></td>
                                                    <td>
                                                        <ul class="mb-0">
                                                            <li>Dashboard</li>
                                                            <li>Agenda Rapat</li>
                                                            <li>Dokumen Rapat</li>
                                                            <li>Panduan</li>
                                                        </ul>
                                                    </td>
                                                    <td>
                                                        <ul class="mb-0">
                                                            <li>Menambah/edit agenda rapat</li>
                                                            <li>Mengisi notulen</li>
                                                            <li>Upload dokumentasi</li>
                                                        </ul>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Pegawai Biasa</strong></td>
                                                    <td>
                                                        <ul class="mb-0">
                                                            <li>Dashboard</li>
                                                            <li>Agenda Rapat (read-only)</li>
                                                            <li>Dokumen Rapat</li>
                                                            <li>Panduan</li>
                                                        </ul>
                                                    </td>
                                                    <td>
                                                        <ul class="mb-0">
                                                            <li>Presensi rapat</li>
                                                            <li>Melihat agenda rapat</li>
                                                            <li>Mengunduh dokumen</li>
                                                        </ul>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <p class="mt-3"><strong>Catatan:</strong> Peran ditentukan dari tabel
                                        <code>peran</code> atau dari role SSO (super, validator_kepeg_satker,
                                        admin_satker) yang otomatis menjadi admin.</p>
                                </div>
                            </div>
                        </div>

                        <!-- Fitur Presensi -->
                        <div class="mb-4">
                            <h5 class="mb-3"><i class='bx bx-check-square me-1'></i>Sistem Presensi</h5>
                            <div class="card border">
                                <div class="card-body">
                                    <h6 class="mb-3">Validasi Presensi:</h6>
                                    <ol>
                                        <li><strong>Perangkat:</strong> Harus menggunakan smartphone/handphone</li>
                                        <li><strong>Jaringan:</strong> Harus terhubung ke WiFi kantor (IP check)</li>
                                        <li><strong>Token Perangkat:</strong> Perangkat harus terdaftar dengan token
                                            unik</li>
                                        <li><strong>Status Login:</strong> Tidak bisa presensi jika login sebagai
                                            PLH/PLT</li>
                                    </ol>
                                    <h6 class="mb-3 mt-4">Proses Pendaftaran Perangkat:</h6>
                                    <ol>
                                        <li>User melakukan presensi pertama kali</li>
                                        <li>Sistem generate token unik (MD5 hash)</li>
                                        <li>Token disimpan di database SSO (<code>sys_users.token_pres</code>)</li>
                                        <li>Token disimpan di cookie (<code>presensi_token</code>) dengan expiry 1 tahun
                                        </li>
                                        <li>Presensi selanjutnya memvalidasi token dari session vs cookie</li>
                                    </ol>
                                </div>
                            </div>
                        </div>

                        <!-- Generate PDF -->
                        <div class="mb-4">
                            <h5 class="mb-3"><i class='bx bx-file-blank me-1'></i>Generate Dokumen PDF</h5>
                            <div class="card border">
                                <div class="card-body">
                                    <p>Sistem menggunakan library <code>dompdf</code> untuk generate PDF. Dokumen yang
                                        bisa di-generate:</p>
                                    <ul>
                                        <li><strong>Surat Undangan:</strong> Template undangan dengan QR code (jika opsi
                                            tanda tangan aktif)</li>
                                        <li><strong>Form Notula:</strong> Notulen rapat dengan dokumentasi dan QR code
                                            penandatangan & notulis</li>
                                        <li><strong>Daftar Presensi:</strong> Daftar hadir dengan QR code penandatangan
                                        </li>
                                    </ul>
                                    <p><strong>QR Code:</strong> Menggunakan library <code>endroid/qr-code</code>,
                                        berisi link ke profil pegawai di SSO server.</p>
                                    <p><strong>Template:</strong> File template berada di
                                        <code>application/views/template_*.php</code></p>
                                </div>
                            </div>
                        </div>

                        <!-- Troubleshooting -->
                        <div class="mb-4">
                            <h5 class="mb-3"><i class='bx bx-bug me-1'></i>Troubleshooting</h5>
                            <div class="accordion" id="accordionTroubleshoot">
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="troubleHeading1">
                                        <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#troubleCollapse1" aria-expanded="true">
                                            Masalah Presensi
                                        </button>
                                    </h2>
                                    <div id="troubleCollapse1" class="accordion-collapse collapse show"
                                        data-bs-parent="#accordionTroubleshoot">
                                        <div class="accordion-body">
                                            <ul>
                                                <li><strong>Presensi tidak bisa dilakukan:</strong>
                                                    <ul>
                                                        <li>Pastikan menggunakan handphone</li>
                                                        <li>Pastikan terhubung ke WiFi kantor</li>
                                                        <li>Clear browser cache dan cookies</li>
                                                        <li>Login ulang dengan akun sendiri (bukan PLH/PLT)</li>
                                                    </ul>
                                                </li>
                                                <li><strong>Error "Perangkat lain":</strong> User harus menggunakan
                                                    perangkat yang sama dengan yang terdaftar, atau admin bisa reset
                                                    token</li>
                                                <li><strong>Error "Jaringan lain":</strong> Pastikan IP address sesuai
                                                    dengan konfigurasi IP satker</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="troubleHeading2">
                                        <button class="accordion-button collapsed" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#troubleCollapse2">
                                            Masalah PDF Generation
                                        </button>
                                    </h2>
                                    <div id="troubleCollapse2" class="accordion-collapse collapse"
                                        data-bs-parent="#accordionTroubleshoot">
                                        <div class="accordion-body">
                                            <ul>
                                                <li><strong>PDF tidak muncul:</strong> Check error log di
                                                    <code>application/logs/</code></li>
                                                <li><strong>QR code tidak muncul:</strong> Pastikan logo_satker ada di
                                                    session dan path valid</li>
                                                <li><strong>Template tidak sesuai:</strong> Check file template di
                                                    <code>application/views/template_*.php</code></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="troubleHeading3">
                                        <button class="accordion-button collapsed" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#troubleCollapse3">
                                            Masalah Koneksi API
                                        </button>
                                    </h2>
                                    <div id="troubleCollapse3" class="accordion-collapse collapse"
                                        data-bs-parent="#accordionTroubleshoot">
                                        <div class="accordion-body">
                                            <ul>
                                                <li><strong>API tidak merespons:</strong> Check koneksi ke SSO server,
                                                    pastikan URL SSO server benar di config</li>
                                                <li><strong>Error 401/403:</strong> Check API key dan session SSO</li>
                                                <li><strong>Data tidak muncul:</strong> Check database connection dan
                                                    query</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Informasi Versi -->
                        <div class="mb-4">
                            <h5 class="mb-3"><i class='bx bx-info-circle me-1'></i>Informasi Versi</h5>
                            <div class="card border">
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-sm table-bordered">
                                            <tbody>
                                                <tr>
                                                    <td><strong>Nama Aplikasi</strong></td>
                                                    <td>Aplikasi Manajemen Rapat Pegawai</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Versi</strong></td>
                                                    <td>1.0.0</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Framework</strong></td>
                                                    <td>CodeIgniter 3.x</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>PHP Version</strong></td>
                                                    <td>Minimal PHP 7.2+</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Database</strong></td>
                                                    <td>MySQL 5.7+ / MariaDB 10.2+</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>