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
        $this->db->select('r.id AS id, r.agenda, r.tanggal, COUNT(p.userid) AS total');
        $this->db->from('register_rapat r');
        $this->db->join('register_presensi_rapat p', 'r.id = p.idrapat', 'left');
        $this->db->where('r.hapus', '0');
        $this->db->group_by('r.id');
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
}