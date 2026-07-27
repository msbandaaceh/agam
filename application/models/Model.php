<?php

class Model extends CI_Model
{
    private $db_sso;

    public function __construct()
    {
        parent::__construct();

        // Inisialisasi variabel private dengan nilai dari session
        $this->db_sso = $this->session->userdata('sso_db');
    }

    private function add_audittrail($action, $title, $table, $descrip)
    {

        $params = [
            'tabel' => 'sys_audittrail',
            'data' => [
                'datetime' => date("Y-m-d H:i:s"),
                'ipaddress' => $this->input->ip_address(),
                'action' => $action,
                'title' => $title,
                'tablename' => $table,
                'description' => $descrip,
                'username' => $this->session->userdata('username')
            ]
        ];

        $this->apihelper->post('apiclient/simpan_data', $params);
    }

    public function cek_aplikasi($id)
    {
        $params = [
            'tabel' => 'ref_client_app',
            'kolom_seleksi' => 'id',
            'seleksi' => $id
        ];

        $result = $this->apihelper->get('apiclient/get_data_seleksi', $params);

        if ($result['status_code'] === 200 && $result['response']['status'] === 'success') {
            $user_data = $result['response']['data'][0];
            $this->session->set_userdata(
                [
                    'nama_client_app' => $user_data['nama_app'],
                    'deskripsi_client_app' => $user_data['deskripsi']
                ]
            );
        }
    }

    public function kirim_notif($data)
    {
        $params = [
            'tabel' => 'sys_notif',
            'data' => $data
        ];

        $this->apihelper->post('apiclient/simpan_data', $params);
    }

    public function get_data_peran()
    {
        $this->db->select('l.id AS id, u.userid AS userid, u.fullname AS nama, l.role AS peran, l.hapus AS hapus');
        $this->db->from('peran l');
        $this->db->join($this->db_sso . '.v_users u', 'l.userid = u.userid', 'left');
        $this->db->order_by('l.id', 'ASC');
        $query = $this->db->get();

        return $query->result();
    }

    public function get_seleksi_array($tabel, $where = [], $order_by = [])
    {
        try {
            $this->db->where('hapus', '0');

            // multiple where
            if (!empty($where)) {
                foreach ($where as $kolom => $nilai) {
                    $this->db->where($kolom, $nilai);
                }
            }

            // multiple order by
            if (!empty($order_by)) {
                foreach ($order_by as $kolom => $arah) {
                    $this->db->order_by($kolom, $arah); // ASC / DESC
                }
            }

            return $this->db->get($tabel);
        } catch (Exception $e) {
            return 0;
        }
    }

    public function simpan_data($tabel, $data)
    {
        try {
            $this->db->insert($tabel, $data);
            $title = "Simpan Data <br />Update tabel <b>" . $tabel . "</b>[]";
            $descrip = null;
            $this->add_audittrail("INSERT", $title, $tabel, $descrip);
            return 1;
        } catch (Exception $e) {
            return 0;
        }
    }

    public function pembaharuan_data($tabel, $data, $kolom_seleksi, $seleksi)
    {
        try {
            $this->db->where($kolom_seleksi, $seleksi);
            $this->db->update($tabel, $data);
            $title = "Pembaharuan Data <br />Update tabel <b>" . $tabel . "</b>[Pada kolom<b>" . $kolom_seleksi . "</b>]";
            $descrip = null;
            $this->add_audittrail("UPDATE", $title, $tabel, $descrip);
            return 1;
        } catch (Exception $e) {
            return 0;
        }
    }

    public function get_register_rapat()
    {
        $this->db->select('r.id AS id, r.agenda, r.tanggal, ' .
            '(SELECT COUNT(*) FROM register_presensi_rapat WHERE idrapat = r.id AND hapus = 0) + ' .
            '(SELECT COUNT(*) FROM presensi_tamu_rapat WHERE idrapat = r.id AND hapus = 0) AS total');
        $this->db->from('register_rapat r');
        $this->db->where('r.hapus', '0');
        $this->db->order_by('r.tanggal', 'DESC');
        $query = $this->db->get();
        return $query->result();
    }

    public function get_detail_presensi_rapat($id)
    {
        $this->db->select('p.id AS id, u.nip AS nip, u.fullname AS nama, u.jabatan AS jabatan, TIME(p.created_on) AS waktu');
        $this->db->where('u.userid = p.userid AND p.idrapat = "' . $id . '"');
        $this->db->where('p.hapus', '0');
        $this->db->order_by('u.id_grup, u.jab_id', 'ASC');
        $query = $this->db->get($this->db_sso . '.v_users u, register_presensi_rapat p');

        return $query->result();
    }

