var result = config.result;
var pesan = config.pesan;
var ipLokal = config.ipServer;
var mobile = config.isMobile;
var token = config.tokenNow;
var token_cookies = config.tokenCookies;
var peran = config.peran;
var userid = config.userid;
let tglPicker; // simpan global untuk tanggal rapat
let tglUndanganPicker; // simpan global untuk tanggal undangan

function notifikasi(pesan, result) {
    let icon;
    if (result == '1') {
        result = 'success';
        icon = 'bx bx-check-circle';
    } else if (result == '2') {
        result = 'warning';
        icon = 'bx bx-error';
    } else if (result == '3') {
        result = 'error';
        icon = 'bx bx-x-circle';
    } else {
        result = 'info';
        icon = 'bx bx-info-circle';
    }

    Lobibox.notify(result, {
        pauseDelayOnHover: true,
        continueDelayOnInactiveTab: false,
        position: 'top right',
        icon: icon,
        sound: false,
        msg: pesan
    });
}

function info(pesan) {
    Swal.fire({
        title: '<h4>Perhatian</h4>',
        html: pesan,
        icon: 'info',
        confirmButtonText: 'OK'
    });
}

function loadPage(page) {
    cekToken();
    $('#app').html(`
        <div class="page-wrapper">
            <div class="page-content">
                <div class="text-center p-4">
                    <div class="spinner-border text-primary" role="status"></div>
                </div>
                <div class="text-center">
                    <span>Memuat Halaman... Harap Tunggu Sebentar</span>
                </div>
            </div>
        </div>
    `);
    $.get("halamanutama/page/" + page, function (data) {
        $('#app').html(data);
    }).fail(function () {
        $('#app').html(`
            <div class="page-wrapper">
                <div class="page-content">
                    <div class="text-center p-4">Halaman tidak ditemukan.</div>
                </div>
            </div>
        `);
    });
}

function loadDetilPresensi(page, idrapat) {
    cekToken();
    $('#app').html('<div class="page-wrapper"><div class="page-content"><div class="text-center p-4">Memuat...</div></div></div>');
    $.get("halamanlaporan/detil_presensi/" + page + "/" + idrapat, function (data) {
        $('#app').html(data);
    }).fail(function () {
        $('#app').html('<div class="text-danger">Halaman tidak ditemukan.</div>');
    });
}

function cekToken() {
    $.ajax({
        url: 'cek_token',
        type: 'POST',
        dataType: 'json',
        success: function (res) {
            if (!res.valid) {
                alert(res.message);
                window.location.href = res.url;
            }
        }
    });
}

function ModalRole(id) {
    $('#role-pegawai').modal('show');
    $('#btnBatal').hide();
    if (id != '-1') {
        $('#tabel-role').html('');
        $('#btnBatal').show();
    }

    $.post('show_role',
        { id: id },
        function (response) {
            try {
                const json = JSON.parse(response); // pastikan response valid JSON
                $('#pegawai_').html('');

                let html = `<select class="form-control select2" id="pegawai" name="pegawai" style="width:100%">`;
                json.pegawai.forEach(row => {
                    html += `<option value="${row.userid}" data-nama="${row.fullname}" data-jabatan="${row.jabatan}">${row.fullname}</option>`;
                });
                html += `</select>`;
                $('#pegawai_').append(html);

                $('#peran_').html('');
                let role = `<select class="form-control select2" id="peran" name="peran" style="width:100%">`;
                role += `<option value="operator">Operator Kepegawaian</option>`;
                role += `</select>`;
                $('#peran_').append(role);

                $('#overlay').hide();

                $('#pegawai').select2({
                    theme: 'bootstrap4',
                    dropdownParent: $('#role-pegawai'),
                    width: '100%',
                    placeholder: "Pilih pegawai",
                    templateResult: formatPegawaiOption,
                    templateSelection: formatPegawaiSelection
                });

                $('#peran').select2({
                    theme: 'bootstrap4',
                    dropdownParent: $('#role-pegawai'),
                    width: '100%',
                    placeholder: "Pilih Peran"
                });

                if (id != '-1') {
                    $('#id').val('');

                    $('#id').val(json.id);
                    $('#pegawai').val(json.editPegawai).trigger('change');
                    $('#peran').val(json.editPeran).trigger('change');

                    $('#pegawai').on('select2:opening select2:selecting', function (e) {
                        e.preventDefault(); // mencegah dropdown terbuka
                    });
                } else {
                    $('#tabel-role').html('');

                    let data = `
                    <div class="table-responsive">
                    <table id="tabelPeran" class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Role</th>
                                <th>Aksi</th>
                            </tr>
                        </thead><tbody>`;
                    json.data_peran.forEach(row => {
                        if (`${row.peran}` == 'operator') {
                            var peran_ = 'Operator Kepegawaian';
                        }
                        data += `
                        <tr>
                            <td>${row.nama}</td>
                            <td>`;

                        if (`${row.hapus}` == '0') {
                            data += `<span class='badge bg-success'>${peran_}</span>
                            </td>
                            <td>
                                <button type="button" class="btn btn-outline-warning" id="editPeran" onclick="ModalRole('${row.id}')" title="Edit Peran">
                                    <i class="bx bx-pencil me-0"></i>
								</button>

                                <button type="button" class="btn btn-outline-danger" id="hapusPeran" onclick="blokPeran('${row.id}')" title="Blok Pegawai">
                                    <i class="bx bx-block me-0"></i>
								</button>`;
                        } else {
                            data += `<span class='badge bg-secondary'>${peran_}</span>
                            </td>
                            <td>
                                <button type="button" class="btn btn-outline-success" id="hapusPeran" onclick="aktifPeran('${row.id}')" title="Aktifkan Pegawai">
                                    <i class="bx bx-check me-0"></i>
								</button>`;
                        }
                        data += `
                            </td>
                        </tr>`;
                    });
                    data += `
                        </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <span class='badge bg-success'>Aktif</span>
                        <span class='badge bg-secondary'>Non-aktif</span>
                    </div>
                    </div>`;
                    $('#tabel-role').append(data);
                    $("#tabelPeran").DataTable({
                        lengthChange: false
                    });
                }
            } catch (e) {
                console.error("Gagal parsing JSON:", e);
                $('#pegawai_').html('<div class="alert alert-danger">Gagal memuat data pegawai.</div>');
            }
        }
    );
}

