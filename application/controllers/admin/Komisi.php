<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Komisi extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('admin/komisi_m');
        $this->load->library('form_validation');
        $this->post = $this->input->post();
        $this->get = $this->input->get();
    }
    public function index()
    {
        $this->data['page']['title'] = 'Mengolah Komisi';
        $this->data['page']['description'] = 'Silahkan edit menu Atau Tambahkan Komisi.!';
        // $this->data['page']['before'] = ['url' => base_url('admin/admin/role'), "title" => "Role Access"];
        $this->data['page']['submenu'] = 'Menu Komisi';


        $validation = $this->form_validation;
        $komisi = $this->komisi_m;

        $this->data['all_komisi'] = $komisi->getWhere()->result_array();

        $validation->set_rules($komisi->getRules());
        if ($validation->run() == false) {
            $this->template->load('admin', 'komisi/index', $this->data);
        } else {
            $komisi->setPrimaryKey($komisi->getLastId());
            $komisi->setFieldTable($this->post);
            $komisi->add();
            $respon  = hasilCUD("Sukses Menambahkan Komisi");
            redirect('admin/komisi');
        }
    }
    public function delete($id)
    {
        $komisi = $this->komisi_m;
        $komisi->setPrimaryKey($id);
        $komisi->deleted();
        hasilCUD("Sukses Menghapus Komisi");
        redirect(base_url('admin/komisi'));
    }
    public function edit($id)
    {
        $komisi = $this->komisi_m;
        $this->post[$komisi->getKeyName()] = $id;
        $komisi->setFieldTable($this->post);
        $komisi->update();
        hasilCUD("Berhasil Update Komisi");
        redirect(base_url('admin/komisi'));
    }
    public function reply($id)
    {
    }
    public function user()
    {
        $this->data['page']['title'] = 'Mengolah data User Komisi';
        $this->data['page']['description'] = 'Silahkan edit User Komisi dan menonaktifkan atau mengaktifkan.!';
        $this->data['page']['before'] = ['url' => base_url('admin/komisi'), "title" => "Komisi"];
        $this->data['page']['submenu'] = 'Daftar User untuk komisi';
        $user = $this->tbl;
        $user->setTable("tbl_user", "user");
        $field = [
            "role_id" => "2"
        ];
        $user->setField($field);
        $eks = $user->getWhere("role_id", [
            "join" => [
                ["tbl_user_file", 'tbl_user']
            ],
            "select" => "tbl_user_file.file",
            "start" => 0
        ]);
        $komisi = $this->tbl;
        $komisi->setTable("web_komisi");
        $kms  = $komisi->getWhere();
        if ($eks->status) {
            $this->data['all_komisi'] = $kms->data;
            $this->data['komisi_user'] = $eks->data;
            $this->template->load('admin', 'komisi/user', $this->data);
        } else
            var_dump($eks);
    }

    public function userSet()
    {
        $user = $this->tbl;
        $user->setTable("tbl_user", "user");
        $user->setField($this->post);
        $user->selectField("is_active");
        $eks = $user->update();
        echo json_encode($eks);
    }

    function setkomisi()
    {

        $komisi = $this->tbl;
        $komisi->setTable("web_komisi_user", "k_u_a");
        $this->post['status'] = 1;
        $komisi->setField($this->post);

        $kms = $komisi->getWhere("user_id");
        if ($kms->status) {
            if ($kms->data->num_rows() < 1) {
                $komisi->add();
            }
        }
    }

    function userDelete($id)
    {
        $user = $this->tbl;
        $user->setTable("tbl_user", "user");
        $user->setPrimaryKey($id);
        $eks = $user->deleted();
        echo json_encode($eks);
    }
}