    /**
     * Mengembalikan daftar hadir gabungan (pegawai + tamu) untuk dipakai pada tabel detil & PDF.
     * Field: nip, nama, jabatan, waktu, tipe
     */
    public function get_presensi_gabungan($idrapat)
    {
        $result = [];

        // Presensi internal (pegawai SSO)
        $sql_internal = "
            SELECT
                p.id AS id_raw,
                u.nip AS nip,
                u.fullname AS nama,
                u.jabatan AS jabatan,
                TIME(p.created_on) AS waktu,
                'INTERNAL' AS tipe
            FROM {$this->db_sso}.v_users u
            INNER JOIN register_presensi_rapat p ON u.userid = p.userid
            WHERE p.idrapat = " . (int) $idrapat . "
              AND p.hapus = '0'
            ORDER BY p.created_on ASC
        ";
        foreach ($this->db->query($sql_internal)->result() as $row) {
            $result[] = $row;
        }

        // Presensi tamu (non-SSO)
        $this->db->select('id AS id_raw, "" AS nip, nama, jabatan_instansi AS jabatan, TIME(waktu_presensi) AS waktu, "TAMU" AS tipe');
        $this->db->from('presensi_tamu_rapat');
        $this->db->where('idrapat', $idrapat);
        $this->db->where('hapus', '0');
        $this->db->order_by('waktu_presensi', 'ASC');
        $queryTamu = $this->db->get();

        foreach ($queryTamu->result() as $row) {
            $result[] = $row;
        }

        // Urutkan gabungan berdasarkan waktu
        usort($result, function ($a, $b) {
            return strcmp((string) $a->waktu, (string) $b->waktu);
        });

        return $result;
    }