function aktifPeran(id) {
    Swal.fire({
        title: "Yakin ingin mengaktifkan kembali peran pegawai?",
        text: "Data peran ini akan diaktifkan perannya.",
        icon: "warning", // ⬅️ gunakan 'icon' bukan 'type'
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Ya, aktifkan!",
        cancelButtonText: "Batal"
    }).then((result) => {
        if (result.isConfirmed) {
            // Eksekusi penghapusan setelah konfirmasi
            $.post('aktif_peran', { id: id }, function (response) {
                Swal.fire("Berhasil!", "Peran telah diaktifkan.", "success");
                ModalRole('-1');
            }).fail(function () {
                Swal.fire("Gagal", "Terjadi kesalahan saat mengaktifkan data.", "error");
            });
        }
    });
}

function blokPeran(id) {
    Swal.fire({
        title: "Yakin ingin menonaktifkan peran pegawai?",
        text: "Data peran ini akan dinonaktifkan perannya.",
        icon: "warning", // ⬅️ gunakan 'icon' bukan 'type'
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Ya, nonaktifkan!",
        cancelButtonText: "Batal"
    }).then((result) => {
        if (result.isConfirmed) {
            // Eksekusi penghapusan setelah konfirmasi
            $.post('blok_peran', { id: id }, function (response) {
                Swal.fire("Berhasil!", "Peran telah dinonaktifkan.", "success");
                ModalRole('-1');
            }).fail(function () {
                Swal.fire("Gagal", "Terjadi kesalahan saat menghapus data.", "error");
            });
        }
    });
}

function formatPegawaiOption(option) {
    if (!option.id) return option.text;

    const nama = $(option.element).data('nama');
    const jabatan = $(option.element).data('jabatan');

    return $(`
        <div style="line-height:1.2">
            <div style="font-weight:bold;">${nama}</div>
            <div style="font-size:12px;">${jabatan}</div>
        </div>
    `);
}

function formatPegawaiSelection(option) {
    if (!option.id) return option.text;

    const nama = $(option.element).data('nama');
    const jabatan = $(option.element).data('jabatan');

    return `${nama} > ${jabatan}`;
}

function getIpKonek() {
    return fetch('https://api.ipify.org?format=json')
        .then(response => response.json())
        .then(data => data.ip)
        .catch(error => {
            console.error('Terjadi kesalahan:', error);
            return null;
        });
}

