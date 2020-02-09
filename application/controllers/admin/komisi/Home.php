<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Home extends Admin_Controller
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
            $this->template->load('admin', 'komisi/home', $this->data);
        } else {
            $komisi->setPrimaryKey($komisi->getLastId());
            $komisi->setFieldTable($this->post);
            $komisi->add();
            $ks = hasilCUD("Komisi Ditambahkan");
            $type =  $ks->status ? "success" : "danger";
            $this->session->set_flashdata("message","<div class='alert alert-$type' role='alert'> $ks->message</div>");
            redirect(base_url('admin/komisi/home'));
        }
    }
    public function delete($id)
    {
        $komisi = $this->komisi_m;
        $komisi->setPrimaryKey($id);
        $komisi->deleted();
        $ks = hasilCUD("Sukses Menghapus Komisi");
        $type =  $ks->status ? "success" : "danger";
        $this->session->set_flashdata("message","<div class='alert alert-$type' role='alert'> $ks->message</div>");
        redirect(base_url('admin/komisi/home'));
    }
    public function edit($id)
    {
        $komisi = $this->komisi_m;
        $this->post[$komisi->getKeyName()] = $id;
        $komisi->setFieldTable($this->post);
        $komisi->update();
        $ks = hasilCUD("Berhasil Update Komisi");
        $type =  $ks->status ? "success" : "danger";
        $this->session->set_flashdata("message","<div class='alert alert-$type' role='alert'> $ks->message</div>");
    }
}
