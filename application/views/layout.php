<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!--favicon-->
    <link rel="icon" href="assets/images/icons/meeting.webp" type="image/webp" />
    <!--plugins-->
    <link href="assets/plugins/simplebar/css/simplebar.css" rel="stylesheet" />
    <link href="assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css" rel="stylesheet" />
    <link href="assets/plugins/metismenu/css/metisMenu.min.css" rel="stylesheet" />
    <link href="assets/plugins/datatable/css/dataTables.bootstrap5.min.css" rel="stylesheet" />
    <link href="assets/plugins/select2/css/select2.min.css" rel="stylesheet" />
    <link href="assets/plugins/select2/css/select2-bootstrap4.css" rel="stylesheet" />
    <link href="assets/plugins/notifications/css/lobibox.min.css" rel="stylesheet" />
    <link href="assets/plugins/flatpickr/flatpickr.min.css" rel="stylesheet" />
    <link href="assets/plugins/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.min.css"
        rel="stylesheet" />
    <link href="assets/plugins/fancy-file-uploader/fancy_fileupload.css" rel="stylesheet" />
    <!-- Bootstrap CSS -->
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/bootstrap-extended.css" rel="stylesheet">
    <link href="assets/css/app.css" rel="stylesheet">
    <link href="assets/css/icons.css" rel="stylesheet">
    <title><?= $this->session->userdata('nama_client_app') ?> | <?= $this->session->userdata('deskripsi_client_app') ?>
    </title>

    <style>
        /* Define keyframes for the glow animation */
        @keyframes glowing {
            0% {
                box-shadow: 0 0 10px #fff;
            }

            50% {
                box-shadow: 0 0 20px #00BFFF;
            }

            100% {
                box-shadow: 0 0 10px #fff;
            }
        }

        /* Apply the glow animation to the link when it's hovered over */
        #presensi {
            animation: glowing 1.5s infinite;
        }
    </style>
</head>