function loadTabelAgendaRapat() {
    $.post('show_tabel_agenda_rapat', function (response) {
        try {
            const json = JSON.parse(response); // Pastikan server kirim JSON valid

            $('#tabelAgendaRapat').html(''); // kosongkan wrapper

            if (!json.data_rapat || json.data_rapat.length === 0) {
                // Kalau kosong
                $('#tabelAgendaRapat').html(`
                    <div class="alert border-0 border-start border-5 border-info alert-dismissible fade show py-2">
                        <div class="d-flex align-items-center">
                            <div class="font-35 text-info"><i class='bx bx-info-square'></i></div>
                            <div class="ms-3">
                                <h6 class="mb-0 text-info">Informasi</h6>
                                <div>Belum Ada Agenda Rapat Yang Dibuat. Terima kasih.</div>
                            </div>
                        </div>
                    </div>
                `);
                return;
            }

            // Kalau ada data, buat tabelnya
            let data = `
                <div class="table-responsive">
                <table id="tabelAgendaRapatData" class="table table-striped table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>NO</th>
                            <th>NAMA AGENDA RAPAT</th>
                            <th>TANGGAL</th>
                            <th>AKSI</th>
                        </tr>
                    </thead>
                    <tbody>
            `;

            json.data_rapat.forEach((row, index) => {
                // Tombol aksi
                let tombolAksi = `
                    <div class="btn-group" role="group" aria-label="Basic example">
                        <button type="button" class="btn btn-info" title="Lihat Agenda Rapat"
                            data-bs-target="#tambah-rapat"
                            onclick="detailRapat('${row.id}')"
                            data-bs-toggle="modal"><i class="bx bxs-show"></i>
                        </button>
                `;

                if (['admin', 'operator'].includes(peran) || row.notulis == userid) {
                    tombolAksi += `
                            <button type="button" class="btn btn-warning" title="Notulen Rapat"
                                data-bs-target="#notulen"
                                onclick="bukaNotulen('${row.id}')"
                                data-bs-toggle="modal"><i class="bx bxs-receipt"></i>
                            </button>
                    `;
                }

                if (['admin', 'operator'].includes(peran) || row.dokumenter == userid) {
                    tombolAksi += `
                            <button type="button" class="btn btn-primary" title="Dokumentasi Rapat"
                                data-bs-target="#dokumentasi"
                                onclick="bukaDokumentasi('${row.id}')"
                                data-bs-toggle="modal"><i class="bx bxs-camera"></i>
                            </button>
                    `;
                }

                if (['admin', 'operator'].includes(peran)) {
                    tombolAksi += `
                        <button type="button" class="btn btn-danger" title="Hapus Data"
                            onclick="hapusRapat('${row.id}')">
                            <i class="bx bxs-trash"></i>
                        </button>
                    `;
                }

                tombolAksi += `</div>`;

                // Baris tabel
                data += `
                    <tr>
                        <td>${index + 1}</td>
                        <td>${row.agenda}</td>
                        <td>${row.tgl}</td>
                        <td>${tombolAksi}</td>
                    </tr>
                `;
            });

            data += `
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>NO</th>
                            <th>NAMA AGENDA RAPAT</th>
                            <th>TANGGAL</th>
                            <th>AKSI</th>
                        </tr>
                    </tfoot>
                </table>
                </div>
            `;

            $('#tabelAgendaRapat').append(data);

            // Aktifkan DataTables
            $("#tabelAgendaRapatData").DataTable();
        } catch (e) {
            console.error("Gagal parsing JSON:", e);
            $('#tabelAgendaRapat').html('<div class="alert alert-danger">Gagal memuat data izin.</div>');
        }
    });
}

function loadTabelPresensiRapat() {
    $.post('show_tabel_presensi_rapat', function (response) {
        try {
            const json = JSON.parse(response); // Pastikan server kirim JSON valid

            $('#tabelPresensiRapat').html(''); // kosongkan wrapper

            if (!json.data_rapat || json.data_rapat.length === 0) {
                // Kalau kosong
                $('#tabelPresensiRapat').html(`
                    <div class="alert border-0 border-start border-5 border-info alert-dismissible fade show py-2">
                        <div class="d-flex align-items-center">
                            <div class="font-35 text-info"><i class='bx bx-info-square'></i></div>
                            <div class="ms-3">
                                <h6 class="mb-0 text-info">Informasi</h6>
                                <div>Belum Ada Agenda Rapat Yang Dibuat. Terima kasih.</div>
                            </div>
                        </div>
                    </div>
                `);
                return;
            }

            // Kalau ada data, buat tabelnya
            let data = `
                <div class="table-responsive">
                <table id="tabelPresensiRapatData" class="table table-striped table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>NO</th>
                            <th>NAMA AGENDA RAPAT</th>
                            <th>TANGGAL</th>
                            <th>JUMLAH PESERTA</th>
                        </tr>
                    </thead>
                    <tbody>
            `;

            json.data_rapat.forEach((row, index) => {
                // Baris tabel
                data += `
                    <tr>
                        <td>${index + 1}</td>
                        <td><a href="javascript:;" data-rapat="detil_presensi" data-id="${row.id}">${row.agenda}</a></td>
                        <td>${row.tgl}</td>
                        <td>${row.total} Pegawai</td>
                    </tr>
                `;
            });

            data += `
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>NO</th>
                            <th>NAMA AGENDA RAPAT</th>
                            <th>TANGGAL</th>
                            <th>JUMLAH PESERTA</th>
                        </tr>
                    </tfoot>
                </table>
                </div>
            `;

            $('#tabelPresensiRapat').append(data);

            // Aktifkan DataTables
            $("#tabelPresensiRapatData").DataTable();
        } catch (e) {
            console.error("Gagal parsing JSON:", e);
            $('#tabelPresensiRapat').html('<div class="alert alert-danger">Gagal memuat data rapat.</div>');
        }
    });
}

