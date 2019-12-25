<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Icon extends Admin_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->model('icons_m');
    }
    function index()
    {
        $icon = $this->icons_m;
        $validation = $this->form_validation;
        $validation->set_rules($icon->rules());

        if ($validation->run()) {
            $data = $this->input->post();
            $icon->add($data);
            hasilCUD("Sukses Menambahkan Icons");
            header("Refresh:0");
        } else {
            $this->template->load('admin', 'menu_management/menu_icons', $this->data);
        }
    }

    //=
    //== CM_edit
    //=


    function edit($id = 0)
    {
        $icon = $this->icons_m;
        $validation = $this->form_validation;
        $validation->set_rules($icon->rules());
        if ($validation->run()) {
            $data = $this->input->post();
            if (!array_key_exists('id', $data))
                $data['id'] = $id;
            $icon->update($data);
            hasilCUD("Sukses EDIT Icons");
        } else {
            $this->session->set_flashdata("message", "<div class='mb-5 alert alert-danger' role='alert'>Lengkapi data.!</div>");
        }
        redirect(base_url('admin/icon'));
    }

    // CM_show


    // CM_delete
    function delete($id = null)
    {
        if ($id != null) {
            $icon = $this->icons_m;
            $data['id'] = $id;
            $icon->delete($data);
            hasilCUD("Sukses Delete Icons");
        }
        Header("Refresh:0");
    }

    // CM_insert page
}
