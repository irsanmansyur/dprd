<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Label extends Admin_Controller
{

    private $post = '';
    public function __construct()
    {
        parent::__construct();
        $this->load->model('admin/label_m');
        $this->load->model('admin/komisi_m');
        $this->load->library('form_validation');
        $this->post = $this->input->post();
        $this->get = $this->input->get();
    }
    public function index($id = null)
    {
        if (!$id || !isAccess(1)) {
            redirect('admin/user/blocked');
        }
        $this->komisi_m->setPrimaryKey($id);
        $this->data['komisi'] = $this->komisi_m->getWhere($this->komisi_m->getKeyName())->row_array();

        if (!$this->data['komisi']) {
            setNotif(0, "Anda baru saja Mengakses Kontent salah");
            redirect('admin/komisi');
        }
        $this->data['page']['title'] = 'Mengolah Data Label';
        $this->data['page']['description'] = 'Silahkan edit Label Atau Tambahkan Label Baru.!';
        $this->data['page']['before'] = ['url' => base_url('admin/komisi'), "title" => "Komisi"];
        $this->data['page']['submenu'] = 'Daftar Label';

        $validation = $this->form_validation;
        $this->label_m->addRules("label");
        $validation->set_rules($this->label_m->getRules());

        if ($validation->run() == false) {
            $this->label_m->setField($this->komisi_m->getPrimaryKey());
            $respon = $this->label_m->getWhere($this->komisi_m->getKeyName(), ["join" => 'web_komisi']);
            if ($respon->status) {
                $this->data['all_label'] = $respon->data;
            } else
                return var_dump($respon);
            $this->template->load('admin', 'label/index', $this->data);
        } else {
            $this->post['komisi_id'] = $id;
            $this->label_m->setField($this->post);

            $this->label_m->add();
            $respon  = hasilCUD("Sukses Menambahkan Label");
            redirect('admin/label/' . $id);
        }
    }
    public function delete($id)
    {
        $komisi_id = $this->get['komisi_id'];
        $this->label_m = $this->label_m;
        $this->label_m->setPrimaryKey($id);
        $this->label_m->deleted();
        hasilCUD("Sukses Menghapus label");
        redirect(base_url('admin/label/' . $komisi_id));
    }
    public function edit($id)
    {
        $komisi_id = $this->get['komisi_id'];
        $this->label_m = $this->label_m;
        $validation = $this->form_validation;
        $this->label_m->addRules("label");
        $validation->set_rules($this->label_m->getRules());
        $this->label_m->setField($this->post);
        $this->label_m->setField($this->get);

        if ($validation->run() == false) {
            // redirect(base_url('admin/label/edit/'));
        } else {
            $this->post[$this->label_m->getKey()] = $id;
            $this->label_m->setField($this->post);
            $this->label_m->update();
            hasilCUD("Berhasil Update Label");
            redirect(base_url('admin/label/' . $komisi_id));
        }
    }
    public function reply($id)
    {
    }
    public function label($id)
    {
        $this->komisi_m = $this->komisi_m;
    }
}