function loadTabelDetilPresensiRapat(idrapat) {
    $.post('show_tabel_detil_presensi_rapat', {
        idrapat: idrapat
    }, function (response) {
        try {
            const json = JSON.parse(response); // Pastikan server kirim JSON valid

            $('#tabelDetilPresensiRapat').html(''); // kosongkan wrapper

            if (!json.data_rapat || json.data_rapat.length === 0) {
                // Kalau kosong
                $('#tabelDetilPresensiRapat').html(`
                    <div class="alert border-0 border-start border-5 border-info alert-dismissible fade show py-2">
                        <div class="d-flex align-items-center">
                            <div class="font-35 text-info"><i class='bx bx-info-square'></i></div>
                            <div class="ms-3">
                                <h6 class="mb-0 text-info">Informasi</h6>
                                <div>Belum Ada Pegawai Yang Melakukan Presensi. Terima kasih.</div>
                            </div>
                        </div>
                    </div>
                `);
                return;
            }

            // Kalau ada data, buat tabelnya
            let data = `
                <div class="table-responsive">
                <table id="tabelPresensiRapatData" class="table table-striped table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>NO</th>
                            <th>NAMA AGENDA RAPAT</th>
                            <th>WAKTU PRESENSI</th>
                            <th>AKSI</th>
                        </tr>
                    </thead>
                    <tbody>
            `;

            json.data_rapat.forEach((row, index) => {
                let tombolAksi = `
                    <div class="btn-group" role="group" aria-label="Basic example">
                        <button type="button" class="btn btn-warning" title="Edit Presensi"
                            data-bs-target="#tambah-pegawai"
                            onclick="presensiPegawai('${json.idrapat}', '${row.id}')"
                            data-bs-toggle="modal"><i class="bx bxs-pencil"></i>
                        </button>
                        <button type="button" class="btn btn-danger" title="Hapus Data"
                            onclick="hapusPegawai('${row.id}')">
                            <i class="bx bxs-trash"></i>
                        </button>
                `;

                tombolAksi += `</div>`;

                // Baris tabel
                data += `
                    <tr>
                        <td>${index + 1}</td>
                        <td>${row.nama}</a></td>
                        <td>${row.waktu}</td>
                        <td>${tombolAksi}</td>
                    </tr>
                `;
            });

            data += `
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>NO</th>
                            <th>NAMA AGENDA RAPAT</th>
                            <th>WAKTU PRESENSI</th>
                            <th>AKSI</th>
                        </tr>
                    </tfoot>
                </table>
                </div>
            `;

            $('#tabelDetilPresensiRapat').append(data);

            // Aktifkan DataTables
            $("#tabelDetilPresensiRapatData").DataTable();
        } catch (e) {
            console.error("Gagal parsing JSON:", e);
            $('#tabelDetilPresensiRapat').html('<div class="alert alert-danger">Gagal memuat data rapat.</div>');
        }
    });
}

function loadTabelDokumenRapat() {
    $.post('show_tabel_agenda_rapat', function (response) {
        try {
            const json = JSON.parse(response); // Pastikan server kirim JSON valid

            $('#tabelDokumenRapat').html(''); // kosongkan wrapper

            if (!json.data_rapat || json.data_rapat.length === 0) {
                // Kalau kosong
                $('#tabelDokumenRapat').html(`
                    <div class="alert border-0 border-start border-5 border-info alert-dismissible fade show py-2">
                        <div class="d-flex align-items-center">
                            <div class="font-35 text-info"><i class='bx bx-info-square'></i></div>
                            <div class="ms-3">
                                <h6 class="mb-0 text-info">Informasi</h6>
                                <div>Belum Ada Agenda Rapat Yang Dibuat. Terima kasih.</div>
                            </div>
                        </div>
                    </div>
                `);
                return;
            }

            // Kalau ada data, buat tabelnya
            let data = `
                <div class="table-responsive">
                <table id="tabelDokumenRapatData" class="table table-striped table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>NO</th>
                            <th>NAMA AGENDA RAPAT</th>
                            <th>TANGGAL</th>
                            <th>AKSI</th>
                        </tr>
                    </thead>
                    <tbody>
            `;

            json.data_rapat.forEach((row, index) => {
                // Tombol aksi
                let tombolAksi = `
                    <div class="btn-group" role="group" aria-label="Basic example">
                        <button type="button" class="btn btn-info" title="Cetak Dokumen Rapat"
                            data-bs-target="#dokumen-rapat"
                            onclick="pilihDokumen('${row.id}')"
                            data-bs-toggle="modal"><i class="bx bxs-printer"></i>
                        </button>
                    </div>
                `;

                // Baris tabel
                data += `
                    <tr>
                        <td>${index + 1}</td>
                        <td>${row.agenda}</td>
                        <td>${row.tgl}</td>
                        <td>${tombolAksi}</td>
                    </tr>
                `;
            });

            data += `
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>NO</th>
                            <th>NAMA AGENDA RAPAT</th>
                            <th>TANGGAL</th>
                            <th>AKSI</th>
                        </tr>
                    </tfoot>
                </table>
                </div>
            `;

            $('#tabelDokumenRapat').append(data);

            // Aktifkan DataTables
            $("#tabelDokumenRapatData").DataTable();
        } catch (e) {
            console.error("Gagal parsing JSON:", e);
            $('#tabelDokumenRapat').html('<div class="alert alert-danger">Gagal memuat data izin.</div>');
        }
    });
}

