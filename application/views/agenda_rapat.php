<style>
    .dtp .dtp-btn-cancel,
    .dtp .dtp-btn-ok {
        color: #fff !important;
    }
</style>
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
                        <li class="breadcrumb-item active" aria-current="page">Daftar Agenda Rapat</li>
                    </ol>
                </nav>
            </div>
        </div>
        <h6 class="mb-0 text-uppercase">Daftar Agenda Rapat</h6>
        <hr />
        <div class="row">
            <div class="col">
                <div class="card">
                    <?php
                    if (in_array($peran, ['admin', 'operator'])) {
                        ?>
                        <div class="card-header text-end">
                            <button type="button" class="btn btn-light px-5" data-bs-toggle="modal"
                                data-bs-target="#tambah-rapat"
                                onclick="loadRapat('<?= base64_encode($this->encryption->encrypt(-1)); ?>')"><i
                                    class="bx bx-user mr-1"></i>Tambah Agenda Rapat</button>
                        </div>
                    <?php } ?>
                    <div class="card-body" id="tabelAgendaRapat">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="tambah-rapat" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <form method="POST" id="formTambahAgendaRapat" class="modal-content bg-gradient-moonlit">
            <div class="modal-header">
                <div>
                    <i class="bx bxs-user me-1 font-22"></i>
                </div>
                <h5 class="mb-0" id="judul_"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div>
                    <input type="hidden" class="form-control" id="id" name="id">
                </div>
                <div class="row mb-3">
                    <div class="col">
                        <label for="tgl" class="form-label">TANGGAL UNDANGAN</label><code> *</code>
                        <input type="text" id="tgl_undangan" name="tgl_undangan" class="form-control" placeholder="Pilih Tanggal Undangan..">
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col">
                        <label for="tgl" class="form-label">TANGGAL RAPAT</label><code> *</code>
                        <input type="text" id="tgl" name="tgl" class="form-control" placeholder="Pilih Tanggal Rapat..">
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col">
                        <label for="no" class="form-label">NOMOR SURAT UNDANGAN RAPAT
                            (<code>Opsional</code>)</label>
                        <input type="text" id="no" name="no" class="form-control"
                            placeholder="Tuliskan Nomor Surat Undangan Rapat.." autocomplete="off">
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col">
                        <label for="pengundang" class="form-label">PENANDATANGAN / PENGUNDANG
                            RAPAT</label><code> *</code>
                        <div id="pengundang_">
                        </div>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col">
                        <label for="tempat" class="form-label">TEMPAT / LOKASI</label><code> *</code>
                        <input type="text" id="tempat" name="tempat" class="form-control"
                            placeholder="Tuliskan lokasi rapat.." autocomplete="off">
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col">
                        <label for="mulai" class="form-label">JAM MULAI RAPAT</label><code> *</code>
                        <input type="text" id="mulai" name="mulai" class="form-control timepicker"
                            placeholder="Pilih jam mulai..">
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col">
                        <label for="selesai" class="form-label">JAM SELESAI RAPAT</label><code> *</code>
                        <input type="text" id="selesai" name="selesai" class="form-control timepicker"
                            placeholder="Pilih jam selesai..">
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col">
                        <label for="agenda" class="form-label">AGENDA RAPAT</label><code> *</code>
                        <textarea row="3" id="agenda" name="agenda" class="form-control"
                            placeholder="Tuliskan agenda rapat.." autocomplete="off"></textarea>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col">
                        <label for="peserta" class="form-label">PESERTA RAPAT</label><code> *</code>
                        <textarea row="3" id="peserta" name="peserta" class="form-control"
                            placeholder="Tuliskan peserta rapat.." autocomplete="off"></textarea>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col">
                        <label for="notulis" class="form-label">NOTULIS RAPAT (<code>Opsional</code>)</label>
                        <div id="notulis_">
                        </div>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col">
                        <label for="dokumenter" class="form-label">DOKUMENTASI RAPAT (<code>Opsional</code>)</label>
                        <div id="dokumenter_">
                        </div>
                    </div>
                </div>
                <div class="row" id="notif_" style="display: none">
                    <div class="col">
                        <label for="dokumenter" class="form-label">Notifikasi Ulang Di Grup Whatsapp</label>
                        <div class="form-check form-switch">
                            <input type="hidden" name="info" id="info_value" value="1">
                            <input class="form-check-input" type="checkbox" id="info">
                            <label class="form-check-label" id="chkInfo" for="info">Tidak</label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-warning" id="btn-edit" style="display: none">Edit</button>
                <button type="submit" class="btn btn-primary" id="btn-simpan">Simpan</button>
            </div>
        </form>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<div class="modal fade" id="notulen" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <form method="POST" id="formNotulen" class="modal-content bg-gradient-moonlit">
            <div class="modal-header">
                <div><i class="bx bxs-receipt me-1 font-22"></i></div>
                <h5 class="mb-0">NOTULEN RAPAT</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <input type="hidden" class="form-control" id="id_notulen" name="id">
                </div>
                <div class="form-group">
                    <div class="row g-2">
                        <div class="col">
                            <textarea class="form-control" id="editor_notulen" name="notulen"></textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.content -->

<div class="modal fade" id="dokumentasi" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content bg-gradient-moonlit">
            <div class="modal-header">
                <div><i class="bx bxs-receipt me-1 font-22"></i></div>
                <h5 class="mb-0">DOKUMENTASI RAPAT</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div class="card">
                    <div class="body">
                        <form id="formDokumentasi" enctype="multipart/form-data">
                            <input type="hidden" id="id_rapat" name="id">

                            <!-- Preview Gambar -->
                            <div id="dokumentasi-preview" class="row mb-3"></div>

                            <!-- Fancy File Upload -->
                            <div id="dokumentasi-upload">
                                <input id="fancy-file-upload" type="file" name="foto"
                                    accept=".jpg, .png, image/jpeg, image/png" multiple>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<div class="modal fade" id="previewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content bg-transparent border-0 shadow-none">
            <div class="modal-body text-center p-0">
                <img id="previewImage" src="" class="img-fluid rounded shadow">
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        loadTabelAgendaRapat();
    });

    $('.timepicker').bootstrapMaterialDatePicker({
        date: false,
        format: 'HH:mm'
    });

    var notula = document.getElementById('editor_notulen');
    if (notula) {
        //CKEditor
        CKEDITOR.replace('editor_notulen');
        CKEDITOR.config.height = 300;
    }

    document.getElementById("info").addEventListener("change", function () {
        let hiddenInput = document.getElementById("info_value");
        let label = document.getElementById("chkInfo");

        if (this.checked) {
            hiddenInput.value = "1";
            label.textContent = "Ya";
        } else {
            hiddenInput.value = "0";
            label.textContent = "Tidak";
        }
    });

    document.getElementById('btn-edit').addEventListener('click', function () {
        if (tglPicker) {
            tglPicker.set('clickOpens', true);
        }
        if (tglUndanganPicker) {
            tglUndanganPicker.set('clickOpens', true);
        }
        $('#pengundang, #notulis, #dokumenter').off('select2:opening', blockOpening);
        $('#mulai, #selesai').prop('disabled', false);
        document.getElementById('notif_').style.display = "block";
        document.getElementById('btn-edit').style.display = "none";
        document.getElementById('btn-simpan').style.display = "block";
        document.getElementById('no').removeAttribute('readonly');
        document.getElementById('tempat').removeAttribute('readonly');
        document.getElementById('agenda').removeAttribute('readonly');
        document.getElementById('peserta').removeAttribute('readonly');
    });
</script>