    public function proses_simpan_agenda_rapat($data)
    {
        if ($data['id'] == "-1") {
            //buat agenda rapat baru
            $dataRapat = array(
                "tanggal" => $data['tgl'],
                "no_surat" => $data['no'],
                "tgl_undangan" => $data['tgl_undangan'],
                "penandatangan" => $data['penandatangan'],
                "mulai" => $data['mulai'],
                "selesai" => $data['selesai'],
                "tempat" => $data['tempat'],
                "agenda" => $data['agenda'],
                "notulis" => $data['notulis'],
                "peserta" => $data['peserta'],
                "dokumenter" => $data['dokumenter'],
                "created_on" => date('Y-m-d H:i:s'),
                "created_by" => $this->session->userdata("fullname")
            );

            $queryRapat = $this->simpan_data('register_rapat', $dataRapat);
        } else {
            $dataRapat = array(
                "tanggal" => $data['tgl'],
                "no_surat" => $data['no'],
                "tgl_undangan" => $data['tgl_undangan'],
                "penandatangan" => $data['penandatangan'],
                "mulai" => $data['mulai'],
                "selesai" => $data['selesai'],
                "tempat" => $data['tempat'],
                "agenda" => $data['agenda'],
                "notulis" => $data['notulis'],
                "dokumenter" => $data['dokumenter'],
                "peserta" => $data['peserta'],
                "modified_on" => date('Y-m-d H:i:s'),
                "modified_by" => $this->session->userdata("fullname")
            );

            $queryRapat = $this->pembaharuan_data('register_rapat', $dataRapat, 'id', $data['id']);
        }

        $tujuan = $this->get_seleksi_array($this->db_sso . '.v_users', ['userid' => $data['penandatangan']]);
        $fullname = $tujuan->row()->fullname;
        $jab = $tujuan->row()->jabatan;

        $peg_notulen = $this->get_seleksi_array($this->db_sso . '.v_users', ['userid' => $data['notulis']])->row()->fullname;
        $peg_dokumentasi = $this->get_seleksi_array($this->db_sso . '.v_users', ['userid' => $data['dokumenter']])->row()->fullname;

        if ($queryRapat == 1) {
            if ($data['id'] == '-1') {
                $hari = $this->tanggalhelper->convertDayDate($data['tgl']);

                $pesanWA = "Assalamualaikum Wr. Wb.\n";
                $pesanWA .= "Yth. Bapak/Ibu Aparatur MS Banda Aceh.\n";
                $pesanWA .= "Akan dilaksanakan Rapat dengan agenda *" . $data['agenda'] . "* oleh *" . $jab . " (" . $fullname . ")* pada :\n";
                $pesanWA .= "Hari : *" . $hari . "*\n";
                $pesanWA .= "Waktu : *" . $data['mulai'] . "* s/d pukul *" . $data['selesai'] . "*\n";
                $pesanWA .= "Tempat : *" . $data['tempat'] . "*\n";
                $pesanWA .= "Peserta Rapat :\n";
                $pesanWA .= "*" . $data['peserta'] . "*\n";
                $pesanWA .= "Notulensi oleh *" . $peg_notulen . "*\n";
                $pesanWA .= "Dokumentasi oleh *" . $peg_dokumentasi . "*\n";
                $pesanWA .= "Demikian diinformasikan, Terima Kasih atas perhatian.";

                $dataNotifRapat = array(
                    'jenis_pesan' => 'wag',
                    'id_pemohon' => $this->session->userdata("userid"),
                    'pesan' => $pesanWA,
                    'id_tujuan' => '999',
                    'created_by' => 'system',
                    'created_on' => date('Y-m-d H:i:s')
                );

                $this->kirim_notif($dataNotifRapat);

                if ($data['notulis'] != '') {
                    $tujuan = $this->get_seleksi_array($this->db_sso . '.v_users', ['userid' => $data['notulis']]);
                    $fullname = $tujuan->row()->fullname;

                    $pesanWA = "Assalamualaikum Wr. Wb., Yth. " . $fullname . ".\n";
                    $pesanWA .= "Ada ditunjuk menjadi notulis rapat, dengan agenda " . $data['agenda'] . " pada hari " . $hari . " pukul " . $data['mulai'] . " s/d " . $data['selesai'] . ".\n";
                    $pesanWA .= "Demikian diinformasikan, Terima Kasih atas perhatian.";

                    $dataNotif = array(
                        'jenis_pesan' => 'rapat',
                        'id_pemohon' => $this->session->userdata("userid"),
                        'pesan' => $pesanWA,
                        'id_tujuan' => $tujuan->row()->pegawai_id,
                        'created_by' => $this->session->userdata('fullname'),
                        'created_on' => date('Y-m-d H:i:s')
                    );

                    $this->kirim_notif($dataNotif);
                }

                if ($data['dokumenter'] != '') {
                    $tugas = $this->get_seleksi_array($this->db_sso . '.v_users', ['userid' => $data['dokumenter']]);
                    $fullname = $tugas->row()->fullname;

                    $pesanWA = "Assalamualaikum Wr. Wb., Yth. " . $fullname . ".\n";
                    $pesanWA .= "Ada ditugaskan untuk mengambil dokumentasi rapat, dengan agenda " . $data['agenda'] . " pada hari " . $hari . " pukul " . $data['mulai'] . " s/d " . $data['selesai'] . ".\n";
                    $pesanWA .= "Demikian diinformasikan, Terima Kasih atas perhatian.";

                    $dataNotif = array(
                        'jenis_pesan' => 'rapat',
                        'id_pemohon' => $this->session->userdata("userid"),
                        'pesan' => $pesanWA,
                        'id_tujuan' => $tugas->row()->pegawai_id,
                        'created_by' => $this->session->userdata('fullname'),
                        'created_on' => date('Y-m-d H:i:s')
                    );

                    $this->kirim_notif($dataNotif);
                }
                return ['status' => true, 'message' => 'Agenda Rapat Berhasil di Tambahkan'];
            } else {
                if ($data['info'] == '1') {
                    $hari = $this->tanggalhelper->convertDayDate($data['tgl']);

                    $pesanWA = "Assalamualaikum Wr. Wb.\n";
                    $pesanWA .= "Yth. Bapak/Ibu Aparatur MS Banda Aceh.\n";
                    $pesanWA .= "Ada perubahan pada agenda *" . $data['agenda'] . "* oleh *" . $jab . " (" . $fullname . ")* pada :\n";
                    $pesanWA .= "Hari : *" . $hari . "*\n";
                    $pesanWA .= "Waktu : *" . $data['mulai'] . "* s/d pukul *" . $data['selesai'] . "*\n";
                    $pesanWA .= "Tempat : *" . $data['tempat'] . "*\n";
                    $pesanWA .= "Peserta Rapat :\n";
                    $pesanWA .= "*" . $data['peserta'] . "*\n";
                    $pesanWA .= "Notulensi oleh *" . $peg_notulen . "*\n";
                    $pesanWA .= "Dokumentasi oleh *" . $peg_dokumentasi . "*\n";
                    $pesanWA .= "Demikian perubahan yang diinformasikan, Terima Kasih atas perhatian.";

                    $dataNotifRapat = array(
                        'jenis_pesan' => 'wag',
                        'id_pemohon' => $this->session->userdata("userid"),
                        'pesan' => $pesanWA,
                        'id_tujuan' => '999',
                        'created_by' => 'system',
                        'created_on' => date('Y-m-d H:i:s')
                    );

                    $this->kirim_notif($dataNotifRapat);

                    if ($data['notulis'] != '') {
                        $queryCekNotulis = $this->get_seleksi_array('register_rapat', ['id' => $data['id']]);
                        $notulis_awal = $queryCekNotulis->row()->notulis;

                        if ($data['notulis'] != $notulis_awal) {
                            $tujuan = $this->get_seleksi_array($this->db_sso . '.v_users', ['userid' => $data['notulis']]);
                            $fullname = $tujuan->row()->fullname;

                            $pesanWA = "Assalamualaikum Wr. Wb., Yth. " . $fullname . ".\n";
                            $pesanWA .= "Ada ditunjuk menjadi notulis rapat, dengan agenda " . $data['agenda'] . " pada hari " . $hari . " pukul " . $data['mulai'] . " s/d " . $data['selesai'] . ".\n";
                            $pesanWA .= "Demikian diinformasikan, Terima Kasih atas perhatian.";

                            $dataNotif = array(
                                'jenis_pesan' => 'rapat',
                                'id_pemohon' => $this->session->userdata("userid"),
                                'pesan' => $pesanWA,
                                'id_tujuan' => $tujuan->row()->pegawai_id,
                                'created_by' => $this->session->userdata('fullname'),
                                'created_on' => date('Y-m-d H:i:s')
                            );

                            $this->kirim_notif($dataNotif);
                        }

                        $queryCekDokumenter = $this->get_seleksi_array('register_rapat', ['id' => $data['id']]);
                        $dokumenter_awal = $queryCekDokumenter->row()->dokumenter;

                        if ($data['dokumenter'] != $dokumenter_awal) {
                            $tugas = $this->get_seleksi_array($this->db_sso . '.v_users', ['userid' => $data['dokumenter']]);
                            $fullname = $tugas->row()->fullname;

                            $pesanWA = "Assalamualaikum Wr. Wb., Yth. " . $fullname . ".\n";
                            $pesanWA .= "Ada ditugaskan untuk mengambil dokumentasi rapat, dengan agenda " . $data['agenda'] . " pada hari " . $hari . " pukul " . $data['mulai'] . " s/d " . $data['selesai'] . ".\n";
                            $pesanWA .= "Demikian diinformasikan, Terima Kasih atas perhatian.";

                            $dataNotif = array(
                                'jenis_pesan' => 'rapat',
                                'id_pemohon' => $this->session->userdata("userid"),
                                'pesan' => $pesanWA,
                                'id_tujuan' => $tugas->row()->pegawai_id,
                                'created_by' => $this->session->userdata('fullname'),
                                'created_on' => date('Y-m-d H:i:s')
                            );

                            $this->kirim_notif($dataNotif);
                        }
                    }
                }
                return ['status' => true, 'message' => 'Agenda Rapat Berhasil di Perbarui'];
            }
        } else {
            return ['status' => false, 'message' => 'Gagal Simpan Agenda Rapat, ' . $queryRapat];
        }
    }