$(function () {
    $(document).on('submit', '#formPeran', function (e) {
        e.preventDefault();
        let form = this;
        let formData = new FormData(form);

        $.ajax({
            url: 'simpan_peran',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function (res) {
                notifikasi(res.message, res.success);
                ModalRole('-1');
            },
            error: function () {
                notifikasi('Terjadi kesalahan saat menyimpan data.', 4);
            }
        });
    });

    $(document).off('submit', '#formTambahAgendaRapat').on('submit', '#formTambahAgendaRapat', function (e) {
        e.preventDefault();
        let form = this;
        let formData = new FormData(form);

        $.ajax({
            url: 'simpan_rapat',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function (res) {
                notifikasi(res.message, res.success);
                if (res.success == '1') {
                    $('#tambah-rapat').modal('hide');
                    loadTabelAgendaRapat();
                }
            },
            error: function () {
                notifikasi('Terjadi kesalahan saat menyimpan data.', 4);
            }
        });
    });

    $(document).off('submit', '#formNotulen').on('submit', '#formNotulen', function (e) {
        e.preventDefault();
        let form = this;
        let formData = new FormData(form);

        $.ajax({
            url: 'simpan_notulen',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function (res) {
                notifikasi(res.message, res.success);
                if (res.success == '1') {
                    $('#notulen').modal('hide');
                    loadTabelAgendaRapat();
                }
            },
            error: function () {
                notifikasi('Terjadi kesalahan saat menyimpan data.', 4);
            }
        });
    });

    $(document).off('submit', '#formPresensi').on('submit', '#formPresensi', function (e) {
        e.preventDefault();
        let form = this;
        let formData = new FormData(form);

        $.ajax({
            url: 'simpan_presensi',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function (res) {
                notifikasi(res.message, res.success);
                if (res.success == '1') {
                    $('#presensi-rapat').modal('hide');
                }
            },
            error: function () {
                notifikasi('Terjadi kesalahan saat menyimpan data.', 4);
            }
        });
    });

    $(document).off('submit', '#formTambahPresensi').on('submit', '#formTambahPresensi', function (e) {
        e.preventDefault();
        let form = this;
        let formData = new FormData(form);

        $.ajax({
            url: 'simpan_presensi_pegawai',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function (res) {
                notifikasi(res.message, res.success);
                if (res.success == '1') {
                    $('#tambah-pegawai').modal('hide');
                    loadTabelDetilPresensiRapat(res.idrapat);
                }
            },
            error: function () {
                notifikasi('Terjadi kesalahan saat menyimpan data.', 4);
            }
        });
    });

    $(document).off('submit', '#formDokumenRapat').on('submit', '#formDokumenRapat', function (e) {
        e.preventDefault();
        let form = this;
        let formData = new FormData(form);

        $.ajax({
            url: 'unduh_dokumen',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function (res) {
                notifikasi(res.message, res.success);
                if (res.success == 1) {
                    window.location = res.url;
                }
            },
            error: function () {
                notifikasi('Terjadi kesalahan saat menyimpan data.', 4);
            }
        });
    });
});

function simpanPerangkat() {
    Swal.fire({
        title: "Anda Belum Mendaftarkan Perangkat Untuk Melakukan Presensi",
        text: "Apa Anda Yakin Akan Mendaftarkan Perangkat Ini Untuk Melakukan Presensi?",
        icon: "info",
        showCancelButton: true,
        confirmButtonColor: "#8EC165",
        confirmButtonText: "Ya, Simpan !",
        cancelButtonText: "Tidak !"
    }).then((result) => {
        if (result.isConfirmed) {
            $.post('simpan_perangkat', function (response) {
                var json = jQuery.parseJSON(response);
                if (json.st == 1) {
                    Swal.fire({
                        title: "Berhasil !",
                        text: "Anda sudah mendaftarkan perangkat, silakan mengisi presensi",
                        icon: "success",
                        confirmButtonColor: "#8EC165",
                        confirmButtonText: "Oke"
                    }).then(() => {
                        location.reload();
                    });
                } else if (json.st == 0) {
                    Swal.fire("Gagal", "Anda Gagal Mendaftarkan Perangkat, Silakan Ulangi Lagi", "error");
                }
            });
        } else if (result.dismiss === Swal.DismissReason.cancel) {
            Swal.fire("Batal", "Anda Tidak Mendaftarkan Perangkat", "error");
        }
    });
}

