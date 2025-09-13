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

        <?php if ($this->session->userdata('agenda_rapat') == 1) { ?>
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

        <div class="card">
            <div class="card-body">
                <h5 class="card-title">INFORMASI</h5>
                <hr />
                <div class="accordion" id="accordionExample">
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingOne">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                APLIKASI MANAJEMEN RAPAT PEGAWAI versi 1.0.0
                            </button>
                        </h2>
                        <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne"
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