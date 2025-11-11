<!DOCTYPE html>

<head>
    <style>
        @page {
            margin-top: 2cm;
            margin-bottom: 2.5cm;
            margin-left: 3cm;
            margin-right: 2cm;
        }

        body {
            font-family: 'Arial', sans-serif;
            font-size: 12px;
        }

        div {
            border-bottom-style: none;
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
    <table style="border:none; width:100%;">
        <tr>
            <td style="border:none;width:12%;">Nomor</td>
            <td style="border:none;width:2%;">:</td>
            <td style="border:none;width:46%;"><?= $nomor ?></td>
            </td>
            <td style="border:none;width:40%;text-align:right" rowspan="3" valign="top">Banda Aceh,
                <?= $tanggal ?>
            </td>
        </tr>
        <tr>
            <td style="border:none;">Sifat</td>
            <td style="border:none;">:</td>
            <td style="border:none;"><?= $sifat ?></td>
        </tr>
        <tr>
            <td style="border:none;">Lampiran</td>
            <td style="border:none;">:</td>
            <td style="border:none;"><?= $lampiran ?></span></td>
        </tr>
        <tr>
            <td style="border:none;" valign="top">Hal</td>
            <td style="border:none;" valign="top">:</td>
            <td colspan="2" style="border:none;">
                <strong>Undangan <?= $agenda ?></strong>
            </td>
        </tr>
    </table>
    <br />

    <table width="50%" style="border:none;">
        <tr>
            <td style="width:10%; vertical-align: top;">Yth.</td>
            <td><?= $peserta ?>
            </td>
        </tr>
        <tr>
            <td colspan="2">di tempat</td>
        </tr>
    </table>
    <br />
    <table>
        <tr>
            <td><em>Assalamua'alaikum Wr. Wb.</em></td>
        </tr>
    </table>
    <p style="text-indent: 2em; text-align: justify;">
        Sehubungan dengan akan dilaksanakannya agenda <?= $agenda ?>, maka
        bersama ini kami mengundang Bapak/Ibu untuk menghadiri agenda dimaksud yang akan dilaksanakan pada :
    </p>
    <table style="margin-left: 2em;">
        <tr>
            <td style="vertical-align: top; width: 20%">Hari, Tanggal</td>
            <td style="vertical-align: top; width: 2%">:</td>
            <td style="vertical-align: top; width: 78%"><?= $tanggal_agenda ?></td>
        </tr>
        <tr>
            <td style="vertical-align: top; width: 20%">Waktu</td>
            <td style="vertical-align: top; width: 2%">:</td>
            <td style="vertical-align: top; width: 78%"><?= $mulai ?> s.d. <?= $selesai ?> WIB</td>
        </tr>
        <tr>
            <td style="vertical-align: top; width: 20%">Tempat</td>
            <td style="vertical-align: top; width: 2%">:</td>
            <td style="vertical-align: top; width: 78%"><?= $tempat ?></td>
        </tr>
        <tr>
            <td style="vertical-align: top; width: 20%">Agenda</td>
            <td style="vertical-align: top; width: 2%">:</td>
            <td style="vertical-align: top; width: 78%"><?= $agenda ?></td>
        </tr>
    </table>
    <p style="text-indent: 2em; text-align: justify;">
        Demikian undangan ini disampaikan untuk dilaksanakan.
    </p>
    <table style="width: 100%; border: none; ">
        <tr>
            <td style="width: 70%; border: none;"></td>
            <td style="width: 30%; border: none;">
                <?= ucwords(strtolower($jabatan)) ?><br>
                <?php if ($qr_code): ?>
                    <img src="<?= $qr_code ?>" alt="<?= $nama ?>"><br>
                <?php else: ?>
                    <br><br><br><br>
                <?php endif; ?>
                <strong><?= $nama ?></strong>
            </td>
        </tr>
    </table>
</body>

</html>