    public function proses_simpan_presensi($data)
    {
        $userid = $this->session->userdata('userid');
        $idrapat = $data['idrapat'];

        # Cek Waktu Rapat
        $queryPresensiRapat = $this->get_seleksi_array('register_rapat', ['id' => $idrapat]);
        $mulai = strtotime($queryPresensiRapat->row()->mulai);
        $selesai = strtotime($queryPresensiRapat->row()->selesai);
        $jamNow = strtotime(date('H:i:s'));
        if ($jamNow >= $mulai && $jamNow <= $selesai) {
            $cekPresensi = $this->get_seleksi_array('register_presensi_rapat', ['idrapat' => $idrapat, 'userid' => $userid]);
            //die(var_dump($cekPresensi));
            if ($cekPresensi->num_rows() > 0) { // If already checked in today
                $querySimpan = 2;
            } else { // If not checked in today
                $dataPengguna = array(
                    'userid' => $userid,
                    'idrapat' => $idrapat,
                    'created_on' => date('Y-m-d H:i:s')
                );

                $querySimpan = $this->simpan_data('register_presensi_rapat', $dataPengguna);
            }

            if ($querySimpan == 1) {
                return ['status' => true, 'message' => 'Presensi Berhasil Disimpan'];
            } elseif ($querySimpan == 2) {
                return ['status' => false, 'message' => 'Anda Sudah Presensi Untuk Rapat ini !'];
            } else {
                return ['status' => false, 'message' => 'Presensi Gagal Simpan, Silakan Ulangi Lagi'];
            }
        } else {
            return ['status' => false, 'message' => 'Anda tidak dapat melakukan Presensi karena berada di luar waktu Rapat yang ditentukan'];
        }
    }

