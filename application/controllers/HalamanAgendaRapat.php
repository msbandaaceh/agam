<?php

class HalamanAgendaRapat extends MY_Controller
{
    public function show_tabel_agenda_rapat()
    {
        $query = $this->model->get_seleksi_array('register_rapat', '', ['tanggal' => 'DESC'])->result();

        $data = [];
        foreach ($query as $row) {
            $data[] = [
                'id' => base64_encode($this->encryption->encrypt($row->id)),
                'tgl' => $this->tanggalhelper->convertDayDate($row->tanggal),
                'agenda' => $row->agenda,
                'notulis' => $row->notulis,
                'dokumenter' => $row->dokumenter
            ];
        }

        echo json_encode(['data_rapat' => $data]);
    }

    public function show_rapat()
    {
        $id = $this->encryption->decrypt(base64_decode($this->input->post('id')));
        $pegawai = array();

        $params = [
            'tabel' => 'v_users'
        ];

        $result = $this->apihelper->get('apiclient/get_data_tabel', $params);

        if ($result['status_code'] === 200 && $result['response']['status'] === 'success') {
            $pegawai[''] = "-- Pilih Pegawai --";
            foreach ($result['response']['data'] as $row) {
                $pegawai[$row['userid']] = $row['fullname'];
            }
        }

        $mulai = "";
        $selesai = "";
        $tempat = "";
        $agenda = "";
        $peserta = "";
        $no = "";
        $tgl = "";
        $tgl_undangan = "";

        if ($id == '-1') {
            $judul = "TAMBAH DATA AGENDA RAPAT";
            $pengundang = form_dropdown('pengundang', $pegawai, '', 'class="form-control select2" id="pengundang"');
            $notulis = form_dropdown('notulis', $pegawai, '', 'class="form-control select2" id="notulis"');
            $dokumenter = form_dropdown('dokumenter', $pegawai, '', 'class="form-control select2" id="dokumenter"');
        } else {
            $judul = "DETAIL AGENDA RAPAT";
            $queryRapat = $this->model->get_seleksi_array('register_rapat', ['id' => $id]);
            $mulai = $queryRapat->row()->mulai;
            $selesai = $queryRapat->row()->selesai;
            $tempat = $queryRapat->row()->tempat;
            $agenda = $queryRapat->row()->agenda;
            $pengundang1 = $queryRapat->row()->penandatangan;
            $notulis1 = $queryRapat->row()->notulis;
            $dokumenter1 = $queryRapat->row()->dokumenter;
            $peserta = $queryRapat->row()->peserta;
            $no = $queryRapat->row()->no_surat;
            $tgl = $queryRapat->row()->tanggal;
            $tgl_undangan = $queryRapat->row()->tgl_undangan;
            $pengundang = form_dropdown('pengundang', $pegawai, $pengundang1, 'class="form-control select2" id="pengundang"');
            $notulis = form_dropdown('notulis', $pegawai, $notulis1, 'class="form-control select2" id="notulis"');
            $dokumenter = form_dropdown('dokumenter', $pegawai, $dokumenter1, 'class="form-control select2" id="dokumenter"');
        }

        echo json_encode(
            array(
                'st' => 1,
                'id' => $id,
                'no' => $no,
                'tgl' => $tgl,
                'tgl_undangan' => $tgl_undangan,
                'judul' => $judul,
                'mulai' => $mulai,
                'selesai' => $selesai,
                'tempat' => $tempat,
                'agenda' => $agenda,
                'peserta' => $peserta,
                'pengundang' => $pengundang,
                'notulis' => $notulis,
                'dokumenter' => $dokumenter
            )
        );
        return;
    }

    public function show_notulen()
    {
        $id = $this->encryption->decrypt(base64_decode($this->input->post('id')));

        if ($id == '-1') {
            $notulen = "";
        } else {
            $queryRapat = $this->model->get_seleksi_array('register_rapat', ['id' => $id]);
            $notulen = $queryRapat->row()->notulen;
        }

        echo json_encode(
            array(
                'st' => 1,
                'id' => $id,
                'notulen' => $notulen
            )
        );
        return;
    }

    public function show_dokumentasi()
    {
        $id = $this->encryption->decrypt(base64_decode($this->input->post('id')));
        $data = $this->model->get_seleksi_array('dokumentasi_rapat', ['agenda_id' => $id])->result_array();

        foreach ($data as &$row) {
            $row['url'] = base_url($row['file']);
        }

        if ($data) {
            echo json_encode(['st' => 1, 'id' => $id, 'data' => $data]);
        } else {
            echo json_encode(['st' => 0, 'id' => $id, 'data' => []]);
        }
    }

