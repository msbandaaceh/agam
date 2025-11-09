<?php

class HalamanUtama extends MY_Controller
{
    public function index()
    {
        #die(var_dump($this->session->all_userdata()));
        $peran = $this->session->userdata('peran');
        $userid = $this->session->userdata('userid');
        
        $data['peran'] = $peran;
        $data['page'] = 'dashboard';
        
        // Data rapat hari ini
        $data['rapat_hari_ini'] = $this->model->get_seleksi_array('register_rapat', [
            'tanggal' => date('Y-m-d'),
            'hapus' => '0'
        ], ['mulai' => 'ASC'])->result();
        
        // Data rapat mendatang (7 hari ke depan)
        $tanggal_mendatang = date('Y-m-d', strtotime('+7 days'));
        $this->db->select('*');
        $this->db->from('register_rapat');
        $this->db->where('tanggal >=', date('Y-m-d'));
        $this->db->where('tanggal <=', $tanggal_mendatang);
        $this->db->where('hapus', '0');
        $this->db->order_by('tanggal', 'ASC');
        $this->db->order_by('mulai', 'ASC');
        $this->db->limit(5);
        $data['rapat_mendatang'] = $this->db->get()->result();
        
        // Statistik rapat bulan ini
        $bulan_ini = date('Y-m');
        $this->db->select('COUNT(*) as total_rapat');
        $this->db->from('register_rapat');
        $this->db->where("DATE_FORMAT(tanggal, '%Y-%m') = '{$bulan_ini}'", null, false);
        $this->db->where('hapus', '0');
        $stat_rapat = $this->db->get()->row();
        $data['statistik_bulan']['total_rapat'] = $stat_rapat->total_rapat ?? 0;
        
        // Total presensi bulan ini
        $this->db->select('COUNT(DISTINCT p.idrapat) as rapat_dengan_presensi, COUNT(p.id) as total_presensi');
        $this->db->from('register_rapat r');
        $this->db->join('register_presensi_rapat p', 'r.id = p.idrapat', 'left');
        $this->db->where("DATE_FORMAT(r.tanggal, '%Y-%m') = '{$bulan_ini}'", null, false);
        $this->db->where('r.hapus', '0');
        $this->db->where('p.hapus', '0');
        $stat_presensi = $this->db->get()->row();
        $data['statistik_bulan']['rapat_dengan_presensi'] = $stat_presensi->rapat_dengan_presensi ?? 0;
        $data['statistik_bulan']['total_presensi'] = $stat_presensi->total_presensi ?? 0;
        
        // Rapat terbaru (5 terakhir)
        $this->db->select('r.*, COUNT(p.id) as jumlah_presensi');
        $this->db->from('register_rapat r');
        $this->db->join('register_presensi_rapat p', 'r.id = p.idrapat AND p.hapus = "0"', 'left');
        $this->db->where('r.hapus', '0');
        $this->db->group_by('r.id');
        $this->db->order_by('r.tanggal', 'DESC');
        $this->db->order_by('r.created_on', 'DESC');
        $this->db->limit(5);
        $data['rapat_terbaru'] = $this->db->get()->result();
        
        // Tugas sebagai notulis atau dokumenter
        $this->db->select('*');
        $this->db->from('register_rapat');
        $this->db->where('(notulis = "' . $userid . '" OR dokumenter = "' . $userid . '")');
        $this->db->where('tanggal >=', date('Y-m-d'));
        $this->db->where('hapus', '0');
        $this->db->order_by('tanggal', 'ASC');
        $this->db->order_by('mulai', 'ASC');
        $data['tugas_rapat'] = $this->db->get()->result();
        
        // Cek presensi hari ini
        $data['presensi_hari_ini'] = [];
        if (!empty($data['rapat_hari_ini'])) {
            foreach ($data['rapat_hari_ini'] as $rapat) {
                $cek_presensi = $this->model->get_seleksi_array('register_presensi_rapat', [
                    'idrapat' => $rapat->id,
                    'userid' => $userid,
                    'hapus' => '0'
                ]);
                if ($cek_presensi->num_rows() > 0) {
                    $data['presensi_hari_ini'][$rapat->id] = $cek_presensi->row();
                }
            }
        }

        $this->load->view('layout', $data);
    }

