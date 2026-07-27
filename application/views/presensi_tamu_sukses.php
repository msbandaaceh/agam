<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" href="<?= base_url() ?>assets/images/icons/meeting.webp" type="image/webp" />
    <link href="<?= base_url() ?>assets/css/icons.css" rel="stylesheet">
    <title>AGAM | PRESENSI RAPAT</title>
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            font-family: 'Segoe UI', sans-serif;
        }

        .sukses-card {
            max-width: 480px;
            margin: 2rem auto;
            border-radius: 16px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.15);
            text-align: center;
            overflow: hidden;
        }

        .sukses-header {
            background: linear-gradient(135deg, #0d6efd, #6610f2);
            color: #fff;
            padding: 2rem;
        }

        .sukses-body {
            padding: 2rem;
        }

        .sukses-icon {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: #198754;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            font-size: 2.5rem;
        }

        .btn-purple {
            background: linear-gradient(135deg, #667eea, #764ba2);
            border: none;
            color: #fff;
            padding: 0.75rem 2rem;
        }

        .btn-purple:hover {
            background: linear-gradient(135deg, #764ba2, #667eea);
            color: #fff;
        }
    </style>
</head>

<body>
    <div class="card sukses-card">
        <div class="sukses-header">
            <h4><i class="bx bx-calendar-check me-2"></i>Presensi Rapat</h4>
        </div>
        <div class="sukses-body">
            <div class="sukses-icon">
                <i class="bx bx-badge-check"></i>
            </div>
            <h5 class="mb-3">Terima Kasih!</h5>
            <p class="text-muted">
                Presensi Anda untuk rapat "<strong><?= htmlspecialchars($rapat->agenda, ENT_QUOTES, 'UTF-8') ?></strong>"
                berhasil disimpan.
            </p>
            <table class="table table-sm table-bordered bg-light mt-3 mb-3">
                <tr>
                    <th class="bg-white">Tanggal</th>
                    <td><?= htmlspecialchars($tanggal_formatted, ENT_QUOTES, 'UTF-8') ?></td>
                </tr>
                <tr>
                    <th class="bg-white">Waktu</th>
                    <td><?= $rapat->mulai ?> - <?= $rapat->selesai ?> WIB</td>
                </tr>
                <tr>
                    <th class="bg-white">Tempat</th>
                    <td><?= htmlspecialchars($rapat->tempat, ENT_QUOTES, 'UTF-8') ?></td>
                </tr>
            </table>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
</body>

</html>