<body class="bg-theme bg-theme1">
    <div class="wrapper">
        <div class="sidebar-wrapper" data-simplebar="true">
            <div class="sidebar-header">
                <div>
                    <img src="assets/images/icons/meeting.webp" class="logo-icon" alt="logo icon">
                </div>
                <div>
                    <h4 class="logo-text"><?= $this->session->userdata('nama_client_app') ?></h4>
                </div>
                <div class="toggle-icon ms-auto"><i class='bx bx-arrow-to-left'></i>
                </div>
            </div>
            <ul class="metismenu" id="menu">
                <li>
                    <a href="javascript:;" data-page="dashboard">
                        <div class="parent-icon"><i class='bx bx-home-circle'></i>
                        </div>
                        <div class="menu-title">Dashboard</div>
                    </a>
                </li>
                <li>
                    <a href="javascript:;" data-page="agenda_rapat">
                        <div class="parent-icon"><i class='bx bx-calendar-event'></i>
                        </div>
                        <div class="menu-title">Agenda Rapat</div>
                    </a>
                </li>

                <li class="menu-label">Laporan</li>
                <?php
                if (in_array($peran, ['admin'])) {
                    ?>
                    <li>
                        <a href="javascript:;" data-page="presensi_rapat">
                            <div class="parent-icon"><i class='bx bx-user-circle'></i>
                            </div>
                            <div class="menu-title">Presensi Rapat</div>
                        </a>
                    </li>
                <?php } ?>
                <li>
                    <a href="javascript:;" data-page="dokumen_rapat">
                        <div class="parent-icon"><i class='bx bx-folder-open'></i>
                        </div>
                        <div class="menu-title">Dokumen Rapat</div>
                    </a>
                </li>

                <li class="menu-label">Panduan & Dokumentasi</li>
                <li>
                    <a href="javascript:;" data-page="panduan">
                        <div class="parent-icon"><i class='bx bx-book-open'></i>
                        </div>
                        <div class="menu-title">Panduan Penggunaan</div>
                    </a>
                </li>
                <?php
                if (in_array($peran, ['admin'])) {
                    ?>
                    <li>
                        <a href="javascript:;" data-page="dokumentasi_teknis">
                            <div class="parent-icon"><i class='bx bx-code-alt'></i>
                            </div>
                            <div class="menu-title">Dokumentasi Teknis</div>
                        </a>
                    </li>
                <?php } ?>
            </ul>
        </div>

        <!--start header -->
        <header>
            <div class="topbar d-flex align-items-center">
                <nav class="navbar navbar-expand">
                    <div class="mobile-toggle-menu"><i class='bx bx-menu'></i>
                    </div>
                    <div class="top-menu ms-auto">
                        <?php
                        if (in_array($peran, ['admin'])) {
                            ?>
                            <ul class="navbar-nav align-items-center">
                                <li class="nav-item dropdown dropdown-large">
                                    <a class="nav-link dropdown-toggle dropdown-toggle-nocaret" href="#" role="button"
                                        data-bs-toggle="dropdown" aria-expanded="false" aria-label="Click for Setting"> <i
                                            class='bx bx-category'></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-end">
                                        <div class="row row-cols-3 g-3 p-3">
                                            <div class="col text-center">
                                                <div class="app-box mx-auto bg-gradient-cosmic text-white"><i
                                                        class='bx bx-group' onclick="ModalRole('-1')"></i>
                                                </div>
                                                <div class="app-title">Peran</div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        <?php } ?>
                    </div>
                    <div class="user-box dropdown">
                        <a class="d-flex align-items-center nav-link dropdown-toggle dropdown-toggle-nocaret" href="#"
                            role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <img src="<?= $this->session->userdata('foto') ?>" class="user-img" alt="user avatar">
                            <div class="user-info ps-3">
                                <p class="user-name mb-0"><?= $this->session->userdata('fullname') ?></p>
                                <p class="designattion mb-0"><?= $this->session->userdata('jabatan') ?></p>
                            </div>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="keluar"><i
                                        class='bx bx-log-out-circle'></i><span>Keluar</span></a>
                            </li>
                        </ul>
                    </div>
                </nav>
            </div>
        </header>
        <!--end header -->

        <div id="app"></div>

        <div class="overlay toggle-icon"></div>
        <!--end overlay-->
        <!--Start Back To Top Button--> <a href="javascript:;" class="back-to-top"><i
                class='bx bxs-up-arrow-alt'></i></a>
        <!--End Back To Top Button-->
        <footer class="page-footer">
            <p class="mb-0">Copyright © 2024. All right reserved.</p>
        </footer>
    </div>

    <div class="modal fade" id="role-pegawai" data-bs-backdrop="static">
        <div class="modal-dialog modal-lg">
            <div class="card card-default">
                <div class="modal-content bg-gradient-moonlit">
                    <div class="overlay" id="overlay">
                        <i class="fas fa-2x fa-sync fa-spin"></i>
                    </div>
                    <div class="modal-header">
                        <h5 class="modal-title" id="judul">Daftar Petugas</h5>
                    </div>
                    <form method="POST" id="formPeran">
                        <input type="hidden" id="id" name="id">
                        <div class="modal-body">
                            <div class="form-group">
                                <label class="form-label">Pilih Pegawai : </label>
                                <div id="pegawai_">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Pilih Peran : </label>
                                <div id="peran_"></div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                            <button type="button" id="btnBatal" onclick="ModalRole('-1')"
                                class="btn btn-danger">Batal</button>
                        </div>
                    </form>
                    <div class="modal-body" id="tabel-role"></div>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>

    <!-- Bootstrap JS -->
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <!--plugins-->
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/plugins/simplebar/js/simplebar.min.js"></script>
    <script src="assets/plugins/metismenu/js/metisMenu.min.js"></script>
    <script src="assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js"></script>
    <script src="assets/plugins/datatable/js/jquery.dataTables.min.js"></script>
    <script src="assets/plugins/datatable/js/dataTables.bootstrap5.min.js"></script>
    <script src="assets/plugins/select2/js/select2.min.js"></script>
    <script src="assets/plugins/notifications/js/lobibox.min.js"></script>
    <script src="assets/plugins/notifications/js/notifications.min.js"></script>
    <script src="assets/plugins/sweetalert2/sweetalert2.all.min.js"></script>
    <script src="assets/plugins/bootstrap-material-datetimepicker/js/moment.min.js"></script>
    <script src="assets/plugins/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.min.js"></script>
    <script src="assets/plugins/flatpickr/flatpickr.js"></script>
    <script src="assets/plugins/ckeditor/ckeditor.js"></script>
    <script src="assets/plugins/fancy-file-uploader/jquery.ui.widget.js"></script>
    <script src="assets/plugins/fancy-file-uploader/jquery.fileupload.js"></script>
    <script src="assets/plugins/fancy-file-uploader/jquery.iframe-transport.js"></script>
    <script src="assets/plugins/fancy-file-uploader/jquery.fancy-fileupload.js"></script>

    <!--app JS-->
    <script src="assets/js/app.js"></script>

    <?php
    if ($this->session->flashdata('info')) {
        $result = $this->session->flashdata('info');
        if ($result == '1') {
            $pesan = $this->session->flashdata('pesan_sukses');
        } elseif ($result == '2') {
            $pesan = $this->session->flashdata('pesan_gagal');
        } else {
            $pesan = $this->session->flashdata('pesan_gagal');
        }
    } else {
        $result = "-1";
        $pesan = "";
    }
    ?>

    <script>
        $(document).ready(function () {
            // Load page
            loadPage('dashboard');

            // Navigasi SPA
            $('[data-page]').on('click', function (e) {
                e.preventDefault();
                $('.wrapper').removeClass('toggled');
                let page = $(this).data('page');
                loadPage(page);
            });

            $(document).on('click', '[data-rapat]', function (e) {
                e.preventDefault();
                let page = $(this).data('rapat');
                let idrapat = $(this).data('id'); // kalau perlu id
                loadDetilPresensi(page, idrapat);
            });
        });
    </script>

    <script type="text/javascript">
        var config = {
            peran: '<?= $peran ?>',
            userid: '<?= $this->session->userdata('userid') ?>',
            ipServer: '<?= $this->session->userdata('ip_satker') ?>',
            isMobile: '<?= $this->agent->is_mobile() ?>',
            tokenNow: '<?= $this->session->userdata("token_now") ?>',
            tokenCookies: '<?= $this->input->cookie('presensi_token', TRUE) ?>',
            result: '<?= $result ?>',
            pesan: '<?= $pesan ?>'
        };
    </script>

    <script src="assets/js/rapat.js?v=1.1.0"></script>
</body>

</html>