function presensiRapat() {
    $.post('show_presensi_rapat', function (response) {
        var json = jQuery.parseJSON(response);
        if (json.st == 1) {
            $("#presensi-rapat").modal('show');
            $("#jamRapat").html('');
            $("#hariRapat").html('');
            $("#rapat_").html('');

            $("#jamRapat").append(json.jam);
            $("#hariRapat").append(json.hari);
            $("#rapat_").append(json.rapat);

            $('#rapat').select2({
                dropdownParent: $('#presensi-rapat .modal-content'),
                theme: 'bootstrap4'
            });
        }
    });
}

function pilihDokumen(id) {
    $('#id_rapat').val('');
    $('#id_rapat').val(id);

    $('#jenis_dokumen').select2({
        theme: 'bootstrap4',
        dropdownParent: $('#dokumen-rapat .modal-content'),
        width: '100%',
        dropdownAutoWidth: true
    });
}

function hapusRapat(id) {
    Swal.fire({
        title: "<h5>HAPUS AGENDA RAPAT</h5>",
        html: "<h5>Apa Anda Yakin Akan Menghapus Agenda Rapat?</h5>",
        icon: "warning",
        background: '#1e1e1e',
        showCancelButton: true,
        confirmButtonColor: "#DD2A2A",
        cancelButtonColor: "#6c757d",
        confirmButtonText: "Ya, Hapus !",
        cancelButtonText: "Tidak !"
    }).then((result) => {
        if (result.isConfirmed) {
            $.post('hapus_rapat', { id: id }, function (response) {
                var json = jQuery.parseJSON(response);
                if (json.st == 1) {
                    Swal.fire({
                        title: "Berhasil !",
                        text: "Anda Sudah Menghapus Agenda Rapat",
                        icon: "success",
                        confirmButtonColor: "#8EC165",
                        confirmButtonText: "Oke"
                    }).then(() => {
                        loadTabelAgendaRapat();
                    });
                } else {
                    Swal.fire("Gagal", "Anda Gagal Menghapus Agenda Rapat, Silakan Ulangi Lagi", "error");
                }
            });
        } else if (result.dismiss === Swal.DismissReason.cancel) {
            Swal.fire("Batal", "Anda Batal Menghapus Agenda Rapat", "info");
        }
    });
}

function hapusPegawai(id) {
    Swal.fire({
        title: "<h5>HAPUS PRESENSI PEGAWAI</h5>",
        html: "<h5>Apa Anda Yakin Akan Menghapus Presensi Pegawai?</h5>",
        icon: "warning",
        background: '#1e1e1e',
        showCancelButton: true,
        confirmButtonColor: "#DD2A2A",
        cancelButtonColor: "#6c757d",
        confirmButtonText: "Ya, Hapus !",
        cancelButtonText: "Tidak !"
    }).then((result) => {
        if (result.isConfirmed) {
            $.post('hapus_presensi', { id: id }, function (response) {
                var json = jQuery.parseJSON(response);
                if (json.st == 1) {
                    Swal.fire({
                        title: "Berhasil !",
                        text: "Anda Sudah Menghapus Presensi Pegawai",
                        icon: "success",
                        confirmButtonColor: "#8EC165",
                        confirmButtonText: "Oke"
                    }).then(() => {
                        loadTabelDetilPresensiRapat(json.idrapat);
                    });
                } else {
                    Swal.fire("Gagal", "Anda Gagal Menghapus Presensi Pegawai, Silakan Ulangi Lagi", "error");
                }
            });
        } else if (result.dismiss === Swal.DismissReason.cancel) {
            Swal.fire("Batal", "Anda Batal Menghapus Presensi", "info");
        }
    });
}

