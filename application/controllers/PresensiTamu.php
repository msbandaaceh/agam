<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Controller publik untuk presensi tamu rapat (peserta non-SSO).
 * Menggunakan encrypted ID dari URL rapat untuk validasi akses.
 * Presensi hanya dibuka sesuai jam mulai dan selesai rapat.
 */
class PresensiTamu extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Model', 'model');
    }

    /**
     * Halaman form presensi tamu.
     * URL: /presensi-tamu/{encrypted_id}
     * Parameter di URL adalah encrypted ID rapat.
     */
    public function index($encrypted_id = null)
    {
        $idrapat = $this->decode_rapat_id($encrypted_id);
        if ($idrapat === false) {
            show_error('Link presensi tidak valid atau sudah kedaluwarsa. Silakan hubungi administrator.', 403, 'Akses Ditolak');
            return;
        }

        $rapat = $this->model->get_seleksi_array('register_rapat', ['id' => $idrapat]);
        if (!$rapat || $rapat->num_rows() === 0) {
            show_error('Agenda rapat tidak ditemukan.', 404, 'Tidak Ditemukan');
            return;
        }
        $rapat = $rapat->row();

        // Validasi waktu: presensi hanya boleh dalam rentang waktu rapat
        $tgl = date('Y-m-d');
        $now = strtotime(date('H:i:s'));
        $mulai = strtotime($rapat->mulai);
        $selesai = strtotime($rapat->selesai);

        $bisa_presensi = ($now >= $mulai && $now <= $selesai && $tgl == $rapat->tanggal);
        $alasan_tutup = null;
        if ($now < $mulai) {
            $alasan_tutup = 'Belum waktunya. Presensi dibuka pada pukul ' . $rapat->mulai . ' WIB.';
        } elseif ($now > $selesai) {
            $alasan_tutup = 'Waktu presensi sudah ditutup. Rapat telah selesai pada pukul ' . $rapat->selesai . ' WIB.';
        } elseif ($tgl != $rapat->tanggal) {
            $alasan_tutup = 'Waktu presensi sudah ditutup. Rapat diselenggarakan pada ' . $this->tanggalhelper->convertDayDate($rapat->tanggal) . ' WIB.';
        }

        // Cek apakah sudah pernah presensi (gunakan cookie sebagai pegangan sederhana)
        $sudah_presensi = $this->input->cookie('presensi_tamu_' . $idrapat);
        $presensi_lama = $sudah_presensi ? $this->model->get_seleksi_array(
            'presensi_tamu_rapat',
            ['idrapat' => $idrapat, 'hapus' => '0'],
            ['id' => 'DESC']
        )->result() : [];

        $namaHari = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
        $namaBulan = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

        $dayOfWeek = date('w', strtotime($rapat->tanggal));
        $monthNum = (int) date('n', strtotime($rapat->tanggal));

        $data = [
            'idrapat' => $idrapat,
            'encrypted_id' => $encrypted_id,
            'rapat' => $rapat,
            'bisa_presensi' => $bisa_presensi,
            'alasan_tutup' => $alasan_tutup,
            'kop' => $this->session->userdata('kop_satker') ?? '',
            'sudah_presensi' => !empty($presensi_lama) ? $presensi_lama[0] : null,
            'tanggal_formatted' => $namaHari[$dayOfWeek] . ', ' . date('d', strtotime($rapat->tanggal)) . ' ' . $namaBulan[$monthNum] . ' ' . date('Y', strtotime($rapat->tanggal)),
            'nama_pengadilan' => $this->session->userdata('nama_pengadilan') ?? '',
        ];

        $this->load->view('presensi_tamu_form', $data);
    }

    /**
     * Halaman sukses setelah submit presensi.
     * URL: /presensi-tamu-sukses/{encrypted_id}
     */
    public function sukses($encrypted_id = null)
    {
        $idrapat = $this->decode_rapat_id($encrypted_id);
        if ($idrapat === false) {
            show_error('Link tidak valid.', 403);
            return;
        }

        $rapat = $this->model->get_seleksi_array('register_rapat', ['id' => $idrapat])->row();
        if (!$rapat) {
            show_error('Rapat tidak ditemukan.', 404);
            return;
        }

        $data = [
            'rapat' => $rapat,
            'tanggal_formatted' => $this->tanggalhelper->convertDayDate($rapat->tanggal)
        ];

        $this->load->view('presensi_tamu_sukses', $data);
    }

    /**
     * Submit presensi tamu.
     * Method: POST
     */
    public function submit()
    {
        header('Content-Type: application/json');

        $encrypted_id = $this->input->post('encrypted_id');
        $idrapat = $this->decode_rapat_id($encrypted_id);

        if ($idrapat === false) {
            echo json_encode([
                'success' => false,
                'message' => 'ID presensi tidak valid.'
            ]);
            return;
        }

        // Validasi waktu lagi di sisi server
        $rapat = $this->model->get_seleksi_array('register_rapat', ['id' => $idrapat])->row();
        if (!$rapat) {
            echo json_encode(['success' => false, 'message' => 'Agenda rapat tidak ditemukan.']);
            return;
        }

        // Cek waktu berdasarkan jam mulai dan selesai rapat
        $tgl = date('Y-m-d');
        $now = strtotime(date('H:i:s'));
        $mulai = strtotime($rapat->mulai);
        $selesai = strtotime($rapat->selesai);

        if ($now < $mulai || $now > $selesai || $tgl != $rapat->tanggal) {
            echo json_encode([
                'success' => false,
                'message' => 'Presensi di luar waktu yang diizinkan.'
            ]);
            return;
        }

        // Validasi form
        $this->load->library('form_validation');
        $this->form_validation->set_rules('nama', 'Nama', 'trim|required|min_length[2]|max_length[100]');
        $this->form_validation->set_rules('jabatan_instansi', 'Jabatan/Instansi', 'trim|required|max_length[200]');
        $this->form_validation->set_rules('no_identitas', 'No. Identitas', 'trim|max_length[50]');
        $this->form_validation->set_rules('email', 'Email', 'trim|valid_email|max_length[100]');
        $this->form_validation->set_rules('no_hp', 'No. HP', 'trim|max_length[25]');

        if ($this->form_validation->run() == FALSE) {
            echo json_encode([
                'success' => false,
                'message' => validation_errors()
            ]);
            return;
        }

        // Anti-duplicate dengan rate-limit sederhana per (IP + nama + rapat) 30 detik
        $ip = $this->input->ip_address();
        $nama = trim($this->input->post('nama'));
        $key = 'presensi_tamu_' . md5($idrapat . '|' . $ip . '|' . strtolower($nama));
        $lastSubmit = $this->input->cookie($key);
        if ($lastSubmit && (time() - (int) $lastSubmit) < 30) {
            echo json_encode([
                'success' => false,
                'message' => 'Mohon tunggu beberapa saat sebelum mengirim ulang.'
            ]);
            return;
        }

        $data = [
            'idrapat' => $idrapat,
            'nama' => $nama,
            'no_identitas' => trim($this->input->post('no_identitas')) ?: null,
            'jabatan_instansi' => trim($this->input->post('jabatan_instansi')),
            'email' => trim($this->input->post('email')) ?: null,
            'no_hp' => trim($this->input->post('no_hp')) ?: null,
            'keterangan' => trim($this->input->post('keterangan')) ?: null,
        ];

        try {
            $insertId = $this->model->simpan_presensi_tamu($data);

            // Set cookie sebagai penanda (untuk UX, bukan keamanan)
            setcookie(
                'presensi_tamu_' . $idrapat,
                $insertId,
                time() + 3600,
                '/',
                $this->config->item('cookie_domain'),
                $this->config->item('cookie_secure'),
                FALSE
            );
            setcookie($key, time(), time() + 60, '/', $this->config->item('cookie_domain'));

            echo json_encode([
                'success' => true,
                'message' => 'Presensi berhasil disimpan.',
                'idrapat' => $idrapat
            ]);
        } catch (Exception $e) {
            log_message('error', 'simpan_presensi_tamu: ' . $e->getMessage());
            echo json_encode([
                'success' => false,
                'message' => 'Terjadi kesalahan. Silakan coba lagi.'
            ]);
        }
    }

    /**
     * Decode encrypted_id dari URL atau form menjadi integer ID rapat.
     * Mengembalikan int|false.
     */
    private function decode_rapat_id($encrypted_id)
    {
        if (!$encrypted_id) {
            return false;
        }

        $decoded = base64_decode($encrypted_id);
        if ($decoded === false) {
            return false;
        }

        try {
            $idrapat = $this->encryption->decrypt($decoded);
        } catch (Exception $e) {
            return false;
        }

        if (!is_numeric($idrapat)) {
            return false;
        }

        return (int) $idrapat;
    }
}
