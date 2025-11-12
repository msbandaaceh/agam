<?php

use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelHigh;

class HalamanLaporan extends MY_Controller
{
    public function show_tabel_presensi_rapat()
    {
        $query = $this->model->get_register_rapat();

        $data = [];
        foreach ($query as $row) {
            $data[] = [
                'id' => base64_encode($this->encryption->encrypt($row->id)),
                'tgl' => $this->tanggalhelper->convertDayDate($row->tanggal),
                'agenda' => $row->agenda,
                'total' => $row->total
            ];
        }

        echo json_encode(['data_rapat' => $data]);
    }

    public function show_tabel_detil_presensi_rapat()
    {
        $idrapat = $this->encryption->decrypt(base64_decode($this->input->post('idrapat')));
        $query = $this->model->get_detail_presensi_rapat($idrapat);

        $data = [];
        foreach ($query as $row) {
            $data[] = [
                'id' => base64_encode($this->encryption->encrypt($row->id)),
                'nama' => $row->nama,
                'waktu' => $row->waktu
            ];
        }

        echo json_encode(['idrapat' => $this->input->post('idrapat'), 'data_rapat' => $data]);
    }

    public function show_presensi()
    {
        $id = $this->encryption->decrypt(base64_decode($this->input->post('id')));

        $waktu = '';
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

        if ($id == '-1') {
            $judul = "TAMBAH DATA PRESENSI PEGAWAI";
            $pegawai_ = form_dropdown('pegawai', $pegawai, '', 'class="form-control select2" id="pegawai"');
        } else {
            $judul = "EDIT DATA PRESENSI PEGAWAI";
            $query = $this->model->get_seleksi_array('register_presensi_rapat', ['id' => $id]);
            $userid = $query->row()->userid;
            $waktu = $query->row()->created_on;

            $pegawai_ = form_dropdown('pegawai', $pegawai, $userid, 'class="form-control select2" id="pegawai"');
        }

        echo json_encode([
            'st' => 1,
            'id' => $this->input->post('id'),
            'judul' => $judul,
            'idrapat' => $this->input->post('idrapat'),
            'pegawai' => $pegawai_,
            'waktu' => $waktu
        ]);
    }

    public function simpan_presensi_pegawai()
    {
        $this->form_validation->set_rules('pegawai', 'Pegawai', 'trim|required');
        $this->form_validation->set_rules('waktu', 'Waktu Presensi', 'trim|required');

        $this->form_validation->set_message(['required' => '%s Tidak Boleh Kosong']);

        if ($this->form_validation->run() == FALSE) {
            echo json_encode(['success' => 2, 'message' => validation_errors()]);
            return;
        }

        $data = [
            'id' => $this->encryption->decrypt(base64_decode($this->input->post('id'))),
            'idrapat' => $this->encryption->decrypt(base64_decode($this->input->post('idrapat'))),
            'pegawai' => $this->input->post('pegawai'),
            'waktu' => $this->input->post('waktu')
        ];

        $result = $this->model->proses_simpan_presensi_manual($data);
        if ($result['status']) {
            echo json_encode(['success' => 1, 'message' => $result['message'], 'idrapat' => $this->input->post('idrapat')]);
        } else {
            echo json_encode(['success' => 3, 'message' => $result['message']]);
        }
    }

    public function detil_presensi($halaman, $idrapat = null)
    {
        $idrapat = $this->encryption->decrypt(base64_decode($idrapat));
        if ($halaman) {
            $rapat = $this->model->get_seleksi_array('register_rapat', ['id' => $idrapat]);
            $data['peran'] = $this->session->userdata('peran');
            $data['page'] = $halaman;
            $data['idrapat'] = base64_encode($this->encryption->encrypt($idrapat));
            $data['agenda'] = $rapat->row()->agenda;

            $this->load->view($halaman, $data);
        } else {
            show_404();
        }
    }