function loadRapat(id, isDetail = false) {
    $.post('show_rapat', {
        id: id
    }, function (response) {
        var json = jQuery.parseJSON(response);
        if (json.st == 1) {
            // Destroy existing flatpickr instances jika ada
            if (tglPicker) {
                tglPicker.destroy();
            }
            if (tglUndanganPicker) {
                tglUndanganPicker.destroy();
            }

            // Konfigurasi flatpickr untuk tanggal rapat
            tglPicker = flatpickr("#tgl", {
                dateFormat: "Y-m-d", // format yg dikirim ke server
                altInput: true,
                disableMobile: true,
                altFormat: "d F Y", // format tampilan
                locale: {
                    firstDayOfWeek: 0,
                    weekdays: {
                        shorthand: ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'],
                        longhand: ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'],
                    },
                    months: {
                        shorthand: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun',
                            'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
                        longhand: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'],
                    },
                },
                disable: [
                    function (date) {
                        // getDay() -> 0 = Minggu, 6 = Sabtu
                        return (date.getDay() === 0 || date.getDay() === 6);
                    }
                ]
            });

            // Konfigurasi flatpickr untuk tanggal undangan
            tglUndanganPicker = flatpickr("#tgl_undangan", {
                dateFormat: "Y-m-d", // format yg dikirim ke server
                altInput: true,
                disableMobile: true,
                altFormat: "d F Y", // format tampilan
                locale: {
                    firstDayOfWeek: 0,
                    weekdays: {
                        shorthand: ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'],
                        longhand: ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'],
                    },
                    months: {
                        shorthand: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun',
                            'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
                        longhand: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'],
                    },
                },
                disable: [
                    function (date) {
                        // getDay() -> 0 = Minggu, 6 = Sabtu
                        return (date.getDay() === 0 || date.getDay() === 6);
                    }
                ]
            });

            // Reset semua field
            $("#id").val('');
            $("#judul_").html('');
            $("#peserta").val('');
            tglPicker.clear();
            tglUndanganPicker.clear();
            $("#mulai").val('');
            $("#selesai").val('');
            $("#tempat").val('');
            $("#agenda").val('');
            $("#pengundang_").html('');
            $("#notulis_").html('');
            $("#dokumenter_").html('');
            $("#no").val('');

            // Set nilai dari response
            $("#id").val(json.id);
            $("#judul_").append(json.judul);
            $("#peserta").val(json.peserta);
            
            // Set tanggal rapat
            if (json.tgl) {
                tglPicker.setDate(json.tgl, true, "Y-m-d");
            }

            // Set tanggal undangan
            if (json.tgl_undangan) {
                tglUndanganPicker.setDate(json.tgl_undangan, true, "Y-m-d");
            }
            
            $("#mulai").val(json.mulai);
            $("#selesai").val(json.selesai);
            $("#tempat").val(json.tempat);
            $("#agenda").val(json.agenda);
            $("#pengundang_").append(json.pengundang);
            $("#notulis_").append(json.notulis);
            $("#dokumenter_").append(json.dokumenter);
            $("#no").val(json.no);

            $('#pengundang, #notulis, #dokumenter').select2({
                theme: 'bootstrap4',
                dropdownParent: $('#tambah-rapat .modal-content'),
                width: '100%',
                dropdownAutoWidth: true
            });

            // kalau mode detail, lock input
            if (isDetail) {
                tglPicker.set('clickOpens', false);
                tglUndanganPicker.set('clickOpens', false);
                $('#pengundang, #notulis, #dokumenter').on('select2:opening', blockOpening);
                $('#mulai, #selesai').prop('disabled', true);
                if (['operator', 'admin'].includes(peran)) {
                    document.getElementById('btn-edit').style.display = "block";
                } else {
                    document.getElementById('btn-edit').style.display = "none";
                }
                document.getElementById('btn-simpan').style.display = "none";
                document.getElementById('no').setAttribute('readonly', true);
                document.getElementById('tempat').setAttribute('readonly', true);
                document.getElementById('agenda').setAttribute('readonly', true);
                document.getElementById('peserta').setAttribute('readonly', true);
            } else {
                $('#mulai, #selesai').prop('disabled', false);
                if (['operator', 'admin'].includes(peran)) {
                    document.getElementById('btn-edit').style.display = "none";
                } else {
                    document.getElementById('btn-edit').style.display = "block";
                }
                document.getElementById('btn-simpan').style.display = "block";
                document.getElementById('no').setAttribute('readonly', false);
                document.getElementById('tempat').setAttribute('readonly', false);
                document.getElementById('agenda').setAttribute('readonly', false);
                document.getElementById('peserta').setAttribute('readonly', false);
            }
        }
    });
}

function presensiPegawai(idrapat, id) {
    $.post('show_presensi', {
        idrapat: idrapat,
        id: id
    }, function (response) {
        var json = jQuery.parseJSON(response);
        if (json.st == 1) {
            var tglPresensiPicker;

            tglPresensiPicker = flatpickr("#waktu", {
                enableTime: true,          // aktifkan input jam
                time_24hr: true,
                dateFormat: "Y-m-d H:i:S", // format yg dikirim ke server
                altInput: true,
                disableMobile: true,
                altFormat: "d F Y H:i:S", // format tampilan
                locale: {
                    firstDayOfWeek: 0,
                    weekdays: {
                        shorthand: ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'],
                        longhand: ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'],
                    },
                    months: {
                        shorthand: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun',
                            'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
                        longhand: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'],
                    },
                },
                disable: [
                    function (date) {
                        // getDay() -> 0 = Minggu, 6 = Sabtu
                        return (date.getDay() === 0 || date.getDay() === 6);
                    }
                ]
            });

            $("#id").val('');
            $("#judul_").html('');
            tglPresensiPicker.clear();
            $("#idrapat").val('');
            $("#waktu").val('');
            $("#pegawai_").html('');

            $("#id").val(json.id);
            $("#judul_").append(json.judul);
            if (json.waktu) {
                tglPresensiPicker.setDate(json.waktu, true, "Y-m-d H:i:s");
            }
            $("#idrapat").val(json.idrapat);
            $("#pegawai_").append(json.pegawai);

            $('#pegawai').select2({
                theme: 'bootstrap4',
                dropdownParent: $('#tambah-pegawai .modal-content'),
                width: '100%',
                dropdownAutoWidth: true
            });
        }
    });
}