    public function proses_simpan_presensi_manual($data)
    {
        $dataPresensi = [
            'idrapat' => $data['idrapat'],
            'userid' => $data['pegawai'],
            'created_on' => $data['waktu']
        ];

        if ($data['id'] == -1)
            $query = $this->simpan_data('register_presensi_rapat', $dataPresensi);
        else {
            $cariPresensi = $this->get_seleksi_array('register_presensi_rapat', ['id' => $data['id']]);
            if ($cariPresensi->num_rows() > 0)
                $query = $this->pembaharuan_data('register_presensi_rapat', $dataPresensi, 'id', $data['id']);
            else
                return ['status' => false, 'message' => 'ID Presensi Tidak Ditemukan'];
        }

        if ($query == 1)
            return ['status' => true, 'message' => 'Presensi Berhasil Disimpan'];
        else
            return ['status' => false, 'message' => 'Presensi Gagal Simpan, Silakan Ulangi Lagi'];
    }

    public function proses_simpan_presensi_manual_tamu($data)
    {
        if ($data['id'] == -1 || $data['id'] == null) {
            $data['id'] = null;
            $query = $this->simpan_data('presensi_tamu_rapat', $data);
        } else {
            $cariPresensi = $this->get_seleksi_array('presensi_tamu_rapat', ['id' => $data['id']]);
            if ($cariPresensi->num_rows() > 0)
                $query = $this->pembaharuan_data('presensi_tamu_rapat', $data, 'id', $data['id']);
            else
                return ['status' => false, 'message' => 'ID Presensi Tidak Ditemukan'];
        }

        if ($query == 1)
            return ['status' => true, 'message' => 'Presensi Berhasil Disimpan'];
        else
            return ['status' => false, 'message' => 'Presensi Gagal Simpan, Silakan Ulangi Lagi'];
    }

    public function get_rapat_5_menit_sebelum_mulai()
    {
        // Mendapatkan waktu sekarang
        $this->db->select('*');
        $this->db->from('register_rapat');
        $this->db->where('TIME(NOW()) BETWEEN TIME(mulai) - INTERVAL 5 MINUTE AND TIME(mulai)');
        $this->db->where('tanggal = DATE(NOW())');
        $this->db->where('reminder', 0);
        $this->db->where('hapus', 0);

        // Menjalankan query dan mengambil hasilnya
        $query = $this->db->get();
        return $query;
    }

    public function get_rapat_kalender($tgl_awal, $tgl_akhir)
    {
        $this->db->select('id, tanggal, agenda, mulai, selesai, tempat, peserta');
        $this->db->from('register_rapat');
        $this->db->where('hapus', '0');
        $this->db->where("tanggal >= '" . $tgl_awal . "'");
        $this->db->where("tanggal <= '" . $tgl_akhir . "'");
        $this->db->order_by('tanggal', 'ASC');
        $this->db->order_by('mulai', 'ASC');
        return $this->db->get()->result_array();
    }

    // ==================== Presensi Tamu (Non-SSO) ====================

    /**
     * Ambil presensi internal saja (pegawai SSO) untuk rapat tertentu.
     * Digunakan oleh template PDF daftar hadir untuk memisahkan bagian internal & tamu.
     */
    public function get_presensi_internal($idrapat)
    {
        $this->db->select('p.id AS id, u.nip AS nip, u.fullname AS nama, u.jabatan AS jabatan, TIME(p.created_on) AS waktu');
        $this->db->from($this->db_sso . '.v_users u');
        $this->db->join('register_presensi_rapat p', 'u.userid = p.userid');
        $this->db->where('p.idrapat', (int) $idrapat);
        $this->db->where('p.hapus', '0');
        $this->db->order_by('p.created_on', 'ASC');
        return $this->db->get()->result();
    }

