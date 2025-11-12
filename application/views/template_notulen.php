<!DOCTYPE html>

<head>
    <style>
        @page {
            margin-top: 2cm;
            margin-bottom: 2.5cm;
            margin-left: 3cm;
            margin-right: 2cm;
        }

        .notulen {
            text-align: left;
            vertical-align: top;
        }

        body {
            font-family: 'Bookman Old Style', serif;
            font-size: 12px;
        }

        div {
            border-bottom-style: none;
        }

        #footer {
            position: fixed;
            bottom: -3cm;
            left: 0;
            right: 0;
            height: 2cm;
            text-align: left;
            font-size: 10pt;
            color: #777;
        }
    </style>
</head>

<body>
    <table style="border:none; width:100%;">
        <tr>
            <td style="text-align:center; border:none;">
                <img src="<?= $kop ?>" style="max-width:100%; height:auto;">
            </td>
        </tr>
    </table>
    <br />
    <table style="border:none; width:100%; font-family: 'Bookman Old Style', serif; font-size: 12px;">
        <tr>
            <td style="text-align:center; border:none;" colspan=3>
                <h3>NOTULA</h3>
            </td>
        </tr>
        <tr>
            <td style="border:none;width:20%;" class="notulen">Dasar</td>
            <td style="border:none;width:2%;" class="notulen">:</td>
            <td style="border:none;width:78%;text-align: justify;" class="notulen">Undangan
                <?= ucwords(strtolower($jabatan)) . ' ' . ucwords(strtolower($this->session->userdata('nama_pengadilan'))) ?>
                Nomor : <?= $nomor ?> tanggal <?= $tgl_undangan ?>
            </td>
        </tr>
        <tr>
            <td style="border:none;" class="notulen">Waktu</td>
            <td style="border:none;" class="notulen">:</td>
            <td style="border:none;text-align: justify;" class="notulen"><?= $tanggal_agenda ?></td>
        </tr>
        <tr>
            <td style="border:none;" class="notulen">Agenda</td>
            <td style="border:none;" class="notulen">:</td>
            <td style="border:none;text-align: justify;" class="notulen"><?= $agenda ?></td>
        </tr>
        <tr>
            <td style="border:none;" class="notulen">Pukul</td>
            <td style="border:none;" class="notulen">:</td>
            <td colspan="2" style="border:none;text-align: justify;" class="notulen">
                <?= $mulai ?> s.d. <?= $selesai ?> WIB
            </td>
        </tr>
        <tr>
            <td style="border:none;" class="notulen">Tempat</td>
            <td style="border:none;" class="notulen">:</td>
            <td style="border:none;text-align: justify;" class="notulen"><?= $tempat ?></td>
        </tr>
        <tr>
            <td style="border:none;" class="notulen">Peserta Rapat</td>
            <td style="border:none;" class="notulen">:</td>
            <td style="border:none;text-align: justify;" class="notulen"><?= $peserta ?></td>
        </tr>
        <tr>
            <td style="border:none;" class="notulen">Jalannya Rapat</td>
            <td style="border:none;" class="notulen">:</td>
            <td style="border:none;" class="notulen"></td>
        </tr>
    </table>
    <br />

    <div style="text-align: justify;">
        <?= $notulen ?>
    </div>

    <table style="width: 100%; border: none;">
        <tr>
            <td style="width: 50%; border: none;">
            </td>
            <td style="width: 50%; border: none;">
                Mengetahui
            </td>
        </tr>
        <tr>
            <td style="width: 50%; border: none;" class="notulen">
                Notulis,
            </td>
            <td style="width: 50%; border: none;">
                <?= ucwords(strtolower($jabatan)) ?>
            </td>
        </tr>
        <?php if ($qr_code_pejabat) {
            ?>
            <tr>
                <td style="width: 50%; border: none;" class="notulen">
                    <img src="<?= $qr_code_notulen ?>">
                </td>
                <td style="width: 50%; border: none;">
                    <img src="<?= $qr_code_pejabat ?>">
                </td>
            </tr>
        <?php } else {
            ?>
            <tr>
                <td colspan="2" style="height: 80px;"></td>
            </tr> <!-- jarak untuk ttd -->
        <?php } ?>
        <tr>
            <td style="border: none;">
                <?= $notulis ?>
            </td>
            <td style="border: none;">
                <?= $pejabat ?><br>
            </td>
        </tr>
    </table>

    <div id="footer">
        <em>Generate dari
            <?= $this->session->userdata('nama_client_app') . ' ' . ucwords(strtolower($this->session->userdata('nama_pengadilan'))) ?>
            - <?= date('Y-m-d H:i:s') ?></em>
    </div>

    <?php if (!empty($dokumentasi)): ?>
        <div style="page-break-before: always;"></div>
        <h3 style="text-align:center; margin-bottom: 20px;">Dokumentasi Rapat</h3>
        <?php foreach ($dokumentasi as $foto): ?>
            <div style="text-align:center; margin-bottom: 20px;">
                <img src="<?= base_url($foto->file) ?>"
                    style="max-width: 90%; height: auto; border:1px solid #ccc; padding: 5px;" />
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</body>

</html>