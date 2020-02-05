<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Aspirasi extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $res = is_login(3);
        if (!$res->status) {
            redirect(base_url('admin/auth/index/3'));
        }
    }
    public function index()
    {
    }
    function id($id)
    {
        $res = getApi(base_url() . "api/aspirasi?id_aspirasi=$id");
        $aspirasi = json_decode($res, TRUE);

        if ($aspirasi['status']) {
            $idKmt = $this->data['page']['id2'];
            $idKmt ? $this->db->update('web_komentar', ['type' => 1], ['id_komentar' => $this->data['page']['id2']]) : '';
        }

        $res = getApi(base_url() . "api/komentar?aspirasi_id=$id");
        $komentar = json_decode($res, TRUE);

        $res = getApi(base_url() . "api/aspirasi?baru='yes'");
        $aspirasi_baru = json_decode($res, TRUE);


        $this->data['komentar'] = $komentar['status'] ? $komentar['data'] : [];
        $this->data['aspirasi'] = $aspirasi['status'] ? $aspirasi['data'][0] : [];

        $this->data['aspirasi_baru'] = $aspirasi['status'] ? $aspirasi_baru['data'] : [];
        $this->data['page']['title'] = 'Selamat datang.! Silahkan Kirim Keluhan atau aspirasi anda disini.!';
        $this->template->load("public", 'aspirasi/single', $this->data);
    }
    function send()
    {
        $tbl = initTable("web_komentar", "kmt");
        $data = [
            "parent" => $this->input->post('parent') ? $this->input->post('parent') : 0,
            "komentar" => $this->input->post('komentar'),
            "aspirasi_id" => $this->input->post('aspirasi_id'),
            "user_id" => $this->data['user']['id_user'],
            $tbl['key'] => $tbl["field"][$tbl['key']],
            "type" => 0,
            "date_created" => time()
        ];

        $this->db->insert($tbl['name'], $data);
        $eks = hasilCUD("Sukses");
        $type = $eks->status ? 'success' : 'danger';
        $this->session->set_flashdata('message', "<div class='mb-5 alert alert-$type' role='alert'>$eks->message .!</div>");
        redirect(base_url("aspirasi/id/" . $this->input->post('aspirasi_id')));
    }
}
