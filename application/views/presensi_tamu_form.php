<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" href="<?= base_url() ?>assets/images/icons/meeting.webp" type="image/webp" />
    <title>AGAM | PRESENSI RAPAT</title>
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            font-family: 'Segoe UI', sans-serif;
        }

        .presensi-card {
            max-width: 520px;
            margin: 2rem auto;
            border-radius: 16px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.15);
        }

        .card-header-custom {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: #fff;
            border-radius: 16px 16px 0 0 !important;
            padding: 1.5rem;
            text-align: center;
        }

        .card-header-custom img {
            max-height: 70px;
            margin-bottom: 0.5rem;
        }

        .closed-card {
            text-align: center;
            padding: 2rem;
        }

        .btn-purple {
            background: linear-gradient(135deg, #667eea, #764ba2);
            border: none;
            color: #fff;
        }

        .btn-purple:hover {
            background: linear-gradient(135deg, #764ba2, #667eea);
            color: #fff;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }

        .label-text {
            font-size: 0.85rem;
            font-weight: 600;
            color: #555;
            margin-bottom: 0.25rem;
        }

        .required::after {
            content: " *";
            color: #dc3545;
        }
    </style>
</head>

<body>
    <div class="card presensi-card">
        <?php if (!$bisa_presensi): ?>
            <!-- STATUS TUTUP -->
            <div class="card-header-custom">
                <h5><i class="bx bx-lock-alt me-2"></i>Presensi Ditutup</h5>
            </div>
            <div class="card-body closed-card">
                <i class="bx bx-error-circle" style="font-size: 4rem; color: #ffc107;"></i>
                <h5 class="mt-3 mb-2">Maaf</h5>
                <p class="text-muted"><?= $alasan_tutup ?></p>
                <?php if ($sudah_presensi): ?>
                    <hr>
                    <div class="alert alert-success">
                        <i class="bx bx-check-circle"></i> Anda sudah melakukan presensi pada rapat ini.<br>
                        <small>Waktu: <?= date('H:i', strtotime($sudah_presensi->waktu_presensi)) ?> WIB</small>
                    </div>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <!-- FORM PRESENSI -->
            <div class="card-header-custom">
                <h4 class="mb-1">DAFTAR HADIR RAPAT</h4>
                <p class="mb-0 small opacity-75"><?= $rapat->agenda ?></p>
                <p class="mb-0 small opacity-75">
                    <?= htmlspecialchars($nama_pengadilan, ENT_QUOTES, 'UTF-8') ?>
                </p>
            </div>

            <div class="card-body p-4">
                <form id="formPresensiTamu">
                    <input type="hidden" name="encrypted_id" value="<?= $encrypted_id ?>">

                    <div class="row g-3">
                        <div class="col-12">
                            <p class="text-muted mb-3 small">
                                <i class="bx bx-calendar"></i>
                                <?= htmlspecialchars($tanggal_formatted, ENT_QUOTES, 'UTF-8') ?>
                                &nbsp;|&nbsp;
                                <i class="bx bx-time"></i>
                                <?= $rapat->mulai ?> - <?= $rapat->selesai ?> WIB
                                &nbsp;|&nbsp;
                                <i class="bx bx-map"></i>
                                <?= htmlspecialchars($rapat->tempat, ENT_QUOTES, 'UTF-8') ?>
                            </p>
                        </div>

                        <div class="col-12">
                            <label class="label-text required">Nama Lengkap</label>
                            <input type="text" class="form-control" name="nama" required
                                placeholder="Masukkan nama lengkap beserta titel" maxlength="100" autocomplete="off">
                            <div class="invalid-feedback">Nama wajib diisi.</div>
                        </div>

                        <div class="col-12">
                            <label class="label-text required">Jabatan / Instansi Asal</label>
                            <input type="text" class="form-control" name="jabatan_instansi" required
                                placeholder="Contoh: Panitera PA/MS ABC" maxlength="200" autocomplete="off">
                            <div class="invalid-feedback">Jabatan/instansi wajib diisi.</div>
                        </div>

                        <div class="col-12">
                            <label class="label-text">Nomor Identitas (NIP/NIK)</label>
                            <input type="text" class="form-control" name="no_identitas" placeholder="Opsional"
                                maxlength="50" autocomplete="off">
                        </div>
                    </div>

                    <div class="d-grid gap-2 mt-4">
                        <button type="submit" class="btn btn-purple btn-lg" id="btnSubmit">
                            <i class="bx bx-check me-2"></i>SIMPAN PRESENSI
                        </button>
                    </div>
                </form>
            </div>
        <?php endif; ?>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(function () {
            $(document).off('submit', '#formPresensiTamu').on('submit', '#formPresensiTamu', function (e) {
                e.preventDefault();

                var form = this;
                var btnSubmit = $('#btnSubmit');
                btnSubmit.prop('disabled', true).html(
                    '<span class="spinner-border spinner-border-sm me-2"></span>Menyimpan...'
                );

                $.ajax({
                    url: '<?= base_url() ?>simpan_presensi_tamu',
                    type: 'POST',
                    data: $(form).serialize(),
                    dataType: 'json',
                    success: function (res) {
                        if (res.success) {
                            window.location.href = '<?= base_url() ?>presensi-tamu-sukses/<?= $encrypted_id ?>';
                        } else {
                            btnSubmit.prop('disabled', false).html(
                                '<i class="bx bx-check me-2"></i>SIMPAN PRESENSI'
                            );
                            alert(res.message || 'Terjadi kesalahan.');
                        }
                    },
                    error: function () {
                        btnSubmit.prop('disabled', false).html(
                            '<i class="bx bx-check me-2"></i>SIMPAN PRESENSI'
                        );
                        alert('Terjadi kesalahan server. Silakan coba lagi.');
                    }
                });
            });
        });
    </script>
</body>

</html>