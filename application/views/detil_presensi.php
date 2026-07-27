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
                        <li class="breadcrumb-item active" aria-current="page">Presensi Agenda Rapat</li>
                    </ol>
                </nav>
            </div>
        </div>
        <h6 class="mb-0 text-uppercase">Data Presensi Rapat <?= $agenda ?></h6>
        <hr />
        <div class="row">
            <!-- QR TAMU CARD -->
            <div class="col-md-4 col-lg-3">
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0">QR Presensi Tamu</h6>
                    </div>
                    <div class="card-body text-center" id="qr-tamu-card">
                        <div class="spinner-border text-primary" role="status"></div>
                    </div>
                </div>
            </div>

            <!-- TABLE -->
            <div class="col">
                <div class="card">
                    <div class="card-header text-end">
                        <button type="button" class="btn btn-primary me-2" data-bs-toggle="modal"
                            data-bs-target="#tambah-tamu"
                            onclick="presensiTamu('<?= $idrapat ?>', '<?= base64_encode($this->encryption->encrypt(-1)); ?>')">
                            <i class="bx bx-user-plus mr-1"></i>Tambah Tamu
                        </button>
                        <button type="button" class="btn btn-light px-4" data-bs-toggle="modal"
                            data-bs-target="#tambah-pegawai"
                            onclick="presensiPegawai('<?= $idrapat ?>', '<?= base64_encode($this->encryption->encrypt(-1)); ?>')"><i
                                class="bx bx-user mr-1"></i>Tambah Pegawai</button>
                    </div>
                    <div class="card-body" id="tabelDetilPresensiRapat">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- MODAL -->
<div class="modal fade" id="tambah-pegawai" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <form id="formTambahPresensi" class="modal-content bg-gradient-moonlit">
            <div class="modal-header">
                <div><i class="bx bxs-medal me-1 font-22"></i></div>
                <h5 class="mb-0" id="judul_"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="id" name="id">
                <input type="hidden" id="idrapat" name="idrapat">
                <div class="row mb-3">
                    <div class="col">
                        <div class="form-label">Pilih Pegawai</div>
                        <div id="pegawai_">
                        </div>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col">
                        <div class="form-label">Waktu Presensi</div>
                        <input class="form-control" type="text" id="waktu" name="waktu">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Tutup</button>
                <button type="submit" class="btn btn-success">Simpan</button>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="tambah-tamu" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <form id="formTambahPresensiTamu" class="modal-content bg-gradient-moonlit">
            <div class="modal-header">
                <div><i class="bx bxs-medal me-1 font-22"></i></div>
                <h5 class="mb-0" id="judul_tamu"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="idrapat_tamu" name="idrapat">
                <input type="hidden" id="id_tamu" name="id">
                <div class="col-12 mb-3">
                    <div class="form-label">Nama Lengkap</div>
                    <input class="form-control" type="text" id="nama_tamu" name="nama_tamu" required>
                </div>
                <div class="col-12 mb-3">
                    <div class="form-label">Jabatan / Instansi / Pekerjaan</div>
                    <input class="form-control" type="text" id="jabatan_instansi" name="jabatan_instansi" required>
                </div>
                <div class="col-md-12 mb-3">
                    <div class="form-label">No. Identitas (NIP/NIK)</div>
                    <input class="form-control" type="text" id="no_identitas" name="no_identitas">
                </div>
                <div class="row mb-3">
                    <div class="col">
                        <div class="form-label">Waktu Presensi</div>
                        <input class="form-control" type="text" id="waktu_tamu" name="waktu">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Tutup</button>
                <button type="submit" class="btn btn-success">Simpan</button>
            </div>
        </form>
    </div>
</div>

<script>
    $(document).ready(function () {
        loadTabelDetilPresensiRapat('<?= $idrapat ?>');
        loadQRTamu('<?= $idrapat ?>');
    });
</script>