    public function page($halaman)
    {
        // Amanin nama file view agar tidak sembarang file bisa diload
        $allowed = [
            'dashboard',
            'agenda_rapat',
            'presensi_rapat',
            'dokumen_rapat',
            'panduan',
            'dokumentasi_teknis'
        ];

        if (in_array($halaman, $allowed)) {
            $peran = $this->session->userdata('peran');
            $userid = $this->session->userdata('userid');
            
            $data['peran'] = $peran;
            $data['page'] = $halaman;
            
            // Jika halaman dashboard, siapkan data yang sama seperti index()
            if ($halaman == 'dashboard') {
                // Data rapat hari ini
                $data['rapat_hari_ini'] = $this->model->get_seleksi_array('register_rapat', [
                    'tanggal' => date('Y-m-d'),
                    'hapus' => '0'
                ], ['mulai' => 'ASC'])->result();
                
                // Data rapat mendatang (7 hari ke depan)
                $tanggal_mendatang = date('Y-m-d', strtotime('+7 days'));
                $this->db->select('*');
                $this->db->from('register_rapat');
                $this->db->where('tanggal >=', date('Y-m-d'));
                $this->db->where('tanggal <=', $tanggal_mendatang);
                $this->db->where('hapus', '0');
                $this->db->order_by('tanggal', 'ASC');
                $this->db->order_by('mulai', 'ASC');
                $this->db->limit(5);
                $data['rapat_mendatang'] = $this->db->get()->result();
                
                // Statistik rapat bulan ini
                $bulan_ini = date('Y-m');
                $this->db->select('COUNT(*) as total_rapat');
                $this->db->from('register_rapat');
                $this->db->where("DATE_FORMAT(tanggal, '%Y-%m') = '{$bulan_ini}'", null, false);
                $this->db->where('hapus', '0');
                $stat_rapat = $this->db->get()->row();
                $data['statistik_bulan']['total_rapat'] = $stat_rapat->total_rapat ?? 0;
                
                // Total presensi bulan ini
                $this->db->select('COUNT(DISTINCT p.idrapat) as rapat_dengan_presensi, COUNT(p.id) as total_presensi');
                $this->db->from('register_rapat r');
                $this->db->join('register_presensi_rapat p', 'r.id = p.idrapat', 'left');
                $this->db->where("DATE_FORMAT(r.tanggal, '%Y-%m') = '{$bulan_ini}'", null, false);
                $this->db->where('r.hapus', '0');
                $this->db->where('p.hapus', '0');
                $stat_presensi = $this->db->get()->row();
                $data['statistik_bulan']['rapat_dengan_presensi'] = $stat_presensi->rapat_dengan_presensi ?? 0;
                $data['statistik_bulan']['total_presensi'] = $stat_presensi->total_presensi ?? 0;
                
                // Rapat terbaru (5 terakhir)
                $this->db->select('r.*, COUNT(p.id) as jumlah_presensi');
                $this->db->from('register_rapat r');
                $this->db->join('register_presensi_rapat p', 'r.id = p.idrapat AND p.hapus = "0"', 'left');
                $this->db->where('r.hapus', '0');
                $this->db->group_by('r.id');
                $this->db->order_by('r.tanggal', 'DESC');
                $this->db->order_by('r.created_on', 'DESC');
                $this->db->limit(5);
                $data['rapat_terbaru'] = $this->db->get()->result();
                
                // Tugas sebagai notulis atau dokumenter
                $this->db->select('*');
                $this->db->from('register_rapat');
                $this->db->where('(notulis = "' . $userid . '" OR dokumenter = "' . $userid . '")');
                $this->db->where('tanggal >=', date('Y-m-d'));
                $this->db->where('hapus', '0');
                $this->db->order_by('tanggal', 'ASC');
                $this->db->order_by('mulai', 'ASC');
                $data['tugas_rapat'] = $this->db->get()->result();
                
                // Cek presensi hari ini
                $data['presensi_hari_ini'] = [];
                if (!empty($data['rapat_hari_ini'])) {
                    foreach ($data['rapat_hari_ini'] as $rapat) {
                        $cek_presensi = $this->model->get_seleksi_array('register_presensi_rapat', [
                            'idrapat' => $rapat->id,
                            'userid' => $userid,
                            'hapus' => '0'
                        ]);
                        if ($cek_presensi->num_rows() > 0) {
                            $data['presensi_hari_ini'][$rapat->id] = $cek_presensi->row();
                        }
                    }
                }
            }

            $this->load->view($halaman, $data);
        } else {
            show_404();
        }
    }