    public function generate_qr_token($id_rapat)
    {
        $token = bin2hex(random_bytes(24));

        $data = [
            'qr_token' => $token,
            'modified_on' => date('Y-m-d H:i:s'),
            'modified_by' => $this->session->userdata('fullname')
        ];

        $result = $this->pembaharuan_data('register_rapat', $data, 'id', $id_rapat);

        if ($result == 1) {
            return $token;
        }

        return false;
    }

    public function cek_qr_token($token)
    {
        $this->db->select('rr.*, rt.nama, rt.no_identitas, rt.jabatan_instansi, rt.waktu_presensi');
        $this->db->from('register_rapat rr');
        $this->db->where('rr.qr_token', $token);
        $this->db->where('rr.hapus', '0');
        $query = $this->db->get();
        return $query->row();
    }

    public function simpan_presensi_tamu($data)
    {
        $presensiData = [
            'idrapat' => $data['idrapat'],
            'nama' => $data['nama'],
            'no_identitas' => $data['no_identitas'] ?? null,
            'jabatan_instansi' => $data['jabatan_instansi'] ?? null,
            'waktu_presensi' => date('Y-m-d H:i:s'),
            'created_by' => 'tamu',
            'created_on' => date('Y-m-d H:i:s')
        ];

        $this->db->insert('presensi_tamu_rapat', $presensiData);
        return $this->db->insert_id();
    }

    public function get_detail_presensi_rapat_lengkap($idrapat)
    {
        $combined = [];

        // Presensi internal (pegawai SSO)
        $this->db->select('p.id AS id, u.nip AS identitas, u.fullname AS nama, u.jabatan AS jabatan_instansi, TIME(p.created_on) AS waktu, "INTERNAL" AS tipe');
        $this->db->where('u.userid = p.userid AND p.idrapat = "' . $idrapat . '"');
        $this->db->where('p.hapus', '0');
        $this->db->order_by('u.id_grup, u.jab_id', 'ASC');
        $queryInternal = $this->db->get($this->db_sso . '.v_users u, register_presensi_rapat p');

        foreach ($queryInternal->result() as $row) {
            $combined[] = (object) [
                'id' => base64_encode($this->encryption->encrypt($row->id)),
                'idraw' => $row->id,
                'nama' => $row->nama,
                'identitas' => $row->identitas,
                'jabatan_instansi' => $row->jabatan_instansi,
                'waktu' => $row->waktu,
                'tipe' => $row->tipe
            ];
        }

        // Presensi tamu (non-SSO)
        $this->db->select('id, nama, no_identitas AS identitas, jabatan_instansi, TIME(waktu_presensi) AS waktu, "TAMU" AS tipe');
        $this->db->where('idrapat', $idrapat);
        $this->db->where('hapus', '0');
        $this->db->order_by('nama', 'ASC');
        $queryTamu = $this->db->get('presensi_tamu_rapat');

        foreach ($queryTamu->result() as $row) {
            $combined[] = (object) [
                'id' => base64_encode($this->encryption->encrypt($row->id)),
                'idraw' => $row->id,
                'nama' => $row->nama,
                'identitas' => $row->identitas,
                'jabatan_instansi' => $row->jabatan_instansi,
                'waktu' => $row->waktu,
                'tipe' => $row->tipe
            ];
        }

        // Urutkan berdasarkan waktu
        usort($combined, function ($a, $b) {
            return strcmp($a->waktu, $b->waktu);
        });

        return $combined;
    }

    public function get_daftar_tamu($idrapat)
    {
        $this->db->select('id, nama, no_identitas, jabatan_instansi, TIME(waktu_presensi) AS waktu, "TAMU" AS tipe');
        $this->db->where('idrapat', $idrapat);
        $this->db->where('hapus', '0');
        $this->db->order_by('nama', 'ASC');
        return $this->db->get('presensi_tamu_rapat')->result();
    }

    public function get_count_presensi($idrapat)
    {
        $internal = $this->db->query("SELECT COUNT(*) as total FROM register_presensi_rapat WHERE idrapat = {$idrapat} AND hapus = 0")->row();
        $tamu = $this->db->query("SELECT COUNT(*) as total FROM presensi_tamu_rapat WHERE idrapat = {$idrapat} AND hapus = 0")->row();

        return (int) ($internal->total ?? 0) + (int) ($tamu->total ?? 0);
    }

}