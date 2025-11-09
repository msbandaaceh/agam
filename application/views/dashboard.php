<?php 
// Inisialisasi default untuk mencegah error
if (!isset($rapat_hari_ini)) {
    $rapat_hari_ini = [];
}
if (!isset($rapat_mendatang)) {
    $rapat_mendatang = [];
}
if (!isset($statistik_bulan)) {
    $statistik_bulan = ['total_rapat' => 0, 'rapat_dengan_presensi' => 0, 'total_presensi' => 0];
}
if (!isset($rapat_terbaru)) {
    $rapat_terbaru = [];
}
if (!isset($tugas_rapat)) {
    $tugas_rapat = [];
}
if (!isset($presensi_hari_ini)) {
    $presensi_hari_ini = [];
}
?>

<div class="page-wrapper">
    <div class="page-content">
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3"><?= $this->session->userdata('nama_client_app') ?></div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Beranda</li>
                    </ol>
                </nav>
            </div>
        </div>
        <h6 class="mb-0 text-uppercase">Beranda</h6>
        <hr />

        <!-- Statistik Rapat Bulan Ini -->
        <div class="row">
            <div class="col-xl-3 col-lg-6 col-md-6 col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="avatar bg-light-primary me-2">
                                <div class="avatar-content">
                                    <i class="bx bx-calendar font-medium-5"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-0">Total Rapat</h6>
                                <small>Bulan <?= $this->tanggalhelper->convertMonthDate(date('Y-m-d')) ?></small>
                                <h3 class="mb-0 mt-1"><?= $statistik_bulan['total_rapat'] ?></h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6 col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="avatar bg-light-success me-2">
                                <div class="avatar-content">
                                    <i class="bx bx-check-circle font-medium-5"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-0">Rapat dengan Presensi</h6>
                                <small>Bulan <?= $this->tanggalhelper->convertMonthDate(date('Y-m-d')) ?></small>
                                <h3 class="mb-0 mt-1"><?= $statistik_bulan['rapat_dengan_presensi'] ?></h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6 col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="avatar bg-light-info me-2">
                                <div class="avatar-content">
                                    <i class="bx bx-user-check font-medium-5"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-0">Total Presensi</h6>
                                <small>Bulan <?= $this->tanggalhelper->convertMonthDate(date('Y-m-d')) ?></small>
                                <h3 class="mb-0 mt-1"><?= $statistik_bulan['total_presensi'] ?></h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6 col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="avatar bg-light-warning me-2">
                                <div class="avatar-content">
                                    <i class="bx bx-time font-medium-5"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-0">Rapat Hari Ini</h6>
                                <small><?= $this->tanggalhelper->convertDayDate(date('Y-m-d')) ?></small>
                                <h3 class="mb-0 mt-1"><?= count($rapat_hari_ini) ?></h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Rapat Hari Ini -->
        <?php if (!empty($rapat_hari_ini)) { ?>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="bx bx-calendar-check"></i> RAPAT HARI INI
                            <small class="text-muted"><?= $this->tanggalhelper->convertDayDate(date('Y-m-d')) ?></small>
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="list-group">
                            <?php foreach ($rapat_hari_ini as $rapat) { 
                                $sudah_presensi = isset($presensi_hari_ini[$rapat->id]);
                                $jam_sekarang = strtotime(date('H:i:s'));
                                $jam_mulai = strtotime($rapat->mulai);
                                $jam_selesai = strtotime($rapat->selesai);
                                $status_waktu = '';
                                if ($jam_sekarang < $jam_mulai) {
                                    $status_waktu = 'warning';
                                    $label_waktu = 'Belum Dimulai';
                                } elseif ($jam_sekarang >= $jam_mulai && $jam_sekarang <= $jam_selesai) {
                                    $status_waktu = 'success';
                                    $label_waktu = 'Sedang Berlangsung';
                                } else {
                                    $status_waktu = 'secondary';
                                    $label_waktu = 'Selesai';
                                }
                            ?>
                            <div class="list-group-item">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1"><?= $rapat->agenda ?></h6>
                                        <p class="mb-1">
                                            <i class="bx bx-time"></i> <?= $rapat->mulai ?> - <?= $rapat->selesai ?>
                                            <span class="mx-2">|</span>
                                            <i class="bx bx-map"></i> <?= $rapat->tempat ?>
                                        </p>
                                        <small class="text-muted">
                                            <?php if ($rapat->peserta) { ?>
                                                <i class="bx bx-group"></i> <?= $rapat->peserta ?>
                                            <?php } ?>
                                        </small>
                                    </div>
                                    <div class="text-end">
                                        <span class="badge bg-<?= $status_waktu ?> mb-2"><?= $label_waktu ?></span>
                                        <br>
                                        <?php if ($sudah_presensi) { ?>
                                            <span class="badge bg-success">
                                                <i class="bx bx-check"></i> Sudah Presensi
                                            </span>
                                        <?php } elseif ($this->session->userdata('agenda_rapat') == 1 && $jam_sekarang >= $jam_mulai && $jam_sekarang <= $jam_selesai) { ?>
                                            <a href="javascript:;" class="btn btn-sm btn-primary" onclick="$('#presensi').click()">
                                                <i class="bx bx-check"></i> Presensi
                                            </a>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php } ?>

        <!-- Tombol Presensi Rapat -->
        <?php if ($this->session->userdata('agenda_rapat') == 1 && !empty($rapat_hari_ini)) { ?>
            <div class="row">
                <div class="col">
                    <a href="javascript:;" class="card bg-gradient-cosmic" id="presensi">
                        <div class="card-body">
                            <div class="align-items-center p-3 text-center">
                                <div class="font-22 text-white"> <i class="lni lni-cup"></i>
                                </div>
                                <div class="ms-2"> <span>PRESENSI RAPAT</span>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        <?php } ?>

        <div class="row">
            <!-- Rapat Mendatang -->
            <?php if (!empty($rapat_mendatang)) { ?>
            <div class="col-lg-6 col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="bx bx-calendar-event"></i> RAPAT MENDATANG
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="list-group">
                            <?php foreach ($rapat_mendatang as $rapat) { ?>
                            <div class="list-group-item">
                                <h6 class="mb-1"><?= $rapat->agenda ?></h6>
                                <p class="mb-1">
                                    <i class="bx bx-calendar"></i> <?= $this->tanggalhelper->convertDayDate($rapat->tanggal) ?>
                                    <span class="mx-2">|</span>
                                    <i class="bx bx-time"></i> <?= $rapat->mulai ?> - <?= $rapat->selesai ?>
                                </p>
                                <small class="text-muted">
                                    <i class="bx bx-map"></i> <?= $rapat->tempat ?>
                                </small>
                            </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php } ?>

            <!-- Tugas Rapat (Notulis/Dokumenter) -->
            <?php if (!empty($tugas_rapat)) { ?>
            <div class="col-lg-6 col-12">
                <div class="card">
                    <div class="card-header bg-warning">
                        <h5 class="card-title mb-0 text-white">
                            <i class="bx bx-task"></i> TUGAS SAYA
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="list-group">
                            <?php foreach ($tugas_rapat as $rapat) { 
                                $tugas = [];
                                if ($rapat->notulis == $this->session->userdata('userid')) {
                                    $tugas[] = 'Notulis';
                                }
                                if ($rapat->dokumenter == $this->session->userdata('userid')) {
                                    $tugas[] = 'Dokumenter';
                                }
                            ?>
                            <div class="list-group-item">
                                <h6 class="mb-1"><?= $rapat->agenda ?></h6>
                                <p class="mb-1">
                                    <i class="bx bx-calendar"></i> <?= $this->tanggalhelper->convertDayDate($rapat->tanggal) ?>
                                    <span class="mx-2">|</span>
                                    <i class="bx bx-time"></i> <?= $rapat->mulai ?> - <?= $rapat->selesai ?>
                                </p>
                                <small>
                                    <span class="badge bg-warning">
                                        <i class="bx bx-briefcase"></i> <?= implode(', ', $tugas) ?>
                                    </span>
                                </small>
                            </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php } ?>
        </div>

        <!-- Rapat Terbaru -->
        <?php if (!empty($rapat_terbaru)) { ?>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="bx bx-history"></i> RAPAT TERBARU
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Agenda</th>
                                        <th>Waktu</th>
                                        <th>Tempat</th>
                                        <th>Jumlah Presensi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($rapat_terbaru as $rapat) { ?>
                                    <tr>
                                        <td><?= $this->tanggalhelper->convertDayDate($rapat->tanggal) ?></td>
                                        <td><?= $rapat->agenda ?></td>
                                        <td><?= $rapat->mulai ?> - <?= $rapat->selesai ?></td>
                                        <td><?= $rapat->tempat ?></td>
                                        <td>
                                            <span class="badge bg-info"><?= $rapat->jumlah_presensi ?> orang</span>
                                        </td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php } ?>

        <div class="card">
            <div class="card-body">
                <h5 class="card-title">INFORMASI</h5>
                <hr />
                <div class="accordion" id="accordionExample">
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingTwo">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
                                APLIKASI MANAJEMEN RAPAT PEGAWAI versi 1.1.0
                            </button>
                        </h2>
                        <div id="collapseTwo" class="accordion-collapse collapse show" aria-labelledby="headingTwo"
                            data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                <h4>Penambahan Fitur :</h4>
                                <ol>
                                    <li>Dashboard diperkaya dengan informasi statistik rapat yang lebih lengkap.
                                    </li>
                                    <li>Statistik rapat bulan ini menampilkan total rapat, rapat dengan presensi, dan total presensi.
                                    </li>
                                    <li>Daftar rapat hari ini dengan status waktu (Belum Dimulai, Sedang Berlangsung, Selesai).
                                    </li>
                                    <li>Indikator status presensi untuk setiap rapat hari ini.
                                    </li>
                                    <li>Daftar rapat mendatang (7 hari ke depan) untuk perencanaan yang lebih baik.
                                    </li>
                                    <li>Widget "Tugas Saya" untuk menampilkan rapat dimana pengguna ditunjuk sebagai notulis atau dokumenter.
                                    </li>
                                    <li>Tabel rapat terbaru dengan informasi jumlah presensi.
                                    </li>
                                </ol>
                                <h4>Optimasi :</h4>
                                <ol>
                                    <li>Perbaikan query database untuk performa yang lebih baik.
                                    </li>
                                    <li>Optimasi tampilan dashboard dengan layout yang lebih informatif dan responsif.
                                    </li>
                                    <li>Peningkatan user experience dengan informasi yang lebih detail dan mudah dipahami.
                                    </li>
                                </ol>
                                Buku Panduan penggunaan aplikasi dapat di unduh melalui <a
                                    href="https://drive.google.com/file/d/15TFYfLGWsq40X9B5wHVbtCGvD51b6sQo/view?usp=drive_link">tautan
                                    ini</a>.
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingOne">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                                APLIKASI MANAJEMEN RAPAT PEGAWAI versi 1.0.0
                            </button>
                        </h2>
                        <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne"
                            data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                <h4>Fitur Utama :</h4>
                                <ol>
                                    <li>Register rapat bagi seluruh Pegawai.
                                    </li>
                                </ol>

                                Buku Panduan penggunaan aplikasi dapat di unduh melalui <a
                                    href="https://drive.google.com/file/d/15TFYfLGWsq40X9B5wHVbtCGvD51b6sQo/view?usp=drive_link">tautan
                                    ini</a>.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="presensi-rapat" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <form id="formPresensi" class="modal-content bg-gradient-cosmic">
            <div class="modal-header">
                <div><i class="bx bxs-medal me-1 font-22"></i></div>
                <h5 class="mb-0">PRESENSI RAPAT</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col">
                        <div class="form-label text-center" id="hariRapat"></div>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <div class="form-label text-center">Saat ini pukul <label class="form-label"
                                id="jamRapat"></label>
                        </div>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col">
                        <div class="form-label">Pilih Agenda Rapat : </div>
                        <div id="rapat_">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">TUTUP</button>
                <button type="submit" class="btn btn-success">SIMPAN</button>
            </div>
        </form>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<script>
    $('#presensi').on('click', async function () {
        var ipLokal = '<?= $this->session->userdata('ip_satker') ?>';
        var mobile = '<?= $this->agent->is_mobile() ?>';
        var token = '<?= $this->session->userdata("token_now") ?>';
        var token_cookies = '<?= $this->input->cookie('presensi_token', TRUE) ?>';

        // Ambil IP koneksi (ipKonek) dari API
        const ipKonek = await getIpKonek();
        if (ipLokal != ipKonek) {
            notifikasi('Anda Mengakses Aplikasi Menggunakan Jaringan Lain. Silakan Presensi Menggunakan Jaringan Wifi Kantor', 3);
        } else if (mobile != '1') {
            notifikasi('Anda Tidak Menggunakan Handphone. Silakan Presensi Melalui Handphone', 3);
        } else if (token == 'plh/plt') {
            notifikasi('Anda login sebagai plh/plt. Silakan login atas diri anda sendiri untuk melakukan presensi', 3);
        } else if (token == '') {
            simpanPerangkat();
        } else if (token != token_cookies) {
            notifikasi('Anda Menggunakan Perangkat Lain Untuk Presensi. Silakan Menggunakan Perangkat Yang Telah Didaftarkan Untuk Presensi', 3);
        } else {
            presensiRapat();
        }
    });
</script>