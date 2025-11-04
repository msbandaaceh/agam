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
                        <li class="breadcrumb-item active" aria-current="page">Panduan Penggunaan</li>
                    </ol>
                </nav>
            </div>
        </div>
        <h6 class="mb-0 text-uppercase">Panduan Penggunaan Aplikasi</h6>
        <hr />

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <!-- Nav tabs -->
                        <ul class="nav nav-tabs nav-primary" role="tablist">
                            <li class="nav-item" role="presentation">
                                <a class="nav-link active" data-bs-toggle="tab" href="#admin" role="tab"
                                    aria-selected="true">
                                    <div class="d-flex align-items-center">
                                        <div class="tab-icon"><i class='bx bx-user-check font-18 me-1'></i>
                                        </div>
                                        <div class="tab-title">Admin</div>
                                    </div>
                                </a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" data-bs-toggle="tab" href="#operator" role="tab"
                                    aria-selected="false">
                                    <div class="d-flex align-items-center">
                                        <div class="tab-icon"><i class='bx bx-user font-18 me-1'></i>
                                        </div>
                                        <div class="tab-title">Operator/Petugas</div>
                                    </div>
                                </a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" data-bs-toggle="tab" href="#pegawai" role="tab"
                                    aria-selected="false">
                                    <div class="d-flex align-items-center">
                                        <div class="tab-icon"><i class='bx bx-user-circle font-18 me-1'></i>
                                        </div>
                                        <div class="tab-title">Pegawai Biasa</div>
                                    </div>
                                </a>
                            </li>
                        </ul>
                        <div class="tab-content py-3">
                            <!-- Tab Admin -->
                            <div class="tab-pane fade show active" id="admin" role="tabpanel">
                                <h5 class="mb-3"><i class='bx bx-user-check me-1'></i>Panduan untuk Administrator</h5>
                                <div class="accordion" id="accordionAdmin">
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="adminHeading1">
                                            <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                                data-bs-target="#adminCollapse1" aria-expanded="true">
                                                1. Mengelola Agenda Rapat
                                            </button>
                                        </h2>
                                        <div id="adminCollapse1" class="accordion-collapse collapse show"
                                            data-bs-parent="#accordionAdmin">
                                            <div class="accordion-body">
                                                <p><strong>Cara Menambah Agenda Rapat:</strong></p>
                                                <ol>
                                                    <li>Klik menu <strong>Agenda Rapat</strong> di sidebar</li>
                                                    <li>Klik tombol <strong>"Tambah Agenda Rapat"</strong> di pojok kanan atas</li>
                                                    <li>Isi form dengan data berikut:
                                                        <ul>
                                                            <li><strong>Tanggal Rapat:</strong> Pilih tanggal rapat</li>
                                                            <li><strong>Nomor Surat:</strong> (Opsional) Masukkan nomor surat undangan</li>
                                                            <li><strong>Penandatangan/Pengundang:</strong> Pilih pejabat yang menandatangani undangan</li>
                                                            <li><strong>Tempat/Lokasi:</strong> Masukkan lokasi rapat</li>
                                                            <li><strong>Jam Mulai & Selesai:</strong> Tentukan waktu rapat</li>
                                                            <li><strong>Agenda Rapat:</strong> Tuliskan agenda rapat</li>
                                                            <li><strong>Peserta Rapat:</strong> Tuliskan daftar peserta</li>
                                                            <li><strong>Notulis:</strong> (Opsional) Pilih notulis rapat</li>
                                                            <li><strong>Dokumenter:</strong> (Opsional) Pilih pegawai yang bertugas dokumentasi</li>
                                                        </ul>
                                                    </li>
                                                    <li>Klik tombol <strong>"Simpan"</strong></li>
                                                </ol>
                                                <p><strong>Cara Mengedit Agenda Rapat:</strong></p>
                                                <ol>
                                                    <li>Pada tabel Agenda Rapat, klik tombol <strong>"Edit"</strong> pada baris yang ingin diedit</li>
                                                    <li>Klik tombol <strong>"Edit"</strong> di modal untuk mengaktifkan mode edit</li>
                                                    <li>Ubah data yang diperlukan</li>
                                                    <li>Klik tombol <strong>"Simpan"</strong></li>
                                                </ol>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="adminHeading2">
                                            <button class="accordion-button collapsed" type="button"
                                                data-bs-toggle="collapse" data-bs-target="#adminCollapse2">
                                                2. Mengelola Presensi Rapat
                                            </button>
                                        </h2>
                                        <div id="adminCollapse2" class="accordion-collapse collapse"
                                            data-bs-parent="#accordionAdmin">
                                            <div class="accordion-body">
                                                <p><strong>Melihat Daftar Presensi:</strong></p>
                                                <ol>
                                                    <li>Klik menu <strong>Presensi Rapat</strong> di sidebar</li>
                                                    <li>Anda akan melihat tabel daftar rapat dengan jumlah peserta</li>
                                                    <li>Klik tombol <strong>"Detail"</strong> untuk melihat daftar presensi per rapat</li>
                                                </ol>
                                                <p><strong>Menambah Presensi Manual:</strong></p>
                                                <ol>
                                                    <li>Pada halaman Presensi Rapat, klik <strong>"Detail"</strong> pada rapat yang diinginkan</li>
                                                    <li>Klik tombol <strong>"Tambah Presensi"</strong></li>
                                                    <li>Pilih pegawai yang akan ditambahkan presensinya</li>
                                                    <li>Pilih atau isi waktu presensi</li>
                                                    <li>Klik tombol <strong>"Simpan"</strong></li>
                                                </ol>
                                                <p><strong>Menghapus Presensi:</strong></p>
                                                <ol>
                                                    <li>Pada halaman detail presensi, klik tombol <strong>"Hapus"</strong> pada baris presensi yang ingin dihapus</li>
                                                    <li>Konfirmasi penghapusan</li>
                                                </ol>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="adminHeading3">
                                            <button class="accordion-button collapsed" type="button"
                                                data-bs-toggle="collapse" data-bs-target="#adminCollapse3">
                                                3. Mengelola Dokumen Rapat
                                            </button>
                                        </h2>
                                        <div id="adminCollapse3" class="accordion-collapse collapse"
                                            data-bs-parent="#accordionAdmin">
                                            <div class="accordion-body">
                                                <p><strong>Mengunduh Dokumen Rapat:</strong></p>
                                                <ol>
                                                    <li>Klik menu <strong>Dokumen Rapat</strong> di sidebar</li>
                                                    <li>Pada tabel, klik tombol <strong>"Unduh Dokumen"</strong> pada rapat yang diinginkan</li>
                                                    <li>Pilih jenis dokumen:
                                                        <ul>
                                                            <li><strong>Surat Undangan:</strong> Dokumen undangan rapat</li>
                                                            <li><strong>Form Notula:</strong> Dokumen notulen rapat (harus diisi notulen terlebih dahulu)</li>
                                                            <li><strong>Daftar Presensi:</strong> Daftar hadir rapat</li>
                                                        </ul>
                                                    </li>
                                                    <li>Pilih opsi tanda tangan (Ya/Tidak)</li>
                                                    <li>Untuk Surat Undangan, pilih sifat undangan dan lampiran (jika ada)</li>
                                                    <li>Klik tombol <strong>"Unduh"</strong></li>
                                                </ol>
                                                <p><strong>Mengisi Notulen Rapat:</strong></p>
                                                <ol>
                                                    <li>Pada halaman Agenda Rapat, klik tombol <strong>"Notulen"</strong> pada rapat yang diinginkan</li>
                                                    <li>Isi notulen rapat menggunakan editor yang tersedia</li>
                                                    <li>Klik tombol <strong>"Simpan"</strong></li>
                                                </ol>
                                                <p><strong>Mengunggah Dokumentasi Rapat:</strong></p>
                                                <ol>
                                                    <li>Pada halaman Agenda Rapat, klik tombol <strong>"Dokumentasi"</strong> pada rapat yang diinginkan</li>
                                                    <li>Klik area upload atau drag & drop file gambar</li>
                                                    <li>Pilih file gambar (format: JPG, PNG)</li>
                                                    <li>File akan otomatis terunggah</li>
                                                </ol>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="adminHeading4">
                                            <button class="accordion-button collapsed" type="button"
                                                data-bs-toggle="collapse" data-bs-target="#adminCollapse4">
                                                4. Mengelola Peran Pegawai
                                            </button>
                                        </h2>
                                        <div id="adminCollapse4" class="accordion-collapse collapse"
                                            data-bs-parent="#accordionAdmin">
                                            <div class="accordion-body">
                                                <p><strong>Menetapkan Peran Pegawai:</strong></p>
                                                <ol>
                                                    <li>Klik ikon <strong>"Setting"</strong> (ikon kategori) di header</li>
                                                    <li>Pilih menu <strong>"Peran"</strong></li>
                                                    <li>Klik tombol <strong>"Tambah Peran"</strong> (jika ada) atau klik ikon edit pada baris yang ingin diubah</li>
                                                    <li>Pilih pegawai dari dropdown</li>
                                                    <li>Pilih peran yang akan diberikan (Admin, Operator, dll)</li>
                                                    <li>Klik tombol <strong>"Simpan"</strong></li>
                                                </ol>
                                                <p><strong>Mengaktifkan/Menonaktifkan Peran:</strong></p>
                                                <ol>
                                                    <li>Pada tabel peran, klik tombol <strong>"Aktif"</strong> atau <strong>"Blokir"</strong> sesuai kebutuhan</li>
                                                </ol>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Tab Operator -->
                            <div class="tab-pane fade" id="operator" role="tabpanel">
                                <h5 class="mb-3"><i class='bx bx-user me-1'></i>Panduan untuk Operator/Petugas</h5>
                                <div class="accordion" id="accordionOperator">
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="operatorHeading1">
                                            <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                                data-bs-target="#operatorCollapse1" aria-expanded="true">
                                                1. Mengelola Agenda Rapat
                                            </button>
                                        </h2>
                                        <div id="operatorCollapse1" class="accordion-collapse collapse show"
                                            data-bs-parent="#accordionOperator">
                                            <div class="accordion-body">
                                                <p><strong>Cara Menambah Agenda Rapat:</strong></p>
                                                <ol>
                                                    <li>Klik menu <strong>Agenda Rapat</strong> di sidebar</li>
                                                    <li>Klik tombol <strong>"Tambah Agenda Rapat"</strong> di pojok kanan atas</li>
                                                    <li>Isi semua field yang wajib diisi:
                                                        <ul>
                                                            <li>Tanggal Rapat</li>
                                                            <li>Penandatangan/Pengundang</li>
                                                            <li>Tempat/Lokasi</li>
                                                            <li>Jam Mulai & Selesai</li>
                                                            <li>Agenda Rapat</li>
                                                            <li>Peserta Rapat</li>
                                                        </ul>
                                                    </li>
                                                    <li>Field opsional: Nomor Surat, Notulis, Dokumenter</li>
                                                    <li>Klik tombol <strong>"Simpan"</strong></li>
                                                </ol>
                                                <p><strong>Catatan Penting:</strong></p>
                                                <ul>
                                                    <li>Pastikan semua informasi rapat diisi dengan lengkap dan akurat</li>
                                                    <li>Periksa kembali tanggal dan waktu rapat sebelum menyimpan</li>
                                                    <li>Notulen dapat diisi setelah rapat selesai</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="operatorHeading2">
                                            <button class="accordion-button collapsed" type="button"
                                                data-bs-toggle="collapse" data-bs-target="#operatorCollapse2">
                                                2. Melihat Dokumen Rapat
                                            </button>
                                        </h2>
                                        <div id="operatorCollapse2" class="accordion-collapse collapse"
                                            data-bs-parent="#accordionOperator">
                                            <div class="accordion-body">
                                                <p><strong>Mengunduh Dokumen Rapat:</strong></p>
                                                <ol>
                                                    <li>Klik menu <strong>Dokumen Rapat</strong> di sidebar</li>
                                                    <li>Pada tabel, klik tombol <strong>"Unduh Dokumen"</strong> pada rapat yang diinginkan</li>
                                                    <li>Pilih jenis dokumen yang ingin diunduh</li>
                                                    <li>Pilih opsi tanda tangan</li>
                                                    <li>Klik tombol <strong>"Unduh"</strong></li>
                                                </ol>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="operatorHeading3">
                                            <button class="accordion-button collapsed" type="button"
                                                data-bs-toggle="collapse" data-bs-target="#operatorCollapse3">
                                                3. Mengisi Notulen dan Dokumentasi
                                            </button>
                                        </h2>
                                        <div id="operatorCollapse3" class="accordion-collapse collapse"
                                            data-bs-parent="#accordionOperator">
                                            <div class="accordion-body">
                                                <p><strong>Mengisi Notulen Rapat:</strong></p>
                                                <ol>
                                                    <li>Pada halaman Agenda Rapat, klik tombol <strong>"Notulen"</strong> pada rapat yang sudah selesai</li>
                                                    <li>Isi notulen rapat dengan lengkap menggunakan editor</li>
                                                    <li>Pastikan semua poin penting rapat tercatat</li>
                                                    <li>Klik tombol <strong>"Simpan"</strong></li>
                                                </ol>
                                                <p><strong>Mengunggah Dokumentasi:</strong></p>
                                                <ol>
                                                    <li>Pada halaman Agenda Rapat, klik tombol <strong>"Dokumentasi"</strong></li>
                                                    <li>Unggah foto dokumentasi rapat (format: JPG, PNG)</li>
                                                    <li>File akan otomatis tersimpan</li>
                                                </ol>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Tab Pegawai -->
                            <div class="tab-pane fade" id="pegawai" role="tabpanel">
                                <h5 class="mb-3"><i class='bx bx-user-circle me-1'></i>Panduan untuk Pegawai Biasa</h5>
                                <div class="accordion" id="accordionPegawai">
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="pegawaiHeading1">
                                            <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                                data-bs-target="#pegawaiCollapse1" aria-expanded="true">
                                                1. Melihat Agenda Rapat
                                            </button>
                                        </h2>
                                        <div id="pegawaiCollapse1" class="accordion-collapse collapse show"
                                            data-bs-parent="#accordionPegawai">
                                            <div class="accordion-body">
                                                <p><strong>Cara Melihat Daftar Agenda Rapat:</strong></p>
                                                <ol>
                                                    <li>Klik menu <strong>Agenda Rapat</strong> di sidebar</li>
                                                    <li>Anda akan melihat daftar semua rapat yang terdaftar</li>
                                                    <li>Informasi yang ditampilkan meliputi:
                                                        <ul>
                                                            <li>Tanggal rapat</li>
                                                            <li>Agenda rapat</li>
                                                            <li>Waktu dan tempat</li>
                                                            <li>Peserta rapat</li>
                                                        </ul>
                                                    </li>
                                                </ol>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="pegawaiHeading2">
                                            <button class="accordion-button collapsed" type="button"
                                                data-bs-toggle="collapse" data-bs-target="#pegawaiCollapse2">
                                                2. Melakukan Presensi Rapat
                                            </button>
                                        </h2>
                                        <div id="pegawaiCollapse2" class="accordion-collapse collapse"
                                            data-bs-parent="#accordionPegawai">
                                            <div class="accordion-body">
                                                <p><strong>Syarat Presensi Rapat:</strong></p>
                                                <ul>
                                                    <li>Menggunakan perangkat <strong>handphone/smartphone</strong></li>
                                                    <li>Terhubung ke <strong>jaringan WiFi kantor</strong></li>
                                                    <li>Perangkat sudah terdaftar untuk presensi</li>
                                                    <li>Login atas nama sendiri (bukan sebagai PLH/PLT)</li>
                                                </ul>
                                                <p><strong>Cara Melakukan Presensi:</strong></p>
                                                <ol>
                                                    <li>Pada halaman <strong>Dashboard</strong>, klik tombol <strong>"PRESENSI RAPAT"</strong> yang berkedip</li>
                                                    <li>Pilih agenda rapat yang sesuai dari dropdown</li>
                                                    <li>Klik tombol <strong>"SIMPAN"</strong></li>
                                                    <li>Anda akan menerima notifikasi jika presensi berhasil</li>
                                                </ol>
                                                <p><strong>Pendaftaran Perangkat:</strong></p>
                                                <ol>
                                                    <li>Jika perangkat belum terdaftar, sistem akan otomatis mendaftarkan perangkat Anda</li>
                                                    <li>Pastikan menggunakan perangkat yang sama untuk presensi selanjutnya</li>
                                                </ol>
                                                <p><strong>Pesan Error yang Mungkin Muncul:</strong></p>
                                                <ul>
                                                    <li><strong>"Anda Mengakses Aplikasi Menggunakan Jaringan Lain":</strong> Gunakan WiFi kantor</li>
                                                    <li><strong>"Anda Tidak Menggunakan Handphone":</strong> Gunakan smartphone untuk presensi</li>
                                                    <li><strong>"Anda login sebagai plh/plt":</strong> Login ulang atas nama sendiri</li>
                                                    <li><strong>"Anda Menggunakan Perangkat Lain":</strong> Gunakan perangkat yang sudah terdaftar</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="pegawaiHeading3">
                                            <button class="accordion-button collapsed" type="button"
                                                data-bs-toggle="collapse" data-bs-target="#pegawaiCollapse3">
                                                3. Mengunduh Dokumen Rapat
                                            </button>
                                        </h2>
                                        <div id="pegawaiCollapse3" class="accordion-collapse collapse"
                                            data-bs-parent="#accordionPegawai">
                                            <div class="accordion-body">
                                                <p><strong>Cara Mengunduh Dokumen:</strong></p>
                                                <ol>
                                                    <li>Klik menu <strong>Dokumen Rapat</strong> di sidebar</li>
                                                    <li>Pada tabel, klik tombol <strong>"Unduh Dokumen"</strong> pada rapat yang diinginkan</li>
                                                    <li>Pilih jenis dokumen:
                                                        <ul>
                                                            <li><strong>Surat Undangan:</strong> Untuk melihat undangan rapat</li>
                                                            <li><strong>Form Notula:</strong> Untuk melihat hasil notulen rapat (jika sudah diisi)</li>
                                                            <li><strong>Daftar Presensi:</strong> Untuk melihat daftar hadir rapat</li>
                                                        </ul>
                                                    </li>
                                                    <li>Pilih opsi tanda tangan (Ya/Tidak)</li>
                                                    <li>Klik tombol <strong>"Unduh"</strong></li>
                                                    <li>File PDF akan terunduh ke perangkat Anda</li>
                                                </ol>
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
    </div>
</div>
