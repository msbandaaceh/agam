<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$route['default_controller'] = 'HalamanUtama';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

$route['cek_token'] = 'HalamanUtama/cek_token_sso';

$route['simpan_perangkat'] = 'HalamanUtama/simpan_perangkat';

$route['show_tabel_agenda_rapat'] = 'HalamanAgendaRapat/show_tabel_agenda_rapat';
$route['show_tabel_presensi_rapat'] = 'HalamanLaporan/show_tabel_presensi_rapat';
$route['show_tabel_detil_presensi_rapat'] = 'HalamanLaporan/show_tabel_detil_presensi_rapat';
$route['show_rapat'] = 'HalamanAgendaRapat/show_rapat';
$route['show_notulen'] = 'HalamanAgendaRapat/show_notulen';
$route['show_dokumentasi'] = 'HalamanAgendaRapat/show_dokumentasi';
$route['get_rapat_kalender'] = 'HalamanAgendaRapat/get_rapat_kalender';
$route['show_presensi_rapat'] = 'HalamanUtama/show_presensi_rapat';
$route['show_presensi'] = 'HalamanLaporan/show_presensi';
$route['simpan_presensi'] = 'HalamanUtama/simpan_presensi';
$route['simpan_presensi_pegawai'] = 'HalamanLaporan/simpan_presensi_pegawai';
$route['simpan_rapat'] = 'HalamanAgendaRapat/simpan_rapat';
$route['simpan_notulen'] = 'HalamanAgendaRapat/simpan_notulen';
$route['simpan_dokumentasi'] = 'HalamanAgendaRapat/simpan_dokumentasi';

$route['hapus_rapat'] = 'HalamanAgendaRapat/hapus_rapat';
$route['hapus_presensi'] = 'HalamanLaporan/hapus_presensi';
$route['hapus_dokumentasi'] = 'HalamanAgendaRapat/hapus_dokumentasi';

$route['unduh_dokumen'] = 'HalamanLaporan/unduh_dokumen';

# ROUTE PROSES PERAN
$route['show_role'] = 'HalamanUtama/show_role';
$route['simpan_peran'] = 'HalamanUtama/simpan_peran';
$route['blok_peran'] = 'HalamanUtama/blok_peran';
$route['aktif_peran'] = 'HalamanUtama/aktif_peran';

$route['keluar'] = 'HalamanUtama/keluar';

# ROUTE PRESENSI TAMU (publik tanpa SSO)
$route['presensi-tamu/(:any)'] = 'PresensiTamu/index/$1';
$route['simpan_presensi_tamu'] = 'PresensiTamu/submit';
$route['presensi-tamu-sukses/(:any)'] = 'PresensiTamu/sukses/$1';
$route['show_presensi_tamu_qr/(:any)'] = 'HalamanLaporan/show_presensi_tamu_qr/$1';

$route['show_qr_tamu'] = 'HalamanLaporan/show_qr_tamu';
$route['show_presensi_tamu'] = 'HalamanLaporan/show_presensi_tamu';
$route['hapus_presensi_tamu'] = 'HalamanLaporan/hapus_presensi_tamu';
$route['simpan_presensi_tamu_manual'] = 'HalamanLaporan/simpan_presensi_tamu';