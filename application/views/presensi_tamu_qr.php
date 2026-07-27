<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>

<body>
    <div class="qr-card">
        <div class="qr-header mb-2">
            <h5 class="mb-1"><?= htmlspecialchars($rapat->agenda, ENT_QUOTES, 'UTF-8') ?></h5>
        </div>
        <div class="qr-body">
            <div class="text-center">
                <img src="<?= $qr_code ?>" alt="QR Code Presensi Tamu" style="max-width:100%; height:auto;">
            </div>
            <p class="mt-3 mb-1 fw-semibold">Scan untuk mengisi presensi tamu</p>
            <p class="small text-muted">Form presensi hanya tersedia pada pukul <?= substr($rapat->mulai, 0, 5) ?> -
                <?= substr($rapat->selesai, 0, 5) ?> WIB.</p>

            <div class="mt-3 d-flex gap-2 justify-content-center flex-wrap">
                <a href="<?= $qr_code ?>" download="qr_presensi_tamu_<?= $rapat->agenda ?>.png"
                    class="btn btn-outline-success">
                    <i class="bx bx-download"></i> Download QR
                </a>
            </div>
        </div>
    </div>
</body>

</html>