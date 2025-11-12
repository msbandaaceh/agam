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

        #tabel1 {
            border-collapse: collapse;
            width: 100%;
        }

        #tabel1 th,
        #tabel1 td {
            border: 1px solid #333;
            /* warna border */
            padding: 6px 8px;
            /* padding agar teks tidak terlalu mepet */
            vertical-align: middle;
        }

        #tabel1 thead th {
            background-color: #f2f2f2;
            /* warna latar header */
            color: #000;
            text-align: center;
        }

        #tabel1 tbody tr:nth-child(even) {
            background-color: #f9f9f9;
            /* striping baris genap */
        }

        #tabel1 tbody tr:hover {
            background-color: #e6f7ff;
            /* efek hover */
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
                <h3>DAFTAR HADIR</h3>
            </td>
        </tr>
        <tr>
            <td style="border:none;width:20%;" class="notulen">Jenis Kegiatan</td>
            <td style="border:none;width:2%;" class="notulen">:</td>
            <td style="border:none;width:78%;text-align: justify;" class="notulen"><strong>Rapat</strong>
            </td>
        </tr>
        <tr>
            <td style="border:none;" class="notulen">Nama Kegiatan</td>
            <td style="border:none;" class="notulen">:</td>
            <td style="border:none;text-align: justify;" class="notulen"><?= $agenda ?></td>
        </tr>
        <tr>
            <td style="border:none;" class="notulen">Hari, Tanggal</td>
            <td style="border:none;" class="notulen">:</td>
            <td style="border:none;text-align: justify;" class="notulen"><?= $tanggal_agenda ?></td>
        </tr>

        <tr>
            <td style="border:none;" class="notulen">Waktu</td>
            <td style="border:none;" class="notulen">:</td>
            <td colspan="2" style="border:none;text-align: justify;" class="notulen">
                Pukul <?= $mulai ?> s.d. <?= $selesai ?> WIB
            </td>
        </tr>
        <tr>
            <td style="border:none;" class="notulen">Tempat</td>
            <td style="border:none;" class="notulen">:</td>
            <td style="border:none;text-align: justify;" class="notulen"><?= $tempat ?></td>
        </tr>

    </table>
    <br />

    <div style="text-align: justify; margin-bottom: 5px;">
        <table id="tabel1" class="table table-bordered table-hover"
            style="font-family: 'Bookman Old Style', serif; font-size: 12px;">
            <thead>
                <tr>
                    <th class="text-center" style="width: 1%">NO</th>
                    <th class="text-center">NIP</th>
                    <th class="text-center">NAMA</th>
                    <th class="text-center">JABATAN</th>
                    <th class="text-center">WAKTU</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                foreach ($peserta as $item) {
                    ?>
                    <tr>
                        <td class="text-center" style="padding: 1px">
                            <?= $no; ?>
                        </td>
                        <td style="padding: 1px">
                            <?= $item->nip; ?>
                        </td>
                        <td style="padding: 1px">
                            <?= $item->nama; ?>
                        </td>
                        <td style="padding: 1px">
                            <?= $item->jabatan; ?>
                        </td>
                        <td class="text-center" style="padding: 1px">
                            <?= $item->waktu; ?>
                        </td>
                    </tr>
                    <?php
                    $no++;
                }
                ?>
            </tbody>
        </table>
    </div>

    <table style="width: 100%; border: none; font-family: 'Bookman Old Style', serif; font-size: 12px;">
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

    <div id="footer">
        <em>Generate dari
            <?= $this->session->userdata('nama_client_app') . ' ' . ucwords(strtolower($this->session->userdata('nama_pengadilan'))) ?>
            - <?= date('Y-m-d H:i:s') ?></em>
    </div>
</body>

</html>