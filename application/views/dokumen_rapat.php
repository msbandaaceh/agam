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
                        <li class="breadcrumb-item active" aria-current="page">Dokumen Rapat</li>
                    </ol>
                </nav>
            </div>
        </div>
        <h6 class="mb-0 text-uppercase">Dokumen Rapat</h6>
        <hr />
        <div class="row">
            <div class="col">
                <div class="card">
                    <div class="card-body" id="tabelDokumenRapat">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="dokumen-rapat" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <form id="formDokumenRapat" class="modal-content bg-gradient-moonlit">
            <div class="modal-header">
                <div><i class="bx bxs-printer me-1 font-22"></i></div>
                <h4 class="mb-0">Pilih Dokumen Rapat</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <input class="form-control" type="hidden" id="id_rapat" name="id">
                <div class="row mb-3">
                    <div class="col">
                        <div class="form-label">Jenis Dokumen</div>
                        <select name="jenis_dokumen" id="jenis_dokumen" class="form-control">
                            <option value="" disabled>Pilih Jenis Dokumen</option>
                            <option value="undangan">Surat Undangan</option>
                            <option value="notula">Form Notula</option>
                            <option value="presensi">Daftar Presensi Rapat</option>
                        </select>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col">
                        <div class="form-label">Tanda Tangan</div>
                        <select name="ttd_tempel" class="form-control">
                            <option value="" disabled>Cetak Dengan Tanda Tangan?</option>
                            <option value="1">Ya</option>
                            <option value="2">Tidak</option>
                        </select>
                    </div>
                </div>
                <div id="undangan">
                    <div class="row mb-3">
                        <div class="col">
                            <div class="form-label">Sifat Undangan</div>
                            <select name="sifat" class="form-control">
                                <option value="" disabled>Pilih Sifat Undangan</option>
                                <option value="Terbatas">Terbatas</option>
                                <option value="Biasa">Biasa</option>
                                <option value="Rahasia">Rahasia</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col">
                            <div class="form-label">Lampiran</div>
                            <input class="form-control" type="text"
                                placeholder="Masukkan Jumlah Lampiran (contoh: 1 Lembar, Kosongkan jika tidak ada)"
                                name="lampiran">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Tutup</button>
                <button type="submit" class="btn btn-info">Unduh</button>
            </div>
            <!-- /.modal-content -->
        </form>
        <!-- /.modal-dialog -->
    </div>
</div>

<script>
    $(document).ready(function () {
        loadTabelDokumenRapat();
    });

    $('#jenis_dokumen').on('change', function () {
        const value = $(this).val();
        let html = '';

        if (value === 'notula' || value === 'presensi') {
            $('#undangan').hide();
        } else {
            $('#undangan').show();
        }
    });
</script>