    public function show_presensi_rapat()
    {
        $hari = $this->tanggalhelper->convertDayDate(date('Y-m-d', time()));
        $jam = date('H:i:s');

        $query = $this->model->get_seleksi_array('register_rapat', ['tanggal' => date('Y-m-d')]);
        $rapat = array();
        $rapat[''] = "-- Pilih Agenda Rapat --";
        foreach ($query->result() as $row) {
            $rapat[$row->id] = $row->agenda;
        }

        $agenda_rapat = form_dropdown('rapat', $rapat, '', 'class="form-control select2"  id="rapat"');

        echo json_encode(
            array(
                'st' => 1,
                'hari' => $hari,
                'jam' => $jam,
                'rapat' => $agenda_rapat
            )
        );
        return;
    }

    public function simpan_presensi() {
        $this->form_validation->set_rules('rapat', 'Agenda Rapat', 'trim|required');
        $this->form_validation->set_message(['required' => '%s Belum Dipilih']);

        if ($this->form_validation->run() == FALSE) {
            echo json_encode(['success' => 2, 'message' => validation_errors()]);
            return;
        }

        $data = [
            'idrapat' => $this->input->post('rapat')
        ];

        $result = $this->model->proses_simpan_presensi($data);
        if ($result['status']) {
            echo json_encode(['success' => 1, 'message' => $result['message']]);
        } else {
            echo json_encode(['success' => 3, 'message' => $result['message']]);
        }
    }

    public function cek_token_sso()
    {
        $token = $this->input->cookie('sso_token');
        $cookie_domain = $this->config->item('sso_server');
        $sso_api = $cookie_domain . "api/cek_token?sso_token={$token}";
        $response = file_get_contents($sso_api);
        $data = json_decode($response, true);

        if ($data['status'] == 'success') {
            echo json_encode(['valid' => true]);
        } else {
            echo json_encode(['valid' => false, 'message' => 'Token Expired, Silakan login ulang', 'url' => $cookie_domain . 'login']);
        }
    }

    public function keluar()
    {
        $sso_server = $this->config->item('sso_server');
        $this->session->sess_destroy();
        redirect($sso_server . '/keluar');
    }

    public function show_role()
    {
        $id = $this->input->post('id');
        $data = [
            "tabel" => "v_users",
            "kolom_seleksi" => "status_pegawai",
            "seleksi" => "1"
        ];

        $users = $this->apihelper->get('apiclient/get_data_seleksi', $data);

        $pegawai = array();
        if ($users['status_code'] === '200') {
            foreach ($users['response']['data'] as $item) {
                $pegawai[$item['userid']] = $item['fullname'];
            }
        }

        if ($id != '-1') {
            $query = $this->model->get_seleksi_array('peran', ['id' => $id]);

            echo json_encode(
                array(
                    'pegawai' => $users['response']['data'],
                    'role' => $pegawai,
                    'id' => $query->row()->id,
                    'editPegawai' => $query->row()->userid,
                    'editPeran' => $query->row()->role
                )
            );
        } else {
            $dataPeran = $this->model->get_data_peran();
            #die(var_dump($dataPeran));

            echo json_encode(
                array(
                    'pegawai' => $users['response']['data'],
                    'role' => $pegawai,
                    'data_peran' => $dataPeran
                )
            );
        }

        return;
    }