    public function simpan_rapat()
    {
        $this->form_validation->set_rules('tgl_undangan', 'Tanggal Undangan Rapat', 'trim|required');
        $this->form_validation->set_rules('tgl', 'Tanggal Agenda Rapat', 'trim|required');
        $this->form_validation->set_rules('pengundang', 'Pengundang Agenda Rapat', 'trim|required');
        $this->form_validation->set_rules('mulai', 'Tanggal Awal', 'trim|required');
        $this->form_validation->set_rules('selesai', 'Tanggal Akhir', 'trim|required');
        $this->form_validation->set_rules('tempat', 'Tempat/Lokasi Agenda Rapat', 'trim|required');
        $this->form_validation->set_rules('peserta', 'Peserta Agenda Rapat', 'trim|required');
        $this->form_validation->set_rules('agenda', 'Agenda Rapat', 'trim|required');

        $this->form_validation->set_message(['required' => '%s Tidak Boleh Kosong']);

        if ($this->form_validation->run() == FALSE) {
            echo json_encode(['success' => 2, 'message' => validation_errors()]);
            return;
        }

        $data = [
            'id' => $this->input->post('id'),
            'tgl' => $this->input->post('tgl'),
            'tgl_undangan' => $this->input->post('tgl_undangan'),
            'penandatangan' => $this->input->post('pengundang'),
            'mulai' => $this->input->post('mulai'),
            'selesai' => $this->input->post('selesai'),
            'tempat' => $this->input->post('tempat'),
            'agenda' => $this->input->post('agenda'),
            'peserta' => $this->input->post('peserta'),
            'notulis' => $this->input->post('notulis'),
            'dokumenter' => $this->input->post('dokumenter'),
            'no' => $this->input->post('no'),
            'info' => $this->input->post('info')
        ];

        $result = $this->model->proses_simpan_agenda_rapat($data);
        if ($result['status']) {
            echo json_encode(['success' => 1, 'message' => $result['message']]);
        } else {
            echo json_encode(['success' => 3, 'message' => $result['message']]);
        }
    }

    public function simpan_notulen()
    {
        $id = $this->input->post('id');
        $notulen = $this->input->post('notulen');

        $dataRapat = array(
            "id" => $id,
            "notulen" => $notulen,
            "modified_on" => date('Y-m-d H:i:s'),
            "modified_by" => $this->session->userdata("fullname")
        );

        $queryRapat = $this->model->pembaharuan_data('register_rapat', $dataRapat, 'id', $id);
        if ($queryRapat == 1) {
            echo json_encode(['success' => 1, 'message' => 'Notulen Rapat Berhasil di Simpan']);
        } else {
            echo json_encode(['success' => 3, 'message' => 'Gagal Simpan Notulen Rapat, ' . $queryRapat]);
        }
    }

    public function simpan_dokumentasi()
    {
        $id = $this->input->post('id_rapat');
        if ($_FILES && isset($_FILES['foto'])) {
            $config['upload_path'] = './dokumen/rapat/' . $id;
            $config['allowed_types'] = 'jpg|jpeg|png';
            $config['max_size'] = 5120; // 5MB
            $config['encrypt_name'] = TRUE;

            if (!is_dir($config['upload_path'])) {
                mkdir($config['upload_path'], 0755, true);
            }

            $this->load->library('upload', $config);

            if (!$this->upload->do_upload('foto')) {
                echo json_encode(['success' => false, 'error' => $this->upload->display_errors()]);
                return;
            }

            $upload_data = $this->upload->data();
            $file_path = $upload_data['full_path'];

            // Kompresi gambar
            $this->_compress_image($file_path, $file_path, 60); // 60% quality

            // Simpan ke database
            $data = [
                'agenda_id' => $id,
                'file' => 'dokumen/rapat/' . $id . '/' . $upload_data['file_name'],
                'created_on' => date('Y-m-d H:i:s'),
                'created_by' => $this->session->userdata('userid')
            ];

            $this->model->simpan_data('dokumentasi_rapat', $data);

            echo json_encode(['success' => true]);
        }
    }

    private function _compress_image($source_path, $destination_path, $quality = 25)
    {
        $info = getimagesize($source_path);
        $mime = $info['mime'];

        switch ($mime) {
            case 'image/jpeg':
                $image = imagecreatefromjpeg($source_path);
                imagejpeg($image, $destination_path, $quality);
                break;
            case 'image/png':
                $image = imagecreatefrompng($source_path);
                imagejpeg($image, $destination_path, $quality); // convert to JPEG
                break;
            case 'image/webp':
                $image = imagecreatefromwebp($source_path);
                imagejpeg($image, $destination_path, $quality);
                break;
            default:
                return false;
        }

        imagedestroy($image);
        return true;
    }

    public function hapus_rapat()
    {
        $id = $this->encryption->decrypt(base64_decode($this->input->post('id')));
        $hapus = $this->model->pembaharuan_data('register_rapat', ['hapus' => '1', 'modified_on' => date('Y-m-d H:i:s'), 'modified_by' => $this->session->userdata('fullname')], 'id', $id);

        if ($hapus == 1) {
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

    public function hapus_dokumentasi()
    {
        $id = $this->input->post('id');

        // Hapus dari DB
        $this->model->pembaharuan_data('dokumentasi_rapat', ['hapus' => 1], 'id', $id);

        echo json_encode(['success' => true]);
    }
}