    public function hapus_presensi()
    {
        $id = $this->encryption->decrypt(base64_decode($this->input->post('id')));
        $query = $this->model->get_seleksi_array('register_presensi_rapat', ['id' => $id]);
        $idrapat = $query->row()->idrapat;

        $hapus = $this->model->pembaharuan_data('register_presensi_rapat', ['hapus' => '1'], 'id', $id);

        if ($hapus == 1) {
            echo json_encode(
                array(
                    'st' => 1,
                    'idrapat' => base64_encode($this->encryption->encrypt($idrapat))
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

    public function unduh_dokumen()
    {
        $this->form_validation->set_rules('jenis_dokumen', 'Jenis Dokumen Rapat', 'trim|required');
        $this->form_validation->set_rules('ttd_tempel', 'Status Tanda Tangan', 'trim|required');
        $this->form_validation->set_message(['required' => '%s Harus Dipilih']);

        if ($this->form_validation->run() == FALSE) {
            echo json_encode(['success' => 2, 'message' => validation_errors()]);
            return;
        }

        $id = $this->encryption->decrypt(base64_decode($this->input->post('id')));
        $dok = $this->input->post('jenis_dokumen');
        $ttd = $this->input->post('ttd_tempel');

        $queryRapat = $this->model->get_seleksi_array('v_rapat', ['id' => $id]);
        $no_surat = $queryRapat->row()->no_surat;
        if ($no_surat == NULL) {
            echo json_encode(['success' => 2, 'message' => 'Nomor Surat belum diisi, silakan mengisi melalui Menu Daftar Agenda Rapat']);
            return;
        }

        if ($dok == 'undangan') {
            $sifat = $this->input->post('sifat');
            $lampiran = $this->input->post('lampiran');
            if ($lampiran == null) {
                $lampiran = '-99';
            }
            $url = site_url('halamanlaporan/get_dokumen_undangan_pdf/' . $id . '/' . $sifat . '/' . $lampiran . '/' . $ttd);
            echo json_encode(['success' => 1, 'message' => 'Download Undangan Berhasil', 'url' => $url]);
            return;
        } elseif ($dok == 'notula') {
            $notulen = $queryRapat->row()->notulen;
            if ($notulen == NULL) {
                echo json_encode(['success' => 2, 'message' => 'Notulen Rapat belum diisi, silakan mengisi melalui Menu Daftar Agenda Rapat']);
                return;
            }
            $url = site_url('halamanlaporan/get_dokumen_notula_pdf/' . $id . '/' . $ttd);
            echo json_encode(['success' => 1, 'message' => 'Download Notulen Berhasil', 'url' => $url]);
            return;
        } elseif ($dok == 'presensi') {
            $url = site_url('halamanlaporan/get_dokumen_presensi_pdf/' . $id . '/' . $ttd);
            echo json_encode(['success' => 1, 'message' => 'Download Daftar Hadir Berhasil', 'url' => $url]);
            return;
        }
    }

    public function get_dokumen_undangan_pdf($id, $sifat, $lampiran, $ttd)
    {
        $query = $this->model->get_seleksi_array('v_rapat', ['id' => $id]);
        $nomor = $query->row()->no_surat;
        $date = new DateTime($query->row()->created_on);
        $tanggal = $this->tanggalhelper->konversiTanggal($date->format('Y-m-d'));
        $agenda = $query->row()->agenda;
        $tgl_undangan = $this->tanggalhelper->konversiTanggal($query->row()->tgl_undangan);
        $tanggal_agenda = $this->tanggalhelper->convertDayDate($query->row()->tanggal);
        $mulai = $query->row()->mulai;
        $selesai = $query->row()->selesai;
        $tempat = $query->row()->tempat;
        $peserta = $query->row()->peserta;

        $pejabat = $query->row()->nama_penandatangan;
        $jabatan = $query->row()->jabatan_penandatangan;

        if ($lampiran == '-99')
            $lampiran = '-';

        $data = array(
            'nomor' => $nomor,
            'sifat' => $sifat,
            'lampiran' => $lampiran,
            'tanggal' => $tanggal,
            'agenda' => $agenda,
            'tgl_undangan' => $tgl_undangan,
            'tanggal_agenda' => $tanggal_agenda,
            'mulai' => $mulai,
            'selesai' => $selesai,
            'tempat' => $tempat,
            'peserta' => $peserta,
            'nama' => $pejabat,
            'jabatan' => $jabatan,
            'kop' => $this->session->userdata('kop_satker')
        );

        if ($ttd == '1') {
            $link = $this->config->item('sso_server') . 'halamankartupegawai/kartu_pegawai/' . base64_encode($this->encryption->encrypt($query->row()->penandatangan)); // atau link apapun sesuai kebutuhan
            $logoPath = $this->session->userdata('logo_satker'); // path logo PNG kecil

            $result = Builder::create()
                ->writer(new PngWriter())
                ->data($link)
                ->encoding(new Encoding('UTF-8'))
                ->errorCorrectionLevel(new ErrorCorrectionLevelHigh())
                ->size(100)
                ->margin(0)
                ->logoPath($logoPath)
                ->logoResizeToWidth(20)
                ->build();

            $data['qr_code'] = $result->getDataUri();
        } else {
            $data['qr_code'] = null;
        }

        #die(var_dump($data));
        $html = $this->load->view('template_undangan', $data, true);
        $clean_nomor = preg_replace('/[^A-Za-z0-9_\-]/', '_', $nomor);
        $this->pdf->loadHtml($html);
        $this->pdf->setPaper('A4', 'portrait');
        $this->pdf->set_option('isRemoteEnabled', true);
        $this->pdf->render();
        $this->pdf->stream('Undangan_Rapat_' . $clean_nomor . '.pdf', array("Attachment" => 1));
    }

    public function get_dokumen_notula_pdf($id, $ttd)
    {
        # Data Agenda Rapat
        $query = $this->model->get_seleksi_array('v_rapat', ['id' => $id]);
        $nomor = $query->row()->no_surat;
        $date = new DateTime($query->row()->created_on);
        $tanggal = $this->tanggalhelper->konversiTanggal($date->format('Y-m-d'));
        $tgl_undangan = $this->tanggalhelper->konversiTanggal($query->row()->tgl_undangan);
        $tanggal_agenda = $this->tanggalhelper->convertDayDate($query->row()->tanggal);
        $mulai = $query->row()->mulai;
        $agenda = $query->row()->agenda;
        $selesai = $query->row()->selesai;
        $tempat = $query->row()->tempat;
        $peserta = $query->row()->peserta;
        $notulen = $query->row()->notulen;

        # Data Penandatangan
        $pejabat = $query->row()->nama_penandatangan;
        $jabatan = $query->row()->jabatan_penandatangan;

        # Data Notulis
        $notulis = $query->row()->nama_notulis;

        $data = array(
            'jabatan' => $jabatan,
            'nomor' => $nomor,
            'tanggal' => $tanggal,
            'agenda' => $agenda,
            'tgl_undangan' => $tgl_undangan,
            'tanggal_agenda' => $tanggal_agenda,
            'mulai' => $mulai,
            'selesai' => $selesai,
            'tempat' => $tempat,
            'peserta' => $peserta,
            'notulen' => $notulen,
            'pejabat' => $pejabat,
            'notulis' => $notulis,
            'kop' => $this->session->userdata('kop_satker')
        );

        # Data Dokumentasi Rapat
        $dokumentasi = $this->model->get_seleksi_array('dokumentasi_rapat', ['agenda_id' => $query->row()->id])->result();
        $data['dokumentasi'] = $dokumentasi;

        if ($ttd == '1') {
            $link = $this->config->item('sso_server') . 'halamankartupegawai/kartu_pegawai/' . base64_encode($this->encryption->encrypt($query->row()->penandatangan)); // atau link apapun sesuai kebutuhan
            $logoPath = $this->session->userdata('logo_satker'); // path logo PNG kecil

            $result = Builder::create()
                ->writer(new PngWriter())
                ->data($link)
                ->encoding(new Encoding('UTF-8'))
                ->errorCorrectionLevel(new ErrorCorrectionLevelHigh())
                ->size(100)
                ->margin(0)
                ->logoPath($logoPath)
                ->logoResizeToWidth(20)
                ->build();

            $data['qr_code_pejabat'] = $result->getDataUri();

            $link1 = $this->config->item('sso_server') . 'halamankartupegawai/kartu_pegawai/' . base64_encode($this->encryption->encrypt($query->row()->notulis)); // atau link apapun sesuai kebutuhan

            $result = Builder::create()
                ->writer(new PngWriter())
                ->data($link1)
                ->encoding(new Encoding('UTF-8'))
                ->errorCorrectionLevel(new ErrorCorrectionLevelHigh())
                ->size(100)
                ->margin(0)
                ->logoPath($logoPath)
                ->logoResizeToWidth(20)
                ->build();

            $data['qr_code_notulen'] = $result->getDataUri();
        }

        #die(var_dump($data));
        $html = $this->load->view('template_notulen', $data, true);
        #die(var_dump($html));
        $clean_nomor = preg_replace('/[^A-Za-z0-9_\-]/', '_', $nomor);
        $this->pdf->loadHtml($html);
        $this->pdf->setPaper('A4', 'portrait');
        $this->pdf->set_option('isRemoteEnabled', true);
        $this->pdf->render();
        $this->pdf->stream('Notulen_Rapat_' . $clean_nomor . '.pdf', array("Attachment" => 1));
    }

    public function get_dokumen_presensi_pdf($id, $ttd)
    {
        # Data Agenda Rapat
        $query = $this->model->get_seleksi_array('v_rapat', ['id' => $id]);
        $id = $query->row()->id;
        $nomor = $query->row()->no_surat;
        $tanggal_agenda = $this->tanggalhelper->convertDayDate($query->row()->tanggal);
        $mulai = $query->row()->mulai;
        $agenda = $query->row()->agenda;
        $selesai = $query->row()->selesai;
        $tempat = $query->row()->tempat;

        # Data Penandatangan
        $nama = $query->row()->nama_penandatangan;
        $jabatan = $query->row()->jabatan_penandatangan;

        $data = array(
            'jabatan' => $jabatan,
            'agenda' => $agenda,
            'tanggal_agenda' => $tanggal_agenda,
            'mulai' => $mulai,
            'selesai' => $selesai,
            'tempat' => $tempat,
            'nama' => $nama,
            'kop' => $this->session->userdata('kop_satker'),
            'peserta' => $this->model->get_detail_presensi_rapat($id)
        );

        if ($ttd == '1') {
            $link = $this->config->item('sso_server') . 'halamankartupegawai/kartu_pegawai/' . base64_encode($this->encryption->encrypt($query->row()->penandatangan)); // atau link apapun sesuai kebutuhan
            $logoPath = $this->session->userdata('logo_satker'); // path logo PNG kecil

            $result = Builder::create()
                ->writer(new PngWriter())
                ->data($link)
                ->encoding(new Encoding('UTF-8'))
                ->errorCorrectionLevel(new ErrorCorrectionLevelHigh())
                ->size(100)
                ->margin(0)
                ->logoPath($logoPath)
                ->logoResizeToWidth(20)
                ->build();

            $data['qr_code'] = $result->getDataUri();
        }

        #die(var_dump($data));
        $html = $this->load->view('template_presensi', $data, true);
        $clean_nomor = preg_replace('/[^A-Za-z0-9_\-]/', '_', $nomor);
        $this->pdf->loadHtml($html);
        $this->pdf->setPaper('A4', 'portrait');
        $this->pdf->set_option('isRemoteEnabled', true);
        $this->pdf->render();
        $this->pdf->stream('Presensi_Rapat_' . $clean_nomor . '.pdf', array("Attachment" => 1));
    }
}