function blockOpening(e) {
    e.preventDefault();
}

function loadEditRapat(id) {
    $.post('show_data_rapat', {
        id: id
    }, function (response) {
        var json = jQuery.parseJSON(response);
        if (json.st == 1) {
            $('#modalEditRapat').modal('show');
            $("#id_edit").val('');
            $("#judul_edit").html('');
            $("#peserta_edit").val('');
            $("#tgl_edit").val('');
            $("#mulai_edit").val('');
            $("#selesai_edit").val('');
            $("#tempat_edit").val('');
            $("#agenda_edit").val('');
            $("#pengundang_edit").html('');
            $("#notulis_edit").html('');
            $("#dokumenter_edit").html('');
            $("#no_edit").val('');

            $("#id_edit").val(json.id);
            $("#judul_edit").append(json.judul);
            $("#peserta_edit").val(json.peserta);
            $("#tgl_edit").val(json.tgl);
            $("#mulai_edit").val(json.mulai);
            $("#selesai_edit").val(json.selesai);
            $("#tempat_edit").val(json.tempat);
            $("#agenda_edit").val(json.agenda);
            $("#pengundang_edit").append(json.pengundang);
            $("#notulis_edit").append(json.notulis);
            $("#dokumenter_edit").append(json.dokumenter);
            $("#no_edit").val(json.no);
            console.log(json.peserta);

        } else if (json.st == 0) {
            pesan('PERINGATAN', json.msg, '');
            $('#table_pegawai').DataTable().ajax.reload();
        }
    });
}

async function detailRapat(id) {
    loadRapat(id, true);
}

function bukaNotulen(id) {
    $.post('show_notulen', {
        id: id
    }, function (response) {
        var json = jQuery.parseJSON(response);
        if (json.st == 1) {
            $("#id_notulen").val('');

            $("#id_notulen").val(json.id);

            // Perbarui nilai CKEditor menggunakan API CKEditor
            CKEDITOR.instances['editor_notulen'].setData(json.notulen);
        } else if (json.st == 0) {
            pesan('PERINGATAN', json.msg, '');
            $('#table_pegawai').DataTable().ajax.reload();
        }
    });
}

function bukaDokumentasi(id) {

    // Kosongkan isi wrapper dulu
    $("#dokumentasi-preview").html('<p class="text-muted">Memuat data...</p>');

    // AJAX cek data dokumentasi
    $.post("show_dokumentasi", { id: id }, function (response) {
        var json = JSON.parse(response);

        $('#id_rapat').val('');
        $('#id_rapat').val(json.id);

        if (json.st == 1 && json.data.length > 0) {
            // === Jika ada data dokumentasi, tampilkan preview gambar ===
            let html = '';
            json.data.forEach(function (item) {
                html += `
                    <div class="col-md-3 text-center mb-3" id="dok-${item.id}">
                        <img src="${item.url}" onclick="previewGambar('${item.url}')"
                             class="img-thumbnail mb-2" style="max-height:120px">
                        <button type="button" class="btn btn-sm btn-danger" 
                                onclick="hapusDokumentasi(${item.id})">
                            <i class="bx bx-trash"></i> Hapus
                        </button>
                    </div>`;
            });
            $("#dokumentasi-preview").html(html);

        } else {
            $("#dokumentasi-preview").html('<p class="text-muted text-center">Belum ada dokumentasi.</p>');
        }

        // Inisialisasi Fancy File Upload ulang
        $('#fancy-file-upload').FancyFileUpload({
            params: {
                id_rapat: json.id
            },
            maxfilesize: 1000000, // 1 MB
            url: "simpan_dokumentasi"
        });
    });
}

function hapusDokumentasi(id) {
    if (confirm("Yakin ingin menghapus file ini?")) {
        $.post("hapus_dokumentasi", { id: id }, function (response) {
            var json = JSON.parse(response);
            if (json.success) {
                $("#dok-" + id).fadeOut(300, function () { $(this).remove(); });
            } else {
                alert("Gagal menghapus: " + json.error);
            }
        });
    }
}

function previewGambar(url) {
    document.getElementById('previewImage').src = url;
    var previewModal = new bootstrap.Modal(document.getElementById('previewModal'));
    previewModal.show();
}