    public function simpan_peran()
    {
        $id = $this->input->post('id');
        $pegawai = $this->input->post('pegawai');
        $peran = $this->input->post('peran');

        if ($id) {
            $data = array(
                'userid' => $pegawai,
                'role' => $peran,
                'modified_by' => $this->session->userdata('fullname'),
                'modified_on' => date('Y-m-d H:i:s')
            );

            $query = $this->model->pembaharuan_data('peran', $data, 'id', $id);
        } else {
            $query = $this->model->get_seleksi_array('peran', ['userid' => $pegawai]);
            if ($query->num_rows() > 0) {
                echo json_encode(['success' => 2, 'message' => 'Pegawai tersebut sudah memiliki peran']);
            }

            $data = array(
                'userid' => $pegawai,
                'role' => $peran,
                'created_by' => $this->session->userdata('fullname'),
                'created_on' => date('Y-m-d H:i:s')
            );

            $query = $this->model->simpan_data('peran', $data);
        }

        if ($query === 1) {
            echo json_encode(['success' => 1, 'message' => 'Penunjukan Peran Pegawai Berhasil']);
        } else {
            echo json_encode(['success' => 3, 'message' => 'Gagal Menunjuk Peran Pegawai']);
        }
    }

    public function aktif_peran()
    {
        $id = $this->input->post('id');

        $data = array(
            'hapus' => '0',
            'modified_by' => $this->session->userdata('username'),
            'modified_on' => date('Y-m-d H:i:s')
        );

        $query = $this->model->pembaharuan_data('peran', $data, 'id', $id);
        if ($query == '1') {
            echo json_encode(
                array(
                    'st' => '1'
                )
            );
        } else {
            echo json_encode(
                array(
                    'st' => '0'
                )
            );
        }
    }

    public function blok_peran()
    {
        $id = $this->input->post('id');

        $data = array(
            'hapus' => '1',
            'modified_by' => $this->session->userdata('username'),
            'modified_on' => date('Y-m-d H:i:s')
        );

        $query = $this->model->pembaharuan_data('peran', $data, 'id', $id);
        if ($query == '1') {
            echo json_encode(
                array(
                    'st' => '1'
                )
            );
        } else {
            echo json_encode(
                array(
                    'st' => '0'
                )
            );
        }
    }

    public function simpan_perangkat()
    {
        $id = $this->session->userdata('userid');
        $token = md5(uniqid());

        $payload = [
            'tabel' => 'sys_users',
            'kunci' => 'userid',
            'id' => $id,
            'data' => [
                'token_pres' => $token,
                'modified_by' => $this->session->userdata('fullname'),
                'modified_on' => date('Y-m-d H:i:s')
            ]
        ];

        $result = $this->apihelper->patch('api_update', $payload);

        if ($result['status_code'] === 200 && !empty($result['response']['status'])) {
            $cookie_domain = $this->config->item('cookie_domain');
            setcookie(
                'presensi_token',
                $token,
                [
                    'expires' => time() + (86500 * 30 * 12),
                    'path' => '/',
                    'domain' => $cookie_domain, // pastikan subdomain
                    'secure' => $this->config->item('cookie_secure'), // hanya jika HTTPS
                    'httponly' => true,
                    'samesite' => 'Lax', // atau 'Strict'
                ]
            );
            $this->session->set_userdata("token_now", $token);

            echo json_encode(
                array(
                    'st' => 1
                )
            );
        } else {
            echo json_encode(
                array(
                    'st' => 0
                )
            );
        }

        return;
    }
}