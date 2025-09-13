<?php

class Api extends CI_Controller
{
    function __construct()
    {
        parent::__construct();

        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
        header('Content-Type: application/json');
    }

    public function reminder_rapat()
    {
        $key = $this->input->get('api_key');
        if ($key == $this->config->item('api_key')) {
            $this->load->model('Model', 'model');

            $data = $this->model->get_rapat_5_menit_sebelum_mulai();

            if ($data->num_rows() > 0) {
                echo json_encode([
                    'status' => 'success',
                    'data' => $data->result_array()
                ]);
            } else {
                echo json_encode([
                    'status' => 'empty',
                    'data' => []
                ]);
            }
        } else {
            echo json_encode([
                'status' => 'false',
                'message' => 'Anda tidak diijikan akses API'
            ]);
        }
    }

    public function pembaharuan_data()
    {
        header('Content-Type: application/json');

        $input = json_decode(trim(file_get_contents("php://input")), true);

        // Validasi input minimal
        if (!isset($input['tabel'], $input['kunci'], $input['id'], $input['data'])) {
            http_response_code(400);
            echo json_encode(['status' => false, 'message' => 'Required fields: tabel, kunci, id, data']);
            return;
        }

        $tabel = $input['tabel'];
        $id = $input['id'];
        $kunci = $input['kunci'];
        $data = $input['data'];

        if ($input['api_key'] == $this->config->item('api_key')) {
            $this->load->model('Model', 'model');
            try {
                $query = $this->model->pembaharuan_data($tabel, $data, $kunci, $id);
                if ($query) {
                    echo json_encode(['status' => true, 'message' => 'Update Data Berhasil']);
                } else {
                    http_response_code(500);
                    echo json_encode(['status' => false, 'message' => 'Gagal Update Data']);
                }

            } catch (Exception $e) {
                http_response_code(500);
                echo json_encode(['status' => false, 'message' => 'Error: ' . $e->getMessage()]);
            }
        } else {
            echo json_encode([
                'status' => 'false',
                'message' => 'Anda tidak diijikan akses API'
            ]);
        }
    }
}